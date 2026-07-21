<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total_points',
        'percentage_score',
        'status',
        'current_question_index',
        'current_question_started_at',
        'started_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'current_question_started_at' => 'datetime',
    ];

    /**
     * Relationship: Attempt belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Attempt belongs to a Quiz.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relationship: Attempt has many Answers.
     */
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Check if this attempt is in progress.
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if this attempt is completed.
     */
    public function isCompleted()
    {
        return in_array($this->status, ['completed', 'passed', 'failed', 'pending_review']);
    }

    /**
     * Check if this attempt is passed.
     */
    public function isPassed()
    {
        return $this->status === 'passed';
    }

    /**
     * Check if this attempt is failed.
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if this attempt is pending review.
     */
    public function isPendingReview()
    {
        return $this->status === 'pending_review';
    }

    /**
     * Get time remaining in seconds for timed quizzes.
     */
    public function getTimeRemainingAttribute()
    {
        if (!$this->quiz->time_limit || !$this->started_at) {
            return null;
        }

        $endTime = $this->started_at->addMinutes($this->quiz->time_limit);
        $now = now();

        if ($now->gt($endTime)) {
            return 0;
        }

        return $now->diffInSeconds($endTime);
    }

    /**
     * Check if time is up for this attempt.
     */
    public function isTimeUp()
    {
        if (!$this->quiz->time_limit || !$this->started_at) {
            return false;
        }

        $endTime = $this->started_at->addMinutes($this->quiz->time_limit);
        return now()->gt($endTime);
    }

    /**
     * Get the number of questions answered in this attempt.
     */
    public function getAnsweredCount()
    {
        return $this->answers()->count();
    }

    /**
     * Get the number of questions in the quiz.
     */
    public function getTotalQuestionCount()
    {
        return $this->quiz->questions()->count();
    }

    /**
     * Calculate and update the score for this attempt.
     */
    public function calculateScore()
    {
        // Use single query to get both score and pending review status
        $answerStats = \DB::table('quiz_answers')
            ->where('quiz_attempt_id', $this->id)
            ->selectRaw('
                SUM(points_earned) as total_score,
                COUNT(CASE WHEN review_status = ? THEN 1 END) as pending_count
            ', ['pending_review'])
            ->first();
        
        $score = $answerStats->total_score ?? 0;
        $hasPendingReviews = $answerStats->pending_count > 0;
        
        // Get total points from quiz (use cached value if available)
        $totalPoints = \DB::table('quiz_questions')
            ->where('quiz_id', $this->quiz_id)
            ->sum('points');
        
        $percentageScore = $totalPoints > 0 ? round(($score / $totalPoints) * 100) : 0;

        // Determine new status based on pending reviews and score
        if ($hasPendingReviews) {
            $newStatus = 'pending_review';
        } elseif ($percentageScore >= $this->quiz->passing_score) {
            $newStatus = 'passed';
        } else {
            $newStatus = 'failed';
        }

        // Use direct DB update for better performance
        \DB::table('quiz_attempts')
            ->where('id', $this->id)
            ->update([
                'score' => $score,
                'total_points' => $totalPoints,
                'percentage_score' => $percentageScore,
                'status' => $newStatus,
                'completed_at' => now(),
                'updated_at' => now(),
            ]);
        
        // Refresh the model to get updated values
        $this->refresh();
    }
}
