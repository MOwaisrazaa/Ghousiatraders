<div>
    <!-- Admin Navigation -->
    @include('partials.admin-nav')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $isEditing ? 'Edit Lecture' : 'Add New Lecture' }}</h1>
            <div>
                @if($isEditing)
                    <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteLectureModal">
                        <i class="fas fa-trash-alt me-1"></i> Delete Lecture
                    </button>
                @endif
                <a href="{{ route('admin.course.edit', $courseId) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Course
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Lecture Details</h4>
                <span class="badge bg-primary">{{ $course->name }}</span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="saveLecture">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Lecture Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" wire:model="description"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="youtube_url" class="form-label">YouTube URL</label>
                                <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" wire:model="youtube_url" placeholder="https://www.youtube.com/embed/...">
                                @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Enter a YouTube embed URL for the lecture video.</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="weekly_price" class="form-label">Weekly Price ($)</label>
                                        <input type="number" step="0.01" class="form-control @error('weekly_price') is-invalid @enderror" id="weekly_price" wire:model="weekly_price">
                                        @error('weekly_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="monthly_price" class="form-label">Monthly Price ($)</label>
                                        <input type="number" step="0.01" class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" wire:model="monthly_price">
                                        @error('monthly_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">Lecture Image</label>

                                @if($isEditing && $currentImage)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($currentImage) }}" alt="Current Image" class="img-thumbnail mb-2 d-block img-thumbnail-200">
                                        <div class="small text-muted">Current image</div>
                                    </div>
                                @endif

                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" wire:model="image">
                                <div class="small text-muted mt-1">Upload a new image (max 1MB)</div>
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            @if($youtube_url)
                                <div class="mb-3">
                                    <label class="form-label">Video Preview</label>
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $youtube_url }}" allowfullscreen></iframe>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" wire:click="cancelEdit" class="btn btn-secondary me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> {{ $isEditing ? 'Update Lecture' : 'Create Lecture' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Lecture Modal -->
        <div class="modal fade" id="deleteLectureModal" tabindex="-1" aria-labelledby="deleteLectureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteLectureModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to delete this lecture? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteLecture()" data-bs-dismiss="modal">
                            <i class="fas fa-trash-alt me-1"></i> Delete Lecture
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
