<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NavigationPage extends Model
{
    use HasFactory;

    protected $table = 'navigation_pages';

    protected $fillable = [
        'name',
        'link',
        'slug',
        'type',
        'content',
        'order',
        'is_active',
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

        static::creating(function ($page) {
            if ($page->type === 'custom' && empty($page->slug)) {
                $page->slug = Str::slug($page->name);
            }
            if ($page->type === 'custom') {
                $page->link = '/page/' . ($page->slug ?: Str::slug($page->name));
            }
        });

        static::updating(function ($page) {
            if ($page->type === 'custom' && empty($page->slug)) {
                $page->slug = Str::slug($page->name);
            }
            if ($page->type === 'custom') {
                $page->link = '/page/' . ($page->slug ?: Str::slug($page->name));
            }
        });
    }

    /**
     * Relationship: A navigation page belongs to many products.
     */
    public function products()
    {
        return $this->belongsToMany(Course::class, 'navigation_page_product', 'navigation_page_id', 'course_id')
            ->withTimestamps();
    }
}
