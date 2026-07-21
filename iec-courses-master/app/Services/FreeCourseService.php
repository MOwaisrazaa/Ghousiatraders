<?php

namespace App\Services;

use App\Models\UserCourse;
use App\Models\Course;
use App\Models\Lecture;

class FreeCourseService
{
    /**
     * Enroll a user in a free course
     *
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public static function enrollInFreeCourse($userId, $courseId)
    {
        $course = Course::find($courseId);
        
        if (!$course || !$course->is_free) {
            return false;
        }

        // Check if user already has access
        $existingAccess = UserCourse::where([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lecture_id' => null,
            'status' => 'active'
        ])->first();

        if ($existingAccess) {
            return true; // Already enrolled
        }

        // Create enrollment record
        UserCourse::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lecture_id' => null,
            'status' => 'active',
            'order_id' => null, // No order for free courses
        ]);

        return true;
    }

    /**
     * Enroll a user in a free lecture
     *
     * @param int $userId
     * @param int $lectureId
     * @return bool
     */
    public static function enrollInFreeLecture($userId, $lectureId)
    {
        $lecture = Lecture::find($lectureId);
        
        if (!$lecture || !$lecture->is_free) {
            return false;
        }

        // Check if user already has access - handle standalone lectures (null course_id)
        $existingAccessQuery = UserCourse::where([
            'user_id' => $userId,
            'lecture_id' => $lectureId,
            'status' => 'active'
        ]);
        
        if ($lecture->course_id) {
            $existingAccessQuery->where('course_id', $lecture->course_id);
        } else {
            $existingAccessQuery->whereNull('course_id');
        }
        
        $existingAccess = $existingAccessQuery->first();

        if ($existingAccess) {
            return true; // Already enrolled
        }

        // Create enrollment record
        UserCourse::create([
            'user_id' => $userId,
            'course_id' => $lecture->course_id, // Will be null for standalone lectures
            'lecture_id' => $lectureId,
            'status' => 'active',
            'order_id' => null, // No order for free lectures
        ]);

        return true;
    }

    /**
     * Check if a user is enrolled in a free course
     *
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public static function isEnrolledInFreeCourse($userId, $courseId)
    {
        return UserCourse::where([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lecture_id' => null,
            'status' => 'active'
        ])->exists();
    }

    /**
     * Check if a user is enrolled in a free lecture
     *
     * @param int $userId
     * @param int $lectureId
     * @return bool
     */
    public static function isEnrolledInFreeLecture($userId, $lectureId)
    {
        $lecture = Lecture::find($lectureId);
        
        if (!$lecture) {
            return false;
        }

        // Handle standalone lectures (null course_id)
        $query = UserCourse::where([
            'user_id' => $userId,
            'lecture_id' => $lectureId,
            'status' => 'active'
        ]);
        
        if ($lecture->course_id) {
            $query->where('course_id', $lecture->course_id);
        } else {
            $query->whereNull('course_id');
        }

        return $query->exists();
    }
}
