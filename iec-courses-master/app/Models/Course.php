<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'long_description',
        'instructor',
        'intro_video_url',
        'weekly_price',
        'monthly_price',
        'image_path',
        'category_id',
        'is_free',
        'purchase_model',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = \Illuminate\Support\Str::slug($course->name);
            }
        });

        static::updating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = \Illuminate\Support\Str::slug($course->name);
            }
        });
    }

    /**
     * Relationship: Course belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Course has many lectures.
     */
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    /**
     * Relationship: Course belongs to many homepage sections.
     */
    public function homepageSections()
    {
        return $this->belongsToMany(HomepageSection::class, 'homepage_section_product', 'course_id', 'homepage_section_id')
            ->withTimestamps();
    }

    /**
     * Relationship: Course has many shopping cart entries.
     */
    public function shoppingCarts()
    {
        return $this->hasMany(ShoppingCart::class);
    }

    /**
     * Relationship: Course has many features.
     */
    public function features()
    {
        return $this->hasMany(CourseFeature::class);
    }

    /**
     * Get course features by type
     */
    public function getFeaturesByType($type)
    {
        return $this->features()->where('feature_type', $type)->orderBy('sort_order')->get();
    }

    /**
     * Get all ratings for this course
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Get the average rating for this course
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()
            ->where('is_approved', true)
            ->avg('rating') ?: 0;
    }

    /**
     * Get the total number of ratings for this course
     */
    public function getRatingCountAttribute()
    {
        return $this->ratings()
            ->where('is_approved', true)
            ->count();
    }

    /**
     * Get formatted price for display.
     */
    public function getPriceFormattedAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    /**
     * Get progress records for this course.
     */
    public function progress()
    {
        return $this->hasMany(UserLectureProgress::class);
    }

    /**
     * Get overall progress percentage for a specific user.
     *
     * @param int $userId
     * @return float
     */
    public function getProgressPercentageForUser($userId)
    {
        $user = User::find($userId);
        if (!$user) return 0;
        
        return $user->getCourseProgress($this->id);
    }

    /**
     * Check if course is completed by a user.
     *
     * @param int $userId
     * @return bool
     */
    public function isCompletedByUser($userId)
    {
        $user = User::find($userId);
        if (!$user) return false;
        
        return $user->hasCourseCompleted($this->id);
    }

    /**
     * Get count of completed lectures for a user.
     *
     * @param int $userId
     * @return int
     */
    public function getCompletedLectureCountForUser($userId)
    {
        return $this->progress()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->count();
    }

    /**
     * Get total lecture count.
     *
     * @return int
     */
    public function getTotalLectureCountAttribute()
    {
        return $this->lectures()->count();
    }

    /**
     * Get the quizzes for this course (through lectures).
     */
    public function quizzes()
    {
        return Quiz::whereHas('lecture', function($query) {
            $query->where('course_id', $this->id);
        });
    }

    /**
     * Get the certificate requests for this course.
     */
    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    /**
     * Get the certificates issued for this course.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if a user is eligible for a certificate.
     * Eligibility is based on course completion and passing required quizzes.
     *
     * @param int $userId
     * @return bool
     */
    public function isUserEligibleForCertificate($userId)
    {
        $user = User::find($userId);
        if (!$user) return false;
        
        return $user->canRequestCertificate($this->id);
    }

    /**
     * Get the suggestions and feedback for this course.
     */
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    /**
     * Check if lectures can be purchased separately for this course
     */
    public function canPurchaseLecturesSeparately()
    {
        return $this->purchase_model === 'flexible';
    }

    /**
     * Check if this course is restricted to whole-course purchase only
     */
    public function isPurchaseRestricted()
    {
        return $this->purchase_model === 'restricted';
    }
}
