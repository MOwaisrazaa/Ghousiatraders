<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
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
                        ];
                    }
                } elseif (isset($item['lecture_id'])) {
                    $lecture = Lecture::find($item['lecture_id']);
                    if ($lecture) {
                        $items[] = [
                            'type' => 'Lecture',
                            'name' => $lecture->lecture_title,
                            'course' => $lecture->course ? $lecture->course->name : 'Standalone Lecture',
                            'price' => $item['price'] ?? 0,
                        ];
                    }
                }
            }
        }

        $billingAddress = json_decode($order->billing_address, true);

        return view('admin.orders.show', compact('order', 'items', 'billingAddress'));
    }

    /**
     * Approve an order payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Order $order)
    {
        $oldStatus = $order->status;
        $order->update(['status' => 'paid']);
        $this->processStatusTransition($order, $oldStatus, 'paid');

        return redirect()->route('admin.orders')
            ->with('success', 'Order #' . $order->id . ' has been approved successfully.');
    }

    /**
     * Reject an order payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Order $order)
    {
        $order->update(['status' => 'rejected']);

        if ($request->has('reason')) {
            $order->update(['rejection_reason' => $request->reason]);
        }

        return redirect()->route('admin.orders')
            ->with('success', 'Order #' . $order->id . ' has been rejected.');
    }

    /**
     * Update order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,rejected',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);
        $this->processStatusTransition($order, $oldStatus, $newStatus);

        return back()->with('success', 'Order #' . $order->id . ' status has been updated to ' . ucfirst($newStatus) . '.');
    }

    /**
     * Helper to process accounting & course permissions when an order gets approved.
     */
    private function processStatusTransition(Order $order, $oldStatus, $newStatus)
    {
        $approvedStatuses = ['paid', 'shipped', 'completed'];
        $wasApprovedBefore = in_array($oldStatus, $approvedStatuses);
        $isApprovedNow = in_array($newStatus, $approvedStatuses);

        if ($isApprovedNow && !$wasApprovedBefore) {
            if (Schema::hasTable('account_transactions') && class_exists(\App\Models\AccountTransaction::class)) {
                $exists = \App\Models\AccountTransaction::where('order_id', $order->id)->exists();
                if (!$exists) {
                    \App\Models\AccountTransaction::create([
                        'order_id' => $order->id,
                        'transaction_type' => 'payment_received',
                        'payment_method' => $order->payment_method ?? 'Cash',
                        'amount' => $order->final_total,
                        'status' => 'completed',
                        'description' => 'Payment for Order #' . $order->id,
                    ]);
                }
            }

            if (Schema::hasTable('account_balances') && class_exists(\App\Models\AccountBalance::class)) {
                $method = strtolower($order->payment_method ?? 'cash');
                $accountName = 'main';

                if (str_contains($method, 'cash')) {
                    $accountName = 'cash';
                } elseif (str_contains($method, 'easypaisa')) {
                    $accountName = 'easypaisa';
                } elseif (str_contains($method, 'bank')) {
                    $accountName = 'bank_account_1';
                }

                $balance = \App\Models\AccountBalance::firstOrCreate(
                    ['account_name' => $accountName],
                    ['balance' => 0, 'total_received' => 0, 'total_used' => 0, 'total_transferred' => 0]
                );

                $balance->increment('balance', $order->final_total);
                $balance->increment('total_received', $order->final_total);
            }

            if (Schema::hasTable('user_courses') && class_exists(\App\Models\UserCourse::class)) {
                $userId = $order->user_id;
                $cartItems = json_decode($order->cart_items, true);

                if (is_array($cartItems)) {
                    foreach ($cartItems as $item) {
                        if (isset($item['course_id'])) {
                            $existingAccess = \App\Models\UserCourse::where([
                                'user_id' => $userId,
                                'course_id' => $item['course_id'],
                                'lecture_id' => null,
                                'status' => 'active'
                            ])->first();

                            if (!$existingAccess) {
                                \App\Models\UserCourse::create([
                                    'user_id' => $userId,
                                    'course_id' => $item['course_id'],
                                    'lecture_id' => null,
                                    'status' => 'active',
                                    'order_id' => $order->id
                                ]);
                            }
                        } elseif (isset($item['lecture_id'])) {
                            $lecture = \App\Models\Lecture::find($item['lecture_id']);

                            if ($lecture) {
                                $existingAccessQuery = \App\Models\UserCourse::where([
                                    'user_id' => $userId,
                                    'lecture_id' => $item['lecture_id'],
                                    'status' => 'active'
                                ]);

                                if ($lecture->course_id) {
                                    $existingAccessQuery->where('course_id', $lecture->course_id);
                                } else {
                                    $existingAccessQuery->whereNull('course_id');
                                }

                                $existingAccess = $existingAccessQuery->first();

                                if (!$existingAccess) {
                                    \App\Models\UserCourse::create([
                                        'user_id' => $userId,
                                        'course_id' => $lecture->course_id,
                                        'lecture_id' => $item['lecture_id'],
                                        'status' => 'active',
                                        'order_id' => $order->id
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
