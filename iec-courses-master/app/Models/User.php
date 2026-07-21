<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location',
        'phone',
        'about',
        'google_id',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relationship: User has many shopping cart entries.
     */
    public function shoppingcarts()
    {
        return $this->hasMany(Shoppingcart::class);
    }
    public function roles(){
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Get the courses that this user has access to.
     */
    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    /**
     * Get the courses that this user has access to.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses')
            ->withPivot('status', 'expires_at')
            ->withTimestamps();
    }

    /**
     * Get the lectures that this user has access to.
     */
    public function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'user_courses')
            ->withPivot('status', 'expires_at')
            ->withTimestamps();
    }

    /**
     * Get the users assigned to this admin.
     * Only applicable for admin users.
     */
    public function assignedUsers()
    {
        return $this->hasMany(AdminUserAssignment::class, 'admin_id')
            ->with('user');
    }

    /**
     * Get the admin that this user is assigned to.
     * Only applicable for regular users.
     */
    public function assignedAdmin()
    {
        return $this->hasOne(AdminUserAssignment::class, 'user_id')
            ->with('admin');
    }

    /**
     * Get admin permissions for this user.
     * Only applicable for admin users.
     */
    public function permissions()
    {
        return $this->hasMany(AdminPermission::class, 'admin_user_id');
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin()
    {
        return $this->roles()->where('name', 'Super Admin')->exists();
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->roles()->whereIn('name', ['Admin', 'Super Admin'])->exists();
    }

    /**
     * Get questions from assigned users.
     * Only applicable for admin users.
     */
    public function assignedUserQuestions()
    {
        $assignedUserIds = $this->assignedUsers->pluck('user.id')->toArray();
        return Question::whereIn('user_id', $assignedUserIds);
    }

    /**
     * Get the lecture progress records for this user.
     */
    public function lectureProgress()
    {
        return $this->hasMany(UserLectureProgress::class);
    }

    /**
     * Get progress for a specific lecture.
     *
     * @param int $lectureId
     * @return \App\Models\UserLectureProgress|null
     */
    public function getProgressForLecture($lectureId)
    {
        return $this->lectureProgress()->where('lecture_id', $lectureId)->first();
    }

    /**
     * Get overall progress for a course.
     *
     * @param int $courseId
     * @return float Percentage of completion (0-100)
     */
    public function getCourseProgress($courseId)
    {
        // Get all lectures in the course
        $course = Course::find($courseId);
        if (!$course) return 0;

        $lectureCount = $course->lectures()->count();
        if ($lectureCount === 0) return 0;

        // Get progress for each lecture in this course
        $lectureProgress = $this->lectureProgress()
            ->where('course_id', $courseId)
            ->get();

        if ($lectureProgress->isEmpty()) return 0;

        // Calculate overall percentage
        $totalPercentage = $lectureProgress->sum('progress_percent');
        $completedLectures = $lectureProgress->where('completed', true)->count();

        // Use a weighted approach:
        // 50% based on average lecture progress + 50% based on completion ratio
        $progressPercent = 0;

        if ($lectureProgress->count() > 0) {
            $averageProgress = $totalPercentage / $lectureCount;
            $completionRatio = ($completedLectures / $lectureCount) * 100;

            $progressPercent = ($averageProgress * 0.5) + ($completionRatio * 0.5);
        }

        return min(100, $progressPercent);
    }

    /**
     * Check if a course is completed.
     *
     * @param int $courseId
     * @return bool
     */
    public function hasCourseCompleted($courseId)
    {
        return $this->getCourseProgress($courseId) >= 90;
    }

    /**
     * Get the certificate requests made by this user.
     */
    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    /**
     * Get the certificates issued to this user.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if user has a pending certificate request for a course
     *
     * @param int $courseId
     * @param int|null $lectureId
     * @return bool
     */
    public function hasPendingCertificateRequest($courseId, $lectureId = null)
    {
        $query = $this->certificateRequests()
            ->whereIn('status', ['pending', 'in_review']);
        
        // Handle null course_id for standalone lectures
        if ($courseId === null) {
            $query->whereNull('course_id');
        } else {
            $query->where('course_id', $courseId);
        }
        
        if ($lectureId !== null) {
            $query->where('lecture_id', $lectureId);
        } else {
            $query->whereNull('lecture_id');
        }
        
        return $query->exists();
    }

    /**
     * Check if user can request a certificate for a course.
     * User can request if:
     * 1. They have completed the course (>= 90% progress)
     * 2. They have passed all required quizzes (if any exist)
     * 3. They don't already have a pending or approved certificate request
     *
     * @param int $courseId
     * @return bool
     */
    public function canRequestCertificate($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) return false;

        // First, check if user has completed at least 90% of the course
        // This applies to all courses (with or without quizzes)
        $courseProgress = $this->getCourseProgress($courseId);
        if ($courseProgress < 90) {
            return false;
        }

        // Get all quizzes for this course through lectures
        $quizzes = $course->quizzes()->get();
        
        // If there are quizzes, check if all are passed
        if ($quizzes->count() > 0) {
            foreach ($quizzes as $quiz) {
                $passed = QuizAttempt::where('user_id', $this->id)
                    ->where('quiz_id', $quiz->id)
                    ->where('status', 'passed')
                    ->exists();

                if (!$passed) {
                    return false;
                }
            }
        }

        // Check if user already has a certificate or pending request
        $existingRequest = $this->certificateRequests()
            ->where('course_id', $courseId)
            ->whereNull('lecture_id')
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        $existingCertificate = $this->certificates()
            ->where('course_id', $courseId)
            ->whereNull('lecture_id')
            ->exists();

        return !($existingRequest || $existingCertificate);
    }

    /**
     * Get the devices associated with this user.
     */
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     * Get the primary device of the user
     */
    public function primaryDevice()
    {
        return $this->devices()->where('is_primary', true)->first();
    }

    /**
     * Check if user has reached the maximum allowed IP addresses (3)
     */
    public function hasReachedMaxIpAddresses()
    {
        return $this->devices()->distinct('ip_address')->count('ip_address') >= 3;
    }

    /**
     * Get the suggestions and feedback submitted by this user.
     */
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
