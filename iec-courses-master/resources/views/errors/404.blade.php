@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/extracted/error-pages.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card error-card">
                <div class="card-header error-card-header error-404">
                    <h4 class="mb-0">Page Not Found</h4>
                </div>
                <div class="card-body error-card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-search error-icon error-404"></i>
                        <h2 class="error-heading">404 - Not Found</h2>
                        <p class="error-description">The page you are looking for does not exist.</p>
                    </div>
                    
                    <div class="alert error-alert error-404">
                        <p>This might be because:</p>
                        <ul>
                            <li>The page has been moved or deleted</li>
                            <li>You typed the wrong URL</li>
                            <li>The link you clicked might be outdated</li>
                        </ul>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary error-button">
                            <i class="fas fa-home mr-1"></i> Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection