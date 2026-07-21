<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Lecture;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;

class LectureForm extends Component
{
    use WithFileUploads;

    public $courseId;
    public $lectureId;
    public $name;
    public $description;
    public $weekly_price;
    public $monthly_price;
    public $youtube_url;
    public $image;
    public $currentImage;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'required',
        'weekly_price' => 'required|numeric|min:0',
        'monthly_price' => 'required|numeric|min:0',
        'youtube_url' => 'nullable|url',
        'image' => 'nullable|image|max:1024',
    ];

    public function mount($courseId, $id = null)
    {
        $this->courseId = $courseId;

        if ($id) {
            $this->isEditing = true;
            $this->lectureId = $id;
            $lecture = Lecture::findOrFail($id);
            $this->name = $lecture->name;
            $this->description = $lecture->description;
            $this->weekly_price = $lecture->weekly_price;
            $this->monthly_price = $lecture->monthly_price;
            $this->youtube_url = $lecture->youtube_url;
            $this->currentImage = $lecture->image_path;
        }
    }

    public function saveLecture()
    {
        $this->validate();

        if ($this->isEditing) {
            $lecture = Lecture::findOrFail($this->lectureId);
        } else {
            $lecture = new Lecture();
            $lecture->course_id = $this->courseId;
        }

        $lecture->name = $this->name;
        $lecture->description = $this->description;
        $lecture->weekly_price = $this->weekly_price;
        $lecture->monthly_price = $this->monthly_price;
        $lecture->youtube_url = $this->youtube_url;

        if ($this->image) {
            // Delete old image variations if exists
            if ($this->isEditing && $this->currentImage) {
                $imageOptimizer = new ImageOptimizationService();
                $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $this->currentImage);
                $basename = basename($basename);
                $imageOptimizer->deleteOptimizedImages($basename, 'lecture-images');
            }

            // Process and optimize image
            $imageOptimizer = new ImageOptimizationService();
            $result = $imageOptimizer->process($this->image, 'lecture-images', 'lecture');
            $lecture->image_path = $result['path'];
        }

        $lecture->save();

        session()->flash('success', 'Lecture saved successfully!');

        // Redirect to course edit page
        return redirect()->route('admin.course.edit', $this->courseId);
    }

    public function deleteLecture()
    {
        if (!$this->isEditing) {
            return;
        }

        $lecture = Lecture::findOrFail($this->lectureId);

        // Delete lecture image variations
        if ($lecture->image_path) {
            $imageOptimizer = new ImageOptimizationService();
            $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $lecture->image_path);
            $basename = basename($basename);
            $imageOptimizer->deleteOptimizedImages($basename, 'lecture-images');
        }

        $lecture->delete();

        session()->flash('success', 'Lecture deleted successfully!');
        return redirect()->route('admin.course.edit', $this->courseId);
    }

    public function cancelEdit()
    {
        return redirect()->route('admin.course.edit', $this->courseId);
    }

    public function render()
    {
        return view('livewire.lecture-form', [
            'course' => Course::findOrFail($this->courseId)
        ])->layout('layouts.app');
    }
}
