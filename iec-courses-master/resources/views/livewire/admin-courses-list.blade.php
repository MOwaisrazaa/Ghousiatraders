<div>
    <!-- Admin Navigation -->
    @include('partials.admin-nav')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Course Management</h1>
            <a href="{{ route('admin.course.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Course
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">All Courses</h5>
            </div>
            <div class="card-body">
                @if(count($courses) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Name</th>
                                    <th>Weekly Price</th>
                                    <th>Monthly Price</th>
                                    <th>Lectures</th>
                                    <th>Features</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td>
                                            <img src="{{ $course->image_path ? \Illuminate\Support\Facades\Storage::url($course->image_path) : 'https://via.placeholder.com/50x50' }}"
                                                alt="{{ $course->name }}"
                                                class="img-thumbnail course-thumbnail-50">
                                        </td>
                                        <td>{{ $course->name }}</td>
                                        <td>Rs {{ number_format($course->weekly_price, 2) }}</td>
                                        <td>Rs {{ number_format($course->monthly_price, 2) }}</td>
                                        <td>{{ $course->lectures_count ?? count($course->lectures ?? []) }}</td>
                                        <td>
                                            <span class="badge bg-primary me-1">{{ $course->learn_features_count ?? 0 }} learning points</span>
                                            <span class="badge bg-info">{{ $course->requirement_features_count ?? 0 }} requirements</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.course.edit', $course->id) }}" class="btn btn-sm btn-primary me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('course.detail', $course->id) }}" class="btn btn-sm btn-info" target="_blank">
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
                        <p>No courses have been added yet. Click the "Add New Course" button to create your first course.</p>
                        <a href="{{ route('admin.course.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Create First Course
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
