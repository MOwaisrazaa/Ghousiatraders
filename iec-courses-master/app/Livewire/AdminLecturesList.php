<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lecture;
use App\Models\Course;

class AdminLecturesList extends Component
{
    public $courseFilter = '';

    public function render()
    {
        $coursesQuery = Course::withCount('lectures');
        $courses = $coursesQuery->get();

        $lecturesQuery = Lecture::with('course');

        if ($this->courseFilter) {
            $lecturesQuery->where('course_id', $this->courseFilter);
        }

        $lectures = $lecturesQuery->get();

        return view('livewire.admin-lectures-list', [
            'lectures' => $lectures,
            'courses' => $courses
        ])->layout('layouts.app');
    }
}
