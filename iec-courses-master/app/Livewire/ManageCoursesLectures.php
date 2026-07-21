<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\CourseFeature;
use App\Models\LectureFeature;
use App\Models\Category;
use Livewire\WithFileUploads;
use GuzzleHttp\Client;

class ManageCoursesLectures extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $courseImagePath; // Update this to hold the file
    public $lectureImagePath;
    public $lecturePdfFile; // New property for PDF file
    public $courses = [];
    public $lectures = [];
    public $courseDescription;
    public $courseId;
    public $lectureId;
    public $courseName, $courseWeeklyPrice, $courseMonthlyPrice, $courseInstructor, $courseIntroVideoUrl, $courseIsFree = false, $coursePurchaseModel = 'flexible';
    public $lectureName, $lectureDescription, $lectureWeeklyPrice, $lectureMonthlyPrice, $lectureYoutubeUrl, $selectedCourse, $lectureInstructor, $lectureIntroVideoUrl, $lectureIsFree = false, $isStandaloneLecture = false;
    public $lectureDuration;
    public $isDurationLoading = false;
    public $durationError = null;

    // For instructor dropdown
    public $instructors = [];
    
    // For category dropdown
    public $categories = [];
    public $categoryId;

    // Course Feature properties
    public $courseFeatureText;
    public $courseFeatureType = 'learn';
    public $courseFeatureId;
    public $courseFeatures = [];
    public $showCourseFeatureModal = false;

    // Lecture Feature properties
    public $lectureFeatureText;
    public $lectureFeatureType = 'learn';
    public $lectureFeatureId;
    public $lectureFeatures = [];
    public $showLectureFeatureModal = false;

    public $isEditMode = false;

    public function mount()
    {
        $this->refreshCourses();
        $this->refreshLectures();
        $this->loadInstructors();
        $this->categories = Category::all();
        
        // Handle query parameters for pre-populating the form
        if (request()->has('course')) {
            $courseId = request()->get('course');
            // Verify the course exists before setting it
            if (Course::find($courseId)) {
                $this->selectedCourse = $courseId;
                // If course is selected, it's not a standalone lecture
                $this->isStandaloneLecture = false;
            }
        }
        
        // Handle type parameter (lecture, course, etc.)
        if (request()->has('type')) {
            $type = request()->get('type');
            // You can add additional logic here based on the type if needed
        }
    }

    private function loadInstructors()
    {
        $this->instructors = \App\Models\InstructorProfile::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    protected $listeners = ['updateCourseDescription'];

    public function updateCourseDescription($content)
    {
        $this->courseDescription = $content;
    }
    protected $rules = [
        'courseName' => 'required|string|max:255',
        'courseDescription' => 'required|string',
        'courseInstructor' => 'nullable|string|max:255',
        'courseWeeklyPrice' => 'nullable|numeric|min:0',
        'courseMonthlyPrice' => 'nullable|numeric|min:0',
        'courseImagePath' => 'nullable|image|max:2048', // Allow image files up to 2MB

        'lectureName' => 'required|string|max:255',
        'lectureDescription' => 'nullable|string',
        'lectureInstructor' => 'nullable|string|max:255',
        'lectureWeeklyPrice' => 'nullable|numeric|min:0',
        'lectureMonthlyPrice' => 'nullable|numeric|min:0',
        'lectureYoutubeUrl' => 'nullable|url',
        'lectureDuration' => 'nullable|string|regex:/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/', // Format: HH:MM:SS or MM:SS
        'lectureImagePath' => 'nullable|image|max:2048',
        'lecturePdfFile' => 'nullable|mimes:pdf|max:5120', // Max 5MB PDF file
    ];

    protected $messages = [
        'lectureDuration.regex' => 'The duration format must be HH:MM:SS or MM:SS',
    ];

    public function refreshCourses()
    {
        $this->courses = Course::all(); // Load all courses into the property
    }

    public function refreshLectures()
    {
        $this->lectures = Lecture::all(); // Load all courses into the property
    }
    public function render()
    {
        return view('livewire.manage-courses-lectures', [
            'courses' => Course::paginate(5),
            'lectures' => Lecture::with('course')->paginate(10), // Show all lectures with course relationship
        ])->layout('layouts.app');
    }

    public function resetForm()
    {
        $this->reset([
            'courseId', 'courseName', 'courseDescription', 'courseInstructor',
            'courseWeeklyPrice', 'courseMonthlyPrice', 'courseImagePath', 'courseIntroVideoUrl', 'courseIsFree',
            'coursePurchaseModel', 'categoryId'
        ]);
        $this->reset([
            'lectureId', 'lectureName', 'lectureDescription', 'lectureInstructor',
            'lectureWeeklyPrice', 'lectureMonthlyPrice', 'lectureYoutubeUrl',
            'lectureImagePath', 'lecturePdfFile', 'selectedCourse', 'lectureIntroVideoUrl', 'lectureIsFree', 'isStandaloneLecture'
        ]);
        $this->courseFeatures = [];
        $this->lectureFeatures = [];
        $this->isEditMode = false;
    }

    // Course CRUD Operations
    public function saveCourse()
    {
        // Dynamic validation based on free status
        $rules = [
            'courseName' => 'required|string|max:255',
            'courseDescription' => 'required|string',
            'courseInstructor' => 'nullable|string|max:255',
            'courseInstructor' => 'nullable|string|max:255',
            // 'courseImagePath' => 'nullable|image|max:2048', // Removed strict image validation here
        ];

        // Conditionally validate image only if it's a file upload (not a string path)
        if ($this->courseImagePath && !is_string($this->courseImagePath)) {
            $rules['courseImagePath'] = 'nullable|image|max:2048';
        }
        
        if (!$this->courseIsFree) {
            // If monthly price is not set, use weekly price (assuming single price input)
            if (empty($this->courseMonthlyPrice) && !empty($this->courseWeeklyPrice)) {
                $this->courseMonthlyPrice = $this->courseWeeklyPrice;
            }
            
            $rules['courseWeeklyPrice'] = 'required|numeric|min:0';
            $rules['courseMonthlyPrice'] = 'required|numeric|min:0';
        }
        
        $rules['categoryId'] = 'nullable|exists:categories,id';
        
        $this->validate($rules);
        
        try {
            // Check if it's an edit or new course
            if ($this->isEditMode && $this->courseId) {
                $course = Course::findOrFail($this->courseId);
                $course->name = $this->courseName;
                $course->description = $this->courseDescription;
                $course->weekly_price = $this->courseIsFree ? 0 : $this->courseWeeklyPrice;
                $course->monthly_price = $this->courseIsFree ? 0 : $this->courseMonthlyPrice;
                $course->instructor = $this->courseInstructor;
                $course->intro_video_url = $this->courseIntroVideoUrl;
                $course->is_free = $this->courseIsFree;
                $course->purchase_model = $this->coursePurchaseModel;
                $course->category_id = $this->categoryId;

                // Check if courseImagePath is a file upload or an existing path
                if ($this->courseImagePath && !is_string($this->courseImagePath)) {
                    $path = $this->courseImagePath->store('courses', 'public');
                    $course->image_path = $path;
                }

                $course->save();

                session()->flash('success', 'Course updated successfully!');
            } else {
                // New course creation
                $course = new Course();
                $course->name = $this->courseName;
                $course->description = $this->courseDescription;
                $course->weekly_price = $this->courseIsFree ? 0 : $this->courseWeeklyPrice;
                $course->monthly_price = $this->courseIsFree ? 0 : $this->courseMonthlyPrice;
                $course->instructor = $this->courseInstructor;
                $course->intro_video_url = $this->courseIntroVideoUrl;
                $course->is_free = $this->courseIsFree;
                $course->purchase_model = $this->coursePurchaseModel;
                $course->category_id = $this->categoryId;

                if ($this->courseImagePath) {
                    $path = $this->courseImagePath->store('courses', 'public');
                    $course->image_path = $path;
                }

                $course->save();

                session()->flash('success', 'Course created successfully!');
            }

            // Reset form and refresh courses
            $this->resetForm();
            $this->refreshCourses();

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function editCourse($id)
    {
        $course = Course::findOrFail($id);
        $this->courseId = $course->id;
        $this->courseName = $course->name;
        $this->courseDescription = $course->description;
        $this->courseInstructor = $course->instructor;
        $this->courseWeeklyPrice = $course->weekly_price;
        $this->courseMonthlyPrice = $course->monthly_price;
        $this->courseImagePath = $course->image_path;
        $this->courseIntroVideoUrl = $course->intro_video_url;
        $this->courseIsFree = $course->is_free ?? false;
        $this->coursePurchaseModel = $course->purchase_model ?? 'flexible';
        $this->categoryId = $course->category_id;

        // Load course features
        $this->loadCourseFeatures($this->courseId);

        $this->isEditMode = true;
    }

    public function deleteCourse($id)
    {
        Course::findOrFail($id)->delete();
        session()->flash('message', 'Course deleted successfully.');

        // Refresh the courses
        $this->refreshCourses();
    }

    // Lecture CRUD Operations
    public function saveLecture()
    {
        try {
            // Dynamic validation based on standalone and free status
            $rules = [
                'lectureName' => 'required|string|min:3',
                'lectureDescription' => 'required|string|min:10',
            ];
            
            // Course is required only if not standalone
            if (!$this->isStandaloneLecture) {
                $rules['selectedCourse'] = 'required|exists:courses,id';
            }
            
            // Price is required only if not free
            if (!$this->lectureIsFree) {
                // If monthly price is not set, use weekly price (assuming single price input)
                if (empty($this->lectureMonthlyPrice) && !empty($this->lectureWeeklyPrice)) {
                    $this->lectureMonthlyPrice = $this->lectureWeeklyPrice;
                }
                
                $rules['lectureWeeklyPrice'] = 'required|numeric|min:0';
                $rules['lectureMonthlyPrice'] = 'required|numeric|min:0';
            }
            
            $this->validate($rules);

            // Check if it's an edit or new lecture
            if ($this->isEditMode && $this->lectureId) {
                $lecture = Lecture::findOrFail($this->lectureId);
                $lecture->name = $this->lectureName;
                $lecture->description = $this->lectureDescription;
                $lecture->weekly_price = $this->lectureIsFree ? 0 : $this->lectureWeeklyPrice;
                $lecture->monthly_price = $this->lectureIsFree ? 0 : $this->lectureMonthlyPrice;
                $lecture->youtube_url = $this->lectureYoutubeUrl;
                $lecture->instructor = $this->lectureInstructor;
                $lecture->intro_video_url = $this->lectureIntroVideoUrl;
                $lecture->duration = $this->lectureDuration;
                $lecture->is_free = $this->lectureIsFree;

                // Check if lectureImagePath is a file upload or an existing path
                if ($this->lectureImagePath && !is_string($this->lectureImagePath)) {
                    $path = $this->lectureImagePath->store('lectures', 'public');
                    $lecture->image_path = $path;
                }

                // Check if lecturePdfFile is a file upload or an existing path
                if ($this->lecturePdfFile && !is_string($this->lecturePdfFile)) {
                    $pdfPath = $this->lecturePdfFile->store('lecture_pdfs', 'public');
                    $lecture->pdf_file_path = $pdfPath;
                }

                $lecture->save();

                session()->flash('success', 'Lecture updated successfully!');
            } else {
                // New lecture creation
                $lecture = new Lecture();
                $lecture->course_id = $this->isStandaloneLecture ? null : $this->selectedCourse;
                $lecture->name = $this->lectureName;
                $lecture->description = $this->lectureDescription;
                $lecture->weekly_price = $this->lectureIsFree ? 0 : $this->lectureWeeklyPrice;
                $lecture->monthly_price = $this->lectureIsFree ? 0 : $this->lectureMonthlyPrice;
                $lecture->youtube_url = $this->lectureYoutubeUrl;
                $lecture->instructor = $this->lectureInstructor;
                $lecture->intro_video_url = $this->lectureIntroVideoUrl;
                $lecture->duration = $this->lectureDuration;
                $lecture->is_free = $this->lectureIsFree;

                if ($this->lectureImagePath) {
                    $path = $this->lectureImagePath->store('lectures', 'public');
                    $lecture->image_path = $path;
                }

                if ($this->lecturePdfFile) {
                    $pdfPath = $this->lecturePdfFile->store('lecture_pdfs', 'public');
                    $lecture->pdf_file_path = $pdfPath;
                }

                $lecture->save();

                session()->flash('success', 'Lecture created successfully!');
            }

            // Reset form and refresh lectures
            $this->resetForm();
            $this->refreshLectures();

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function editLecture($id)
    {
        $lecture = Lecture::findOrFail($id);
        $this->lectureId = $lecture->id;
        $this->selectedCourse = $lecture->course_id;
        $this->isStandaloneLecture = $lecture->course_id === null;
        $this->lectureName = $lecture->name;
        $this->lectureDescription = $lecture->description;
        $this->lectureInstructor = $lecture->instructor;
        $this->lectureWeeklyPrice = $lecture->weekly_price;
        $this->lectureMonthlyPrice = $lecture->monthly_price;
        $this->lectureYoutubeUrl = $lecture->youtube_url;
        $this->lectureIntroVideoUrl = $lecture->intro_video_url;
        $this->lectureDuration = $lecture->duration;
        $this->lectureImagePath = $lecture->image_path;
        $this->lectureIsFree = $lecture->is_free ?? false;

        // Load lecture features
        $this->loadLectureFeatures($this->lectureId);

        $this->isEditMode = true;
    }

    public function deleteLecture($id)
    {
        Lecture::findOrFail($id)->delete();
        session()->flash('message', 'Lecture deleted successfully.');
        $this->refreshLectures();

    }

    /**
     * Manually fetch YouTube video duration
     * This method can be called via wire:click
     */
    public function fetchYoutubeDuration()
    {
        $this->updatedLectureYoutubeUrl();
    }

    public function updatedLectureYoutubeUrl()
    {
        if (!empty($this->lectureYoutubeUrl)) {
            $this->isDurationLoading = true;
            $this->durationError = null;

            try {
                $videoId = $this->extractYoutubeId($this->lectureYoutubeUrl);

                if (!$videoId) {
                    $this->durationError = "Could not extract video ID from URL";
                    $this->isDurationLoading = false;
                    \Log::warning('Failed to extract YouTube video ID from URL: ' . $this->lectureYoutubeUrl);
                    return;
                }

                // Try using the YouTube API first
                $apiKey = config('services.youtube.api_key', 'AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8');
                $apiUrl = "https://www.googleapis.com/youtube/v3/videos?id={$videoId}&key={$apiKey}&part=contentDetails";

                \Log::info('Attempting to fetch YouTube video data', [
                    'videoId' => $videoId,
                    'api_url' => $apiUrl
                ]);

                // Use cURL for more direct control
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 second timeout
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    $this->durationError = "cURL Error: " . curl_error($ch);
                    \Log::error('cURL Error: ' . curl_error($ch));
                    curl_close($ch);
                    $this->isDurationLoading = false;
                    return;
                }

                curl_close($ch);

                $durationFound = false;

                // If API key doesn't work (403 forbidden), try alternate method
                if ($httpCode == 403) {
                    \Log::warning("YouTube API returned 403, trying fallback method", ['video_id' => $videoId]);
                    $this->lectureDuration = $this->getYoutubeDurationWithoutApi($videoId);
                    if ($this->lectureDuration) {
                        \Log::info('Successfully retrieved duration using fallback method', ['duration' => $this->lectureDuration]);
                        $durationFound = true;
                    }
                } else if ($httpCode != 200) {
                    $errorResponse = json_decode($response, true);
                    $errorMessage = "API Error: HTTP status $httpCode";

                    // Extract more detailed error information if available
                    if (isset($errorResponse['error']['message'])) {
                        $errorMessage .= " - " . $errorResponse['error']['message'];

                        // Common error messages and helpful hints
                        if (strpos($errorResponse['error']['message'], 'API key not valid') !== false) {
                            $errorMessage = "YouTube API key is invalid or has expired. Please contact the administrator.";
                        } else if (strpos($errorResponse['error']['message'], 'quota') !== false) {
                            $errorMessage = "YouTube API quota exceeded. Try again tomorrow or contact the administrator.";
                        } else if (strpos($errorResponse['error']['message'], 'disabled') !== false) {
                            $errorMessage = "YouTube API has been disabled for this project. Please contact the administrator.";
                        }
                    }

                    $this->durationError = $errorMessage;
                    \Log::error("YouTube API error", [
                        'http_status' => $httpCode,
                        'response' => $response,
                        'error_details' => $errorResponse['error'] ?? 'No error details available'
                    ]);
                    $this->isDurationLoading = false;

                    // Try fallback method if API fails
                    $this->lectureDuration = $this->getYoutubeDurationWithoutApi($videoId);
                    if ($this->lectureDuration) {
                        \Log::info('Successfully retrieved duration using fallback method after API error', ['duration' => $this->lectureDuration]);
                        $durationFound = true;
                        $this->durationError = null; // Clear the error since we got the duration
                    }

                    return;
                }

                // If we didn't use the fallback method yet
                if (!$durationFound) {
                    $data = json_decode($response, true);

                    if (!$data || json_last_error() !== JSON_ERROR_NONE) {
                        $this->durationError = "Invalid JSON response from API";
                        \Log::error('JSON decode error: ' . json_last_error_msg(), ['response' => $response]);
                        $this->isDurationLoading = false;
                        return;
                    }

                    if (empty($data['items'])) {
                        $this->durationError = "No data found for this video";
                        \Log::warning('No items returned from YouTube API for video ID: ' . $videoId);
                        $this->isDurationLoading = false;
                        return;
                    }

                    // Get duration in ISO 8601 format (e.g., PT1H2M3S)
                    $duration = $data['items'][0]['contentDetails']['duration'];

                    // Convert ISO 8601 duration to human-readable format
                    $this->lectureDuration = $this->formatDuration($duration);
                    \Log::info('Successfully retrieved duration using API', ['duration' => $this->lectureDuration]);
                }

            } catch (\Exception $e) {
                $this->durationError = "Error: " . $e->getMessage();
                \Log::error('Error getting YouTube video duration: ' . $e->getMessage(), [
                    'url' => $this->lectureYoutubeUrl,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $this->isDurationLoading = false;
        } else {
            $this->durationError = null;
            $this->lectureDuration = null;
        }
    }

    /**
     * Format ISO 8601 duration to human-readable format
     *
     * @param string $isoDuration
     * @return string
     */
    private function formatDuration($isoDuration)
    {
        try {
            $interval = new \DateInterval($isoDuration);

            $hours = $interval->h;
            $minutes = $interval->i;
            $seconds = $interval->s;

            // Add days if present (for very long videos)
            if ($interval->d > 0) {
                $hours += $interval->d * 24;
            }

            if ($hours > 0) {
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            } else {
                return sprintf('%02d:%02d', $minutes, $seconds);
            }
        } catch (\Exception $e) {
            \Log::error('Error formatting duration: ' . $e->getMessage(), ['isoDuration' => $isoDuration]);
            return null;
        }
    }

    /**
     * Extract YouTube video ID from URL
     *
     * @param string $url
     * @return string|null
     */
    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\s*(?:\w*\s*\/)*(?:v\/)?|(?:v|e(?:mbed)?)\/|\w+\?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get YouTube video duration without using the YouTube API
     * This is a fallback method when the API key has issues or quota is exceeded
     *
     * @param string $videoId
     * @return string|null
     */
    private function getYoutubeDurationWithoutApi($videoId)
    {
        try {
            // Try to get the video info page
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/watch?v=$videoId");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            $html = curl_exec($ch);

            if (curl_errno($ch)) {
                \Log::error('cURL Error in fallback method: ' . curl_error($ch));
                curl_close($ch);
                return null;
            }

            curl_close($ch);

            // Try to extract duration using regex
            // First try to find lengthSeconds in the videoDetails
            if (preg_match('/"lengthSeconds":"(\d+)"/', $html, $matches)) {
                $seconds = intval($matches[1]);
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;

                if ($hours > 0) {
                    return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
                } else {
                    return sprintf('%02d:%02d', $minutes, $secs);
                }
            }

            // Try alternative pattern
            if (preg_match('/"length_seconds":"(\d+)"/', $html, $matches)) {
                $seconds = intval($matches[1]);
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;

                if ($hours > 0) {
                    return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
                } else {
                    return sprintf('%02d:%02d', $minutes, $secs);
                }
            }

            // Try yet another pattern for duration
            if (preg_match('/PT((\d+)H)?((\d+)M)?((\d+)S)?/', $html, $matches)) {
                $hours = !empty($matches[2]) ? intval($matches[2]) : 0;
                $minutes = !empty($matches[4]) ? intval($matches[4]) : 0;
                $seconds = !empty($matches[6]) ? intval($matches[6]) : 0;

                if ($hours > 0) {
                    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                } else {
                    return sprintf('%02d:%02d', $minutes, $seconds);
                }
            }

            // If all patterns fail, return null
            \Log::warning('Could not extract duration from YouTube page', ['video_id' => $videoId]);
            return null;

        } catch (\Exception $e) {
            \Log::error('Error in fallback duration method: ' . $e->getMessage(), [
                'video_id' => $videoId,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    // Course Feature Methods
    public function openCourseFeatureModal()
    {
        $this->resetCourseFeatureForm();
        $this->showCourseFeatureModal = true;
    }

    public function closeCourseFeatureModal()
    {
        $this->showCourseFeatureModal = false;
        $this->resetCourseFeatureForm();
    }

    public function resetCourseFeatureForm()
    {
        $this->courseFeatureId = null;
        $this->courseFeatureText = '';
        $this->courseFeatureType = 'learn';
    }

    public function loadCourseFeatures($courseId)
    {
        if (!$courseId) return;

        $this->courseFeatures = CourseFeature::where('course_id', $courseId)
                                ->orderBy('feature_type')
                                ->orderBy('sort_order')
                                ->get()
                                ->toArray();
    }

    public function editCourseFeature($featureId)
    {
        $feature = CourseFeature::findOrFail($featureId);
        $this->courseFeatureId = $feature->id;
        $this->courseFeatureText = $feature->feature_text;
        $this->courseFeatureType = $feature->feature_type;
        $this->showCourseFeatureModal = true;
    }

    public function saveCourseFeature()
    {
        $this->validate([
            'courseFeatureText' => 'required|string|min:3',
            'courseFeatureType' => 'required|in:learn,requirement,includes',
        ]);

        if (!$this->courseId) {
            session()->flash('error', 'Please select a course first');
            return;
        }

        // Get max sort order for this type
        $maxSortOrder = CourseFeature::where('course_id', $this->courseId)
                        ->where('feature_type', $this->courseFeatureType)
                        ->max('sort_order');

        if ($this->courseFeatureId) {
            // Update existing feature
            $feature = CourseFeature::findOrFail($this->courseFeatureId);
            $feature->update([
                'feature_text' => $this->courseFeatureText,
                'feature_type' => $this->courseFeatureType,
            ]);
            session()->flash('message', 'Course feature updated successfully.');
        } else {
            // Create new feature
            CourseFeature::create([
                'course_id' => $this->courseId,
                'feature_text' => $this->courseFeatureText,
                'feature_type' => $this->courseFeatureType,
                'sort_order' => ($maxSortOrder ?? 0) + 1,
            ]);
            session()->flash('message', 'Course feature added successfully.');
        }

        $this->loadCourseFeatures($this->courseId);
        $this->closeCourseFeatureModal();
    }

    public function deleteCourseFeature($featureId)
    {
        CourseFeature::destroy($featureId);
        session()->flash('message', 'Course feature deleted successfully.');
        $this->loadCourseFeatures($this->courseId);
    }

    // Lecture Feature Methods
    public function openLectureFeatureModal()
    {
        $this->resetLectureFeatureForm();
        $this->showLectureFeatureModal = true;
    }

    public function closeLectureFeatureModal()
    {
        $this->showLectureFeatureModal = false;
        $this->resetLectureFeatureForm();
    }

    public function resetLectureFeatureForm()
    {
        $this->lectureFeatureId = null;
        $this->lectureFeatureText = '';
        $this->lectureFeatureType = 'learn';
    }

    public function loadLectureFeatures($lectureId)
    {
        if (!$lectureId) return;

        $this->lectureFeatures = LectureFeature::where('lecture_id', $lectureId)
                                ->orderBy('feature_type')
                                ->orderBy('sort_order')
                                ->get()
                                ->toArray();
    }

    public function editLectureFeature($featureId)
    {
        $feature = LectureFeature::findOrFail($featureId);
        $this->lectureFeatureId = $feature->id;
        $this->lectureFeatureText = $feature->feature_text;
        $this->lectureFeatureType = $feature->feature_type;
        $this->showLectureFeatureModal = true;
    }

    public function saveLectureFeature()
    {
        $this->validate([
            'lectureFeatureText' => 'required|string|min:3',
            'lectureFeatureType' => 'required|in:learn,requirement',
        ]);

        if (!$this->lectureId) {
            session()->flash('error', 'Please select a lecture first');
            return;
        }

        // Get max sort order for this type
        $maxSortOrder = LectureFeature::where('lecture_id', $this->lectureId)
                        ->where('feature_type', $this->lectureFeatureType)
                        ->max('sort_order');

        if ($this->lectureFeatureId) {
            // Update existing feature
            $feature = LectureFeature::findOrFail($this->lectureFeatureId);
            $feature->update([
                'feature_text' => $this->lectureFeatureText,
                'feature_type' => $this->lectureFeatureType,
            ]);
            session()->flash('message', 'Lecture feature updated successfully.');
        } else {
            // Create new feature
            LectureFeature::create([
                'lecture_id' => $this->lectureId,
                'feature_text' => $this->lectureFeatureText,
                'feature_type' => $this->lectureFeatureType,
                'sort_order' => ($maxSortOrder ?? 0) + 1,
            ]);
            session()->flash('message', 'Lecture feature added successfully.');
        }

        $this->loadLectureFeatures($this->lectureId);
        $this->closeLectureFeatureModal();
    }

    public function deleteLectureFeature($featureId)
    {
        LectureFeature::destroy($featureId);
        session()->flash('message', 'Lecture feature deleted successfully.');
        $this->loadLectureFeatures($this->lectureId);
    }
}
