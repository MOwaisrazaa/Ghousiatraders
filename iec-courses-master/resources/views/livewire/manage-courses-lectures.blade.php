<div>
<div class="container">
    <h1>Manage Courses & Lectures</h1>

    {{-- Manage Courses --}}
    <div class="card mb-5">
        <div class="card-header">Manage Courses</div>
        <div class="card-body">
            <form wire:submit.prevent="saveCourse">
                <input type="hidden" wire:model="courseId">

                {{-- Course Name --}}
                <div class="mb-3">
                    <label for="courseName" class="form-label">Course Name</label>
                    <input type="text" id="courseName" wire:model="courseName" class="form-control" placeholder="Enter course name">
                </div>

                {{-- Course Instructor --}}
                <div class="mb-3">
                    <label for="courseInstructor" class="form-label">Instructor</label>
                    <select id="courseInstructor" wire:model="courseInstructor" class="form-control">
                        <option value="">Select an instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor['name'] }}">{{ $instructor['name'] }} ({{ $instructor['title'] }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Course Category --}}
                <div class="mb-3">
                    <label for="categoryId" class="form-label">Category</label>
                    <select id="categoryId" wire:model="categoryId" class="form-control">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Course Description --}}
                <div class="mb-3">
                    <label for="courseDescription" class="form-label">Course Description</label>
                    <textarea id="courseDescription" wire:model.debounce.500ms="courseDescription" class="form-control editor"></textarea>
                </div>

                {{-- Free Course Checkbox --}}
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="courseIsFree" wire:model="courseIsFree">
                        <label class="form-check-label" for="courseIsFree">
                            <strong>Free Course</strong>
                        </label>
                        <div class="form-text">Check this box to make the course free for all users</div>
                    </div>
                </div>

                {{-- Purchase Model --}}
                <div class="mb-3">
                    <label for="coursePurchaseModel" class="form-label">Purchase Model</label>
                    <select id="coursePurchaseModel" wire:model="coursePurchaseModel" class="form-control">
                        <option value="flexible">Flexible (Whole Course + Individual Lectures)</option>
                        <option value="restricted">Restricted (Whole Course Only)</option>
                    </select>
                    <small class="text-muted d-block mt-2">
                        <strong>Flexible:</strong> Users can buy the whole course or individual lectures<br>
                        <strong>Restricted:</strong> Users can only buy the entire course. Individual lectures won't be shown on the public dashboard.
                    </small>
                </div>

                {{-- Course Price --}}
                @if(!$courseIsFree)
                <div class="mb-3">
                    <label for="courseWeeklyPrice" class="form-label">Price</label>
                    <input type="number" step="0.01" id="courseWeeklyPrice" wire:model="courseWeeklyPrice" class="form-control" placeholder="Enter price">
                </div>
                @endif

                {{-- Course Image Path --}}
                {{-- Course Image Path --}}
<div class="mb-3">
    <label for="courseImagePath" class="form-label">Upload Image</label>
    <input type="file" id="courseImagePath" wire:model="courseImagePath" class="form-control">
    @error('courseImagePath') <span class="text-danger">{{ $message }}</span> @enderror
    <div wire:loading wire:target="courseImagePath">Uploading...</div>
    
    @if ($courseImagePath)
        <div class="mt-2">
            @if (is_string($courseImagePath))
                <p>Current Image:</p>
                <img src="{{ Storage::url($courseImagePath) }}" alt="Course Image" class="img-thumbnail" style="max-height: 150px;">
            @elseif (method_exists($courseImagePath, 'temporaryUrl'))
                <p>New Image Preview:</p>
                <img src="{{ $courseImagePath->temporaryUrl() }}" alt="New Image" class="img-thumbnail" style="max-height: 150px;">
            @endif
        </div>
    @endif
