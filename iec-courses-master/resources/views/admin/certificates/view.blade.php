@extends('admin.layout')

@section('title', 'View Certificate')

@section('header')
    View Certificate Details
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
            <h6 class="m-0 font-weight-bold text-primary">Certificate Details</h6>
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Certificates
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Certificate Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Certificate Number</th>
                            <td>{{ $certificate->certificate_number }}</td>
                        </tr>
                        <tr>
                            <th>Issue Date</th>
                            <td>{{ $certificate->issue_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Expiry Date</th>
                            <td>
                                @if($certificate->expiry_date)
                                    <span class="badge bg-{{ $certificate->hasExpired() ? 'danger' : 'warning' }}">
                                        {{ $certificate->expiry_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-success">Never Expires</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">User & Course Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>User</th>
                            <td>{{ $certificate->user->name }} ({{ $certificate->user->email }})</td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td>
                                @if($certificate->course)
                                    {{ $certificate->course->name }}
                                @elseif($certificate->lecture)
                                    <span class="badge bg-info">Standalone Lecture</span>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @if($certificate->lecture)
                        <tr>
                            <th>Lecture</th>
                            <td>{{ $certificate->lecture->name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Request Date</th>
                            <td>{{ $certificate->certificateRequest->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($certificate->file_path)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="font-weight-bold">Certificate Preview</h5>
                        <div class="certificate-container">
                            @php
                                $extension = pathinfo($certificate->file_path, PATHINFO_EXTENSION);
                                $isPdf = strtolower($extension) === 'pdf';
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                // Handle both 'certificates/file.pdf' and 'public/certificates/file.pdf' formats
                                $cleanPath = str_replace('public/', '', $certificate->file_path);
                                $filePath = asset('storage/' . $cleanPath);
                            @endphp

                            @if($isPdf)
                                <div class="ratio ratio-16x9">
                                    <embed src="{{ $filePath }}" type="application/pdf" width="100%" height="600px" />
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ $filePath }}" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-external-link-alt mr-1"></i> Open PDF in New Tab
                                    </a>
                                </div>
                            @elseif($isImage)
                                <div class="text-center">
                                    <img src="{{ $filePath }}" alt="Certificate" class="img-fluid certificate-image">
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Certificate file format is not supported for preview. Please download to view.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Certificate file not available for preview.
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Admin Notes</h5>
                    <div class="card">
                        <div class="card-body">
                            @if($certificate->certificateRequest->admin_notes)
                                {{ $certificate->certificateRequest->admin_notes }}
                            @else
                                <em>No admin notes available.</em>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Actions</h5>
                    <a href="{{ route('admin.certificates.download', $certificate->id) }}" class="btn btn-primary mb-2">
                        <i class="fas fa-download mr-1"></i> Download Certificate
                    </a>
                    
                    <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="btn btn-warning mb-2 ml-2">
                        <i class="fas fa-edit mr-1"></i> Edit Certificate
                    </a>
                    
                    <!-- Additional actions like revoke certificate could be added here -->
                </div>
            </div>
        </div>
    </div>

    <style>
        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 15px;
        }
        .certificate-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #eee;
        }
        .ratio {
            position: relative;
            width: 100%;
        }
        .ratio-16x9 {
            padding-top: 56.25%;
        }
        .ratio > * {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection 