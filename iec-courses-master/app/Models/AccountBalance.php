<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'balance',
        'total_received',
        'total_used',
        'total_transferred',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_received' => 'decimal:2',
        'total_used' => 'decimal:2',
        'total_transferred' => 'decimal:2',
    ];
}