</div>

                {{-- Course Intro Video URL --}}
                <div class="mb-3">
                    <label for="courseIntroVideoUrl" class="form-label">Introduction Video URL (YouTube)</label>
                    <input type="url" id="courseIntroVideoUrl" wire:model="courseIntroVideoUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=example">
                    <small class="text-muted">Enter a YouTube URL for the course introduction video</small>
                </div>

                <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Update Course' : 'Add Course' }}</button>
            </form>

            @if($isEditMode && $courseId)
                {{-- Course Features Management --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Course Features</h4>
                        <button type="button" class="btn btn-sm btn-primary" wire:click.prevent="openCourseFeatureModal">
                            <i class="fas fa-plus"></i> Add Feature
                        </button>
                    </div>

                    @if(count($courseFeatures) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Feature Type</th>
                                        <th>Feature Text</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courseFeatures as $feature)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $feature['feature_type'] === 'learn' ? 'primary' : ($feature['feature_type'] === 'requirement' ? 'info' : 'success') }}">
                                                    {{ ucfirst($feature['feature_type']) }}
                                                </span>
                                            </td>
                                            <td>{{ $feature['feature_text'] }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" wire:click.prevent="editCourseFeature({{ $feature['id'] }})" wire:key="edit-course-feature-{{ $feature['id'] }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" wire:click.prevent="deleteCourseFeature({{ $feature['id'] }})" onclick="return confirm('Are you sure you want to delete this feature?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No features added to this course yet.</div>
                    @endif
                </div>
            @endif

            {{-- List Courses --}}
            <h3 class="mt-4">Course List</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 65%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td style="padding: 10px; word-break: break-word; white-space: normal; overflow-wrap: break-word;">{{ $course->name }}</td>
                                <td style="text-align: center; padding: 10px; vertical-align: middle;">
                                    <button wire:click="editCourse({{ $course->id }})" class="btn btn-sm btn-warning me-1">Edit</button>
                                    <button wire:click="deleteCourse({{ $course->id }})" class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; padding: 10px;">No courses available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Manage Lectures --}}
    <div class="card">
        <div class="card-header">Manage Lectures</div>
        <div class="card-body">
            <form wire:submit.prevent="saveLecture">
                {{-- Standalone Lecture Checkbox --}}
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isStandaloneLecture" wire:model="isStandaloneLecture">
                        <label class="form-check-label" for="isStandaloneLecture">
                            <strong>Standalone Lecture</strong>
                        </label>
                        <div class="form-text">Check this box to create a lecture that doesn't belong to any course</div>
                    </div>
                </div>

                {{-- Select Course (only show if not standalone) --}}
                @if(!$isStandaloneLecture)
                <div class="mb-3">
                    <label for="selectedCourse" class="form-label">Select Course <span class="text-danger">*</span></label>
                    <select id="selectedCourse" wire:model="selectedCourse" class="form-control">
                        <option value="">Select a course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCourse') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @endif


                {{-- Lecture Name --}}
                <div class="mb-3">
                    <label for="lectureName" class="form-label">Lecture Name</label>
                    <input type="text" id="lectureName" wire:model="lectureName" class="form-control" placeholder="Enter lecture name">
                    @error('lectureName') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Lecture Instructor --}}
                <div class="mb-3">
                    <label for="lectureInstructor" class="form-label">Instructor</label>
                    <select id="lectureInstructor" wire:model="lectureInstructor" class="form-control">
                        <option value="">Select an instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor['name'] }}">{{ $instructor['name'] }} ({{ $instructor['title'] }})</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Leave empty to use course instructor</small>
                </div>

                {{-- Lecture Description --}}
                <div class="mb-3">
                    <label for="lectureDescription" class="form-label">Lecture Description</label>
                    <textarea id="lectureDescription" wire:model.debounce.500ms="lectureDescription" class="form-control editor"></textarea>
                    @error('lectureDescription') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Lecture Intro Video URL --}}
                <div class="mb-3">
                    <label for="lectureIntroVideoUrl" class="form-label">Introduction Video URL (YouTube)</label>
                    <input type="url" id="lectureIntroVideoUrl" wire:model="lectureIntroVideoUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=example">
                    <small class="text-muted">Enter a YouTube URL for the lecture introduction video (different from the main lecture content)</small>
                </div>

                {{-- Free Lecture Checkbox --}}
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="lectureIsFree" wire:model="lectureIsFree">
                        <label class="form-check-label" for="lectureIsFree">
                            <strong>Free Lecture</strong>
                        </label>
                        <div class="form-text">Check this box to make the lecture free for all users</div>
                    </div>
                </div>

                {{-- Lecture Price --}}
                @if(!$lectureIsFree)
                <div class="mb-3">
                    <label for="lectureWeeklyPrice" class="form-label">Price</label>
                    <input type="number" step="0.01" id="lectureWeeklyPrice" wire:model="lectureWeeklyPrice" class="form-control" placeholder="Enter price">
                    @error('lectureWeeklyPrice') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Lecture YouTube URL --}}
                <div class="mb-3">
                    <label for="lectureYoutubeUrl" class="form-label">YouTube URL</label>
                    <div class="input-group">
                        <input type="text" id="lectureYoutubeUrl" wire:model.lazy="lectureYoutubeUrl" class="form-control" placeholder="Enter YouTube URL">
                        <button type="button" class="btn btn-outline-secondary" wire:click="fetchYoutubeDuration" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="fetchYoutubeDuration">
                                <i class="fas fa-sync"></i> Get Duration
                            </span>
                            <span wire:loading wire:target="fetchYoutubeDuration">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </span>
                        </button>
                    </div>
                    @if($isDurationLoading)
                        <div class="mt-2 text-info">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <small class="ms-2">Fetching video duration...</small>
                        </div>
                    @elseif($durationError)
                        <div class="mt-2 text-danger">
                            <small><i class="fas fa-exclamation-circle"></i> {{ $durationError }}</small>
                        </div>
                    @elseif($lectureDuration)
                        <div class="mt-2 text-success">
                            <small><i class="fas fa-check-circle"></i> Auto-detected Duration: <span class="font-weight-bold">{{ $lectureDuration }}</span></small>
                        </div>
                    @endif
                </div>

                {{-- Lecture Duration - Always visible --}}
                <div class="mb-3">
                    <label for="lectureDuration" class="form-label">Video Duration (HH:MM:SS)</label>
                    <input type="text" id="lectureDuration" wire:model="lectureDuration" class="form-control" placeholder="Enter duration (e.g., 00:15:30)">
                    @error('lectureDuration') <span class="text-danger">{{ $message }}</span> @enderror
                    <small class="text-muted">This will be auto-filled when a valid YouTube URL is entered, but you can modify it manually if needed.</small>
                </div>

                {{-- Lecture Image Path --}}
                <div class="mb-3">
    <label for="lectureImagePath" class="form-label">Upload Image</label>
    <input type="file" id="lectureImagePath" wire:model="lectureImagePath" class="form-control">
    @error('lectureImagePath') <span class="text-danger">{{ $message }}</span> @enderror
    <div wire:loading wire:target="lectureImagePath">Uploading...</div>
