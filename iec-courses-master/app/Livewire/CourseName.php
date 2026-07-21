<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CourseName as CourseModel; // Alias the model to avoid conflict

class CourseName extends Component
{
    public $courses = [];
    public $name = '';
    public $courseId = null;

    public function mount()
    {
        $this->loadCourses();
    }

    public function loadCourses()
    {
        $this->courses = CourseModel::all(); // Use the alias for the model
    }

    public function addCourse()
    {
        $this->validate(['name' => 'required|string|max:255']);
        CourseModel::create(['name' => $this->name]); // Use the alias for the model
        $this->reset('name');
        $this->loadCourses();
    }

    public function editCourse($id)
    {
        $course = CourseModel::findOrFail($id); // Use the alias for the model
        $this->name = $course->name;
        $this->courseId = $course->id;
    }

    public function updateCourse()
    {
        $this->validate(['name' => 'required|string|max:255']);
        $course = CourseModel::findOrFail($this->courseId); // Use the alias for the model
        $course->update(['name' => $this->name]);
        $this->reset(['name', 'courseId']);
        $this->loadCourses();
    }

    public function deleteCourse($id)
    {
        CourseModel::findOrFail($id)->delete(); // Use the alias for the model
        $this->loadCourses();
    }

    public function render()
    {
        return view('livewire.course-name')->layout('layouts.app');
    }
}
