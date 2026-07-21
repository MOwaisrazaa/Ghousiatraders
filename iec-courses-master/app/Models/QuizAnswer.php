<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_attempt_id',
        'quiz_question_id',
        'quiz_option_id',
        'answer_text',
        'is_correct',
        'points_earned',
        'review_status',
        'feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_correct' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Relationship: Answer belongs to an Attempt.
     */
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    /**
     * Relationship: Answer is for a Question.
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    /**
     * Relationship: Answer selects an Option (for multiple choice).
     */
    public function selectedOption()
    {
        return $this->belongsTo(QuizOption::class, 'quiz_option_id');
    }

    /**
     * Relationship: Answer was reviewed by an admin.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Calculate if the answer is correct and how many points it earns.
     */
    public function evaluateAnswer()
    {
        $question = $this->question;

        // For multiple choice questions
        if ($question->isMultipleChoice() && $this->quiz_option_id) {
            $option = $this->selectedOption;
            $this->is_correct = $option && $option->is_correct;
            $this->points_earned = $this->is_correct ? $question->points : 0;
            $this->review_status = 'auto_graded';
        }
        // For open-ended questions (requires manual grading)
        elseif ($question->isOpenEnded() && !empty($this->answer_text)) {
            // Open-ended questions are marked as pending review
            $this->is_correct = null; // null indicates pending review
            $this->points_earned = 0;
            $this->review_status = 'pending_review';
        }

        $this->save();
        return $this;
    }

    /**
     * Check if this answer is pending review.
     */
    public function isPendingReview()
    {
        return $this->review_status === 'pending_review';
    }

    /**
     * Check if this answer has been reviewed.
     */
    public function isReviewed()
    {
        return $this->review_status === 'reviewed';
    }

    /**
     * Mark this answer as reviewed by an admin.
     */
    public function markAsReviewed($adminId, $isCorrect, $feedback = null)
    {
        $this->is_correct = $isCorrect;
        $this->points_earned = $isCorrect ? $this->question->points : 0;
        $this->review_status = 'reviewed';
        $this->feedback = $feedback;
        $this->reviewed_by = $adminId;
        $this->reviewed_at = now();
        $this->save();

        return $this;
    }
}
