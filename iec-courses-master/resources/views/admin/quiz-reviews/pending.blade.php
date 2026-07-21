@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        @if(Auth::user()->isSuperAdmin())
            <h1 class="h3 mb-0 text-gray-800">Pending Quiz Reviews</h1>
        @else
            <h1 class="h3 mb-0 text-gray-800">Pending Quiz Reviews from My Assigned Users</h1>
        @endif

        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-fw"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-list fa-fw"></i> All Quizzes
            </a>
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

    @if($pendingAnswers->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Text Answers Pending Review ({{ $pendingAnswers->total() }})
                </h6>
                <button type="button" class="btn btn-success btn-sm" id="submitAllReviewsBtn">
                    <i class="fas fa-check-double me-1"></i> Submit All Reviews
                </button>
            </div>
            <div class="card-body">
                <form id="reviewForm" action="{{ route('admin.quiz-reviews.review') }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Quiz</th>
                                    <th>Question</th>
                                    <th>Student Answer</th>
                                    <th>Points</th>
                                    <th>Grade</th>
                                    <th>Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAnswers as $index => $answer)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-3">
                                                    <p class="fw-bold mb-1">{{ $answer->attempt->user->name }}</p>
                                                    <p class="text-muted mb-0">{{ $answer->attempt->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $answer->question->quiz->lecture->course->title }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $answer->question->quiz->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $answer->question->quiz->lecture->title }}</small>
                                        </td>
                                        <td>
                                            <div class="question-text">
                                                {{ Str::limit($answer->question->question_text, 100) }}
                                                @if(strlen($answer->question->question_text) > 100)
                                                    <button type="button" class="btn btn-link btn-sm p-0" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#questionModal{{ $answer->id }}">
                                                        Read more...
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="answer-text">
                                                {{ Str::limit($answer->answer_text, 150) }}
                                                @if(strlen($answer->answer_text) > 150)
                                                    <button type="button" class="btn btn-link btn-sm p-0" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#answerModal{{ $answer->id }}">
                                                        Read more...
                                                    </button>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                Submitted: {{ $answer->created_at->format('M d, Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $answer->question->points }} pts</span>
                                        </td>
                                        <td>
                                            <input type="hidden" name="reviews[{{ $index }}][answer_id]" value="{{ $answer->id }}">
                                            
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="reviews[{{ $index }}][is_correct]" 
                                                       id="correct{{ $answer->id }}" value="1" required>
                                                <label class="btn btn-outline-success btn-sm" for="correct{{ $answer->id }}">
                                                    <i class="fas fa-check"></i> Correct
                                                </label>

                                                <input type="radio" class="btn-check" name="reviews[{{ $index }}][is_correct]" 
                                                       id="incorrect{{ $answer->id }}" value="0" required>
                                                <label class="btn btn-outline-danger btn-sm" for="incorrect{{ $answer->id }}">
                                                    <i class="fas fa-times"></i> Incorrect
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm" 
                                                      name="reviews[{{ $index }}][feedback]" 
                                                      rows="2" 
                                                      placeholder="Optional feedback for student..."></textarea>
                                        </td>
                                    </tr>

                                    <!-- Question Modal -->
                                    @if(strlen($answer->question->question_text) > 100)
                                        <div class="modal fade" id="questionModal{{ $answer->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Full Question</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $answer->question->question_text }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Answer Modal -->
                                    @if(strlen($answer->answer_text) > 150)
                                        <div class="modal fade" id="answerModal{{ $answer->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Full Answer</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $answer->answer_text }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $pendingAnswers->links() }}
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Pending Reviews</h5>
                <p class="text-muted">All text-based quiz answers have been reviewed!</p>
                <a href="{{ route('admin.quizzes.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-1"></i> View All Quizzes
                </a>
            </div>
        </div>
    @endif
</div>

<script >
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitAllReviewsBtn');
    
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            // Check if all answers have been graded
            const radioGroups = document.querySelectorAll('input[type="radio"][name*="[is_correct]"]');
            const groupNames = new Set();
            
            radioGroups.forEach(radio => {
                groupNames.add(radio.name);
            });
            
            let allGraded = true;
            groupNames.forEach(groupName => {
                const checkedRadio = document.querySelector(`input[name="${groupName}"]:checked`);
                if (!checkedRadio) {
                    allGraded = false;
                }
            });
            
            if (!allGraded) {
                alert('Please grade all answers before submitting.');
                return;
            }
            
            if (confirm('Are you sure you want to submit all reviews? This action cannot be undone.')) {
                document.getElementById('reviewForm').submit();
            }
        });
    }
});
</script>
@endsection
