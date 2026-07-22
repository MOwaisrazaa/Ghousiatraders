@extends('ghousiatraders.layouts.app')

@section('title', 'Sign In — Ghousia Traders')

@push('head')
    <link rel="stylesheet" href="{{ asset('ghousiatraders/assets/css/signin.css') }}">
@endpush

@section('content')
    <main>
        <!-- Sign-In Card Section -->
        <section class="signin-section">
            <div class="signin-card">
                <!-- Left Side - Visual Welcome Panel -->
                <div class="signin-visual-panel">
                    <img class="signin-hero-bg" src="{{ asset('ghousiatraders/assets/images/signin-hero.jpg') }}" alt="Luxury baby nursery background with white ride-on car and teddy bear">
                    <div class="signin-visual-overlay"></div>
                    
                    <div class="signin-visual-content">
                        <!-- Tiny Leaf Flourish Icon -->
                        <div class="signin-flourish">
                            <i data-lucide="leaf"></i>
                        </div>
                        
                        <h2 class="welcome-heading">Welcome Back</h2>
                        <p class="welcome-subtext">Sign in to continue shopping for your little one.</p>
                        
                        <!-- Benefits list -->
                        <div class="benefits-list">
                            <!-- Benefit 1 -->
                            <div class="benefit-item">
                                <div class="benefit-icon-container">
                                    <i data-lucide="shield-check"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Secure Checkout</h4>
                                    <p class="benefit-desc">Your data is always protected</p>
                                </div>
                            </div>
                            <!-- Benefit 2 -->
                            <div class="benefit-item">
                                <div class="benefit-icon-container">
                                    <i data-lucide="truck"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Fast Delivery</h4>
                                    <p class="benefit-desc">Quick & reliable to your doorstep</p>
                                </div>
                            </div>
                            <!-- Benefit 3 -->
                            <div class="benefit-item">
                                <div class="benefit-icon-container">
                                    <i data-lucide="heart"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4 class="benefit-title">Family-Focused Care</h4>
                                    <p class="benefit-desc">Trusted by parents, loved by kids</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Sign-In Form -->
                <div class="signin-form-panel">
                    <div class="signin-breadcrumb">
                        <a href="{{ route('home') }}">Home</a> &gt; <a href="#">Account</a> &gt; <span>Sign In</span>
                    </div>
                    
                    <h1 class="form-heading">Sign In</h1>
                    <p class="form-subtext">Welcome back! Please sign in to your account.</p>
                    
                    <!-- Login Form -->
                    <form class="signin-form" method="POST" action="{{ url('/sign-in') }}">
                        @csrf

                        <!-- Status & Warning alerts -->
                        @if (session('status'))
                            <div style="color: #0D9488; margin-bottom: 15px; font-size: 0.9rem; font-weight: 500;">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('warning'))
                            <div style="color: #D97706; margin-bottom: 15px; font-size: 0.9rem; font-weight: 500;">
                                {{ session('warning') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div style="color: #E11D48; margin-bottom: 15px; font-size: 0.9rem; font-weight: 500;">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <!-- Email Input -->
                        <div class="input-group">
                            <label class="input-label" for="signinEmail">Email Address</label>
                            <div class="input-wrapper">
                                <i data-lucide="mail" class="input-icon-left"></i>
                                <input type="email" name="email" id="signinEmail" class="form-input" placeholder="Enter your email address" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Password Input -->
                        <div class="input-group">
                            <label class="input-label" for="signinPassword">Password</label>
                            <div class="input-wrapper">
                                <i data-lucide="lock" class="input-icon-left"></i>
                                <input type="password" name="password" id="signinPassword" class="form-input" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle Password Visibility">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Options (Remember me & Forgot Password) -->
                        <div class="form-options">
                            <label class="remember-me-container">
                                <input type="checkbox" name="remember" value="1" id="rememberMe" class="remember-me-checkbox">
                                Remember me
                            </label>
                            <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot Password?</a>
                        </div>
                        
                        <!-- Sign In Button -->
                        <button type="submit" class="btn-signin">Sign In</button>
                        
                        <!-- Social Login Divider -->
                        <div class="form-divider">or continue with</div>
                        
                        <!-- Social login buttons -->
                        <div class="social-container">
                            <!-- Google -->
                            <a href="{{ route('google.redirect') }}" class="btn-social google-btn" style="text-decoration: none; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <path fill="#EA4335" d="M12 5.04c1.66 0 3.2.57 4.38 1.69l3.27-3.27C17.67 1.53 14.98 0 12 0 7.35 0 3.37 2.67 1.43 6.56l3.86 3C6.26 6.84 8.91 5.04 12 5.04z"/>
                                    <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.36H12v4.51h6.46c-.29 1.48-1.14 2.73-2.4 3.58l3.76 2.92c2.2-2.03 3.67-5.01 3.67-8.65z"/>
                                    <path fill="#FBBC05" d="M5.29 14.44a7.136 7.136 0 0 1 0-4.88l-3.86-3a11.967 11.967 0 0 0 0 10.88l3.86-3z"/>
                                    <path fill="#34A853" d="M12 24c3.24 0 5.97-1.07 7.96-2.91l-3.76-2.92c-1.1.74-2.5 1.18-4.2 1.18-3.09 0-5.74-1.8-6.71-4.52l-3.86 3C3.37 21.33 7.35 24 12 24z"/>
                                </svg>
                                <span>Continue with Google</span>
                            </a>
                        </div>
                        
                        <!-- Bottom Links -->
                        <p class="signin-footer-text">Don’t have an account? <a href="{{ route('sign-up') }}">Create Account</a></p>
                        <p class="signin-terms-text">By signing in, you agree to our <a href="#">Terms</a> &amp; <a href="#">Privacy Policy</a>.</p>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('ghousiatraders/assets/js/signin.js') }}"></script>
@endpush
