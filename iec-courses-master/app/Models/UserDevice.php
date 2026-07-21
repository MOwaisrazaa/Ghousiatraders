<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_name',
        'browser',
        'platform',
        'device_type',
        'device_id',
        'ip_address',
        'last_login_at',
        'is_primary'
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'is_primary' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
