<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'lecture_id',
        'status',
        'expires_at',
        'order_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns this course access.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course associated with this access.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lecture associated with this access.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Get the order that granted this access.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
