<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'permission',
        'is_allowed'
    ];

    protected $casts = [
        'is_allowed' => 'boolean'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
