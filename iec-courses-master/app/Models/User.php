<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        'username',
        'email',
        'password',
        'location',
        'phone',
        'about',
        'google_id',
        'role',
        'status',
        'group',
        'shipping_address',
        'billing_address',
        'notes',
        'profile_image',
        'password_change_required',
        'last_login_at',
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
        'password_change_required' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relationship: User has many shopping cart entries.
     */
    public function shoppingcarts()
    {
        return $this->hasMany(Shoppingcart::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses')
            ->withPivot('status', 'expires_at')
            ->withTimestamps();
    }

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'user_courses')
            ->withPivot('status', 'expires_at')
            ->withTimestamps();
    }

    public function assignedUsers()
    {
        return $this->hasMany(AdminUserAssignment::class, 'admin_id')
            ->with('user');
    }

    public function assignedAdmin()
    {
        return $this->hasOne(AdminUserAssignment::class, 'user_id')
            ->with('admin');
    }

    public function permissions()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('admin_permissions')) {
            return $this->hasMany(AdminPermission::class, 'admin_user_id');
        }
        return $this->hasManyThrough(
            RolePermission::class,
            Role::class,
            'id',
            'role_id',
            'id',
            'id'
        );
    }

    public function isSuperAdmin()
    {
        return $this->roles()->where('name', 'Super Admin')->exists();
    }

    public function isAdmin()
    {
        return $this->roles()->whereIn('name', ['Admin', 'Super Admin', 'Administrator', 'Manager', 'Editor', 'Customer Support', 'Sales Agent', 'Inventory Manager'])->exists();
    }

    /**
     * Check if user has permission for a specific module/action or page.
     * Super Admin always has full access.
     */
    public function hasPermission($permissionKey)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check role_permissions table through assigned roles
        $roleIds = $this->roles()->pluck('roles.id')->toArray();
        if (!empty($roleIds)) {
            $moduleKey = strtok($permissionKey, '.');
            $hasRolePerm = RolePermission::whereIn('role_id', $roleIds)
                ->where(function($q) use ($permissionKey, $moduleKey) {
                    $q->where('permission', $permissionKey)
                      ->orWhere('permission', $moduleKey)
                      ->orWhere('permission', $moduleKey . '.manage')
                      ->orWhere('permission', $moduleKey . '.view');
                })
                ->where('is_allowed', true)
                ->exists();

            if ($hasRolePerm) {
                return true;
            }
        }

        // Fallback to legacy AdminPermission table only if table exists in database
        if (\Illuminate\Support\Facades\Schema::hasTable('admin_permissions')) {
            $moduleKey = strtok($permissionKey, '.');
            return AdminPermission::where('admin_user_id', $this->id)
                ->where('page', $moduleKey)
                ->where('is_allowed', true)
                ->exists();
        }

        return false;
    }

    /**
     * Accessor for profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return null;
        }

        if (Str::startsWith($this->profile_image, ['http://', 'https://'])) {
            return $this->profile_image;
        }

        $cleanPath = ltrim(str_replace('\\', '/', $this->profile_image), '/');
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }

        return asset($cleanPath);
    }

    /**
     * Get initials for circular avatar fallback.
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get user primary role display.
     */
    public function getPrimaryRoleAttribute()
    {
        return $this->roles->first();
    }

    public function assignedUserQuestions()
    {
        $assignedUserIds = $this->assignedUsers->pluck('user.id')->toArray();
        return Question::whereIn('user_id', $assignedUserIds);
    }

    public function lectureProgress()
    {
        return $this->hasMany(UserLectureProgress::class);
    }

    public function getProgressForLecture($lectureId)
    {
        return $this->lectureProgress()->where('lecture_id', $lectureId)->first();
    }

    public function getCourseProgress($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) return 0;

        $lectureCount = $course->lectures()->count();
        if ($lectureCount === 0) return 0;

        $lectureProgress = $this->lectureProgress()
            ->where('course_id', $courseId)
            ->get();

        if ($lectureProgress->isEmpty()) return 0;

        $totalPercentage = $lectureProgress->sum('progress_percent');
        $completedLectures = $lectureProgress->where('completed', true)->count();

        $progressPercent = 0;
        if ($lectureProgress->count() > 0) {
            $averageProgress = $totalPercentage / $lectureCount;
            $completionRatio = ($completedLectures / $lectureCount) * 100;
            $progressPercent = ($averageProgress * 0.5) + ($completionRatio * 0.5);
        }

        return min(100, $progressPercent);
    }

    public function hasCourseCompleted($courseId)
    {
        return $this->getCourseProgress($courseId) >= 90;
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function hasPendingCertificateRequest($courseId, $lectureId = null)
    {
        $query = $this->certificateRequests()
            ->whereIn('status', ['pending', 'in_review']);
        
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

    public function canRequestCertificate($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) return false;

        $courseProgress = $this->getCourseProgress($courseId);
        if ($courseProgress < 90) {
            return false;
        }

        $quizzes = $course->quizzes()->get();
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

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function primaryDevice()
    {
        return $this->devices()->where('is_primary', true)->first();
    }

    public function hasReachedMaxIpAddresses()
    {
        return $this->devices()->distinct('ip_address')->count('ip_address') >= 3;
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
