<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUserAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'user_id',
    ];

    /**
     * Get the admin user that owns the assignment.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the regular user that is assigned to the admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
