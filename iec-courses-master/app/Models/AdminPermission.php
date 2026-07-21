<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_user_id',
        'page',
        'is_allowed'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_allowed' => 'boolean'
    ];

    /**
     * Get the admin user that owns the permission.
     */
    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * Scope a query to only include permissions for a specific admin.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $adminId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('admin_user_id', $adminId);
    }

    /**
     * Scope a query to only include permissions for a specific page.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $page
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPage($query, $page)
    {
        return $query->where('page', $page);
    }
}
