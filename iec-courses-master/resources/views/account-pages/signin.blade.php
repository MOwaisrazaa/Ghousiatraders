<x-app-layout>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- <x-guest.sidenav-guest /> -->
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent text-center">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary mb-3">
                                    <i class="fas fa-home me-1"></i> Back to Home
                                </a>
                                <h3 class="font-weight-bold text-dark display-6">Welcome to Islamic Finance Academy</h3>
                                <p class="mb-0">Explore the knowledge of Shariah-compliant finance with us!</p>
                                <p class="mb-0">Create an account to start your learning journey today.</p>

                            </div>
                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="mb-4 font-medium text-sm text-green-600">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    {{-- Warning Messages --}}
                                    @if (session('warning'))
                                        <div class="alert alert-warning text-sm mb-4" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>{{ session('warning') }}</strong>
                                            </div>
                                            @if (session('warning_details'))
                                                <div class="mt-2 text-xs">
                                                    {{ session('warning_details') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Enhanced Error Messages for Device Restrictions --}}
                                    @if (session('error'))
                                        <div class="alert alert-danger text-sm mb-4" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>{{ session('error') }}</strong>
                                            </div>
                                            @if (session('error_details'))
                                                <div class="mt-2 text-xs">
                                                    {{ session('error_details') }}
                                                </div>
                                            @endif
                                            @if (session('support_contact'))
                                                <div class="mt-3 p-2 bg-light rounded">
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i>
                                                        Need help? Contact us at:
                                                        <a href="mailto:{{ session('support_contact') }}" class="auth-support-link">
                                                            {{ session('support_contact') }}
                                                        </a>
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @error('message')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    @error('email')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="card-body px-4">
                                    <form role="form" class="text-start" method="POST" action="/signin">
                                        @csrf

                                        {{-- Email Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-envelope me-2 auth-icon-primary"></i>Email Address
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control form-control-lg border-2 auth-input"
                                                placeholder="Enter your email address"
                                                value="{{ old('email') }}"
                                                required>
                                        </div>

                                        {{-- Password Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-lock me-2 auth-icon-primary"></i>Password
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" id="password" name="password"
                                                    value="{{ old('password') }}"
                                                    class="form-control form-control-lg border-2 auth-input-password" placeholder="Enter your password" required>
                                                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 auth-password-toggle" type="button" id="togglePasswordBtn">
                                                    <i class="fas fa-eye auth-icon-secondary" id="password-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Captcha --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-shield-alt me-2 auth-icon-primary"></i>Security Verification
                                            </label>
                                            <div class="d-flex align-items-center">
                                                <div class="captcha-container me-3" id="captcha-display">
                                                    <!-- Captcha will be generated here -->
                                                </div>
                                                <button type="button" class="btn btn-outline-secondary me-3" data-action="refresh-captcha" title="Refresh Captcha">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <input type="text" id="captcha" name="captcha" class="form-control form-control-lg border-2 auth-captcha-input"
                                                       placeholder="Enter captcha" required>
                                            </div>
                                            <input type="hidden" id="captcha-answer" name="captcha_answer">
                                        </div>

                                        {{-- Remember Me and Forgot Password --}}
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="form-check form-check-info text-left mb-0">
                                                <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                                                <label class="font-weight-normal text-dark mb-0" for="remember">
                                                    Remember for 14 days
                                                </label>
                                            </div>
                                            <a href="{{ route('password.request') }}" class="text-xs font-weight-bold ms-auto auth-icon-primary">Forgot password?</a>
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg w-100 shadow-lg text-white fw-bold auth-submit-btn">
                                                <i class="fas fa-sign-in-alt me-2"></i>
                                                Sign In
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-3 px-4 bg-light">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="text-muted me-2">Don't have an account?</span>
                                        <a href="{{ route('sign-up') }}" class="btn btn-sm text-white auth-signup-btn">
                                            <i class="fas fa-user-plus me-1"></i>Sign Up
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1 auth-icon-primary"></i>
                                            Your data is protected with enterprise-grade security
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8 auth-bg-image">
                                    <div
                                        class="blur mt-12 p-4 text-center border border-white border-radius-md position-absolute fixed-bottom m-4">
                                        <h2 class="mt-3 text-dark font-weight-bold">Join our global community of Islamic Finance learners.</h2>
                                        <h6 class="text-dark text-sm mt-5">Copyright © {{ date('Y') }} IEC DawateIslami. All rights reserved.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/extracted/auth.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/extracted/account-signin.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/password-toggle.js') }}"></script>
        <script src="{{ asset('assets/js/extracted/auth.js') }}"></script>
        <script src="{{ asset('assets/js/extracted/account-signin.js') }}"></script>
        <script src="{{ asset('assets/js/extracted/csp-test.js') }}"></script>
    @endpush
</x-app-layout>
