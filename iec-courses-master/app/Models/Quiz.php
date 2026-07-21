<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lecture_id',
        'title',
        'description',
        'total_points',
        'passing_score',
        'is_required',
        'time_limit',
    ];

    /**
     * Relationship: Quiz belongs to a Lecture.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Relationship: Quiz has many Questions.
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    /**
     * Relationship: Quiz has many Attempts by users.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get attempts by a specific user.
     */
    public function attemptsByUser($userId)
    {
        return $this->attempts()->where('user_id', $userId);
    }

    /**
     * Check if a user has passed this quiz.
     */
    public function isPassed($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'passed')
            ->exists();
    }

    /**
     * Check if a user has an in-progress attempt.
     */
    public function hasInProgressAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->exists();
    }

    /**
     * Get the most recent attempt by a user.
     */
    public function getLatestAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    /**
     * Get the in-progress attempt by a user.
     */
    public function getInProgressAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->first();
    }
} 