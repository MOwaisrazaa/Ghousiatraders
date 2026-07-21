<!-- Suggestion & Feedback Modal -->
<div class="modal fade" id="suggestionModal" tabindex="-1" aria-labelledby="suggestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white" id="suggestionModalLabel">
                    <i class="fas fa-lightbulb me-2"></i>Share Your Suggestion or Feedback
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suggestions.store') }}" method="POST" id="suggestionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">-- Select Type --</option>
                            <option value="suggestion">Suggestion</option>
                            <option value="feedback">Feedback</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                               placeholder="Brief title of your suggestion" required maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="course_id" class="form-label fw-bold">Related Course (Optional)</label>
                        <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id">
                            <option value="">-- Select a Course --</option>
                            @foreach(Auth::user()->courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold">Details <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" 
                                  rows="5" placeholder="Please provide detailed information about your suggestion or feedback..." 
                                  required minlength="10"></textarea>
                        <small class="text-muted">Minimum 10 characters required</small>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Reset form when modal is hidden
    document.getElementById('suggestionModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('suggestionForm').reset();
    });
</script>
