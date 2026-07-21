@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-0">
                                <i class="fas fa-comments me-2"></i>Suggestions & Feedback
                            </h3>
                            <p class="text-white opacity-8 mb-0">Manage user suggestions and feedback</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total</p>
                                <h5 class="font-weight-bolder mb-0">{{ $stats['total'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle p-3">
                                <i class="fas fa-comments text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending</p>
                                <h5 class="font-weight-bolder mb-0 text-warning">{{ $stats['pending'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle p-3">
                                <i class="fas fa-hourglass-half text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Reviewed</p>
                                <h5 class="font-weight-bolder mb-0 text-info">{{ $stats['reviewed'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle p-3">
                                <i class="fas fa-eye text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Resolved</p>
                                <h5 class="font-weight-bolder mb-0 text-success">{{ $stats['resolved'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle p-3">
                                <i class="fas fa-check-circle text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suggestions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0"><i class="fas fa-list me-2 text-primary"></i>All Suggestions & Feedback</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Course</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suggestions as $suggestion)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="text-sm font-weight-bold mb-0">{{ $suggestion->user->name }}</p>
                                                <p class="text-xs text-muted mb-0">{{ $suggestion->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $suggestion->type === 'suggestion' ? 'info' : 'primary' }}">
                                            {{ ucfirst($suggestion->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ Str::limit($suggestion->title, 30) }}</p>
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
                                        <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No suggestions or feedback yet.</p>
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
@endsection
