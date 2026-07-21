<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Order;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;

class CourseDetail extends Component
{
    public $course;
    public $instructorProfiles = [];

    public function mount($slug = null)
    {
        // Get route parameter from request if not passed
        if ($slug === null) {
            $slug = request()->route('slug');
        }

        $this->course = Course::where('slug', $slug)
            ->with(['lectures', 'features', 'ratings' => function($query) {
                $query->where('is_approved', true)
                     ->where('show_publicly', true)
                     ->with('user')
                     ->latest();
            }])->firstOrFail();

        // Ensure instructor data is available for display
        if (!$this->course->instructor) {
            $this->course->instructor = 'Instructor'; // Default value if none set
        }

        // Get instructor profiles for the course and its lectures
        $this->loadInstructorProfiles();
    }

    /**
     * Load instructor profiles for the course and its lectures
     */
    private function loadInstructorProfiles()
    {
        // Get all unique instructor names from this course and its lectures
        $instructorNames = collect([$this->course->instructor]);
        $lectureInstructors = $this->course->lectures->pluck('instructor')->filter();
        $instructorNames = $instructorNames->merge($lectureInstructors)->filter()->unique()->values();

        if ($instructorNames->isEmpty()) {
            return;
        }

        // Load instructor profiles with debug info
        $profiles = \App\Models\InstructorProfile::whereIn('name', $instructorNames)
            ->where('is_active', true)
            ->get();

        // Log retrieved profiles for debugging
        \Log::info('Instructor profiles loaded for course', [
            'course_id' => $this->course->id,
            'instructor_names' => $instructorNames->toArray(),
            'profiles_count' => $profiles->count(),
            'profiles' => $profiles->pluck('name', 'id')->toArray()
        ]);

        $this->instructorProfiles = $profiles->keyBy('name')->toArray();
    }

