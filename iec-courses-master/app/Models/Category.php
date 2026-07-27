<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'parent_id',
        'display_order',
        'status',
        'seo_title',
        'meta_description',
    ];

    /**
     * Get courses (products) belonging to this category.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Relationship: Category belongs to a parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relationship: Category has many subcategories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('display_order');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });
    }
}
