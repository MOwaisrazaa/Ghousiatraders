<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CannedResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'shortcut',
        'department_id',
        'content',
        'is_active',
        'created_by',
        'usage_count'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function department()
    {
        return $this->belongsTo(SupportDepartment::class, 'department_id');
    }
}