    public function enrollInFreeCourse($id, $type = 'course')
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login or register to enroll in free courses.');
            return redirect()->route('sign-in');
        }

        $userId = auth()->id();
        $enrolled = false;

        if ($type === 'course') {
            $course = Course::find($id);
            if (!$course || !$course->is_free) {
                session()->flash('error', 'This course is not available for free enrollment.');
                return;
            }

            if (\App\Services\FreeCourseService::enrollInFreeCourse($userId, $id)) {
                // Remove from cart if it was added there by mistake
                \App\Models\Shoppingcart::where('user_id', $userId)
                    ->where('course_id', $id)
                    ->delete();
                
                session()->flash('success', 'You have successfully enrolled in this free course!');
                $this->dispatch('cartUpdated');
                $enrolled = true;
            } else {
                session()->flash('error', 'You are already enrolled in this course.');
            }
        } else {
            $lecture = $this->course->lectures->find($id);
            if (!$lecture || !$lecture->is_free) {
                session()->flash('error', 'This lecture is not available for free enrollment.');
                return;
            }

            if (\App\Services\FreeCourseService::enrollInFreeLecture($userId, $id)) {
                // Remove from cart if it was added there by mistake
                \App\Models\Shoppingcart::where('user_id', $userId)
                    ->where('lecture_id', $id)
                    ->delete();
                
                session()->flash('success', 'You have successfully enrolled in this free lecture!');
                $this->dispatch('cartUpdated');
                $enrolled = true;
            } else {
                session()->flash('error', 'You are already enrolled in this lecture.');
            }
        }

        // Redirect to dashboard after successful enrollment
        if ($enrolled) {
            return redirect()->route('user.dashboard');
        }
    }

    public function addToCart($id, $type = 'course')
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login or register to add items to cart.');
            return redirect()->route('sign-in');
        }

        $userId = auth()->id();

        if ($type === 'course') {
            // Prevent free courses from being added to cart
            if ($this->course->is_free) {
                session()->flash('error', 'Free courses cannot be added to cart. Please use the "Enroll Now (Free)" button instead.');
                return;
            }

            // Get course price (using weekly_price as the single price field)
            $price = $this->course->weekly_price;

            // Check if user has already purchased any lectures from this course
            $purchasedLectures = \App\Models\UserCourse::where('user_id', $userId)
                ->whereNotNull('lecture_id')
                ->where('status', 'active')
                ->whereHas('lecture', function ($query) use ($id) {
                    $query->where('course_id', $id);
                })
                ->with('lecture')
                ->get();

            // If user has purchased lectures from this course, subtract their cost
            $discountAmount = 0;
            foreach ($purchasedLectures as $purchasedLecture) {
                if ($purchasedLecture->lecture) {
                    $lecturePrice = $purchasedLecture->lecture->weekly_price;
                    $discountAmount += $lecturePrice;
                }
            }

            // Apply discount - don't go below zero
            $finalPrice = max(0, $price - $discountAmount);

            // Generate a reason message if there's a discount
            $discountReason = null;
            if ($discountAmount > 0) {
                $purchasedLectureNames = $purchasedLectures->pluck('lecture.name')->implode(', ');
                $discountReason = "Discount for previously purchased lecture(s): " . $purchasedLectureNames;
            }

            // Logic for adding course to cart
            \App\Models\Shoppingcart::updateOrCreate(
                ['user_id' => $userId, 'course_id' => $id],
                [
                    'price' => $finalPrice,
                    'price_type' => null,
                    'original_price' => $price,
                    'discount_amount' => $discountAmount,
                    'discount_reason' => $discountReason
                ]
            );

            // If there was a discount, show a message
            if ($discountAmount > 0) {
                session()->flash('success', 'Course added to cart! $' . number_format($discountAmount, 2) . ' discount applied for previously purchased lectures.');
            } else {
                session()->flash('success', 'Course added to cart successfully!');
            }
        } else {
            // Logic for adding lecture to cart
            $lecture = $this->course->lectures->find($id);
            if (!$lecture) {
                session()->flash('error', 'Lecture not found.');
                return;
            }

            // Check if lecture belongs to restricted course
            if ($lecture->belongsToRestrictedCourse()) {
                session()->flash('error', 'This lecture can only be purchased as part of the complete course.');
                return;
            }

            // Prevent free lectures from being added to cart
            if ($lecture->is_free) {
                session()->flash('error', 'Free lectures cannot be added to cart. Please use the "Enroll Now (Free)" button instead.');
                return;
            }

            $lecturePrice = $lecture->weekly_price;

            \App\Models\Shoppingcart::updateOrCreate(
                ['user_id' => $userId, 'lecture_id' => $id],
                ['price' => $lecturePrice, 'price_type' => null]
            );

            session()->flash('success', 'Lecture added to cart successfully!');
        }

        // Dispatch event to update cart icon
        $this->dispatch('cartUpdated');
    }

    public function isPurchased($itemId, $type = 'course')
    {
        if (!auth()->check()) {
            return false;
        }

        if ($type === 'course') {
            return UserCourse::where('user_id', auth()->id())
                ->where('course_id', $itemId)
                ->whereNull('lecture_id')
                ->where('status', 'active')
                ->exists();
        } else {
            return UserCourse::where('user_id', auth()->id())
                ->where('lecture_id', $itemId)
                ->where('status', 'active')
                ->exists();
        }
    }

    public function isPending($itemId, $type = 'course')
    {
        if (!auth()->check()) {
            return false;
        }

        $userId = auth()->id();
        $pendingOrders = Order::where('user_id', $userId)
            ->whereIn('status', ['pending', 'awaiting_payment'])
            ->get();

        foreach ($pendingOrders as $order) {
            $cartItems = json_decode($order->cart_items, true);
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    $itemKey = $type . '_id';
                    if (isset($item[$itemKey]) && $item[$itemKey] == $itemId) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function render()
    {
        return view('livewire.course-detail', [
            'course' => $this->course
        ])->layout('layouts.app');
    }
}
