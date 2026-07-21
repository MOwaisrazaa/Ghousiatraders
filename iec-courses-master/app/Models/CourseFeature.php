<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'feature_text',
        'feature_type', // 'learn', 'requirement', or 'includes'
        'sort_order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
