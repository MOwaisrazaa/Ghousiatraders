@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-questions.css') }}">
@endsection

@extends('admin.layout')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if(Auth::user()->isSuperAdmin())
            <h1 class="h3 mb-0 text-gray-800">All Pending Questions</h1>
        @else
            <h1 class="h3 mb-0 text-gray-800">My Assigned Users' Questions</h1>
        @endif

        <div>
            <a href="{{ route('admin.questions.all') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-list fa-fw"></i> View All Questions
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

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Questions Requiring Response</h6>

            @if(!Auth::user()->isSuperAdmin())
                <span class="badge bg-info text-white">
                    Showing questions from your {{ Auth::user()->assignedUsers->count() }} assigned users
                </span>
            @endif
        </div>
        <div class="card-body">
            @if($pendingQuestions->count() > 0)
                <div class="questions-list">
                    @foreach($pendingQuestions as $question)
                    <div class="question-card mb-4" id="question-{{ $question->id }}">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $question->user->name }}</strong>
                                    <span class="text-muted ml-2">{{ $question->created_at->format('M d, Y H:i') }}</span>

                                    @if(Auth::user()->isSuperAdmin())
                                        @php
                                            $adminAssignment = App\Models\AdminUserAssignment::where('user_id', $question->user_id)->first();
                                        @endphp

                                        @if($adminAssignment)
                                            <span class="badge bg-secondary ml-2">
                                                Assigned to: {{ $adminAssignment->admin->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark ml-2">Unassigned User</span>
                                        @endif
                                    @endif
                                </div>
                                <div>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    <form action="{{ route('admin.questions.reject', $question->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm ms-2 question-reject-btn">
                                            <i class="fas fa-times fa-fw"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="question-content">
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

                                <div class="metadata mt-2">
                                    <div class="d-flex flex-wrap text-muted small">
                                        <div class="me-3">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            <a href="{{ route('user.course.purchased', $question->course_id) }}" class="text-primary" target="_blank">
                                                {{ $question->course?->name ?? 'N/A' }}
                                                <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                            </a>
                                        </div>
                                        @if($question->lecture)
                                        <div>
                                            <i class="fas fa-video me-1"></i>
                                            @if($question->course_id)
                                                <a href="{{ route('user.lecture.purchased', ['course' => $question->course_id, 'lecture' => $question->lecture_id]) }}" class="text-primary" target="_blank">
                                                    {{ $question->lecture?->name ?? 'N/A' }}
                                                    <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('user.lecture.standalone', ['lecture' => $question->lecture_id]) }}" class="text-primary" target="_blank">
                                                    {{ $question->lecture?->name ?? 'N/A' }}
                                                    <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                                </a>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                        <div class="mt-2">
                                            @if($question->lecture_id)
                                                @if($question->course_id)
                                                    <a href="{{ route('user.lecture.purchased', ['course' => $question->course_id, 'lecture' => $question->lecture_id]) }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="fas fa-play-circle me-1"></i> View Lecture Content
                                                    </a>
                                                @else
                                                    <a href="{{ route('user.lecture.standalone', ['lecture' => $question->lecture_id]) }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="fas fa-play-circle me-1"></i> View Lecture Content
                                                    </a>
                                                @endif
                                        @elseif($question->course_id)
                                            <a href="{{ route('user.course.purchased', $question->course_id) }}" class="btn btn-info btn-sm" target="_blank">
                                                <i class="fas fa-book me-1"></i> View Course Content
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Existing Answers Section -->
                                @if($question->answers && $question->answers->count() > 0)
                                <div class="answers-section mt-4">
                                    <hr>
                                    <h6 class="mb-3"><i class="fas fa-comments me-1"></i> Answers ({{ $question->answers->count() }})</h6>

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

                                        <!-- Answer Attachments -->
                                        @if($answer->attachments && $answer->attachments->count() > 0)
                                        <div class="answer-attachments mt-3">
                                            <div class="row">
                                                @foreach($answer->attachments as $attachment)
                                                    @if($attachment->file_type == 'image')
                                                        <div class="col-md-3 mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                <img src="{{ Storage::url($attachment->file_path) }}" alt="Answer Attachment" class="img-thumbnail question-attachment-100">
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'pdf')
                                                        <div class="col-md-3 mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-file-pdf me-1"></i> PDF Document
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'voice')
                                                        <div class="col-md-6 mb-2">
                                                            <audio controls class="w-100 audio-controls-300">
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
                                    @endforeach
                                </div>
                                @endif

                                <!-- Answer Button for Modal -->
                                @if($question->status == 'pending')
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#answerModal{{ $question->id }}">
                                            <i class="fas fa-reply me-1"></i> Answer Question
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Answer Modals -->
                @foreach($pendingQuestions as $question)
                    @if($question->status == 'pending')
                        <!-- Answer Modal -->
                        <div class="modal fade" id="answerModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Answer Question from {{ $question->user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Question Content -->
                                        <div class="question-content mb-4">
                                            <h6 class="text-primary">Question:</h6>
                                            <p class="border-start border-primary border-3 ps-3">{{ $question->content }}</p>

                                            <!-- Question Attachments -->
                                            @if($question->attachments && $question->attachments->count() > 0)
                                            <div class="question-attachments mt-3">
                                                <h6 class="text-muted">Attachments:</h6>
                                                <div class="row">
                                                    @foreach($question->attachments as $attachment)
                                                        @if($attachment->file_type == 'image')
                                                            <div class="col-md-3 mb-2">
                                                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                    <img src="{{ Storage::url($attachment->file_path) }}" alt="Question Attachment" class="img-thumbnail question-attachment-100">
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

                                        <!-- Simple Answer Form -->
                                        @livewire('admin.simple-answer-form', ['questionId' => $question->id], key('simple-answer-form-'.$question->id))
                                    </div>
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
                    @endif
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingQuestions->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="mb-0">Great! There are no pending questions at the moment.</p>
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
<script src="{{ asset('js/admin-questions.min.js') }}"></script>
@endpush
