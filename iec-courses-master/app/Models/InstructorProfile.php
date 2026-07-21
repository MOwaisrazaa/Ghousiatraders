<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'bio',
        'title',
        'expertise',
        'skills',
        'image_path',
        'social_linkedin',
        'social_twitter',
        'social_website',
        'is_active'
    ];

    /**
     * Get the courses taught by this instructor.
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor', 'name');
    }

    /**
     * Get the lectures taught by this instructor.
     */
    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'instructor', 'name');
    }

    /**
     * Get the instructor's expertise as an array.
     */
    public function getExpertiseArrayAttribute()
    {
        return $this->expertise ? array_map('trim', explode(',', $this->expertise)) : [];
    }

    /**
     * Get the instructor's skills as an array.
     */
    public function getSkillsArrayAttribute()
    {
        return $this->skills ? array_map('trim', explode(',', $this->skills)) : [];
    }

    /**
     * Get instructor by name.
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }
}
