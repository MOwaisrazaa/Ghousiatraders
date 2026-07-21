<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\InstructorProfile;
use Livewire\Component;
use Livewire\WithPagination;

class InstructorProfiles extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewCourses($instructorName)
    {
        // Redirect to courses page with instructor filter
        return redirect()->route('courses', ['instructor' => $instructorName]);
    }

    public function render()
    {
        $instructorProfiles = InstructorProfile::where('is_active', true)
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('title', 'like', '%' . $this->search . '%')
                        ->orWhere('expertise', 'like', '%' . $this->search . '%')
                        ->orWhere('skills', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate(9);

        return view('livewire.instructor-profiles', [
            'instructorProfiles' => $instructorProfiles,
        ])->layout('layouts.app');
    }
} 