<div>
    <!-- Admin Navigation -->
    @include('partials.admin-nav')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Lecture Management</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Filter Lectures</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="courseFilter" class="form-label">Filter by Course</label>
                            <select class="form-select" id="courseFilter" wire:model.live="courseFilter">
                                <option value="">All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->lectures_count }} lectures)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Lectures</h5>
                <div>
                    @if($courseFilter)
                        <a href="{{ route('admin.lecture.create', $courseFilter) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add New Lecture
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(count($lectures) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Lecture Name</th>
                                    <th>Course</th>
                                    <th>Weekly Price</th>
                                    <th>Monthly Price</th>
                                    <th>Video</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lectures as $lecture)
                                    <tr>
                                        <td>
                                            <img src="{{ $lecture->image_path ? \Illuminate\Support\Facades\Storage::url($lecture->image_path) : 'https://via.placeholder.com/50x50' }}"
                                                alt="{{ $lecture->name }}"
                                                class="img-thumbnail course-thumbnail-50">
                                        </td>
                                        <td>{{ $lecture->name }}</td>
                                        <td>
                                            @if($lecture->course)
                                                <a href="{{ route('admin.course.edit', $lecture->course_id) }}">
                                                    {{ $lecture->course->name }}
                                                </a>
                                            @else
                                                <span class="badge bg-info">Standalone</span>
                                            @endif
                                        </td>
                                        <td>Rs {{ number_format($lecture->weekly_price, 2) }}</td>
                                        <td>Rs {{ number_format($lecture->monthly_price, 2) }}</td>
                                        <td>
                                            @if($lecture->youtube_url)
                                                <a href="{{ $lecture->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fab fa-youtube"></i>
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">No Video</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lecture->course_id)
                                                <a href="{{ route('admin.lecture.edit', ['courseId' => $lecture->course_id, 'id' => $lecture->id]) }}" class="btn btn-sm btn-primary me-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.course.create') }}?lecture={{ $lecture->id }}&type=lecture&standalone=1" class="btn btn-sm btn-primary me-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('lecture.detail', ['course' => $lecture->course_id ?? 0, 'lecture' => $lecture->id]) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        @if($courseFilter)
                            <p>No lectures found for this course. Add some lectures to get started.</p>
                            <a href="{{ route('admin.lecture.create', $courseFilter) }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Add First Lecture
                            </a>
                        @else
                            <p>No lectures found. Select a course to add lectures.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
