<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_question_id',
        'option_text',
        'is_correct',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Relationship: Option belongs to a Question.
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    /**
     * Relationship: Option has many Answers from user attempts.
     */
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_option_id');
    }
} 