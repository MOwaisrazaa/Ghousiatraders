<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseViewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show course overview
     */
    public function showCourse(Course $course)
    {
        return redirect()->route('home');
    }

    /**
     * Show purchased course detail page with sidebar navigation
     */
    public function showPurchasedCourse(Course $course)
    {
        return redirect()->route('home');
    }

    /**
     * Show specific lecture
     */
    public function showLecture(Course $course, Lecture $lecture)
    {
        return redirect()->route('home');
    }

    /**
     * Show standalone lecture detail page (without course)
     */
    public function showStandaloneLecture(Lecture $lecture, Request $request)
    {
        return redirect()->route('home');
    }

    /**
     * Show purchased lecture detail page
     */
    public function showPurchasedLecture(Course $course, Lecture $lecture, Request $request)
    {
        return redirect()->route('home');
    }

    /**
     * Get related questions for admin users
     */
    private function getRelatedQuestionsForAdmin($user, $courseId, $lectureId = null)
    {
        // If super admin, show all questions for this course/lecture
        if ($user->isSuperAdmin()) {
            $query = \App\Models\Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->where('course_id', $courseId)
                ->orderBy('created_at', 'desc');

            if ($lectureId) {
                $query->where('lecture_id', $lectureId);
            }

            return $query->get();
        } else {
            // Regular admin only sees questions from their assigned users
            $assignedUserIds = \App\Models\AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            $query = \App\Models\Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->whereIn('user_id', $assignedUserIds)
                ->where('course_id', $courseId)
                ->orderBy('created_at', 'desc');

            if ($lectureId) {
                $query->where('lecture_id', $lectureId);
            }

            return $query->get();
        }
    }

    /**
     * Check if user has access to course
     *
     * @param  \App\Models\Course  $course
     * @param  bool  $isAdminView  Whether to consider admin role for access (default: true)
     * @return bool
     */
    private function userHasAccess($course, $isAdminView = true)
    {
        // Consider admin role only in admin views or marketing views
        if ($isAdminView && (auth()->user()->role === 'admin' || auth()->user()->roles()->where('name', 'admin')->exists())) {
            return true;
        }

        // Check if the user has purchased this course
        return \App\Models\UserCourse::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if user has access to a specific lecture
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\Lecture  $lecture
     * @param  bool  $isAdminView  Whether to consider admin role for access (default: true)
     * @return bool
     */
    private function userHasAccessToLecture($course, $lecture, $isAdminView = true)
    {
        $user = auth()->user();

        // First check if lecture actually belongs to the course
        if ($lecture->course_id != $course->id) {
            return false;
        }

        // Consider admin role only in admin views or marketing views
        if ($isAdminView && ($user->isAdmin() || $user->isSuperAdmin())) {
            return true;
        }

        // First directly check if user has specific lecture access - with course_id
        $hasLectureAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('lecture_id', $lecture->id)
            ->where('status', 'active')
            ->exists();

        if ($hasLectureAccess) {
            return true;
        }

        // Then check if user has full course access
        return \App\Models\UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNull('lecture_id')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get previous item in course navigation
     */
    private function getPreviousItem($course, $currentType, $currentId)
    {
        $result = ['item' => null, 'type' => null];

        if ($currentType == 'lecture') {
            // Get all lectures ordered by id
            $lectures = $course->lectures()->orderBy('id')->get();
            $currentIndex = $lectures->search(function($item) use ($currentId) {
                return $item->id == $currentId;
            });

            if ($currentIndex > 0) {
                // There's a previous lecture
                $result['item'] = $lectures[$currentIndex - 1];
                $result['type'] = 'lecture';
            }
        }

        return $result;
    }

    /**
     * Get next item in course navigation
     */
    private function getNextItem($course, $currentType, $currentId)
    {
        $result = ['item' => null, 'type' => null];

        if ($currentType == 'lecture') {
            // Get all lectures ordered by id
            $lectures = $course->lectures()->orderBy('id')->get();
            $currentIndex = $lectures->search(function($item) use ($currentId) {
                return $item->id == $currentId;
            });

            if ($currentIndex < $lectures->count() - 1) {
                // There's a next lecture
                $result['item'] = $lectures[$currentIndex + 1];
                $result['type'] = 'lecture';
            }
        }

        return $result;
    }
}
