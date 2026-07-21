<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureFeature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lecture_id',
        'feature_type',
        'feature_text',
        'sort_order'
    ];

    /**
     * Relationship: Feature belongs to a Lecture.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
