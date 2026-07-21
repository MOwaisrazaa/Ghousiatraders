<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'sort_order',
    ];

    /**
     * Get the course that owns the material.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the questions for this material.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'content_id')
            ->where('content_type', 'material')
            ->orderBy('created_at', 'desc');
    }
}
