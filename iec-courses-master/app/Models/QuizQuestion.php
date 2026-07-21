<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'points',
        'order',
    ];

    /**
     * Relationship: Question belongs to a Quiz.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relationship: Question has many Options (for multiple choice).
     */
    public function options()
    {
        return $this->hasMany(QuizOption::class)->orderBy('order');
    }

    /**
     * Relationship: Question has many Answers from user attempts.
     */
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Check if this question is multiple choice.
     */
    public function isMultipleChoice()
    {
        return $this->question_type === 'multiple_choice';
    }

    /**
     * Check if this question is open-ended.
     */
    public function isOpenEnded()
    {
        return $this->question_type === 'open_ended';
    }

    /**
     * Get the correct option for multiple choice questions.
     */
    public function getCorrectOption()
    {
        if (!$this->isMultipleChoice()) {
            return null;
        }

        return $this->options()->where('is_correct', true)->first();
    }

    /**
     * Check if this question has been attempted by any student.
     */
    public function hasAttempts()
    {
        return $this->answers()->count() > 0;
    }
} 