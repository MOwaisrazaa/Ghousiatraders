<?php

namespace App\Services;

use App\Models\Question;
use App\Models\User;
use App\Models\UserCourse;

class QuestionLimitService
{
    // Question limits
    const QUESTIONS_PER_LECTURE = 5;
    const QUESTIONS_PER_COURSE = 15;

    /**
     * Check if user can ask a question for a lecture
     *
     * @param User $user
     * @param int|null $courseId
     * @param int|null $lectureId
     * @return array ['can_ask' => bool, 'remaining' => int, 'limit' => int, 'message' => string]
     */
    public function canAskQuestion(User $user, ?int $courseId, ?int $lectureId = null): array
    {
        // For standalone lectures (no course_id), only check lecture limit
        if (!$courseId && $lectureId) {
            return $this->checkStandaloneLectureLimit($user, $lectureId);
        }
        
        if ($lectureId) {
            return $this->checkLectureLimit($user, $courseId, $lectureId);
        } else {
            return $this->checkCourseLimit($user, $courseId);
        }
    }

    /**
     * Check standalone lecture question limit (no course)
     */
    private function checkStandaloneLectureLimit(User $user, int $lectureId): array
    {
        // Count questions asked by user for this standalone lecture
        $questionsAsked = Question::where('user_id', $user->id)
            ->where('lecture_id', $lectureId)
            ->whereNull('course_id')
            ->count();

        $remaining = max(0, self::QUESTIONS_PER_LECTURE - $questionsAsked);

        return [
            'can_ask' => $remaining > 0,
            'remaining' => $remaining,
            'limit' => self::QUESTIONS_PER_LECTURE,
            'message' => $remaining > 0 
                ? "You have $remaining question(s) left for this lecture."
                : 'You have reached the question limit for this lecture.'
        ];
    }

    /**
     * Check lecture-level question limit
     */
    private function checkLectureLimit(User $user, ?int $courseId, ?int $lectureId): array
    {
        // If no lecture_id, return unlimited for lecture level
        if (!$lectureId) {
            return [
                'can_ask' => true,
                'remaining' => 999,
                'limit' => self::QUESTIONS_PER_LECTURE,
                'message' => 'No lecture selected.'
            ];
        }

        // Check if user has access to this course (either full course or specific lecture)
        // User can have access in two ways:
        // 1. Full course access (lecture_id = NULL in user_courses)
        // 2. Specific lecture access (lecture_id = $lectureId in user_courses)
        $hasAccess = UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->where(function($query) use ($lectureId) {
                $query->whereNull('lecture_id')  // Full course access
                      ->orWhere('lecture_id', $lectureId);  // Specific lecture access
            })
            ->exists();

        if (!$hasAccess) {
            return [
                'can_ask' => false,
                'remaining' => 0,
                'limit' => self::QUESTIONS_PER_LECTURE,
                'message' => 'You do not have access to this lecture.'
            ];
        }

        // Count questions asked by user for this lecture
        $questionsAsked = Question::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('lecture_id', $lectureId)
            ->count();

        $remaining = max(0, self::QUESTIONS_PER_LECTURE - $questionsAsked);

        return [
            'can_ask' => $remaining > 0,
            'remaining' => $remaining,
            'limit' => self::QUESTIONS_PER_LECTURE,
            'message' => $remaining > 0 
                ? "You have $remaining question(s) left for this lecture."
                : 'You have reached the question limit for this lecture.'
        ];
    }

    /**
     * Check course-level question limit
     */
    private function checkCourseLimit(User $user, int $courseId): array
    {
        // Verify user has access to this course
        $hasAccess = UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->exists();

        if (!$hasAccess) {
            return [
                'can_ask' => false,
                'remaining' => 0,
                'limit' => self::QUESTIONS_PER_COURSE,
                'message' => 'You do not have access to this course.'
            ];
        }

        // Count questions asked by user for this course (all lectures)
        $questionsAsked = Question::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->count();

        $remaining = max(0, self::QUESTIONS_PER_COURSE - $questionsAsked);

        return [
            'can_ask' => $remaining > 0,
            'remaining' => $remaining,
            'limit' => self::QUESTIONS_PER_COURSE,
            'message' => $remaining > 0 
                ? "You have $remaining question(s) left for this course."
                : 'You have reached the question limit for this course.'
        ];
    }

    /**
     * Determine if user has full course access
     */
    private function hasFullCourseAccess(User $user, int $courseId): bool
    {
        return UserCourse::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->whereNull('lecture_id')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get question limits info for user
     *
     * @param User $user
     * @param int|null $courseId
     * @param int|null $lectureId
     * @return array
     */
    public function getQuestionLimitsInfo(User $user, ?int $courseId, ?int $lectureId = null): array
    {
        // For standalone lectures (no course_id)
        if (!$courseId && $lectureId) {
            $lectureLimit = $this->checkStandaloneLectureLimit($user, $lectureId);
            return [
                'lecture' => $lectureLimit,
                'course' => null,
                'show_lecture_limit' => true,
                'show_course_limit' => false,
                'can_ask' => $lectureLimit['can_ask'],
                'blocking_reason' => !$lectureLimit['can_ask'] ? $lectureLimit['message'] : null
            ];
        }

        // For course lectures
        $lectureLimit = $this->checkLectureLimit($user, $courseId, $lectureId);
        $courseLimit = $this->checkCourseLimit($user, $courseId);
        
        // Determine what limits to show
        $hasFullCourse = $this->hasFullCourseAccess($user, $courseId);
        
        // If user has full course access, show only course limit
        // If user has only lecture access, show only lecture limit
        $showLectureLimit = !$hasFullCourse && $lectureId;
        $showCourseLimit = $hasFullCourse || !$lectureId;

        return [
            'lecture' => $lectureLimit,
            'course' => $courseLimit,
            'show_lecture_limit' => $showLectureLimit,
            'show_course_limit' => $showCourseLimit,
            'can_ask' => $lectureLimit['can_ask'] && $courseLimit['can_ask'],
            'blocking_reason' => !$lectureLimit['can_ask'] 
                ? $lectureLimit['message'] 
                : (!$courseLimit['can_ask'] ? $courseLimit['message'] : null)
        ];
    }
}
