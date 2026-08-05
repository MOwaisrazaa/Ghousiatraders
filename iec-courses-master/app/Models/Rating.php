<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'reviewer_name',
        'rating',
        'title',
        'comment',
        'is_approved',
        'show_publicly',
        'status',
        'is_verified_purchase',
        'moderation_note',
        'rateable_type',
        'rateable_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_approved' => 'boolean',
        'show_publicly' => 'boolean',
        'is_verified_purchase' => 'boolean',
    ];

    /**
     * Get the user who created the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent rateable model (course or lecture).
     */
    public function rateable()
    {
        return $this->morphTo();
    }
}