</div>

                {{-- Lecture PDF File --}}
                <div class="mb-3">
                    <label for="lecturePdfFile" class="form-label">Upload PDF File</label>
                    <input type="file" id="lecturePdfFile" wire:model="lecturePdfFile" class="form-control" accept=".pdf">
                    @error('lecturePdfFile') <span class="text-danger">{{ $message }}</span> @enderror
                    <div wire:loading wire:target="lecturePdfFile">Uploading...</div>
                    @if($isEditMode && $lectureId)
                        @php
                            $lecture = App\Models\Lecture::find($lectureId);
                        @endphp
                        @if($lecture && $lecture->pdf_file_path)
                            <div class="mt-2">
                                <span class="text-success">Current PDF: </span>
                                <a href="{{ asset('storage/' . $lecture->pdf_file_path) }}" target="_blank">View PDF</a>
                            </div>
                        @endif
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ $isEditMode ? 'Update Lecture' : 'Add Lecture' }}</button>
            </form>

            @if($isEditMode && $lectureId)
                {{-- Lecture Features Management --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Lecture Features</h4>
                        <button type="button" class="btn btn-sm btn-primary" wire:click.prevent="openLectureFeatureModal">
                            <i class="fas fa-plus"></i> Add Feature
                        </button>
                    </div>

                    @if(count($lectureFeatures) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Feature Type</th>
                                        <th>Feature Text</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lectureFeatures as $feature)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $feature['feature_type'] === 'learn' ? 'primary' : 'info' }}">
                                                    {{ ucfirst($feature['feature_type']) }}
                                                </span>
                                            </td>
                                            <td>{{ $feature['feature_text'] }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" wire:click.prevent="editLectureFeature({{ $feature['id'] }})" wire:key="edit-lecture-feature-{{ $feature['id'] }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" wire:click.prevent="deleteLectureFeature({{ $feature['id'] }})" onclick="return confirm('Are you sure you want to delete this feature?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No features added to this lecture yet.</div>
                    @endif
                </div>
            @endif

            {{-- List Lectures --}}
            <h3 class="mt-4">Lecture List</h3>
            <div class="table-responsive lecture-list-table-wrapper">
                <table class="table table-bordered table-sm lecture-list-table" style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 25%;">
                        <col style="width: 10%;">
                        <col style="width: 12%;">
                        <col style="width: 33%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th style="text-align: center;">Type</th>
                            <th style="text-align: center;">Price</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lectures as $lecture)
                            <tr>
                                <td style="padding: 10px; word-break: break-word; white-space: normal; overflow-wrap: break-word;">{{ $lecture->name }}</td>
                            <td class="lecture-course-cell" style="padding: 10px; word-break: break-word; white-space: normal; overflow-wrap: break-word;">
                                @if($lecture->course)
                                    {{ $lecture->course->name }}
                                @else
                                    <span class="badge bg-info">Standalone</span>
                                @endif
                                </td>
                                <td style="text-align: center; padding: 10px; vertical-align: middle;">
                                    @if($lecture->is_free)
                                        <span class="badge bg-success">Free</span>
                                    @else
                                        <span class="badge bg-primary">Paid</span>
                                    @endif
                                </td>
                                <td style="text-align: center; padding: 10px; vertical-align: middle;">
                                    @if($lecture->is_free)
                                        <span class="text-success">FREE</span>
                                    @else
                                        Rs {{ number_format($lecture->weekly_price, 2) }}
                                    @endif
                                </td>
                                <td style="text-align: center; padding: 10px; vertical-align: middle;">
                                    <button wire:click="editLecture({{ $lecture->id }})" class="btn btn-sm btn-warning me-1">Edit</button>
                                    <button wire:click="deleteLecture({{ $lecture->id }})" class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Course Features Inline Form (No Bootstrap Modal) --}}
