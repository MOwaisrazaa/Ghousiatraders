@section('styles')
    <link rel="stylesheet" href="{{ asset('css/quiz-components.css') }}">
@endsection

<x-app-layout>
    <!-- Loading Overlay -->
    <div id="quiz-loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center;">
        <div style="text-align: center; color: white;">
            <div class="spinner-border" role="status" style="width: 3rem; height: 3rem; margin-bottom: 1rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h4>Submitting your quiz...</h4>
            <p>Please wait while we calculate your results.</p>
        </div>
    </div>

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
                        <li class="breadcrumb-item active">Quiz: {{ $quiz->title }}</li>
                    </ol>
                </nav>

                <!-- Quiz Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $quiz->title }}</h4>
                            @if($quiz->time_limit)
                                <div class="quiz-timer" data-time-remaining="{{ $attempt->time_remaining ?? 0 }}">
                                    <span class="badge bg-warning text-dark p-2">
                                        <i class="fas fa-clock me-2"></i> <span id="timer-display">--:--</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="quiz-info mb-4">
                            @if($quiz->description)
                                <div class="mb-3">
                                    <h5>Instructions</h5>
                                    <p>{{ $quiz->description }}</p>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Total Points:</strong> {{ $quiz->total_points }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Passing Score:</strong> {{ $quiz->passing_score }}%
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($quiz->time_limit)
                                        <div class="mb-2">
                                            <strong>Time Limit:</strong> {{ $quiz->time_limit }} minutes
                                        </div>
                                    @endif
                                    <div class="mb-2">
                                        <strong>Questions:</strong> {{ $quiz->questions->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Progress Bar -->
                        @php
                            $totalQuestions = $quiz->questions->count();
                            $answeredCount = count($answeredQuestionIds);
                            $progressPercent = $totalQuestions > 0 ? ($answeredCount / $totalQuestions * 100) : 0;
                        @endphp
                        <div class="progress-container mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Progress</span>
                                <span id="progress-text">{{ $answeredCount }}/{{ $totalQuestions }} questions</span>
                            </div>
                            <div class="progress progress-small">
                                <div class="progress-bar bg-success dynamic-progress-bar" id="progress-bar" role="progressbar" data-width="{{ $progressPercent }}"
                                    aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions -->
                <div id="questions-container">
                    @foreach($quiz->questions as $index => $question)
                        <div class="question-card card shadow-sm mb-4" id="question-{{ $question->id }}" data-question-id="{{ $question->id }}">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Question {{ $index + 1 }} <small class="text-muted">({{ $question->points }} points)</small></h5>
                            </div>
                            <div class="card-body">
                                <div class="question-text mb-3">
                                    <p class="fw-bold">{{ $question->question_text }}</p>
                                </div>

                                <div class="question-answers">
                                    @if($question->isMultipleChoice())
                                        <!-- Multiple Choice Question -->
                                        <div class="multiple-choice-options">
                                            @foreach($question->options as $option)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="question-{{ $question->id }}"
                                                        id="option-{{ $option->id }}" value="{{ $option->id }}"
                                                        @if(in_array($question->id, $answeredQuestionIds)) disabled @endif>
                                                    <label class="form-check-label" for="option-{{ $option->id }}">
                                                        {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Open Ended Question -->
                                        <div class="open-ended-answer">
                                            <textarea class="form-control" id="answer-text-{{ $question->id }}" rows="3"
                                                placeholder="Type your answer here..." @if(in_array($question->id, $answeredQuestionIds)) disabled @endif></textarea>
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <div class="answer-status" id="answer-status-{{ $question->id }}">
                                        @if(in_array($question->id, $answeredQuestionIds))
                                            <span class="badge bg-success">Answered</span>
                                        @endif
                                    </div>

                                    @if(!in_array($question->id, $answeredQuestionIds))
                                        <form action="{{ route('quiz.submit-answer', $attempt->id) }}" method="POST" class="submit-answer-form">
                                            @csrf
                                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                                            <input type="hidden" name="question_type" value="{{ $question->question_type }}">
                                            <input type="hidden" name="option_id" id="hidden-option-{{ $question->id }}" value="">
                                            <input type="hidden" name="answer_text" id="hidden-text-{{ $question->id }}" value="">
                                            <button type="submit" class="btn btn-primary submit-answer-btn">
                                                <i class="fas fa-check me-1"></i> Submit Answer
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-primary" disabled>
                                            <i class="fas fa-check me-1"></i> Submit Answer
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Complete Quiz Button -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Lecture
                        </a>
                        <form action="{{ route('quiz.complete', $attempt->id) }}" method="POST" id="complete-form">
                            @csrf
                            <button type="submit" class="btn btn-success" id="complete-quiz">
                                <i class="fas fa-flag-checkered me-1"></i> Finish Quiz
                            </button>
                            <small class="text-muted d-none" id="submission-warning">
                                <i class="fas fa-spinner fa-spin me-1"></i> Waiting for answers to submit...
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Global variable to track submissions
        window.submittingQuestions = new Set();
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Quiz page loaded successfully');
            // Initialize timer if time limit exists
            if (document.querySelector('.quiz-timer')) {
                initializeTimer();
            }

            // Handle answer submissions with simple form validation
            document.querySelectorAll('.submit-answer-form').forEach(form => {
                let isSubmitting = false; // Prevent double submission

                form.addEventListener('submit', function(e) {
                    // Prevent double submission
                    if (isSubmitting) {
                        e.preventDefault();
                        console.log('Form already submitting, ignoring duplicate submission');
                        return false;
                    }

                    const questionId = this.querySelector('input[name="question_id"]').value;
                    const questionType = this.querySelector('input[name="question_type"]').value;

                    console.log('Form submit started for question:', questionId);

                    // Validate answer before submitting
                    if (questionType === 'multiple_choice') {
                        const selectedOption = document.querySelector(`input[name="question-${questionId}"]:checked`);
                        if (!selectedOption) {
                            e.preventDefault();
                            alert('Please select an answer before submitting.');
                            return false;
                        }
                        // Set the hidden field value
                        this.querySelector('input[name="option_id"]').value = selectedOption.value;
                        console.log('Selected option:', selectedOption.value);
                    } else {
                        const answerText = document.getElementById(`answer-text-${questionId}`).value.trim();
                        if (!answerText) {
                            e.preventDefault();
                            alert('Please enter your answer before submitting.');
                            return false;
                        }
                        // Set the hidden field value
                        this.querySelector('input[name="answer_text"]').value = answerText;
                        console.log('Answer text length:', answerText.length);
                    }

                    // Mark as submitting
                    isSubmitting = true;

                    // Show loading state
                    const submitBtn = this.querySelector('.submit-answer-btn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';

                    console.log('Form submitting to:', this.action);

                    // Form will submit normally
                    return true;
                });
            });

            // Confirm before completing quiz
            document.getElementById('complete-form').addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to finish this quiz? All unanswered questions will be marked as incorrect.')) {
                    e.preventDefault();
                    return false;
                }

                // Show loading overlay
                const overlay = document.getElementById('quiz-loading-overlay');
                if (overlay) {
                    overlay.style.display = 'flex';
                }

                // Show loading state on the submit button
                const submitButton = document.getElementById('complete-quiz');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';

                // Allow form to submit normally
                return true;
            });

            // Function to initialize timer
            function initializeTimer() {
                const timerElement = document.querySelector('.quiz-timer');
                let timeRemaining = parseInt(timerElement.dataset.timeRemaining);
                const timerDisplay = document.getElementById('timer-display');

                // Update timer every second
                const timerInterval = setInterval(function() {
                    timeRemaining--;

                    if (timeRemaining <= 0) {
                        clearInterval(timerInterval);
                        // Show loading overlay
                        const overlay = document.getElementById('quiz-loading-overlay');
                        if (overlay) {
                            overlay.style.display = 'flex';
                        }
                        document.getElementById('complete-form').submit();
                        return;
                    }

                    // Calculate minutes and seconds
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;

                    // Display timer with leading zeros if needed
                    timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                    // Add visual indication when time is running out
                    if (timeRemaining < 60) {
                        timerDisplay.parentElement.classList.remove('bg-warning');
                        timerDisplay.parentElement.classList.add('bg-danger', 'text-white');
                    }
                }, 1000);
            }

            // OLD AJAX FUNCTION - NO LONGER USED (Replaced with simple form submission)
            // Function to submit an answer with retry logic
            function submitAnswer_OLD(button, retryCount = 0) {
                const questionId = button.dataset.questionId;
                const maxRetries = 3;

                // Prevent multiple submissions using global tracker
                if (window.submittingQuestions.has(questionId)) {
                    console.log('Question', questionId, 'is already being submitted');
                    return;
                }

                const questionType = button.dataset.questionType;
                const attemptId = button.dataset.attemptId;
                let answer;

                console.log('Starting submission for question:', questionId, 'Type:', questionType, 'Retry:', retryCount);

                // Get answer based on question type
                if (questionType === 'multiple_choice') {
                    const selectedOption = document.querySelector(`input[name="question-${questionId}"]:checked`);
                    if (!selectedOption) {
                        alert('Please select an answer.');
                        return;
                    }
                    answer = selectedOption.value;
                    console.log('Selected option:', answer);
                } else {
                    const answerText = document.getElementById(`answer-text-${questionId}`).value.trim();
                    if (!answerText) {
                        alert('Please enter your answer.');
                        return;
                    }
                    answer = answerText;
                    console.log('Text answer length:', answer.length);
                }

                // Mark as submitting AFTER validation passes
                window.submittingQuestions.add(questionId);
                updateFinishButtonState();

                // Disable the button while submitting
                button.disabled = true;
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';

                // Add a visual indicator that shows progress
                let dots = 0;
                const loadingInterval = setInterval(() => {
                    dots = (dots + 1) % 4;
                    const dotString = '.'.repeat(dots);
                    button.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i> Submitting${dotString}`;
                }, 500);

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log('CSRF token found:', token ? 'Yes' : 'No');

                // Prepare request data
                const requestData = {
                    question_id: questionId,
                    question_type: questionType,
                    option_id: questionType === 'multiple_choice' ? answer : null,
                    answer_text: questionType === 'open_ended' ? answer : null
                };
                console.log('Request data:', requestData);

                // Submit answer via AJAX with timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
                
                const startTime = performance.now();
                const url = `/quiz/attempts/${attemptId}/submit-answer`;
                console.log('=== QUIZ SUBMISSION START ===');
                console.log('Sending request to:', url);
                console.log('Request data:', requestData);
                console.log('CSRF Token:', token);
                console.log('About to call fetch...');

                console.log('Fetch called!');

                const fetchPromise = fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(requestData),
                    signal: controller.signal
                });

                console.log('Fetch promise created:', fetchPromise);

                fetchPromise.then(response => {
                    console.log('=== FETCH RESPONSE RECEIVED ===');
                    clearTimeout(timeoutId);
                    clearInterval(loadingInterval);
                    const endTime = performance.now();
                    const duration = ((endTime - startTime) / 1000).toFixed(2);
                    console.log(`Response received in ${duration}s - Status:`, response.status, 'OK:', response.ok);

                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response:', text);
                            // Remove from submitting tracker on error
                            window.submittingQuestions.delete(questionId);
                            throw new Error(`HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success! Data received:', data);

                    // Remove from submitting tracker on success
                    window.submittingQuestions.delete(questionId);
                    updateFinishButtonState();
                    
                    if (data.success) {
                        // Update question status based on answer type
                        const statusElement = document.getElementById(`answer-status-${questionId}`);

                        if (data.is_correct === null) {
                            // Pending review for text answers
                            statusElement.innerHTML = '<span class="badge bg-warning">Pending Review</span>';
                        } else {
                            // Auto-graded answers
                            statusElement.innerHTML = '<span class="badge bg-success">Answered</span>';
                        }

                        // Disable the question inputs
                        if (questionType === 'multiple_choice') {
                            document.querySelectorAll(`input[name="question-${questionId}"]`).forEach(input => {
                                input.disabled = true;
                            });
                        } else {
                            document.getElementById(`answer-text-${questionId}`).disabled = true;
                        }

                        // Update progress bar
                        updateProgressBar();

                        // Keep button disabled and show submitted state
                        button.innerHTML = '<i class="fas fa-check-circle me-1"></i> Submitted';
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-success');

                        console.log('Answer submitted successfully!');

                        // If all questions are answered, suggest completing the quiz
                        if (data.is_complete) {
                            setTimeout(() => {
                                if (confirm('All questions have been answered. Would you like to finish the quiz now?')) {
                                    // Show loading overlay
                                    const overlay = document.getElementById('quiz-loading-overlay');
                                    if (overlay) {
                                        overlay.style.display = 'flex';
                                    }
                                    document.getElementById('complete-form').submit();
                                }
                            }, 500);
                        }
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.log('=== FETCH ERROR CAUGHT ===');
                    clearTimeout(timeoutId);
                    clearInterval(loadingInterval);
                    console.error('Submission error:', error);
                    console.error('Error name:', error.name);
                    console.error('Error message:', error.message);
                    console.error('Error stack:', error.stack);

                    // Remove from submitting tracker
                    window.submittingQuestions.delete(questionId);
                    updateFinishButtonState();
                    
                    let errorMessage = 'An error occurred while submitting your answer.';
                    let shouldRetry = false;
                    
                    if (error.name === 'AbortError') {
                        errorMessage = 'Request timed out.';
                        shouldRetry = true;
                    } else if (error.message && error.message.includes('500')) {
                        errorMessage = 'Server error occurred.';
                        shouldRetry = true;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    // Retry logic
                    if (shouldRetry && retryCount < maxRetries) {
                        console.log('Retrying submission...', retryCount + 1);
                        button.innerHTML = `<i class="fas fa-sync fa-spin me-1"></i> Retrying... (${retryCount + 1}/${maxRetries})`;
                        setTimeout(() => {
                            submitAnswer(button, retryCount + 1);
                        }, 1000);
                        return;
                    }
                    
                    alert(errorMessage + ' Please try again.');
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                });
            }

            // Function to update progress bar
            function updateProgressBar() {
                const totalQuestions = {{ $totalQuestions }};
                const answeredElements = document.querySelectorAll('.answer-status .badge:not(.d-none)').length;
                const progressPercent = totalQuestions > 0 ? (answeredElements / totalQuestions) * 100 : 0;

                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                
                if (progressBar) {
                    progressBar.style.width = progressPercent + '%';
                    progressBar.setAttribute('aria-valuenow', progressPercent);
                }
                
                if (progressText) {
                    progressText.textContent = `${answeredElements}/${totalQuestions} questions`;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
