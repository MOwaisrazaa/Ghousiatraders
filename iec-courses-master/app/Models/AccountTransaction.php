<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_type',
        'payment_method',
        'amount',
        'status',
        'description',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
