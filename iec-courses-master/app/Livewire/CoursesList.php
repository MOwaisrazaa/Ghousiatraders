<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shoppingcart;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lecture;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\UserCourse;
use App\Models\Order;

class CoursesList extends Component
{
    public $courses;
    public $lectures; // Add standalone lectures
    public $categories;
    public $selectedType = 'all'; // Default to all courses
    public $selectedCategory = 'all'; // Default to all categories
    public $selectedInstructor = null; // For filtering by instructor

    public function mount($instructor = null)
    {
        $this->selectedInstructor = $instructor;
        $this->loadCourses();
        $this->loadStandaloneLectures();
        $this->categories = Category::all();
    }

    public function loadCourses()
    {
        $query = Course::with([
            'lectures', 
            'ratings' => function($query) {
                $query->where('is_approved', true)
                     ->where('show_publicly', true);
            },
            'lectures.ratings' => function($query) {
                $query->where('is_approved', true)
                     ->where('show_publicly', true);
            }
        ]);

        // Filter by category if not "all"
        if ($this->selectedCategory !== 'all') {
            $query->where('category_id', $this->selectedCategory);
        }

        // Filter by instructor if provided
        if ($this->selectedInstructor) {
            $query->where(function($q) {
                $q->where('instructor', $this->selectedInstructor)
                  ->orWhereHas('lectures', function($subQuery) {
                      $subQuery->where('instructor', $this->selectedInstructor);
                  });
            });
        }

        $this->courses = $query->get();
    }

    public function loadStandaloneLectures()
    {
        $query = Lecture::with([
                'course', // Load related course to check purchase model
                'ratings' => function($query) {
                    $query->where('is_approved', true)
                         ->where('show_publicly', true);
                }
            ])
            ->where(function($q) {
                // Include: 1. Standalone lectures (no course), OR 2. Lectures from flexible courses
                $q->whereNull('course_id')
                  ->orWhereHas('course', function($courseQuery) {
                      $courseQuery->where('purchase_model', 'flexible');
                  });
            });

        // Filter by instructor if provided
        if ($this->selectedInstructor) {
            $query->where('instructor', $this->selectedInstructor);
        }

        $this->lectures = $query->get();
    }

    public function filterByType($type)
    {
        $this->selectedType = $type;
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->loadCourses();
        $this->loadStandaloneLectures(); // Also reload standalone lectures
    }

    /**
     * Enroll user directly in a free course or lecture (no cart)
     */
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
                session()->flash('error', 'This course is not free.');
                return;
            }

            if (\App\Services\FreeCourseService::enrollInFreeCourse($userId, $id)) {
                // Remove from cart if it was added there
                \App\Models\Shoppingcart::where('user_id', $userId)
                    ->where('course_id', $id)
                    ->delete();
                
                session()->flash('success', 'You have successfully enrolled in this free course!');
                $this->dispatch('cartUpdated');
                $enrolled = true;
            } else {
                session()->flash('info', 'You are already enrolled in this course.');
            }
        } else {
            $lecture = Lecture::find($id);
            if (!$lecture || !$lecture->is_free) {
                session()->flash('error', 'This lecture is not free.');
                return;
            }

            if (\App\Services\FreeCourseService::enrollInFreeLecture($userId, $id)) {
                // Remove from cart if it was added there
                \App\Models\Shoppingcart::where('user_id', $userId)
                    ->where('lecture_id', $id)
                    ->delete();
                
                session()->flash('success', 'You have successfully enrolled in this free lecture!');
                $this->dispatch('cartUpdated');
                $enrolled = true;
            } else {
                session()->flash('info', 'You are already enrolled in this lecture.');
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

        // Prevent free items from being added to cart
        if ($type === 'course') {
            $course = Course::find($id);
            if ($course && $course->is_free) {
                return $this->enrollInFreeCourse($id, 'course');
            }
        } else {
            $lecture = Lecture::find($id);
            if ($lecture && $lecture->is_free) {
                return $this->enrollInFreeCourse($id, 'lecture');
            }
        }

        if ($type === 'course') {
            // Add course to cart
            $course = Course::find($id);
            $price = $course->weekly_price;

            // Check if user has already purchased any lectures from this course
            $purchasedLectures = UserCourse::where('user_id', $userId)
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
            // Add lecture to cart
            $lecture = Lecture::find($id);
            $price = $lecture->weekly_price;

            \App\Models\Shoppingcart::updateOrCreate(
                ['user_id' => $userId, 'lecture_id' => $id],
                ['price' => $price, 'price_type' => null]
            );

            session()->flash('success', 'Lecture added to cart successfully!');
        }

        $this->dispatch('cartUpdated');
    }

    public function isInCart($itemId, $type = 'course')
    {
        if (!auth()->check()) {
            return false;
        }

        if ($type === 'course') {
            // For courses, check if the full course is in cart
            return Shoppingcart::where('user_id', auth()->id())
                ->where('course_id', $itemId)
                ->whereNull('lecture_id') // Only full course in cart
                ->exists();
        } else {
            // For lectures, check if the lecture itself is in cart
            $directlyInCart = Shoppingcart::where('user_id', auth()->id())
                ->where('lecture_id', $itemId)
                ->exists();

            if ($directlyInCart) {
                return true;
            }

            // Also check if the parent FULL COURSE is in cart (only for non-standalone lectures)
            $lecture = Lecture::find($itemId);
            if ($lecture && $lecture->course_id) {
                return Shoppingcart::where('user_id', auth()->id())
                    ->where('course_id', $lecture->course_id)
                    ->whereNull('lecture_id') // Only check for full course in cart
                    ->exists();
            }
        }

        return false;
    }

    public function isPurchased($itemId, $type = 'course')
    {
        if (!auth()->check()) {
            return false;
        }

        if ($type === 'course') {
            // For courses, only return true if the FULL COURSE is purchased (lecture_id is NULL)
            return UserCourse::where('user_id', auth()->id())
                ->where('course_id', $itemId)
                ->whereNull('lecture_id') // Only full course purchase, not individual lectures
                ->where('status', 'active')
                ->exists();
        } else {
            // For lectures, check if the lecture itself is purchased
            $directPurchase = UserCourse::where('user_id', auth()->id())
                ->where('lecture_id', $itemId)
                ->where('status', 'active')
                ->exists();

            if ($directPurchase) {
                return true;
            }

            // Also check if the parent FULL COURSE is purchased (only for non-standalone lectures)
            $lecture = Lecture::find($itemId);
            if ($lecture && $lecture->course_id) {
                return UserCourse::where('user_id', auth()->id())
                    ->where('course_id', $lecture->course_id)
                    ->whereNull('lecture_id') // Only check for full course purchase
                    ->where('status', 'active')
                    ->exists();
            }
        }

        return false;
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

        // Check if the item itself is pending
        $directPending = false;
        foreach ($pendingOrders as $order) {
            $cartItems = json_decode($order->cart_items, true);
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    $itemKey = $type . '_id';
                    if (isset($item[$itemKey]) && $item[$itemKey] == $itemId) {
                        $directPending = true;
                        break 2;
                    }
                }
            }
        }

        // If it's directly pending or not a lecture, return the result
        if ($directPending || $type !== 'lecture') {
            return $directPending;
        }

        // If it's a lecture, also check if its parent FULL COURSE is pending (only for non-standalone lectures)
        $lecture = Lecture::find($itemId);
        if ($lecture && $lecture->course_id) {
            foreach ($pendingOrders as $order) {
                $cartItems = json_decode($order->cart_items, true);
                if (is_array($cartItems)) {
                    foreach ($cartItems as $item) {
                        // Only check for full course purchase, not individual lectures
                        if (isset($item['course_id']) && !isset($item['lecture_id']) && $item['course_id'] == $lecture->course_id) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function render()
    {
        return view('livewire.courseslist', [
            'courses' => $this->courses,
            'categories' => $this->categories,
            'selectedType' => $this->selectedType,
            'selectedCategory' => $this->selectedCategory
        ])->layout('layouts.app');
    }
}
