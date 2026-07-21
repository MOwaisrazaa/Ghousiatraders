<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\UserCourse;
use App\Models\Course;
use App\Models\Lecture;

class PaymentDetailsController extends Controller
{
    /**
     * Display user's payment details and transaction history.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all orders for the user (including all statuses)
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all user courses with their details
        $userCourses = UserCourse::where('user_id', $user->id)
            ->with(['course', 'order'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate summary statistics - only count paid/completed orders for total spent
        $totalSpent = Order::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'completed'])
            ->sum('final_total');

        // Get all active user courses with their orders
        $allUserCourses = UserCourse::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('order')
            ->get();

        $totalCourses = $allUserCourses->pluck('course_id')->unique()->count();

        // Count free courses (where final_total is 0 or null)
        $freeCourseIds = [];
        foreach ($allUserCourses as $userCourse) {
            if ($userCourse->order && ($userCourse->order->final_total == 0 || $userCourse->order->final_total === null)) {
                $freeCourseIds[] = $userCourse->course_id;
            }
        }
        $freeCourses = count(array_unique($freeCourseIds));
        $paidCourses = $totalCourses - $freeCourses;

        return view('user.payment-details', compact(
            'orders',
            'userCourses',
            'totalSpent',
            'totalCourses',
            'freeCourses',
            'paidCourses'
        ));
    }

    /**
     * Display details of a specific order.
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $cartItems = json_decode($order->cart_items, true);
        $items = [];

        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                if (isset($item['course_id'])) {
                    $course = Course::find($item['course_id']);
                    if ($course) {
                        $items[] = [
                            'type' => 'Course',
                            'name' => $course->name,
                            'price' => $item['price'] ?? 0,
                            'course_id' => $course->id,
                        ];
                    }
                }
            }
        }

        $billingAddress = json_decode($order->billing_address, true);

        return view('user.order-details', compact('order', 'items', 'billingAddress'));
    }
}
