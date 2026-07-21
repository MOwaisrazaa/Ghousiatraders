@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-questions.css') }}">
@endsection

@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if(Auth::user()->isSuperAdmin())
            <h1 class="h3 mb-0 text-gray-800">All Questions</h1>
        @else
            <h1 class="h3 mb-0 text-gray-800">All Questions from My Assigned Users</h1>
        @endif

        <div></div>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-clock fa-fw"></i> Pending Questions
            </a>
            @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.assignments.index') }}" class="btn btn-success btn-sm ml-2">
                    <i class="fas fa-users fa-fw"></i> Manage User Assignments
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Questions</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.questions.all') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                @if(Auth::user()->isSuperAdmin())
                <div class="col-md-3">
                    <label for="admin_id" class="form-label">Admin</label>
                    <select name="admin_id" id="admin_id" class="form-select">
                        <option value="">All Admins</option>
                        @foreach(App\Models\User::whereHas('roles', function($query) { $query->where('name', 'Admin'); })->get() as $admin)
                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3">
                    <label for="course_id" class="form-label">Course</label>
                    <select name="course_id" id="course_id" class="form-select">
                        <option value="">All Courses</option>
                        @foreach(App\Models\Course::all() as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                    <a href="{{ route('admin.questions.all') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Questions List</h6>

            @if(!Auth::user()->isSuperAdmin())
                <span class="badge bg-info text-white">
                    Showing questions from your {{ Auth::user()->assignedUsers->count() }} assigned users
                </span>
            @endif
        </div>
        <div class="card-body">
            @if($questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Content</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            <tr>
                                <td>{{ $question->id }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $question->user->name }}</strong>
                                        @if(Auth::user()->isSuperAdmin())
                                            @php
                                                $adminAssignment = App\Models\AdminUserAssignment::where('user_id', $question->user_id)->first();
                                            @endphp

                                            @if($adminAssignment)
                                                <div class="small text-muted">
                                                    Assigned to: {{ $adminAssignment->admin->name }}
                                                </div>
                                            @else
                                                <div class="small text-warning">
                                                    Unassigned
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate text-truncate-200">
                                        {{ $question->content }}
                                    </div>
                                    @if($question->attachments->count() > 0)
                                        <span class="badge bg-info text-white">
                                            {{ $question->attachments->count() }} attachment(s)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        @if($question->course_id)
                                            <a href="{{ route('user.course.purchased', $question->course_id) }}" class="text-primary fw-bold" target="_blank">
                                                {{ $question->course?->name ?? 'N/A' }}
                                                <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Standalone Lecture</span>
                                        @endif
                                    </div>
                                    @if($question->lecture)
                                        <div class="small text-muted mt-1">
                                            @if($question->course_id)
                                                <a href="{{ route('user.lecture.purchased', ['course' => $question->course_id, 'lecture' => $question->lecture_id]) }}" class="text-muted" target="_blank">
                                                    Lecture: {{ $question->lecture?->name }}
                                                    <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('user.lecture.standalone', ['lecture' => $question->lecture_id]) }}" class="text-muted" target="_blank">
                                                    Lecture: {{ $question->lecture?->name }}
                                                    <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($question->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($question->status == 'answered')
                                        <span class="badge bg-success">Answered</span>
                                    @elseif($question->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif

                                    @if($question->answers->count() > 0)
                                        <div class="small text-muted mt-1">
                                            {{ $question->answers->count() }} answer(s)
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $question->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#questionModal{{ $question->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Question Modal -->
                            <div class="modal fade" id="questionModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Question Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="question-header mb-3">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong>From:</strong> {{ $question->user->name }} ({{ $question->user->email }})
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-{{ $question->status == 'pending' ? 'warning text-dark' : ($question->status == 'answered' ? 'success' : 'danger') }}">
                                                            {{ ucfirst($question->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-muted small">
                                                    <strong>Course:</strong>
                                                    @if($question->course_id)
                                                        <a href="{{ route('user.course.purchased', $question->course_id) }}" class="text-primary" target="_blank">
                                                            {{ $question->course?->name ?? 'N/A' }}
                                                            <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Standalone Lecture</span>
                                                    @endif
                                                    @if($question->lecture)
                                                        <span class="mx-2">|</span>
                                                        <strong>Lecture:</strong>
                                                        @if($question->course_id)
                                                            <a href="{{ route('user.lecture.purchased', ['course' => $question->course_id, 'lecture' => $question->lecture_id]) }}" class="text-primary" target="_blank">
                                                                {{ $question->lecture?->name }}
                                                                <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('user.lecture.standalone', ['lecture' => $question->lecture_id]) }}" class="text-primary" target="_blank">
                                                                {{ $question->lecture?->name }}
                                                                <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                    <span class="mx-2">|</span>
                                                    <strong>Date:</strong> {{ $question->created_at->format('F d, Y h:i A') }}
                                                </div>
                                            </div>

                                            <div class="question-content p-3 bg-light rounded mb-3">
                                                <p>{{ $question->content }}</p>

                                                @if($question->attachments && $question->attachments->count() > 0)
                                                <div class="question-attachments mt-3">
                                                    <h6 class="mb-2">Attachments:</h6>
                                                    <div class="row">
                                                        @foreach($question->attachments as $attachment)
                                                            @if($attachment->file_type == 'image')
                                                                <div class="col-md-3 mb-2">
                                                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                        <img src="{{ Storage::url($attachment->file_path) }}" alt="Attachment" class="img-thumbnail attachment-thumbnail-150">
                                                                    </a>
                                                                </div>
                                                            @elseif($attachment->file_type == 'pdf')
                                                                <div class="col-md-3 mb-2">
                                                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-outline-danger">
                                                                        <i class="fas fa-file-pdf me-1"></i> {{ $attachment->file_name ?? 'PDF Document' }}
                                                                    </a>
                                                                </div>
                                                            @elseif($attachment->file_type == 'voice')
                                                                <div class="col-md-6 mb-2">
                                                                    <audio controls class="w-100">
                                                                        <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type ?? 'audio/webm' }}">
                                                                        Your browser does not support the audio element.
                                                                    </audio>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <!-- Answers Section -->
                                            <div class="answers-section">
                                                <h6 class="mb-3">Answers ({{ $question->answers->count() }})</h6>

                                                @if($question->answers->count() > 0)
                                                    @foreach($question->answers as $answer)
                                                    <div class="answer-card mb-3 {{ $answer->is_pinned ? 'border-start border-success border-3 ps-3' : '' }}">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong class="text-primary">{{ $answer->user->name }}</strong>
                                                                <small class="text-muted ms-2">{{ $answer->created_at->diffForHumans() }}</small>
                                                                @if($answer->is_pinned)
                                                                    <span class="badge bg-success ms-2">
                                                                        <i class="fas fa-thumbtack me-1"></i> Pinned
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <p class="mb-0 mt-2">{{ $answer->content }}</p>

                                                        @if($answer->attachments && $answer->attachments->count() > 0)
                                                        <div class="answer-attachments mt-2">
                                                            @foreach($answer->attachments as $attachment)
                                                                @if($attachment->file_type == 'image')
                                                                    <div class="mb-2">
                                                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                            <img src="{{ Storage::url($attachment->file_path) }}" class="img-thumbnail attachment-thumbnail-150">
                                                                        </a>
                                                                    </div>
                                                                @elseif($attachment->file_type == 'pdf')
                                                                    <div class="mb-2">
                                                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                                            <i class="fas fa-file-pdf me-1"></i> {{ $attachment->original_name }}
                                                                        </a>
                                                                    </div>
                                                                @elseif($attachment->file_type == 'voice')
                                                                    <div class="mb-2">
                                                                        <audio controls>
                                                                            <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type ?? 'audio/webm' }}">
                                                                            Your browser does not support the audio element.
                                                                        </audio>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-light text-center">
                                                        No answers yet
                                                    </div>
                                                @endif

                                                @if($question->status == 'pending')
                                                    <!-- Simple Answer Form -->
                                                    @livewire('admin.simple-answer-form', ['questionId' => $question->id], key('simple-answer-form-'.$question->id))
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="me-auto">
                                                @if($question->lecture_id)
                                                    @if($question->course_id)
                                                        <a href="{{ route('user.lecture.purchased', ['course' => $question->course_id, 'lecture' => $question->lecture_id]) }}" class="btn btn-info" target="_blank">
                                                            <i class="fas fa-play-circle me-1"></i> View Lecture Content
                                                        </a>
                                                    @else
                                                        <a href="{{ route('user.lecture.standalone', ['lecture' => $question->lecture_id]) }}" class="btn btn-info" target="_blank">
                                                            <i class="fas fa-play-circle me-1"></i> View Lecture Content
                                                        </a>
                                                    @endif
                                                @elseif($question->course_id)
                                                    <a href="{{ route('user.course.purchased', $question->course_id) }}" class="btn btn-info" target="_blank">
                                                        <i class="fas fa-book me-1"></i> View Course Content
                                                    </a>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="mb-0">No questions found matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for answer submission success
    window.addEventListener('answer-submitted', function(event) {
        const data = event.detail[0];

        // Show success message
        if (data.message) {
            // Create and show toast notification
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            document.body.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
            });
        }

        // Refresh the page after a short delay to show updated question status
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    });

    // Listen for modal close events
    window.addEventListener('close-modal', function(event) {
        const data = event.detail[0];
        if (data.modalId) {
            const modal = document.getElementById(data.modalId);
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        }
    });
});
</script>
@endpush
