<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-info p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="text-white mb-0">My Quiz Results</h3>
                                <p class="text-white opacity-8 mb-0">Track your quiz performance and progress</p>
                            </div>
                            <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                                <a href="{{ route('user.dashboard') }}" class="btn btn-white btn-sm ms-auto">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Attempts</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $quizStats['total'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle p-3">
                                    <i class="fas fa-chart-bar text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Passed</p>
                                    <h5 class="font-weight-bolder mb-0 text-success">{{ $quizStats['passed'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle p-3">
                                    <i class="fas fa-check-circle text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Failed</p>
                                    <h5 class="font-weight-bolder mb-0 text-danger">{{ $quizStats['failed'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle p-3">
                                    <i class="fas fa-times-circle text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending Review</p>
                                    <h5 class="font-weight-bolder mb-0 text-warning">{{ $quizStats['pending'] }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle p-3">
                                    <i class="fas fa-clock text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0"><i class="fas fa-list me-2 text-info"></i>All Quiz Attempts</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($quizAttempts->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quiz</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Course</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Score</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quizAttempts as $attempt)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $attempt->quiz->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $attempt->quiz->lecture->lecture_title }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        @if($attempt->quiz->lecture->course)
                                                            {{ $attempt->quiz->lecture->course->name }}
                                                        @else
                                                            <span class="badge bg-info">Standalone</span>
                                                        @endif
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $attempt->score ?? 0 }}/{{ $attempt->total_points ?? 0 }}
                                                        <span class="text-secondary">({{ $attempt->percentage_score ?? 0 }}%)</span>
                                                    </p>
                                                </td>
                                                <td>
                                                    @if($attempt->isPassed())
                                                        <span class="badge bg-success">Passed</span>
                                                    @elseif($attempt->isFailed())
                                                        <span class="badge bg-danger">Failed</span>
                                                    @elseif($attempt->isPendingReview())
                                                        <span class="badge bg-warning">Pending Review</span>
                                                    @else
                                                        <span class="badge bg-secondary">In Progress</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : 'In Progress' }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    @if($attempt->isCompleted())
                                                        <a href="{{ route('quiz.result', [
                                                            'course' => $attempt->quiz->lecture->course_id ?? 0,
                                                            'lecture' => $attempt->quiz->lecture->id,
                                                            'quiz' => $attempt->quiz->id,
                                                            'attempt' => $attempt->id
                                                        ]) }}" class="btn btn-link text-primary mb-0">
                                                            <i class="fas fa-eye me-1"></i> View Result
                                                        </a>
                                                        
                                                        @if($attempt->isPassed())
                                                                $user = Auth::user();
                                                                $lecture = $attempt->quiz->lecture;
                                                                $courseId = $lecture->course_id;
                                                                $query = $user->certificates()->where('lecture_id', $lecture->id);
                                                                
                                                                if ($courseId) {
                                                                    $query->where('course_id', $courseId);
                                                                } else {
                                                                    $query->whereNull('course_id');
                                                                }
                                                                
                                                                $hasLectureCertificate = $query->exists();
                                                            @endphp
                                                            
                                                            @if($hasLectureCertificate)
                                                                <a href="{{ route('user.certificates') }}" class="btn btn-link text-success mb-0">
                                                                    <i class="fas fa-certificate me-1"></i> Certificate
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <a href="{{ route('quiz.show', [
                                                            'course' => $attempt->quiz->lecture->course_id ?? 0,
                                                            'lecture' => $attempt->quiz->lecture->id,
                                                            'quiz' => $attempt->quiz->id
                                                        ]) }}" class="btn btn-link text-warning mb-0">
                                                            <i class="fas fa-play me-1"></i> Continue
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($quizAttempts->hasPages())
                                <div class="card-footer px-3 py-4">
                                    {{ $quizAttempts->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-chart-bar fa-4x text-muted opacity-5"></i>
                                </div>
                                <h4 class="font-weight-normal">No quiz attempts yet</h4>
                                <p class="text-muted mb-4">You haven't taken any quizzes yet. Start learning to unlock quizzes!</p>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Go to My Courses</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-app.footer />

    <style>
        .bg-gradient-info {
            background-image: linear-gradient(310deg, #17a2b8 0%, #6f42c1 100%);
        }
        .bg-gradient-success {
            background-image: linear-gradient(310deg, #2DCE89 0%, #2DCEFF 100%);
        }
        .bg-gradient-danger {
            background-image: linear-gradient(310deg, #dc3545 0%, #fd7e14 100%);
        }
        .bg-gradient-warning {
            background-image: linear-gradient(310deg, #F53939 0%, #FBCF33 100%);
        }
        .icon-shape {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</x-app-layout>
