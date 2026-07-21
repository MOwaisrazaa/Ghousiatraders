@extends('admin.layout')

@section('title', 'Certificate Management')

@section('header')
    Certificate Management
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

    <!-- Pending Certificate Requests -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pending Certificate Requests</h6>
        </div>
        <div class="card-body">
            @if(count($pendingRequests) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="pendingRequestsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Course / Lecture</th>
                                <th>Type</th>
                                <th>Request Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $request)
                                <tr>
                                    <td>{{ $request->user->name }} ({{ $request->user->email }})</td>
                                    <td>
                                        @if($request->course)
                                            {{ $request->course->name }}
                                        @elseif($request->lecture)
                                            <span class="badge bg-info">Standalone Lecture</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                        @if($request->lecture)
                                            <br><small class="text-muted">{{ $request->lecture->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->lecture)
                                            <span class="badge bg-info">Lecture</span>
                                        @else
                                            <span class="badge bg-primary">Course</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.certificates.show-request', $request->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $pendingRequests->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="mb-0">No pending certificate requests at this time.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Issued Certificates -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Issued Certificates</h6>
        </div>
        <div class="card-body">
            @if(count($issuedCertificates) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="issuedCertificatesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Certificate Number</th>
                                <th>User</th>
                                <th>Course / Lecture</th>
                                <th>Type</th>
                                <th>Issue Date</th>
                                <th>Expiry Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issuedCertificates as $certificate)
                                <tr>
                                    <td>{{ $certificate->certificate_number }}</td>
                                    <td>{{ $certificate->user->name }}</td>
                                    <td>
                                        @if($certificate->course)
                                            {{ $certificate->course->name }}
                                        @elseif($certificate->lecture)
                                            <span class="badge bg-info">Standalone Lecture</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                        @if($certificate->lecture)
                                            <br><small class="text-muted">{{ $certificate->lecture->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($certificate->lecture)
                                            <span class="badge bg-info">Lecture</span>
                                        @else
                                            <span class="badge bg-primary">Course</span>
                                        @endif
                                    </td>
                                    <td>{{ $certificate->issue_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($certificate->expiry_date)
                                            <span class="badge bg-{{ $certificate->hasExpired() ? 'danger' : 'warning' }}">
                                                {{ $certificate->expiry_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">Never Expires</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.certificates.view', $certificate->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.certificates.download', $certificate->id) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $issuedCertificates->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                    <p class="mb-0">No certificates have been issued yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#pendingRequestsTable').DataTable({
            "order": [[ 2, "asc" ]], // Order by request date ascending
            "pageLength": 10
        });
        
        $('#issuedCertificatesTable').DataTable({
            "order": [[ 3, "desc" ]], // Order by issue date descending
            "pageLength": 10
        });
    });
</script>
@endsection 