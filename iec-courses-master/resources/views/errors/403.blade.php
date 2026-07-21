@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/extracted/error-pages.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card error-card">
                <div class="card-header error-card-header error-403">
                    <h4 class="mb-0">Access Forbidden</h4>
                </div>
                <div class="card-body error-card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-triangle error-icon error-403"></i>
                        <h2 class="error-heading">403 - Access Denied</h2>
                        <p class="error-description">You don't have permission to access this resource.</p>
                    </div>
                    
                    <div class="alert error-alert error-403">
                        <p>This might be because:</p>
                        <ul>
                            <li>You're trying to access a restricted area</li>
                            <li>You don't have the necessary permissions</li>
                            <li>The resource has been moved or removed</li>
                        </ul>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home mr-1"></i> Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection