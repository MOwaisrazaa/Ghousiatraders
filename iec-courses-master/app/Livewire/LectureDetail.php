<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Order;
use App\Models\UserCourse;

class LectureDetail extends Component
{
    public $course;
    public $lecture;
    public $features;
    public $instructorProfiles = [];

    public function mount($course = null, $lecture = null)
    {
        // Get route parameters from request if not passed as parameters
        // The route parameter names differ:
        // - /lecture/{course}/{lecture} has both 'course' and 'lecture' parameters
        // - /lecture/{lecture} has only 'lecture' parameter
        // - /lecture/standalone/{lecture} has 'lecture' parameter

        $routeLecture = request()->route('lecture');
        $routeCourse = request()->route('course');

        // For standalone routes, if only lecture param exists, use it as the first parameter
        if ($routeLecture && !$routeCourse) {
            $course = $routeLecture;  // The {lecture} parameter from /lecture/{lecture}
            $lecture = null;
        } else {
            // For course+lecture routes, use both parameters
            if ($course === null && $routeCourse !== null) {
                $course = $routeCourse;
            }
            if ($lecture === null && $routeLecture !== null) {
                $lecture = $routeLecture;
            }
        }

        // Handle two route cases:
        // 1. Standalone lecture: /lecture/{lecture-slug} - only lecture parameter
        // 2. Course lecture: /lecture/{course-slug}/{lecture-slug} - both parameters

        // If $lecture is null, it means we're in standalone mode (route: /lecture/{lecture})
        if ($lecture === null) {
            // Standalone lecture mode: $course parameter is actually the lecture slug
            $this->lecture = Lecture::where('slug', $course)
                                   ->whereNull('course_id')
                                   ->with(['ratings' => function($query) {
                                       $query->where('is_approved', true)
                                            ->where('show_publicly', true)
                                            ->with('user')
                                            ->latest();
                                   }])->firstOrFail();
            $this->course = null;
        } else {
            // Course lecture mode: both parameters provided
            $this->course = Course::where('slug', $course)->firstOrFail();
            $this->lecture = Lecture::where('slug', $lecture)
                                   ->where('course_id', $this->course->id)
                                   ->with(['ratings' => function($query) {
                                       $query->where('is_approved', true)
                                            ->where('show_publicly', true)
                                            ->with('user')
                                            ->latest();
                                   }])
                                   ->firstOrFail();
        }

        // Load ratings for standalone lectures
        if (!$this->course) {
            $this->lecture->load(['ratings' => function($query) {
                $query->where('is_approved', true)
                     ->where('show_publicly', true)
                     ->with('user')
                     ->latest();
            }]);
        }

        // Handle instructor display
        if (!$this->lecture->instructor) {
            if ($this->course && $this->course->instructor) {
                $this->lecture->instructor = $this->course->instructor;
            } else {
                $this->lecture->instructor = 'Instructor'; // Default value if none set
            }
        }

        $this->features = [
            'learn' => $this->lecture->getFeaturesByType('learn'),
            'requirement' => $this->lecture->getFeaturesByType('requirement'),
        ];

        // Load instructor profiles
        $this->loadInstructorProfiles();
    }

    /**
     * Load instructor profiles for the lecture and course
     */
    private function loadInstructorProfiles()
    {
        // Get instructor names
        $instructorNames = collect([
            $this->lecture->instructor,
            $this->course ? $this->course->instructor : null
        ])
        ->filter()
        ->unique()
        ->values();

        if ($instructorNames->isEmpty()) {
            return;
        }

        // Load instructor profiles with debug info
        $profiles = \App\Models\InstructorProfile::whereIn('name', $instructorNames)
            ->where('is_active', true)
            ->get();

        // Log retrieved profiles for debugging
        \Log::info('Instructor profiles loaded for lecture', [
            'lecture_id' => $this->lecture->id,
            'instructor_names' => $instructorNames->toArray(),
            'profiles_count' => $profiles->count(),
            'profiles' => $profiles->pluck('name', 'id')->toArray()
        ]);

        $this->instructorProfiles = $profiles->keyBy('name')->toArray();
    }

    public function enrollInFreeCourse($id, $type = 'lecture')
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please log in to enroll in free lectures.');
            return redirect()->route('sign-in');
        }

        $userId = auth()->id();
        $enrolled = false;

        if ($type === 'lecture') {
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

    public function addToCart($id)
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please log in to add items to your cart.');
            return redirect()->route('sign-in');
        }

        // Check if lecture belongs to restricted course
        if ($this->lecture->belongsToRestrictedCourse()) {
            session()->flash('error', 'This lecture can only be purchased as part of the complete course.');
            return;
        }

        // Prevent free lectures from being added to cart
        if ($this->lecture->is_free) {
            session()->flash('error', 'Free lectures cannot be added to cart. Please use the "Enroll Now (Free)" button instead.');
            return;
        }

        $userId = auth()->id();
        $lecturePrice = $this->lecture->weekly_price;

        \App\Models\Shoppingcart::updateOrCreate(
            ['user_id' => $userId, 'lecture_id' => $id],
            ['price' => $lecturePrice, 'price_type' => null]
        );

        session()->flash('success', 'Lecture added to cart successfully!');

        // Dispatch event to update cart icon
        $this->dispatch('cartUpdated');
    }

    public function isPurchased($itemId, $type = 'lecture')
    {
        if (!auth()->check()) {
            return false;
        }

        if ($type === 'lecture') {
            return UserCourse::where('user_id', auth()->id())
                ->where('lecture_id', $itemId)
                ->where('status', 'active')
                ->exists();
        }

        return UserCourse::where('user_id', auth()->id())
            ->where($type.'_id', $itemId)
            ->where('status', 'active')
            ->exists();
    }

    public function isPending($itemId, $type = 'lecture')
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
        return view('livewire.lecture-detail', [
            'lecture' => $this->lecture,
            'course' => $this->course
        ])->layout('layouts.app');
    }
}
