<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="text-white mb-0">Certificate of Completion</h3>
                                <p class="text-white opacity-8 mb-0">
                                    @if($certificate->course)
                                        {{ $certificate->course->name }}
                                    @elseif($certificate->lecture)
                                        Standalone Lecture
                                    @endif
                                    @if($certificate->lecture)
                                        - {{ $certificate->lecture->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                                <a href="{{ route('user.certificates') }}" class="btn btn-white btn-sm ms-auto">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Certificates
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="mb-1">Certificate of Completion</h2>
                            <p class="text-muted">This certificate verifies course completion</p>
                        </div>

                        @if($certificate->file_path)
                            <div class="text-center mb-4">
                                <div class="certificate-container">
                                    @php
                                        $extension = pathinfo($certificate->file_path, PATHINFO_EXTENSION);
                                        $isPdf = strtolower($extension) === 'pdf';
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                        // Use the secure stream route for viewing
                                        $filePath = route('user.certificate.stream', $certificate->id);
                                    @endphp

                                    @if($isPdf)
                                        <div style="position: relative; width: 100%; padding-bottom: 71%; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; background-color: #ffffff;">
                                            <iframe src="{{ $filePath }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH" width="100%" height="100%" style="position: absolute; top: 0; left: 0; border: none;">
                                                <p>Your browser does not support iframes.</p>
                                            </iframe>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <a href="{{ $filePath }}" class="btn btn-sm btn-outline-primary me-2" target="_blank">
                                                <i class="fas fa-external-link-alt me-1"></i> Open PDF in New Tab
                                            </a>
                                            <a href="{{ route('user.certificate.download', $certificate->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download me-1"></i> Download PDF
                                            </a>
                                        </div>
                                    @elseif($isImage)
                                        <img src="{{ $filePath }}" alt="Certificate" class="img-fluid certificate-image">
                                    @else
                                        <div class="alert alert-warning">
                                            Certificate file format is not supported for preview. Please download to view.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Certificate file not available for preview. Please contact support if this issue persists.
                            </div>
                        @endif

                        <div class="row g-4 mt-2">
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 border rounded-3 text-center h-100">
                                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-barcode text-dark text-gradient opacity-10 top-0"></i>
                                    </div>
                                    <h6 class="mb-0 text-xs text-uppercase text-muted">Certificate No</h6>
                                    <p class="font-weight-bolder mb-0 text-sm overflow-hidden" title="{{ $certificate->certificate_number }}">{{ $certificate->certificate_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 border rounded-3 text-center h-100">
                                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-calendar-alt text-dark text-gradient opacity-10 top-0"></i>
                                    </div>
                                    <h6 class="mb-0 text-xs text-uppercase text-muted">Issue Date</h6>
                                    <p class="font-weight-bolder mb-0 text-sm">{{ $certificate->issue_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 border rounded-3 text-center h-100">
                                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-book text-dark text-gradient opacity-10 top-0"></i>
                                    </div>
                                    <h6 class="mb-0 text-xs text-uppercase text-muted">
                                        @if($certificate->lecture) Lecture @else Course @endif
                                    </h6>
                                    <p class="font-weight-bolder mb-0 text-sm text-truncate" title="{{ $certificate->lecture ? $certificate->lecture->name : ($certificate->course ? $certificate->course->name : 'N/A') }}">
                                        {{ $certificate->lecture ? $certificate->lecture->name : ($certificate->course ? $certificate->course->name : 'N/A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="p-3 border rounded-3 text-center h-100">
                                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center mx-auto mb-2">
                                        <i class="fas fa-user text-dark text-gradient opacity-10 top-0"></i>
                                    </div>
                                    <h6 class="mb-0 text-xs text-uppercase text-muted">Recipient</h6>
                                    <p class="font-weight-bolder mb-0 text-sm">{{ $certificate->user->name }}</p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-app.footer />

    <style>
        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .certificate-image {
            max-width: 100%;
            height: auto;
        }
        .bg-gradient-primary {
            background-image: linear-gradient(310deg, #7928CA 0%, #FF0080 100%);
        }
        .certificate-details {
            max-width: 800px;
            margin: 20px auto;
        }
    </style>
</x-app-layout> 