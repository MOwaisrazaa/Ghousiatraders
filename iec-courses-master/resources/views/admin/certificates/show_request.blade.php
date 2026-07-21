@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-certificates.css') }}">
@endsection

@extends('admin.layout')

@section('title', 'Review Certificate Request')

@section('header')
    Review Certificate Request
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Certificate Request Details</h6>
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Certificates
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">User Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $request->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $request->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Request Date</th>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Course Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Course</th>
                            <td>
                                @if($request->course)
                                    {{ $request->course->name }}
                                @elseif($request->lecture)
                                    <span class="badge bg-info">Standalone Lecture</span>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @if($request->lecture)
                            <tr>
                                <th>Lecture</th>
                                <td>{{ $request->lecture->name }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td><span class="badge bg-info">Lecture Certificate</span></td>
                            </tr>
                        @else
                            <tr>
                                <th>Type</th>
                                <td><span class="badge bg-primary">Course Certificate</span></td>
                            </tr>
                            @if(isset($progressData['percent']))
                                <tr>
                                    <th>Progress</th>
                                    <td>
                                        <div class="progress progress-medium">
                                            <div class="progress-bar bg-success dynamic-progress-bar" role="progressbar" data-width="{{ $progressData['percent'] }}" aria-valuenow="{{ $progressData['percent'] }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($progressData['percent'], 0) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $progressData['completed_lectures'] }} of {{ $progressData['total_lectures'] }} lectures completed</small>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="font-weight-bold">Quiz Results</h5>
                    @if(count($quizAttempts) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Quiz</th>
                                        @if(!$request->lecture)
                                            <th>Required for Certificate</th>
                                        @endif
                                        <th>Attempt Date</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quizAttempts as $quizAttempt)
                                        <tr>
                                            <td>{{ $quizAttempt['quiz']->title }}</td>
                                            @if(!$request->lecture)
                                                <td>
                                                    @if($quizAttempt['quiz']->required_for_completion)
                                                        <span class="badge bg-primary">Required</span>
                                                    @else
                                                        <span class="badge bg-secondary">Optional</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if($quizAttempt['attempt'])
                                                    {{ $quizAttempt['attempt']->completed_at ? $quizAttempt['attempt']->completed_at->format('M d, Y H:i') : 'In Progress' }}
                                                @else
                                                    Not Attempted
                                                @endif
                                            </td>
                                            <td>
                                                @if($quizAttempt['attempt'])
                                                    {{ $quizAttempt['attempt']->percentage_score }}% ({{ $quizAttempt['attempt']->score }}/{{ $quizAttempt['attempt']->total_points }})
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($quizAttempt['passed'])
                                                    <span class="badge bg-success">Passed</span>
                                                @elseif($quizAttempt['attempt'])
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-warning">Not Attempted</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            @if($request->lecture)
                                No quiz found for this lecture.
                            @else
                                No required quizzes for this course.
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <h5 class="font-weight-bold">Approve Request</h5>
                            <form action="{{ route('admin.certificates.approve', $request->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="certificate_file" class="form-label">Upload Certificate File (PDF or Image)</label>
                                    <input type="file" class="form-control @error('certificate_file') is-invalid @enderror" id="certificate_file" name="certificate_file" required>
                                    @error('certificate_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Accepted formats: PDF, JPG, JPEG, PNG. Max size: 5MB</small>
                                </div>
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date (Optional)</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                    <small class="text-muted">Leave blank if the certificate never expires</small>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"></textarea>
                                    <small class="text-muted">These notes are for internal use only</small>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle mr-1"></i> Approve & Issue Certificate
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-left-danger">
                        <div class="card-body">
                            <h5 class="font-weight-bold">Reject Request</h5>
                            <form action="{{ route('admin.certificates.reject', $request->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="reject_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="reject_notes" name="admin_notes" rows="5" required></textarea>
                                    @error('admin_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">This will be visible to the user</small>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times-circle mr-1"></i> Reject Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 