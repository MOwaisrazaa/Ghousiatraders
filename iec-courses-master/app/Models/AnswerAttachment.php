<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AnswerAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answer_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
        'mime_type',
    ];

    /**
     * Get the answer that this attachment belongs to.
     */
    public function answer()
    {
        return $this->belongsTo(Answer::class);
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

    /**
     * Get the name of the file.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->original_name;
    }
}
