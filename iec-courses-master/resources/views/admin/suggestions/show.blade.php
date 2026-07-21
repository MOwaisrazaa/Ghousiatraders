@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.suggestions.index') }}" class="btn btn-secondary btn-sm mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Suggestions
            </a>
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary p-4">
                    <h3 class="text-white mb-0">
                        <i class="fas fa-comment me-2"></i>{{ ucfirst($suggestion->type) }} Details
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Suggestion Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">{{ $suggestion->title }}</h5>
                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-{{ $suggestion->type === 'suggestion' ? 'info' : 'primary' }}">
                                {{ ucfirst($suggestion->type) }}
                            </span>
                            <span class="badge bg-{{ $suggestion->status === 'pending' ? 'warning' : ($suggestion->status === 'reviewed' ? 'info' : 'success') }}">
                                {{ ucfirst($suggestion->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase text-sm fw-bold mb-2">Content</h6>
                        <p class="text-dark">{{ $suggestion->content }}</p>
                    </div>

                    @if($suggestion->course)
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase text-sm fw-bold mb-2">Related Course</h6>
                            <p class="text-dark">{{ $suggestion->course->name }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase text-sm fw-bold mb-2">Submitted By</h6>
                            <p class="text-dark">
                                <strong>{{ $suggestion->user->name }}</strong><br>
                                <small class="text-muted">{{ $suggestion->user->email }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase text-sm fw-bold mb-2">Submitted On</h6>
                            <p class="text-dark">{{ $suggestion->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                    </div>

                    @if($suggestion->admin_response)
                        <hr>
                        <div class="mt-4">
                            <h6 class="text-muted text-uppercase text-sm fw-bold mb-2">Admin Response</h6>
                            <div class="alert alert-info">
                                <p class="mb-0">{{ $suggestion->admin_response }}</p>
                                <small class="text-muted d-block mt-2">
                                    Reviewed by: <strong>{{ $suggestion->admin->name ?? 'N/A' }}</strong> on {{ $suggestion->reviewed_at->format('M d, Y H:i A') }}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Response Form -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0"><i class="fas fa-reply me-2 text-primary"></i>Admin Response</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.suggestions.update', $suggestion) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ $suggestion->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $suggestion->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="resolved" {{ $suggestion->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_response" class="form-label fw-bold">Response</label>
                            <textarea class="form-control @error('admin_response') is-invalid @enderror" id="admin_response" 
                                      name="admin_response" rows="5" placeholder="Write your response here...">{{ $suggestion->admin_response }}</textarea>
                            <small class="text-muted">Optional - provide feedback to the user</small>
                            @error('admin_response')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Save Response
                        </button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.suggestions.destroy', $suggestion) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
