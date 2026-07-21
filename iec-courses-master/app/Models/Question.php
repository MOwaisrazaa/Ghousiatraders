<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'lecture_id',
        'title',
        'content',
        'content_type',
        'content_id',
        'status',
        'is_resolved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    /**
     * Get the user who asked the question.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('created_at');
    }

    /**
     * Get the course this question belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lecture this question belongs to.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Get the related material if content_type is material.
     */
    public function material()
    {
        if ($this->content_type === 'material') {
            return $this->belongsTo(CourseMaterial::class, 'content_id');
        }

        return null;
    }

    /**
     * Get the attachments for the question.
     */
    public function attachments()
    {
        return $this->hasMany(QuestionAttachment::class);
    }
}
