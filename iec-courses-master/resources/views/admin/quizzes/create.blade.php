<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Create New Quiz</h1>
                <p class="mb-0">Add a quiz to a lecture</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Quizzes
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
                <h6 class="m-0 font-weight-bold text-primary">Quiz Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.quizzes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="lecture_id" class="form-label">Lecture <span class="text-danger">*</span></label>
                        <select class="form-select @error('lecture_id') is-invalid @enderror" id="lecture_id" name="lecture_id" required>
                            <option value="">-- Select Lecture --</option>
                            @foreach($lectures as $lecture)
                                <option value="{{ $lecture->id }}">{{ $lecture->name }} ({{ $lecture->course ? 'Course: ' . $lecture->course->name : 'Standalone' }})</option>
                            @endforeach
                        </select>
                        @error('lecture_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description/Instructions</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_points" class="form-label">Total Points <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_points') is-invalid @enderror" id="total_points" name="total_points" value="{{ old('total_points', 10) }}" min="1" required>
                                <small class="text-muted">The maximum points possible for this quiz</small>
                                @error('total_points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="passing_score" class="form-label">Passing Score % <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', 70) }}" min="1" max="100" required>
                                <small class="text-muted">Percentage required to pass (1-100)</small>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror" id="time_limit" name="time_limit" value="{{ old('time_limit') }}" min="1">
                                <small class="text-muted">Leave blank for no time limit</small>
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1" {{ old('is_required', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">
                                        Required to Complete Lecture
                                    </label>
                                </div>
                                <small class="text-muted">If checked, students must pass this quiz to complete the lecture</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Create Quiz
                        </button>
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary ms-1">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 