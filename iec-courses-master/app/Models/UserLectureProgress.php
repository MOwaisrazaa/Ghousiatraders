<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLectureProgress extends Model
{
    use HasFactory;

    protected $table = 'user_lecture_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'lecture_id',
        'current_time',
        'duration',
        'progress_percent',
        'completed',
        'last_watched_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'last_watched_at' => 'datetime',
        'progress_percent' => 'float',
    ];

    /**
     * Get the user that owns the progress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course associated with this progress.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lecture associated with this progress.
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * Update progress based on current time and duration.
     *
     * @param int $currentTime Current position in seconds
     * @param int $duration Total duration in seconds
     * @return self
     */
    public function updateProgress($currentTime, $duration)
    {
        $this->current_time = $currentTime;

        // Only update duration if it's provided and greater than the current value
        if ($duration > 0 && ($this->duration == 0 || $duration > $this->duration)) {
            $this->duration = $duration;
        }

        // Calculate progress percentage
        if ($this->duration > 0) {
            $newProgressPercent = min(100, ($this->current_time / $this->duration) * 100);

            // Only update progress_percent if the new value is higher than the existing one
            // This prevents "downgrading" progress when re-watching
            if ($newProgressPercent > $this->progress_percent) {
                $this->progress_percent = $newProgressPercent;
            }

            // Mark as completed if progress is >= 90%
            if ($this->progress_percent >= 90) {
                $this->completed = true;
            }
        }

        $this->last_watched_at = now();

        return $this;
    }

    /**
     * Get formatted current time (MM:SS).
     *
     * @return string
     */
    public function getFormattedCurrentTimeAttribute()
    {
        $minutes = floor($this->current_time / 60);
        $seconds = $this->current_time % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get formatted duration (MM:SS).
     *
     * @return string
     */
    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Get the progress percentage formatted for display.
     *
     * @return string
     */
    public function getFormattedProgressAttribute()
    {
        return number_format($this->progress_percent, 0) . '%';
    }
}
