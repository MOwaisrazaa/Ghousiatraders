<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Quiz Attempt Details</h1>
                <p class="mb-0">
                    <span class="text-muted">Student:</span> {{ $attempt->user->name }} | 
                    <span class="text-muted">Quiz:</span> {{ $attempt->quiz->title }}
                </p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.quizzes.attempts', $attempt->quiz) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Attempts
                    </a>
                    <a href="{{ route('admin.quizzes.show', $attempt->quiz) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i> View Quiz
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $attempt->passed ? 'success' : 'danger' }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $attempt->passed ? 'success' : 'danger' }} text-uppercase mb-1">
                                    Result</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-{{ $attempt->passed ? 'check' : 'times' }}-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Score</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $attempt->score }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percentage fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Time Spent</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @if($attempt->time_spent)
                                        {{ floor($attempt->time_spent / 60) }}m {{ $attempt->time_spent % 60 }}s
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Attempt Date</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $attempt->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Student Answers</h6>
            </div>
            <div class="card-body">
                @if($attempt->answers->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p class="mb-0">No answers were recorded for this attempt.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="45%">Question</th>
                                    <th width="35%">Answer</th>
                                    <th width="15%">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempt->answers as $answer)
                                    <tr>
                                        <td>{{ $answer->question->order }}</td>
                                        <td>
                                            <strong>{{ $answer->question->question_text }}</strong>
                                            <div class="text-muted mt-1">
                                                <small>
                                                    {{ ucfirst($answer->question->question_type) }} - 
                                                    {{ $answer->question->points }} points
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($answer->question->question_type == 'multiple_choice')
                                                <ul class="list-group mb-0">
                                                    @foreach($answer->question->options as $option)
                                                        <li class="list-group-item px-3 py-2 {{ $option->id == $answer->selected_option_id ? 'active' : '' }} {{ $option->is_correct ? 'list-group-item-success' : ($option->id == $answer->selected_option_id && !$option->is_correct ? 'list-group-item-danger' : '') }}">
                                                            {{ $option->option_text }}
                                                            @if($option->id == $answer->selected_option_id)
                                                                <i class="fas fa-user-check float-end"></i>
                                                            @endif
                                                            @if($option->is_correct)
                                                                <i class="fas fa-check float-end"></i>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div class="border p-3 rounded {{ $answer->is_correct ? 'border-success' : 'border-danger' }}">
                                                    {{ $answer->answer_text ?: 'No answer provided' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($answer->is_correct)
                                                <span class="badge bg-success p-2"><i class="fas fa-check me-1"></i> Correct</span>
                                            @elseif($answer->is_correct === false)
                                                <span class="badge bg-danger p-2"><i class="fas fa-times me-1"></i> Incorrect</span>
                                            @else
                                                <span class="badge bg-warning p-2"><i class="fas fa-clock me-1"></i> Pending Review</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        @if($attempt->answers->where('question.question_type', 'open_ended')->where('is_correct', null)->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Answers Pending Review</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quiz-attempts.grade', $attempt) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @foreach($attempt->answers->where('question.question_type', 'open_ended')->where('is_correct', null) as $answer)
                            <div class="mb-4 pb-4 border-bottom">
                                <h5>Question {{ $answer->question->order }}: {{ $answer->question->question_text }}</h5>
                                <div class="mb-3">
                                    <strong>Student's Answer:</strong>
                                    <div class="border p-3 rounded bg-light">
                                        {{ $answer->answer_text ?: 'No answer provided' }}
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Grade this Answer:</label>
                                    <div class="d-flex">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="grades[{{ $answer->id }}]" id="correct_{{ $answer->id }}" value="1">
                                            <label class="form-check-label text-success" for="correct_{{ $answer->id }}">
                                                <i class="fas fa-check me-1"></i> Correct
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="grades[{{ $answer->id }}]" id="incorrect_{{ $answer->id }}" value="0">
                                            <label class="form-check-label text-danger" for="incorrect_{{ $answer->id }}">
                                                <i class="fas fa-times me-1"></i> Incorrect
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="feedback_{{ $answer->id }}" class="form-label">Feedback (Optional):</label>
                                    <textarea class="form-control" id="feedback_{{ $answer->id }}" name="feedback[{{ $answer->id }}]" rows="2"></textarea>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Submit Grades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout> 