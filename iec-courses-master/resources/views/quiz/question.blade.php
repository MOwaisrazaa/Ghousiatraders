<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Progress Bar -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Question {{ $currentQuestionIndex + 1 }}</strong> of {{ $totalQuestions }}</span>
                            <span class="text-muted">{{ round((($currentQuestionIndex + 1) / $totalQuestions) * 100) }}% Complete</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}%"
                                aria-valuenow="{{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}" 
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timer Card -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header text-white py-3" id="timer-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Time Remaining
                            </h5>
                            <h2 class="mb-0" id="timer-display">60</h2>
                        </div>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-light py-3">
                        <h4 class="mb-0">
                            Question {{ $currentQuestionIndex + 1 }}
                            <span class="badge bg-primary float-end">{{ $question->points }} points</span>
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="mb-4">{{ $question->question_text }}</h5>

                        <form id="answer-form" action="{{ route('quiz.submit-question', ['course' => $course ? $course->id : 0, 'lecture' => $lecture->id, 'quiz' => $quiz->id, 'attempt' => $attempt->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                            <input type="hidden" name="time_expired" id="time-expired" value="0">

                            @if($question->question_type === 'multiple_choice')
                                <div class="options-container">
                                    @foreach($question->options as $index => $option)
                                        <div class="form-check option-card p-3 mb-3 border rounded">
                                            <input class="form-check-input" type="radio" name="option_id" 
                                                id="option{{ $option->id }}" value="{{ $option->id }}" required>
                                            <label class="form-check-label w-100" for="option{{ $option->id }}">
                                                <strong>{{ chr(65 + $index) }}.</strong> {{ $option->option_text }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mb-3">
                                    <label for="answer_text" class="form-label">Your Answer:</label>
                                    <textarea class="form-control" id="answer_text" name="answer_text" rows="5" required></textarea>
                                </div>
                            @endif

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="submit-btn">
                                    <i class="fas fa-check-circle me-2"></i>Submit Answer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .option-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .option-card:hover {
            background-color: #f8f9fa;
            border-color: #667eea !important;
        }
        
        .form-check-input:checked + .form-check-label {
            color: #667eea;
            font-weight: bold;
        }
        
        .option-card:has(.form-check-input:checked) {
            background-color: #e7f3ff;
            border-color: #667eea !important;
        }
        
        #timer-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .timer-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            animation: pulse 1s infinite;
        }
        
        .timer-danger {
            background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%) !important;
            animation: pulse 0.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        let timeRemaining = 60;
        let timerInterval;
        let formSubmitted = false;

        function startTimer() {
            const timerDisplay = document.getElementById('timer-display');
            const timerHeader = document.getElementById('timer-header');
            const submitBtn = document.getElementById('submit-btn');
            
            timerInterval = setInterval(function() {
                timeRemaining--;
                timerDisplay.textContent = timeRemaining;
                
                // Change color based on time remaining
                if (timeRemaining <= 10) {
                    timerHeader.classList.add('timer-danger');
                    timerHeader.classList.remove('timer-warning');
                } else if (timeRemaining <= 30) {
                    timerHeader.classList.add('timer-warning');
                }
                
                // Auto-submit when time runs out
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    if (!formSubmitted) {
                        document.getElementById('time-expired').value = '1';
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Time Expired - Auto Submitting...';
                        document.getElementById('answer-form').submit();
                        formSubmitted = true;
                    }
                }
            }, 1000);
        }

        // Prevent double submission
        document.getElementById('answer-form').addEventListener('submit', function(e) {
            if (formSubmitted) {
                e.preventDefault();
                return false;
            }
            
            formSubmitted = true;
            clearInterval(timerInterval);
            
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
        });

        // Start timer when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
        });

        // Warn user before leaving page
        window.addEventListener('beforeunload', function(e) {
            if (!formSubmitted) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });
    </script>
    @endpush
</x-app-layout>

