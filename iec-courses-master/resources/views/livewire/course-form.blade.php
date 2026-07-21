<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ $isEditing ? 'Edit Course' : 'Add New Course' }}</h1>
                @if($isEditing)
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal">
                    <i class="fas fa-trash-alt me-1"></i> Delete Course
                </button>
                @endif
            </div>

            <!-- Course Form -->
            <div class="card shadow-sm mb-5">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Course Details</h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="saveCourse">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Course Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" wire:model="description"></textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_free" wire:model="is_free">
                                        <label class="form-check-label" for="is_free">
                                            <strong>Free Course</strong>
                                        </label>
                                        <div class="form-text">Check this box to make the course free for all users</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="purchase_model" class="form-label">Purchase Model</label>
                                    <select wire:model="purchase_model" id="purchase_model" class="form-select @error('purchase_model') is-invalid @enderror">
                                        <option value="flexible">Flexible (Whole Course + Individual Lectures)</option>
                                        <option value="restricted">Restricted (Whole Course Only)</option>
                                    </select>
                                    <small class="form-text text-muted d-block mt-2">
                                        <strong>Flexible:</strong> Users can buy the whole course or individual lectures<br>
                                        <strong>Restricted:</strong> Users can only buy the entire course. Individual lectures won't be shown on the public dashboard.
                                    </small>
                                    @error('purchase_model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                @if(!$is_free)
                                <div class="mb-3">
                                    <label for="weekly_price" class="form-label">Price ($)</label>
                                    <input type="number" step="0.01" class="form-control @error('weekly_price') is-invalid @enderror" id="weekly_price" wire:model="weekly_price">
                                    @error('weekly_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Course Image</label>

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
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.courses') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> {{ $isEditing ? 'Update Course' : 'Create Course' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($isEditing)
                <!-- Course Features Section -->
                <div class="card shadow-sm mb-5">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Course Features</h4>
                    </div>
                    <div class="card-body">
                        <!-- What You'll Learn -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">What You'll Learn</h5>
                            <div class="mb-3">
                                <ul class="list-group mb-3">
                                    @forelse($features['learn'] as $feature)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                {{ $feature['feature_text'] }}
                                            </div>
                                            <div>
                                                <button wire:click="moveFeatureUp({{ $feature['id'] }}, 'learn')" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                <button wire:click="moveFeatureDown({{ $feature['id'] }}, 'learn')" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button wire:click="deleteFeature({{ $feature['id'] }})" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">No items added yet.</li>
                                    @endforelse
                                </ul>

                                <div class="input-group">
                                    <input type="text" class="form-control" wire:model="newFeatureText.learn" wire:keydown.enter="addFeature('learn')" placeholder="Add what students will learn...">
                                    <button class="btn btn-primary" wire:click="addFeature('learn')" type="button">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Requirements</h5>
                            <div class="mb-3">
                                <ul class="list-group mb-3">
                                    @forelse($features['requirement'] as $feature)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-dot-circle text-primary me-2"></i>
                                                {{ $feature['feature_text'] }}
                                            </div>
                                            <div>
                                                <button wire:click="moveFeatureUp({{ $feature['id'] }}, 'requirement')" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                <button wire:click="moveFeatureDown({{ $feature['id'] }}, 'requirement')" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button wire:click="deleteFeature({{ $feature['id'] }})" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">No requirements added yet.</li>
                                    @endforelse
                                </ul>

                                <div class="input-group">
                                    <input type="text" class="form-control" wire:model="newFeatureText.requirement" wire:keydown.enter="addFeature('requirement')" placeholder="Add course requirement...">
                                    <button class="btn btn-primary" wire:click="addFeature('requirement')" type="button">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- This Course Includes -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">This Course Includes</h5>
                            <div class="mb-3">
                                <ul class="list-group mb-3">
                                    @forelse($features['includes'] as $feature)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-check text-info me-2"></i>
                                                {{ $feature['feature_text'] }}
                                            </div>
                                            <div>
                                                <button wire:click="moveFeatureUp({{ $feature['id'] }}, 'includes')" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                <button wire:click="moveFeatureDown({{ $feature['id'] }}, 'includes')" class="btn btn-sm btn-outline-secondary me-1">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button wire:click="deleteFeature({{ $feature['id'] }})" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">No inclusions added yet.</li>
                                    @endforelse
                                </ul>

                                <div class="input-group">
                                    <input type="text" class="form-control" wire:model="newFeatureText.includes" wire:keydown.enter="addFeature('includes')" placeholder="Add what's included in the course...">
                                    <button class="btn btn-primary" wire:click="addFeature('includes')" type="button">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lectures Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Lectures</h4>
                        <button type="button" class="btn btn-sm btn-primary" wire:click="showAddLecture">
                            <i class="fas fa-plus me-1"></i> Add Lecture
                        </button>
                    </div>
                    <div class="card-body">
                        @if($showLectureForm)
                            <!-- Lecture Form -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ $editingLecture ? 'Edit Lecture' : 'Add New Lecture' }}</h5>
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="saveLecture">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label for="lectureName" class="form-label">Lecture Name</label>
                                                    <input type="text" class="form-control @error('lectureName') is-invalid @enderror" id="lectureName" wire:model="lectureName">
                                                    @error('lectureName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="lectureDescription" class="form-label">Description</label>
                                                    <textarea class="form-control @error('lectureDescription') is-invalid @enderror" id="lectureDescription" rows="4" wire:model="lectureDescription"></textarea>
                                                    @error('lectureDescription') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" id="lectureIsFree" wire:model="lectureIsFree">
                                                        <label class="form-check-label" for="lectureIsFree">
                                                            <strong>Free Lecture</strong>
                                                        </label>
                                                        <div class="form-text">Check this box to make the lecture free for all users</div>
                                                    </div>
                                                </div>

                                                @if(!$lectureIsFree)
                                                <div class="mb-3">
                                                    <label for="lectureWeeklyPrice" class="form-label">Price ($)</label>
                                                    <input type="number" step="0.01" class="form-control @error('lectureWeeklyPrice') is-invalid @enderror" id="lectureWeeklyPrice" wire:model="lectureWeeklyPrice">
                                                    @error('lectureWeeklyPrice') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                @endif
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="lectureImage" class="form-label">Lecture Image</label>

                                                    @if($editingLecture && $currentLectureImage)
                                                        <div class="mb-3">
                                                            <img src="{{ Storage::url($currentLectureImage) }}" alt="Current Lecture Image" class="img-thumbnail mb-2 d-block img-thumbnail-150">
                                                            <div class="small text-muted">Current image</div>
                                                        </div>
                                                    @endif

                                                    <input type="file" class="form-control @error('lectureImage') is-invalid @enderror" id="lectureImage" wire:model="lectureImage">
                                                    <div class="small text-muted mt-1">Upload a new image (max 1MB)</div>
                                                    @error('lectureImage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary me-2" wire:click="cancelLecture">Cancel</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> {{ $editingLecture ? 'Update Lecture' : 'Add Lecture' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Lectures List -->
                        @if(count($lectures) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Type</th>
                                            <th width="150">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lectures as $lecture)
                                            <tr>
                                                <td>
                                                    <img src="{{ $lecture->image_path ? Storage::url($lecture->image_path) : 'https://via.placeholder.com/50x50' }}"
                                                        alt="{{ $lecture->name }}"
                                                        class="img-thumbnail course-thumbnail-50">
                                                </td>
                                                <td>{{ $lecture->name }}</td>
                                                <td>
                                                    @if($lecture->is_free)
                                                        <span class="badge bg-success">FREE</span>
                                                    @else
                                                        Rs {{ number_format($lecture->weekly_price, 2) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($lecture->is_free)
                                                        <span class="badge bg-info">Free</span>
                                                    @else
                                                        <span class="badge bg-primary">Paid</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary me-1" wire:click="editLecture({{ $lecture->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" wire:click="confirmDeleteLecture({{ $lecture->id }})" data-bs-toggle="modal" data-bs-target="#deleteLectureModal">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                No lectures have been added to this course yet.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Success Messages -->
    @if (session()->has('success'))
        <div class="position-fixed bottom-0 end-0 p-3 toast-position">
            <div class="toast show bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Course Modal -->
    <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCourseModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this course? This will also delete all associated lectures. This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteCourse()" data-bs-dismiss="modal">
                        <i class="fas fa-trash-alt me-1"></i> Delete Course
                    </button>
                </div>
            </div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="cancelDeleteLecture">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteLecture()" data-bs-dismiss="modal">
                        <i class="fas fa-trash-alt me-1"></i> Delete Lecture
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
