<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoppingcart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'shoppingcarts'; // Explicit table name if necessary

    protected $fillable = [
        'quantity',
        'course_id',
        'lecture_id',
        'price',
        'user_id',
        'price_type',
        'original_price',
        'discount_amount',
        'discount_reason',
    ];

    /**
     * Relationship: ShoppingCart belongs to a Lecture.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Relationship: ShoppingCart belongs to a Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relationship: ShoppingCart belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getItemNameAttribute()
    {
        return $this->course?->course_name ?? $this->lecture?->lecture_title;
    }
}
