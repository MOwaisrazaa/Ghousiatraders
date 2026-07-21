@extends('layouts.app')

@section('main-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Session Expired</h4>
                </div>
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-4x text-warning mb-3"></i>
                        <h3>Your session has expired</h3>
                        <p class="lead text-muted">For your security, your session has timed out due to inactivity.</p>
                    </div>

                    <p>Please click the button below to sign back in and continue where you left off.</p>

                    <div class="mt-4">
                        <a href="{{ route('sign-in') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
