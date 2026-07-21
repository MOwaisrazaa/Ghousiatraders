<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="text-white mb-0">My Certificates</h3>
                                <p class="text-white opacity-8 mb-0">View and download your course and lecture certificates</p>
                            </div>
                            <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                                <a href="{{ route('user.dashboard') }}" class="btn btn-white btn-sm ms-auto">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                </a>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Issued Certificates -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0"><i class="fas fa-certificate me-2 text-success"></i>My Certificates</h5>
                    </div>
                    <div class="card-body p-3">
                        @if(count($certificates) > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Course / Lecture</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Certificate Number</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Issue Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Expiry Date</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($certificates as $certificate)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            @if($certificate->course)
                                                                <img src="{{ isset($certificate->course->image_path) ? Storage::url($certificate->course->image_path) : 'https://via.placeholder.com/300x200?text=Course+Image' }}"
                                                                    class="avatar avatar-sm me-3" alt="{{ $certificate->course->name }}">
                                                            @elseif($certificate->lecture && $certificate->lecture->course)
                                                                <img src="{{ isset($certificate->lecture->course->image_path) ? Storage::url($certificate->lecture->course->image_path) : 'https://via.placeholder.com/300x200?text=Lecture+Image' }}"
                                                                    class="avatar avatar-sm me-3" alt="{{ $certificate->lecture->course->name }}">
                                                            @else
                                                                <img src="https://via.placeholder.com/300x200?text=Lecture+Image"
                                                                    class="avatar avatar-sm me-3" alt="Lecture">
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            @if($certificate->course)
                                                                <h6 class="mb-0 text-sm">{{ $certificate->course->name }}</h6>
                                                            @elseif($certificate->lecture && $certificate->lecture->course)
                                                                <h6 class="mb-0 text-sm">{{ $certificate->lecture->course->name }}</h6>
                                                            @else
                                                                <h6 class="mb-0 text-sm">Standalone Lecture</h6>
                                                            @endif
                                                            @if($certificate->lecture)
                                                                <p class="text-xs text-secondary mb-0">{{ $certificate->lecture->name }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($certificate->lecture)
                                                        <span class="badge bg-info">Lecture</span>
                                                    @else
                                                        <span class="badge bg-primary">Course</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $certificate->certificate_number }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $certificate->issue_date->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    @if($certificate->expiry_date)
                                                        <span class="badge bg-{{ $certificate->hasExpired() ? 'danger' : 'warning' }}">
                                                            {{ $certificate->expiry_date->format('M d, Y') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">Never Expires</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('user.certificate.view', $certificate->id) }}" class="btn btn-link text-primary mb-0">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                    <a href="{{ route('user.certificate.download', $certificate->id) }}" class="btn btn-link text-info mb-0">
                                                        <i class="fas fa-download me-1"></i> Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-certificate fa-4x text-muted opacity-5"></i>
                                </div>
                                <h4 class="font-weight-normal">No certificates yet</h4>
                                <p class="text-muted mb-4">You don't have any certificates issued yet. Complete lectures and pass their quizzes to request certificates.</p>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Go to My Courses</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Certificate Requests -->
        @if(count($pendingRequests) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white p-3">
                            <h5 class="mb-0"><i class="fas fa-hourglass-half me-2 text-warning"></i>Pending Certificate Requests</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="alert alert-info border-0 bg-info bg-gradient text-white" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>Certificate Processing:</strong> Your certificate requests are being reviewed by our team. Once approved, your certificates will appear in the section above.
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Course / Lecture</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Requested Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingRequests as $request)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            @if($request->course)
                                                                <img src="{{ isset($request->course->image_path) ? Storage::url($request->course->image_path) : 'https://via.placeholder.com/300x200?text=Course+Image' }}"
                                                                    class="avatar avatar-sm me-3" alt="{{ $request->course->name }}">
                                                            @elseif($request->lecture && $request->lecture->course)
                                                                <img src="{{ isset($request->lecture->course->image_path) ? Storage::url($request->lecture->course->image_path) : 'https://via.placeholder.com/300x200?text=Lecture+Image' }}"
                                                                    class="avatar avatar-sm me-3" alt="{{ $request->lecture->course->name }}">
                                                            @else
                                                                <img src="https://via.placeholder.com/300x200?text=Lecture+Image"
                                                                    class="avatar avatar-sm me-3" alt="Lecture">
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            @if($request->course)
                                                                <h6 class="mb-0 text-sm">{{ $request->course->name }}</h6>
                                                            @elseif($request->lecture && $request->lecture->course)
                                                                <h6 class="mb-0 text-sm">{{ $request->lecture->course->name }}</h6>
                                                            @else
                                                                <h6 class="mb-0 text-sm">Standalone Lecture</h6>
                                                            @endif
                                                            @if($request->lecture)
                                                                <p class="text-xs text-secondary mb-0">{{ $request->lecture->name }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($request->lecture)
                                                        <span class="badge bg-info">Lecture</span>
                                                    @else
                                                        <span class="badge bg-primary">Course</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">Pending Review</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Rejected Certificate Requests -->
        @if(count($rejectedRequests) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white p-3">
                            <h5 class="mb-0"><i class="fas fa-times-circle me-2 text-danger"></i>Rejected Certificate Requests</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Course / Lecture</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Requested Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Review Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reason for Rejection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rejectedRequests as $request)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            @if($request->course)
                                                                <img src="{{ isset($request->course->image_path) ? Storage::url($request->course->image_path) : 'https://via.placeholder.com/300x200?text=Course+Image' }}"
                                                                    class="avatar avatar-sm me-3" alt="{{ $request->course->name }}">
                                                            @elseif($request->lecture && $request->lecture->course)
                                                                <img src="{{ isset($request->lecture->course->image_path) ? Storage::url($request->lecture->course->image_path) : 'https://via.placeholder.com/300x200?text=Lecture+Image' }}"
                                                                    class="avatar avatar-sm me-3" alt="{{ $request->lecture->course->name }}">
                                                            @else
                                                                <img src="https://via.placeholder.com/300x200?text=Lecture+Image"
                                                                    class="avatar avatar-sm me-3" alt="Lecture">
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            @if($request->course)
                                                                <h6 class="mb-0 text-sm">{{ $request->course->name }}</h6>
                                                            @elseif($request->lecture && $request->lecture->course)
                                                                <h6 class="mb-0 text-sm">{{ $request->lecture->course->name }}</h6>
                                                            @else
                                                                <h6 class="mb-0 text-sm">Standalone Lecture</h6>
                                                            @endif
                                                            @if($request->lecture)
                                                                <p class="text-xs text-secondary mb-0">{{ $request->lecture->name }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($request->lecture)
                                                        <span class="badge bg-info">Lecture</span>
                                                    @else
                                                        <span class="badge bg-primary">Course</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->reviewed_at->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs text-wrap mb-0">{{ $request->admin_notes }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-app.footer />

    <style>
        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 0.25rem;
            object-fit: cover;
        }
        .bg-gradient-primary {
            background-image: linear-gradient(310deg, #7928CA 0%, #FF0080 100%);
        }
    </style>
</x-app-layout> 