<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Quiz Details</h1>
                <p class="mb-0">Viewing quiz: {{ $quiz->title }}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Quiz
                    </a>
                    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Quizzes
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Attempts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $quiz->attempts->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pass Rate</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $totalAttempts = $quiz->attempts->count();
                                        $passedAttempts = $quiz->attempts->where('passed', true)->count();
                                        $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100) : 0;
                                    @endphp
                                    {{ $passRate }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percentage fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Average Score
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $scores = $quiz->attempts->pluck('score')->toArray();
                                        $avgScore = !empty($scores) ? round(array_sum($scores) / count($scores)) : 0;
                                    @endphp
                                    {{ $avgScore }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quiz Information</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Title</th>
                            <td>{{ $quiz->title }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $quiz->description ?: 'No description provided' }}</td>
                        </tr>
                        <tr>
                            <th>Lecture</th>
                            <td>
                                {{ $quiz->lecture->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td>
                                {{ $quiz->lecture->course->name ?? 'Standalone' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Required</th>
                            <td>
                                @if($quiz->is_required)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Time Limit</th>
                            <td>
                                @if($quiz->time_limit)
                                    {{ $quiz->time_limit }} minutes
                                @else
                                    No time limit
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Passing Score</th>
                            <td>{{ $quiz->passing_score }}%</td>
                        </tr>
                        <tr>
                            <th>Total Points</th>
                            <td>{{ $quiz->total_points }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $quiz->created_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $quiz->updated_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Quiz Questions</h6>
                <a href="{{ route('admin.quiz-questions.create', $quiz) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Question
                </a>
            </div>
            <div class="card-body">
                @if($quiz->questions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No questions found for this quiz yet.</p>
                    </div>
                @else
                    <div class="accordion" id="questionsAccordion">
                        @foreach($quiz->questions->sortBy('order') as $index => $question)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $question->id }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $question->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $question->id }}">
                                        <div class="d-flex justify-content-between w-100 me-3">
                                            <span>Q{{ $question->order }}: {{ Str::limit($question->question_text, 100) }}</span>
                                            <span>
                                                @if($question->question_type == 'multiple_choice')
                                                    <span class="badge bg-info">Multiple Choice</span>
                                                @else
                                                    <span class="badge bg-warning">Open Ended</span>
                                                @endif
                                                <span class="badge bg-secondary ms-1">{{ $question->points }} pts</span>
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $question->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $question->id }}" data-bs-parent="#questionsAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <strong>Question:</strong>
                                            <p>{{ $question->question_text }}</p>
                                        </div>
                                        
                                        @if($question->question_type == 'multiple_choice')
                                            <div class="mb-3">
                                                <strong>Answer Options:</strong>
                                                <ul class="list-group">
                                                    @foreach($question->options as $option)
                                                        <li class="list-group-item {{ $option->is_correct ? 'list-group-item-success' : '' }}">
                                                            {{ $option->option_text }}
                                                            @if($option->is_correct)
                                                                <span class="badge bg-success float-end">Correct Answer</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3 text-end">
                                            <a href="{{ route('admin.quiz-questions.edit', $question) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit me-1"></i> Edit Question
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Attempts</h6>
            </div>
            <div class="card-body">
                @if($quiz->attempts->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No attempts have been made for this quiz yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Result</th>
                                    <th>Time Spent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quiz->attempts->sortByDesc('created_at')->take(10) as $attempt)
                                    <tr>
                                        <td>
                                            {{ $attempt->user->name }}
                                        </td>
                                        <td>{{ $attempt->created_at->format('M d, Y h:i A') }}</td>
                                        <td>{{ $attempt->score }}%</td>
                                        <td>
                                            @if($attempt->passed)
                                                <span class="badge bg-success">Passed</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt->time_spent)
                                                {{ floor($attempt->time_spent / 60) }}m {{ $attempt->time_spent % 60 }}s
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.quiz-attempts.show', $attempt) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.quizzes.attempts', $quiz) }}" class="btn btn-outline-primary">
                            View All Attempts
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 