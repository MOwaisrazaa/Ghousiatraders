<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_id',
        'url',
        'referer',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
