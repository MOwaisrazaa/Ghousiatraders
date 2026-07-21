@section('styles')
    <link rel="stylesheet" href="{{ asset('css/quiz-components.css') }}">
@endsection

<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">My Courses</a></li>
                        @if($course)
                            <li class="breadcrumb-item"><a href="{{ route('user.course.purchased', $course->id) }}">{{ $course->name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) }}">{{ $lecture->name }}</a></li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ route('user.lecture.standalone', ['lecture' => $lecture->id]) }}">{{ $lecture->name }}</a></li>
                        @endif
                        <li class="breadcrumb-item active">Quiz Results: {{ $quiz->title }}</li>
                    </ol>
                </nav>

                <!-- Quiz Results Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header {{ $attempt->isPassed() ? 'bg-success' : ($attempt->isPendingReview() ? 'bg-warning' : 'bg-danger') }} text-white py-3">
                        <h4 class="mb-0">
                            {{ $quiz->title }} - Result:
                            @if($attempt->isPassed())
                                PASSED
                            @elseif($attempt->isPendingReview())
                                PENDING ADMIN REVIEW
                            @else
                                FAILED
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Completion Date:</strong> {{ $attempt->completed_at->format('F j, Y, g:i a') }}
                                </div>
                                <div class="mb-2">
                                    <strong>Score:</strong> {{ $attempt->score }} / {{ $attempt->total_points }} points
                                </div>
                                <div class="mb-2">
                                    <strong>Percentage:</strong> {{ $attempt->percentage_score }}%
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Required to Pass:</strong> {{ $quiz->passing_score }}%
                                </div>
                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    @if($attempt->isPassed())
                                        <span class="badge bg-success">Passed</span>
                                    @elseif($attempt->isPendingReview())
                                        <span class="badge bg-warning">Pending Admin Review</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <strong>Time Taken:</strong>
                                    @php
                                        $startedAt = new \Carbon\Carbon($attempt->started_at);
                                        $completedAt = new \Carbon\Carbon($attempt->completed_at);
                                        $timeTaken = $startedAt->diff($completedAt);
                                        $formattedTime = $timeTaken->format('%H:%I:%S');
                                    @endphp
                                    {{ $formattedTime }}
                                </div>
                            </div>
                        </div>

                        <!-- Score Visualization -->
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-3">
                                <div class="score-chart mb-3">
                                    <div class="progress progress-large">
                                        <div class="progress-bar {{ $attempt->isPassed() ? 'bg-success' : ($attempt->isPendingReview() ? 'bg-warning' : 'bg-danger') }} dynamic-progress-bar"
                                            role="progressbar"
                                            data-width="{{ $attempt->percentage_score }}"
                                            aria-valuenow="{{ $attempt->percentage_score }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $attempt->percentage_score }}%
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small>0%</small>
                                        <small>Passing: {{ $quiz->passing_score }}%</small>
                                        <small>100%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($attempt->isPendingReview())
                <!-- Pending Review Notice -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-2">Quiz Under Review</h5>
                                    <p class="mb-2">Your quiz contains text-based answers that require manual review by an instructor. Your final score and pass/fail status will be updated once all answers have been reviewed.</p>
                                    <p class="mb-0"><strong>Current Score:</strong> {{ $attempt->score }} / {{ $attempt->total_points }} points ({{ $attempt->percentage_score }}%) - This may change after review.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Answers Review -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Review Your Answers</h5>
                    </div>
                    <div class="card-body">
                        <div class="answers-review">
                            @foreach($attempt->answers as $index => $answer)
                                <div class="answer-review mb-4 pb-3 {{ $index < ($attempt->answers->count() - 1) ? 'border-bottom' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6>Question {{ $index + 1 }}</h6>
                                        <div>
                                            @if($answer->is_correct === null && $answer->isPendingReview())
                                                <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Pending Review</span>
                                            @elseif($answer->is_correct)
                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> Correct</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Incorrect</span>
                                            @endif
                                            <span class="ms-2 badge bg-secondary">{{ $answer->points_earned }} / {{ $answer->question->points }} points</span>
                                        </div>
                                    </div>

                                    <div class="question-text mb-3">
                                        <p class="fw-bold">{{ $answer->question->question_text }}</p>
                                    </div>

                                    @if($answer->question->isMultipleChoice())
                                        <!-- Multiple Choice Review -->
                                        <div class="multiple-choice-review">
                                            <div class="mb-2">
                                                <strong>Your Answer:</strong>
                                                @if($answer->selectedOption)
                                                    <div class="mt-1 ps-3 {{ $answer->is_correct ? 'text-success' : 'text-danger' }}">
                                                        {{ $answer->selectedOption->option_text }}
                                                        @if($answer->is_correct)
                                                            <i class="fas fa-check-circle ms-1"></i>
                                                        @else
                                                            <i class="fas fa-times-circle ms-1"></i>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="mt-1 ps-3 text-danger">
                                                        <em>No answer provided</em>
                                                    </div>
                                                @endif
                                            </div>

                                            @if(!$answer->is_correct)
                                                <div class="mb-2">
                                                    <strong>Correct Answer:</strong>
                                                    <div class="mt-1 ps-3 text-success">
                                                        {{ $answer->question->getCorrectOption()->option_text }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Open Ended Review -->
                                        <div class="open-ended-review">
                                            <div class="mb-2">
                                                <strong>Your Answer:</strong>
                                                <div class="mt-1 ps-3 {{ $answer->is_correct ? 'text-success' : ($answer->isPendingReview() ? 'text-warning' : 'text-muted') }}">
                                                    {{ $answer->answer_text ?? 'No answer provided' }}
                                                </div>
                                            </div>

                                            @if($answer->isPendingReview())
                                                <div class="alert alert-warning mt-2">
                                                    <i class="fas fa-clock me-1"></i>
                                                    This answer is pending review by the instructor. You will be notified once it has been graded.
                                                </div>
                                            @elseif($answer->question->isOpenEnded() && !$answer->is_correct && !$answer->isPendingReview())
                                                <div class="alert alert-danger mt-2">
                                                    <i class="fas fa-times-circle me-1"></i>
                                                    This answer has been reviewed and marked as incorrect.
                                                    @if($answer->feedback)
                                                        <br><strong>Feedback:</strong> {{ $answer->feedback }}
                                                    @endif
                                                </div>
                                            @elseif($answer->question->isOpenEnded() && $answer->is_correct)
                                                <div class="alert alert-success mt-2">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    This answer has been reviewed and marked as correct.
                                                    @if($answer->feedback)
                                                        <br><strong>Feedback:</strong> {{ $answer->feedback }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        @if($course)
                            <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Lecture
                            </a>
                        @else
                            <a href="{{ route('user.lecture.standalone', ['lecture' => $lecture->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Lecture
                            </a>
                        @endif

                        <div class="d-flex gap-2">
                            @if($attempt->isPassed())
                                @php
                                    $user = Auth::user();

                                    // Check if user purchased full course (course_id set, lecture_id null)
                                    $fullCourseAccess = false;
                                    if ($course) {
                                        $fullCourseAccess = \App\Models\UserCourse::where('user_id', $user->id)
                                            ->where('course_id', $course->id)
                                            ->whereNull('lecture_id')
                                            ->where('status', 'active')
                                            ->exists();
                                    }

                                    // Check if user purchased individual lecture (lecture_id set)
                                    $lecturePurchaseQuery = \App\Models\UserCourse::where('user_id', $user->id)
                                        ->where('lecture_id', $lecture->id)
                                        ->where('status', 'active');
                                    
                                    if ($course) {
                                        $lecturePurchaseQuery->where('course_id', $course->id);
                                    } else {
                                        $lecturePurchaseQuery->whereNull('course_id');
                                    }
                                    
                                    $lecturePurchase = $lecturePurchaseQuery->exists();

                                    $hasLectureCertificateQuery = $user->certificates()->where('lecture_id', $lecture->id);
                                    if ($course) {
                                        $hasLectureCertificateQuery->where('course_id', $course->id);
                                    } else {
                                        // For standalone or null course_id
                                        $hasLectureCertificateQuery->whereNull('course_id');
                                    }
                                    $hasLectureCertificate = $hasLectureCertificateQuery->exists();
                                    
                                    $hasPendingLectureRequest = $user->hasPendingCertificateRequest($course ? $course->id : null, $lecture->id);
                                @endphp

                                @if($lecturePurchase || !$course) {{-- Treat standalone as "lecture purchase" basically --}}
                                    {{-- User purchased individual lecture - show lecture certificate option --}}
                                    @if($hasLectureCertificate)
                                        <a href="{{ route('user.certificates') }}" class="btn btn-success">
                                            <i class="fas fa-certificate me-1"></i> View Lecture Certificate
                                        </a>
                                    @elseif($hasPendingLectureRequest)
                                        <button type="button" class="btn btn-warning" disabled>
                                            <i class="fas fa-clock me-1"></i> Certificate Request Pending
                                        </button>
                                    @else
                                        <form action="{{ route('user.request-lecture-certificate', $lecture->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-certificate me-1"></i> Request Lecture Certificate
                                            </button>
                                        </form>
                                    @endif
                                @elseif($fullCourseAccess)
                                    {{-- User purchased full course - direct them to dashboard for course certificate --}}
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-info">
                                        <i class="fas fa-certificate me-1"></i> Request Course Certificate from Dashboard
                                    </a>
                                @endif
                            @elseif($attempt->isPendingReview())
                                {{-- Quiz is pending review - don't allow retake until reviewed --}}
                                <button type="button" class="btn btn-warning" disabled>
                                    <i class="fas fa-clock me-1"></i> Waiting for Review - Cannot Retake Yet
                                </button>
                            @else
                                {{-- Quiz failed - allow retake --}}
                                <a href="{{ route('quiz.show', ['course' => $course ? $course->id : 0, 'lecture' => $lecture->id, 'quiz' => $quiz->id]) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-redo me-1"></i> Try Again
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
