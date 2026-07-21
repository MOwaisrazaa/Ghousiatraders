<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'cart_items',
        'total',
        'status',
        'billing_address',
        'payment_method',
        'discount',
        'final_total',
        'coupon_code',
        'rejection_reason',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the accounting transaction for this order.
     */
    public function accountTransaction()
    {
        return $this->hasOne(\App\Models\AccountTransaction::class);
    }
}
