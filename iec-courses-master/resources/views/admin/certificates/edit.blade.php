@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-certificates.css') }}">
@endsection

@extends('admin.layout')

@section('title', 'Edit Certificate')

@section('header')
    Edit Certificate
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
            <h6 class="m-0 font-weight-bold text-primary">Edit Certificate</h6>
            <a href="{{ route('admin.certificates.view', $certificate->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Certificate
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Certificate Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="certificate_number" class="form-label">Certificate Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('certificate_number') is-invalid @enderror" 
                                        id="certificate_number" name="certificate_number" 
                                        value="{{ old('certificate_number', $certificate->certificate_number) }}" required>
                                    @error('certificate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Issue Date</label>
                                    <input type="text" class="form-control" 
                                        id="issue_date" value="{{ $certificate->issue_date->format('M d, Y') }}" readonly disabled>
                                    <small class="text-muted">Issue date cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                        id="expiry_date" name="expiry_date" 
                                        value="{{ old('expiry_date', $certificate->expiry_date ? $certificate->expiry_date->format('Y-m-d') : '') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave blank if the certificate never expires</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">User & Course Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">User</label>
                                    <input type="text" class="form-control" 
                                        value="{{ $certificate->user->name }} ({{ $certificate->user->email }})" readonly disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Course</label>
                                    <input type="text" class="form-control" 
                                        value="{{ $certificate->course->name ?? 'Standalone Lecture' }}" readonly disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="admin_notes" class="form-label">Admin Notes</label>
                                    <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                        id="admin_notes" name="admin_notes" 
                                        rows="4">{{ old('admin_notes', $certificate->certificateRequest->admin_notes) }}</textarea>
                                    @error('admin_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Certificate File</h6>
                            </div>
                            <div class="card-body">
                                @if($certificate->file_path)
                                    <div class="mb-3">
                                        <label class="form-label">Current Certificate File</label>
                                        <div class="certificate-preview">
                                            @php
                                                $extension = pathinfo($certificate->file_path, PATHINFO_EXTENSION);
                                                $isPdf = strtolower($extension) === 'pdf';
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                            @endphp

                                            @if($isPdf)
                                                <div>
                                                    <p><strong>Current file:</strong> PDF document</p>
                                                    <a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-external-link-alt mr-1"></i> View PDF
                                                    </a>
                                                </div>
                                            @elseif($isImage)
                                                <img src="{{ asset('storage/' . $certificate->file_path) }}" alt="Certificate" class="img-fluid certificate-image mb-2">
                                            @else
                                                <p>Current file: Unknown format</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="certificate_file" class="form-label">Replace Certificate File</label>
                                    <input type="file" class="form-control @error('certificate_file') is-invalid @enderror" 
                                        id="certificate_file" name="certificate_file">
                                    @error('certificate_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave blank to keep the current file. Accepted formats: PDF, JPG, JPEG, PNG. Max size: 5MB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.certificates.view', $certificate->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .certificate-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
        }
    </style>
@endsection 