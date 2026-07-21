<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;

class AdminCoursesList extends Component
{
    public function render()
    {
        $courses = Course::withCount([
            'lectures',
            'features as learn_features_count' => function($query) {
                $query->where('feature_type', 'learn');
            },
            'features as requirement_features_count' => function($query) {
                $query->where('feature_type', 'requirement');
            }
        ])->get();

        return view('livewire.admin-courses-list', [
            'courses' => $courses
        ])->layout('layouts.app');
    }
}
