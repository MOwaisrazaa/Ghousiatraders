<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Lecture Details</h1>
                <p class="mb-0">Viewing lecture: {{ $lecture->name }}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.lectures') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Lectures
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Lecture Information</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Name</th>
                            <td>{{ $lecture->name }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $lecture->description ?: 'No description provided' }}</td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td>
                                @if($lecture->course)
                                    {{ $lecture->course->name }}
                                @else
                                    <span class="badge bg-info">Standalone Lecture</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{ $lecture->duration_formatted ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Order</th>
                            <td>{{ $lecture->order }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $lecture->created_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $lecture->updated_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if($lecture->materials && $lecture->materials->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Lecture Materials</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lecture->materials as $material)
                                <tr>
                                    <td>
                                        @if($material->type == 'video')
                                            <span class="badge bg-primary">Video</span>
                                        @elseif($material->type == 'document')
                                            <span class="badge bg-info">Document</span>
                                        @elseif($material->type == 'image')
                                            <span class="badge bg-success">Image</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($material->type) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $material->title }}</td>
                                    <td>{{ $material->description ?: 'No description' }}</td>
                                    <td>{{ $material->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(isset($lecture->quizzes) && $lecture->quizzes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Lecture Quizzes</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Points</th>
                                <th>Passing Score</th>
                                <th>Required</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lecture->quizzes as $quiz)
                                <tr>
                                    <td>{{ $quiz->title }}</td>
                                    <td>{{ $quiz->total_points }}</td>
                                    <td>{{ $quiz->passing_score }}%</td>
                                    <td>
                                        @if($quiz->is_required)
                                            <span class="badge bg-success">Required</span>
                                        @else
                                            <span class="badge bg-secondary">Optional</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout> 