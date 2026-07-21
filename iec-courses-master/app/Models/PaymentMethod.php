<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'description',
        'icon',
        'instructions',
        'is_active',
        'sort_order',
        'details',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'details' => 'array',
    ];
}
