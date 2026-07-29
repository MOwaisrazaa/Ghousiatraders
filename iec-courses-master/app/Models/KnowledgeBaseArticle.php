<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'is_published',
        'helpful_count',
        'unhelpful_count'
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];
}
