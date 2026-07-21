<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'question',
        'answer',
        'is_published',
        'answered_by',
        'answered_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'answered_at' => 'datetime',
    ];

    /**
     * Get the user who asked the question.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who answered the question.
     */
    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }
}
