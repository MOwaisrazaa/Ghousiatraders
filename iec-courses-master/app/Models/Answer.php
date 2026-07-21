<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'question_id',
        'content',
        'is_correct',
        'is_pinned',
        'is_accepted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_correct' => 'boolean',
        'is_accepted' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Get the user who provided the answer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question this answer belongs to.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the attachments for this answer.
     */
    public function attachments()
    {
        return $this->hasMany(AnswerAttachment::class);
    }
}
