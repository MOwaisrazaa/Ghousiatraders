<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class QuestionAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'mime_type',
    ];

    /**
     * Get the question that owns the attachment.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the URL for the attachment.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
