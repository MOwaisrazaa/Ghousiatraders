<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'name',
        'slug',
        'description',
        'instructor',
        'intro_video_url',
        'weekly_price',
        'monthly_price',
        'youtube_url',
        'duration',
        'image_path',
        'pdf_file_path',
        'is_free',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lecture) {
            if (empty($lecture->slug)) {
                $lecture->slug = \Illuminate\Support\Str::slug($lecture->name);
            }
        });

        static::updating(function ($lecture) {
            if (empty($lecture->slug)) {
                $lecture->slug = \Illuminate\Support\Str::slug($lecture->name);
            }
        });
    }

    /**
     * Relationship: Lecture belongs to a Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relationship: Lecture has many ShoppingCart entries.
     */
    public function shoppingCarts()
    {
        return $this->hasMany(ShoppingCart::class, 'lecture_id');
    }

    /**
     * Relationship: Lecture has many Features.
     */
    public function features()
    {
        return $this->hasMany(LectureFeature::class);
    }

    /**
     * Relationship: Lecture has many Quizzes.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Relationship: Get the first quiz for this lecture (convenience method).
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Get features by type.
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturesByType($type)
    {
        return $this->features()
            ->where('feature_type', $type)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all ratings for this lecture
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Get the average rating for this lecture
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()
            ->where('is_approved', true)
            ->avg('rating') ?: 0;
    }

    /**
     * Get the total number of ratings for this lecture
     */
    public function getRatingCountAttribute()
    {
        return $this->ratings()
            ->where('is_approved', true)
            ->count();
    }

    /**
     * Check if a user has passed all quizzes for this lecture
     */
    public function hasPassedAllQuizzes($userId)
    {
        $quizzes = $this->quizzes()->where('is_required', true)->get();
        
        if ($quizzes->isEmpty()) {
            return true;  // No quizzes to pass
        }
        
        foreach ($quizzes as $quiz) {
            if (!$quiz->isPassed($userId)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get the materials for the lecture.
     */
    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    /**
     * Get formatted duration for display.
     */
    public function getDurationFormattedAttribute()
    {
        return $this->duration ? $this->duration . ' min' : 'N/A';
    }

    /**
     * Get the progress records for this lecture.
     */
    public function progress()
    {
        return $this->hasMany(UserLectureProgress::class);
    }

    /**
     * Get a specific user's progress for this lecture.
     *
     * @param int $userId
     * @return \App\Models\UserLectureProgress|null
     */
    public function getProgressForUser($userId)
    {
        return $this->progress()->where('user_id', $userId)->first();
    }

    /**
     * Get the completion percentage for a user.
     *
     * @param int $userId
     * @return float
     */
    public function getCompletionPercentage($userId)
    {
        $progress = $this->getProgressForUser($userId);
        return $progress ? $progress->progress_percent : 0;
    }

    /**
     * Check if the lecture is completed by a user.
     *
     * @param int $userId
     * @return bool
     */
    public function isCompletedByUser($userId)
    {
        $progress = $this->getProgressForUser($userId);
        return $progress ? $progress->completed : false;
    }

    /**
     * Check if this lecture can be purchased separately
     */
    public function canBePurchasedSeparately()
    {
        // If lecture has no course, it's standalone - always purchasable
        if (!$this->course_id) {
            return true;
        }
        
        // If lecture belongs to a course, check course's purchase model
        return $this->course && $this->course->canPurchaseLecturesSeparately();
    }

    /**
     * Check if this lecture belongs to a restricted course
     */
    public function belongsToRestrictedCourse()
    {
        return $this->course_id && $this->course && $this->course->isPurchaseRestricted();
    }
}
