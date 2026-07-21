<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\CourseFeature;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;

class CourseForm extends Component
{
    use WithFileUploads;

    public $course;
    public $courseId;
    public $name;
    public $description;
    public $weekly_price;
    public $monthly_price;
    public $is_free = false;
    public $purchase_model = 'flexible';
    public $image;
    public $currentImage;
    public $isEditing = false;

    public $lectures = [];
    public $editingLecture = null;
    public $deletingLecture = null;
    public $showLectureForm = false;
    public $lectureId;
    public $lectureName;
    public $lectureDescription;
    public $lectureWeeklyPrice;
    public $lectureMonthlyPrice;
    public $lectureIsFree = false;
    public $lectureImage;
    public $currentLectureImage;

    // Course Features
    public $features = [
        'learn' => [],
        'requirement' => [],
        'includes' => []
    ];
    public $newFeatureText = [
        'learn' => '',
        'requirement' => '',
        'includes' => ''
    ];

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'required',
        'weekly_price' => 'nullable|numeric|min:0',
        'monthly_price' => 'nullable|numeric|min:0',
        'image' => 'nullable|image|max:1024',
    ];

    protected $lectureRules = [
        'lectureName' => 'required|min:3|max:255',
        'lectureDescription' => 'required',
        'lectureWeeklyPrice' => 'nullable|numeric|min:0',
        'lectureMonthlyPrice' => 'nullable|numeric|min:0',
        'lectureImage' => 'nullable|image|max:1024',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditing = true;
            $this->courseId = $id;
            $this->course = Course::with(['lectures', 'features'])->findOrFail($id);
            $this->name = $this->course->name;
            $this->description = $this->course->description;
            $this->weekly_price = $this->course->weekly_price;
            $this->monthly_price = $this->course->monthly_price;
            $this->is_free = $this->course->is_free ?? false;
            $this->purchase_model = $this->course->purchase_model ?? 'flexible';
            $this->currentImage = $this->course->image_path;
            $this->lectures = $this->course->lectures;

            // Load features
            $this->loadFeatures();
        } else {
            $this->course = new Course();
            $this->lectures = collect([]);
            $this->purchase_model = 'flexible';
        }
    }

    public function loadFeatures()
    {
        $this->features = [
            'learn' => $this->course->getFeaturesByType('learn')->toArray(),
            'requirement' => $this->course->getFeaturesByType('requirement')->toArray(),
            'includes' => $this->course->getFeaturesByType('includes')->toArray()
        ];
    }

    public function saveCourse()
    {
        // Dynamic validation based on free status
        $rules = $this->rules;
        if (!$this->is_free) {
            $rules['weekly_price'] = 'required|numeric|min:0';
            $rules['monthly_price'] = 'required|numeric|min:0';
        }
        $this->validate($rules);

        if ($this->isEditing) {
            $course = $this->course;
        } else {
            $course = new Course();
        }

        $course->name = $this->name;
        $course->description = $this->description;
        $course->weekly_price = $this->is_free ? 0 : $this->weekly_price;
        $course->monthly_price = $this->is_free ? 0 : $this->monthly_price;
        $course->is_free = $this->is_free;
        $course->purchase_model = $this->purchase_model;

        if ($this->image) {
            // Delete old image variations if exists
            if ($this->isEditing && $this->currentImage) {
                $imageOptimizer = new ImageOptimizationService();
                // Extract base filename from path (e.g., "course-images/course-123456-abc" from "course-images/course-123456-abc-large.webp")
                $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $this->currentImage);
                $basename = basename($basename);
                $imageOptimizer->deleteOptimizedImages($basename, 'course-images');
            }

            // Process and optimize image
            $imageOptimizer = new ImageOptimizationService();
            $result = $imageOptimizer->process($this->image, 'course-images', 'course');
            $course->image_path = $result['path'];
        }

        $course->save();

        if (!$this->isEditing) {
            $this->courseId = $course->id;
            $this->course = $course;
            $this->isEditing = true;
        }

        session()->flash('success', 'Course saved successfully!');
    }

    public function addFeature($type)
    {
        if (empty($this->newFeatureText[$type])) {
            return;
        }

        // Get max sort order
        $maxSortOrder = 0;
        if (!empty($this->features[$type])) {
            $maxSortOrder = collect($this->features[$type])->max('sort_order') ?? 0;
        }

        $feature = CourseFeature::create([
            'course_id' => $this->courseId,
            'feature_text' => $this->newFeatureText[$type],
            'feature_type' => $type,
            'sort_order' => $maxSortOrder + 1
        ]);

        $this->newFeatureText[$type] = '';
        $this->loadFeatures();
    }

    public function deleteFeature($id)
    {
        CourseFeature::destroy($id);
        $this->loadFeatures();
    }

    public function moveFeatureUp($id, $type)
    {
        $feature = CourseFeature::find($id);
        if (!$feature || $feature->sort_order <= 1) {
            return;
        }

        $previousFeature = CourseFeature::where('course_id', $this->courseId)
            ->where('feature_type', $type)
            ->where('sort_order', $feature->sort_order - 1)
            ->first();

        if ($previousFeature) {
            $previousFeature->sort_order += 1;
            $previousFeature->save();

            $feature->sort_order -= 1;
            $feature->save();

            $this->loadFeatures();
        }
    }

    public function moveFeatureDown($id, $type)
    {
        $feature = CourseFeature::find($id);
        if (!$feature) {
            return;
        }

        $nextFeature = CourseFeature::where('course_id', $this->courseId)
            ->where('feature_type', $type)
            ->where('sort_order', $feature->sort_order + 1)
            ->first();

        if ($nextFeature) {
            $nextFeature->sort_order -= 1;
            $nextFeature->save();

            $feature->sort_order += 1;
            $feature->save();

            $this->loadFeatures();
        }
    }

    public function deleteCourse()
    {
        if (!$this->isEditing) {
            return;
        }

        $imageOptimizer = new ImageOptimizationService();

        // Delete course image variations
        if ($this->currentImage) {
            $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $this->currentImage);
            $basename = basename($basename);
            $imageOptimizer->deleteOptimizedImages($basename, 'course-images');
        }

        // Delete all lecture image variations
        foreach ($this->course->lectures as $lecture) {
            if ($lecture->image_path) {
                $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $lecture->image_path);
                $basename = basename($basename);
                $imageOptimizer->deleteOptimizedImages($basename, 'lecture-images');
            }
        }

        $this->course->delete();

        session()->flash('success', 'Course deleted successfully!');
        return redirect()->route('admin.courses');
    }

    public function showAddLecture()
    {
        $this->resetLectureForm();
        $this->showLectureForm = true;
        $this->editingLecture = null;
    }

    public function editLecture($lectureId)
    {
        $lecture = Lecture::findOrFail($lectureId);
        $this->editingLecture = $lecture;
        $this->lectureId = $lecture->id;
        $this->lectureName = $lecture->name;
        $this->lectureDescription = $lecture->description;
        $this->lectureWeeklyPrice = $lecture->weekly_price;
        $this->lectureMonthlyPrice = $lecture->monthly_price;
        $this->lectureIsFree = $lecture->is_free ?? false;
        $this->currentLectureImage = $lecture->image_path;
        $this->showLectureForm = true;
    }

    public function confirmDeleteLecture($lectureId)
    {
        $this->deletingLecture = Lecture::findOrFail($lectureId);
    }

    public function deleteLecture()
    {
        if (!$this->deletingLecture) {
            return;
        }

        // Delete lecture image variations
        if ($this->deletingLecture->image_path) {
            $imageOptimizer = new ImageOptimizationService();
            $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $this->deletingLecture->image_path);
            $basename = basename($basename);
            $imageOptimizer->deleteOptimizedImages($basename, 'lecture-images');
        }

        $this->deletingLecture->delete();
        $this->deletingLecture = null;
        $this->lectures = $this->course->lectures()->get();

        session()->flash('success', 'Lecture deleted successfully!');
    }

    public function cancelDeleteLecture()
    {
        $this->deletingLecture = null;
    }

    public function saveLecture()
    {
        // Dynamic validation based on free status
        $rules = $this->lectureRules;
        if (!$this->lectureIsFree) {
            $rules['lectureWeeklyPrice'] = 'required|numeric|min:0';
            $rules['lectureMonthlyPrice'] = 'required|numeric|min:0';
        }
        $this->validate($rules);

        if ($this->editingLecture) {
            $lecture = $this->editingLecture;
        } else {
            $lecture = new Lecture();
            $lecture->course_id = $this->courseId;
        }

        $lecture->name = $this->lectureName;
        $lecture->description = $this->lectureDescription;
        $lecture->weekly_price = $this->lectureIsFree ? 0 : $this->lectureWeeklyPrice;
        $lecture->monthly_price = $this->lectureIsFree ? 0 : $this->lectureMonthlyPrice;
        $lecture->is_free = $this->lectureIsFree;

        if ($this->lectureImage) {
            // Delete old image variations if exists
            if ($this->editingLecture && $this->currentLectureImage) {
                $imageOptimizer = new ImageOptimizationService();
                $basename = preg_replace('/-(?:small|medium|large|small_png|large_png)\.(?:webp|png)$/', '', $this->currentLectureImage);
                $basename = basename($basename);
                $imageOptimizer->deleteOptimizedImages($basename, 'lecture-images');
            }

            // Process and optimize image
            $imageOptimizer = new ImageOptimizationService();
            $result = $imageOptimizer->process($this->lectureImage, 'lecture-images', 'lecture');
            $lecture->image_path = $result['path'];
        }

        $lecture->save();

        $this->showLectureForm = false;
        $this->resetLectureForm();
        $this->lectures = $this->course->lectures()->get();

        session()->flash('success', 'Lecture saved successfully!');
    }

    public function cancelLecture()
    {
        $this->showLectureForm = false;
        $this->resetLectureForm();
    }

    private function resetLectureForm()
    {
        $this->editingLecture = null;
        $this->lectureId = null;
        $this->lectureName = '';
        $this->lectureDescription = '';
        $this->lectureWeeklyPrice = '';
        $this->lectureMonthlyPrice = '';
        $this->lectureIsFree = false;
        $this->lectureImage = null;
        $this->currentLectureImage = null;
    }

    public function render()
    {
        return view('livewire.course-form')
            ->layout('layouts.app');
    }
}
