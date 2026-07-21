<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Edit Question</h1>
                <p class="mb-0">Editing question for quiz: {{ $question->quiz->title }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.quizzes.edit', $question->quiz) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Quiz
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Question Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.quiz-questions.update', $question) }}" method="POST" id="questionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="quiz_id" value="{{ $question->quiz_id }}">
                    
                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="question_type" class="form-label">Question Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('question_type') is-invalid @enderror" id="question_type" name="question_type" required {{ $question->hasAttempts() ? 'disabled' : '' }}>
                            <option value="multiple_choice" {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="open_ended" {{ old('question_type', $question->question_type) == 'open_ended' ? 'selected' : '' }}>Open Ended</option>
                        </select>
                        @if($question->hasAttempts())
                            <input type="hidden" name="question_type" value="{{ $question->question_type }}">
                            <small class="text-muted">Question type cannot be changed because students have already attempted this quiz.</small>
                        @endif
                        @error('question_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="points" class="form-label">Points <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('points') is-invalid @enderror" id="points" name="points" value="{{ old('points', $question->points) }}" min="1" required>
                        <small class="text-muted">The number of points this question is worth</small>
                        @error('points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $question->order) }}" min="1">
                        <small class="text-muted">The order in which this question appears in the quiz</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Multiple Choice Options Section -->
                    @if($question->question_type == 'multiple_choice')
                    <div id="multipleChoiceOptions">
                        <hr>
                        <h5>Answer Options</h5>
                        <p class="text-muted">Add at least 2 options and mark the correct one.</p>
                        
                        <div id="optionsContainer">
                            @forelse($question->options as $index => $option)
                                <div class="option-item mb-3 pb-3 border-bottom" data-option-index="{{ $index }}" data-option-id="{{ $option->id }}">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="mb-2">
                                                <label class="form-label">Option Text <span class="text-danger">*</span></label>
                                                <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">
                                                <input type="text" class="form-control option-text" name="options[{{ $index }}][option_text]" value="{{ $option->option_text }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input correct-option" type="radio" name="correct_option" value="{{ $index }}" {{ $option->is_correct ? 'checked' : '' }} required>
                                                <label class="form-check-label">
                                                    Correct Answer
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-sm btn-danger remove-option" {{ $index < 2 || $question->hasAttempts() ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <!-- Default options if none exist -->
                                <div class="option-item mb-3 pb-3 border-bottom" data-option-index="0">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="mb-2">
                                                <label class="form-label">Option Text <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control option-text" name="options[0][option_text]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input correct-option" type="radio" name="correct_option" value="0" required>
                                                <label class="form-check-label">
                                                    Correct Answer
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-sm btn-danger remove-option" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="option-item mb-3 pb-3 border-bottom" data-option-index="1">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="mb-2">
                                                <label class="form-label">Option Text <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control option-text" name="options[1][option_text]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input correct-option" type="radio" name="correct_option" value="1" required>
                                                <label class="form-check-label">
                                                    Correct Answer
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-sm btn-danger remove-option" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" id="addOptionBtn" class="btn btn-sm btn-outline-primary" {{ $question->hasAttempts() ? 'disabled' : '' }}>
                                <i class="fas fa-plus me-1"></i> Add Another Option
                            </button>
                            @if($question->hasAttempts())
                                <small class="text-muted d-block mt-1">Options cannot be added or removed because students have already attempted this quiz.</small>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Question
                        </button>
                        <a href="{{ route('admin.quizzes.edit', $question->quiz) }}" class="btn btn-outline-secondary ms-1">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionTypeSelect = document.getElementById('question_type');
            const multipleChoiceOptions = document.getElementById('multipleChoiceOptions');
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOptionBtn');
            const form = document.getElementById('questionForm');
            const hasAttempts = {{ $question->hasAttempts() ? 'true' : 'false' }};
            
            // Handle question type change
            if (questionTypeSelect && !hasAttempts) {
                questionTypeSelect.addEventListener('change', function() {
                    if (this.value === 'multiple_choice') {
                        multipleChoiceOptions.style.display = 'block';
                    } else {
                        multipleChoiceOptions.style.display = 'none';
                    }
                });
            }
            
            // Add option button
            if (addOptionBtn && !hasAttempts) {
                addOptionBtn.addEventListener('click', function() {
                    const optionItems = document.querySelectorAll('.option-item');
                    const newIndex = optionItems.length;
                    
                    // Clone the first option
                    const newOption = optionItems[0].cloneNode(true);
                    
                    // Update all the indices and clear values
                    newOption.setAttribute('data-option-index', newIndex);
                    newOption.removeAttribute('data-option-id');
                    
                    const optionTextInput = newOption.querySelector('.option-text');
                    optionTextInput.name = `options[${newIndex}][option_text]`;
                    optionTextInput.value = '';
                    
                    // Remove any hidden ID field if present
                    const hiddenIdField = newOption.querySelector('input[name^="options"][name$="[id]"]');
                    if (hiddenIdField) {
                        hiddenIdField.remove();
                    }
                    
                    const correctRadio = newOption.querySelector('.correct-option');
                    correctRadio.value = newIndex;
                    correctRadio.checked = false;
                    
                    const removeBtn = newOption.querySelector('.remove-option');
                    removeBtn.disabled = false;
                    removeBtn.addEventListener('click', function() {
                        newOption.remove();
                        updateOptionIndices();
                    });
                    
                    // Add to container
                    optionsContainer.appendChild(newOption);
                });
            }
            
            // Enable remove buttons (except first two)
            if (!hasAttempts) {
                document.querySelectorAll('.option-item').forEach((item, index) => {
                    if (index > 1) {
                        const removeBtn = item.querySelector('.remove-option');
                        removeBtn.disabled = false;
                        removeBtn.addEventListener('click', function() {
                            // If this option has an ID, mark it for deletion
                            const optionId = item.getAttribute('data-option-id');
                            if (optionId) {
                                const hiddenField = document.createElement('input');
                                hiddenField.type = 'hidden';
                                hiddenField.name = 'deleted_options[]';
                                hiddenField.value = optionId;
                                form.appendChild(hiddenField);
                            }
                            
                            item.remove();
                            updateOptionIndices();
                        });
                    }
                });
            }
            
            // Update option indices for form submission
            function updateOptionIndices() {
                document.querySelectorAll('.option-item').forEach((item, index) => {
                    item.setAttribute('data-option-index', index);
                    
                    const optionTextInput = item.querySelector('.option-text');
                    const oldName = optionTextInput.name;
                    const newName = oldName.replace(/options\[\d+\]/, `options[${index}]`);
                    optionTextInput.name = newName;
                    
                    const hiddenIdField = item.querySelector('input[name^="options"][name$="[id]"]');
                    if (hiddenIdField) {
                        const oldIdName = hiddenIdField.name;
                        const newIdName = oldIdName.replace(/options\[\d+\]/, `options[${index}]`);
                        hiddenIdField.name = newIdName;
                    }
                    
                    const correctRadio = item.querySelector('.correct-option');
                    correctRadio.value = index;
                });
            }
            
            // Form validation before submit
            form.addEventListener('submit', function(e) {
                const questionType = questionTypeSelect.value;
                
                if (questionType === 'multiple_choice') {
                    // Check if at least one option is marked as correct
                    const hasCorrectOption = document.querySelector('.correct-option:checked');
                    
                    if (!hasCorrectOption) {
                        e.preventDefault();
                        alert('Please select a correct answer option.');
                        return false;
                    }
                    
                    // Process options data before submit
                    document.querySelectorAll('.option-item').forEach((item, index) => {
                        const optionIndex = parseInt(item.getAttribute('data-option-index'));
                        const isCorrect = document.querySelector(`.correct-option[value="${optionIndex}"]:checked`) !== null;
                        
                        // Add hidden field for is_correct
                        const hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = `options[${index}][is_correct]`;
                        hiddenField.value = isCorrect ? 1 : 0;
                        form.appendChild(hiddenField);
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 