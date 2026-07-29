<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'icon',
        'is_active',
        'default_priority'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'department_id');
    }
}
