@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-0">
                                <i class="fas fa-comments me-2"></i>My Suggestions & Feedback
                            </h3>
                            <p class="text-white opacity-8 mb-0">Track your submitted suggestions and feedback</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#suggestionModal">
                                <i class="fas fa-plus me-2"></i>New Suggestion
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Course</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Submitted</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suggestions as $suggestion)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $suggestion->type === 'suggestion' ? 'info' : 'primary' }}">
                                            {{ ucfirst($suggestion->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $suggestion->title }}</p>
                                        <p class="text-xs text-muted mb-0">{{ Str::limit($suggestion->content, 50) }}</p>
                                    </td>
                                    <td>
                                        @if($suggestion->course)
                                            <p class="text-sm mb-0">{{ $suggestion->course->name }}</p>
                                        @else
                                            <p class="text-sm text-muted mb-0">General</p>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $suggestion->status === 'pending' ? 'warning' : ($suggestion->status === 'reviewed' ? 'info' : 'success') }}">
                                            {{ ucfirst($suggestion->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ $suggestion->created_at->format('M d, Y') }}</p>
                                    </td>
                                    <td>
                                        @if($suggestion->admin_response)
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                    data-bs-target="#responseModal{{ $suggestion->id }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        @else
                                            <span class="text-muted text-sm">Pending</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Response Modal -->
                                @if($suggestion->admin_response)
                                    <div class="modal fade" id="responseModal{{ $suggestion->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white">Admin Response</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">{{ $suggestion->admin_response }}</p>
                                                    <small class="text-muted">
                                                        Reviewed by: <strong>{{ $suggestion->admin->name ?? 'Admin' }}</strong><br>
                                                        On: {{ $suggestion->reviewed_at->format('M d, Y H:i A') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted mb-0">You haven't submitted any suggestions or feedback yet.</p>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#suggestionModal">
                                            <i class="fas fa-plus me-1"></i>Submit Your First Suggestion
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $suggestions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('suggestions.modal')
@endsection