@if($showCourseFeatureModal)
<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
    <div style="background-color: white; border-radius: 8px; padding: 2rem; max-width: 500px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <h5 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">{{ $courseFeatureId ? 'Edit' : 'Add' }} Course Feature</h5>

        <form wire:submit="saveCourseFeature">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Feature Type</label>
                <select wire:model="courseFeatureType" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    <option value="learn">What You'll Learn</option>
                    <option value="requirement">Requirements</option>
                    <option value="includes">This Course Includes</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Feature Text</label>
                <textarea wire:model="courseFeatureText" rows="4" placeholder="Enter feature text" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit;"></textarea>
                @error('courseFeatureText') <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" wire:click="closeCourseFeatureModal" style="padding: 0.5rem 1rem; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Cancel</button>
                <button type="submit" style="padding: 0.5rem 1rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Save</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Lecture Features Inline Form (No Bootstrap Modal) --}}
@if($showLectureFeatureModal)
<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
    <div style="background-color: white; border-radius: 8px; padding: 2rem; max-width: 500px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <h5 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">{{ $lectureFeatureId ? 'Edit' : 'Add' }} Lecture Feature</h5>

        <form wire:submit="saveLectureFeature">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Feature Type</label>
                <select wire:model="lectureFeatureType" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    <option value="learn">What You'll Learn</option>
                    <option value="requirement">Requirements</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Feature Text</label>
                <textarea wire:model="lectureFeatureText" rows="4" placeholder="Enter feature text" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit;"></textarea>
                @error('lectureFeatureText') <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" wire:click="closeLectureFeatureModal" style="padding: 0.5rem 1rem; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Cancel</button>
                <button type="submit" style="padding: 0.5rem 1rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Save</button>
            </div>
        </form>
    </div>
</div>
@endif
</div>
