<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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
                        <li class="breadcrumb-item active">Quiz: {{ $quiz->title }}</li>
                    </ol>
                </nav>

                <!-- Quiz Start Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="text-center">
                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <h2 class="mb-0">{{ $quiz->title }}</h2>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        @if($quiz->description)
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Instructions:</strong> {{ $quiz->description }}
                            </div>
                        @endif

                        <!-- Quiz Information -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-question-circle fa-2x text-primary mb-3"></i>
                                        <h5 class="card-title">Total Questions</h5>
                                        <h2 class="text-primary mb-0">{{ $quiz->questions->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-star fa-2x text-warning mb-3"></i>
                                        <h5 class="card-title">Marks Per Question</h5>
                                        <h2 class="text-warning mb-0">{{ $quiz->questions->first()->points ?? 20 }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-trophy fa-2x text-success mb-3"></i>
                                        <h5 class="card-title">Passing Percentage</h5>
                                        <h2 class="text-success mb-0">{{ $quiz->passing_score }}%</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-clock fa-2x text-danger mb-3"></i>
                                        <h5 class="card-title">Time Per Question</h5>
                                        <h2 class="text-danger mb-0">60 <small>seconds</small></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Important Notes -->
                        <div class="alert alert-warning mb-4">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes:</h5>
                            <ul class="mb-0">
                                <li>Each question has a <strong>60-second timer</strong></li>
                                <li>If time runs out, the question will be <strong>automatically submitted as incorrect</strong></li>
                                <li>You cannot go back to previous questions</li>
                                <li>You must answer all questions to complete the quiz</li>
                                <li>Your final score will be shown at the end</li>
                            </ul>
                        </div>

                        <!-- Total Points and Passing Score -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-calculator fa-2x text-info me-3"></i>
                                    <div>
                                        <small class="text-muted">Total Points</small>
                                        <h4 class="mb-0">{{ $quiz->total_points }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                    <div>
                                        <small class="text-muted">Required to Pass</small>
                                        <h4 class="mb-0">{{ ($quiz->total_points * $quiz->passing_score) / 100 }} points</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Start Button -->
                        <div class="text-center">
                            <form action="{{ route('quiz.begin', ['course' => $course ? $course->id : 0, 'lecture' => $lecture->id, 'quiz' => $quiz->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                    <i class="fas fa-play-circle me-2"></i>Start Quiz
                                </button>
                            </form>
                            @if($course)
                                <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) }}" class="btn btn-outline-secondary btn-lg px-5 py-3 mt-3">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Lecture
                                </a>
                            @else
                                <a href="{{ route('user.lecture.standalone', ['lecture' => $lecture->id]) }}" class="btn btn-outline-secondary btn-lg px-5 py-3 mt-3">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Lecture
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card {
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
    @endpush
</x-app-layout>

