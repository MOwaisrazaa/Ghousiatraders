<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'order',
        'is_active',
        'bg_theme',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            if (empty($section->slug)) {
                $section->slug = Str::slug($section->title);
            }
        });

        static::updating(function ($section) {
            if (empty($section->slug)) {
                $section->slug = Str::slug($section->title);
            }
        });
    }

    /**
     * Relationship: A homepage section belongs to many products (courses).
     */
    public function products()
    {
        return $this->belongsToMany(Course::class, 'homepage_section_product', 'homepage_section_id', 'course_id')
            ->withTimestamps();
    }
}
