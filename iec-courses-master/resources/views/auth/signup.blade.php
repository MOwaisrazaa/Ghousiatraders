@extends('ghousiatraders.layouts.app')

@section('title', 'Sign Up — Ghousia Traders')

@push('head')
    <link rel="stylesheet" href="{{ asset('ghousiatraders/assets/css/signup.css') }}">
@endpush

@section('content')
    @php
        $countriesJson = [];
        try {
            $countriesJson = json_decode(file_get_contents(public_path('assets/js/countrycode.json')), true) ?: [];
        } catch (\Throwable $e) {
            $countriesJson = [];
        }
    @endphp

    <main>
        <!-- Signup Card Section -->
        <section class="signup-section">
            <div class="signup-card">
                
                <!-- Left Visual Panel -->
                <div class="signup-visual-panel">
                    <img class="signup-hero-bg" src="{{ asset('ghousiatraders/assets/images/signup-products.png') }}" alt="Premium baby care products background">
                    <div class="signup-visual-overlay"></div>
                    
                    <div class="signup-visual-content">
                        <div class="signup-welcome-header">
                            <span class="eyebrow-text">Join Ghousia Traders</span>
                            <hr class="eyebrow-line">
                            <h2 class="signup-visual-heading">Create Your<br>Account</h2>
                            <p class="signup-visual-desc">Sign up and enjoy a better shopping experience with exclusive benefits.</p>
                            <hr class="decorative-line-short">
                        </div>

                        <!-- Account benefits list -->
                        <div class="signup-benefits-list">
                            <!-- Benefit 1 -->
                            <div class="signup-benefit-item">
                                <div class="signup-benefit-icon">
                                    <i data-lucide="tag"></i>
                                </div>
                                <div class="signup-benefit-info">
                                    <h4 class="signup-benefit-title">Exclusive Offers</h4>
                                    <p class="signup-benefit-desc">Get access to special discounts and member only deals.</p>
                                </div>
                            </div>
                            <!-- Benefit 2 -->
                            <div class="signup-benefit-item">
                                <div class="signup-benefit-icon">
                                    <i data-lucide="package"></i>
                                </div>
                                <div class="signup-benefit-info">
                                    <h4 class="signup-benefit-title">Faster Checkout</h4>
                                    <p class="signup-benefit-desc">Save your details and enjoy one click checkout.</p>
                                </div>
                            </div>
                            <!-- Benefit 3 -->
                            <div class="signup-benefit-item">
                                <div class="signup-benefit-icon">
                                    <i data-lucide="heart"></i>
                                </div>
                                <div class="signup-benefit-info">
                                    <h4 class="signup-benefit-title">Wishlist & Save</h4>
                                    <p class="signup-benefit-desc">Save your favorite products and buy them later.</p>
                                </div>
                            </div>
                            <!-- Benefit 4 -->
                            <div class="signup-benefit-item">
                                <div class="signup-benefit-icon">
                                    <i data-lucide="clipboard-list"></i>
                                </div>
                                <div class="signup-benefit-info">
                                    <h4 class="signup-benefit-title">Order Tracking</h4>
                                    <p class="signup-benefit-desc">Track your orders and stay updated every step of the way.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Form Panel -->
                <div class="signup-form-panel">
                    <div class="signup-breadcrumb">
                        <a href="{{ route('home') }}">Home</a> &gt; <a href="{{ route('sign-in') }}">Account</a> &gt; <span>Sign Up</span>
                    </div>

                    <h1 class="form-heading">Sign Up</h1>
                    <hr class="heading-underline">
                    <p class="form-subtext">Already have an account? <a href="{{ route('sign-in') }}">Sign in</a></p>

                    <!-- Registration Form -->
                    <form class="signup-form" id="signupForm" method="POST" action="{{ url('/sign-up') }}">
                        @csrf

                        @if (session('message'))
                            <div style="color: #D97706; margin-bottom: 15px; font-size: 0.9rem; font-weight: 500; grid-column: span 2;">
                                {{ session('message') }}
                            </div>
                        @endif

                        <!-- Full Name -->
                        <div class="input-group grid-col-full">
                            <label class="input-label" for="name">Full Name<span class="required-asterisk">*</span></label>
                            <div class="input-wrapper">
                                <i data-lucide="user" class="input-icon-left"></i>
                                <input type="text" name="name" id="name" class="form-input" placeholder="Enter your full name" value="{{ old('name') }}" required>
                            </div>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                            @error('name')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="input-group grid-col-full">
                            <label class="input-label" for="emailAddress">Email Address<span class="required-asterisk">*</span></label>
                            <div class="input-wrapper">
                                <i data-lucide="mail" class="input-icon-left"></i>
                                <input type="email" name="email" id="emailAddress" class="form-input" placeholder="Enter your email address" value="{{ old('email') }}" required>
                            </div>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                            @error('email')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Country Selection -->
                        <div class="input-group grid-col-half">
                            <label class="input-label" for="country">Country<span class="required-asterisk">*</span></label>
                            <div class="input-wrapper">
                                <i data-lucide="globe" class="input-icon-left" style="z-index: 10;"></i>
                                <select name="country" id="country" class="form-input" style="padding-left: 40px; height: 100%; border: 1px solid var(--border-color); border-radius: var(--radius-md);" required>
                                    @foreach($countriesJson as $countryItem)
                                        <option value="{{ $countryItem['code'] }}" {{ old('country', 'PK') == $countryItem['code'] ? 'selected' : '' }}>
                                            {{ $countryItem['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                            @error('country')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="input-group grid-col-half">
                            <label class="input-label" for="phone">Phone Number<span class="required-asterisk">*</span></label>
                            <div class="input-wrapper">
                                <i data-lucide="phone" class="input-icon-left"></i>
                                <input type="text" name="phone" id="phone" class="form-input" placeholder="03XX-XXXXXXX" value="{{ old('phone') }}" required>
                            </div>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                            @error('phone')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="input-group grid-col-half">
                            <label class="input-label" for="password">Password<span class="required-asterisk">*</span></label>
                            <div class="input-wrapper">
                                <i data-lucide="lock" class="input-icon-left"></i>
                                <input type="password" name="password" id="password" class="form-input" placeholder="Create a password" required>
                                <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle Password Visibility">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                            @error('password')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-group grid-col-half">
                            <label class="input-label" for="confirmPassword">Confirm Password<span class="required-asterisk">*</span></label>
                            <div class="input-wrapper">
                                <i data-lucide="lock" class="input-icon-left"></i>
                                <input type="password" name="password_confirmation" id="confirmPassword" class="form-input" placeholder="Confirm your password" required>
                                <button type="button" class="password-toggle" id="confirmPasswordToggle" aria-label="Toggle Confirm Password Visibility">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                        </div>

                        <!-- Terms & Conditions Checkbox -->
                        <div class="input-group grid-col-full">
                            <label class="terms-container">
                                <input type="checkbox" name="terms" value="1" id="termsAgreement" class="terms-checkbox" {{ old('terms') ? 'checked' : '' }} required>
                                <span>I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></span>
                            </label>
                            <div class="error-msg" style="display: none; color: #E11D48; font-size: 0.8rem; margin-top: 5px;"></div>
                            @error('terms')
                                <div class="error-msg" style="display: block; color: #E11D48; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="grid-col-full" style="margin-top: 10px;">
                            <button type="submit" id="signupSubmitBtn" class="btn-signup-submit">
                                <i data-lucide="user-plus"></i>
                                <span>Create Account</span>
                            </button>
                        </div>

                        <!-- Social Sign-up Divider -->
                        <div class="grid-col-full social-divider">
                            or sign up with
                        </div>

                        <!-- Social Sign-up Buttons -->
                        <div class="grid-col-full social-signup-container">
                            <a href="{{ route('google.redirect') }}" class="btn-social-signup" style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <path fill="#EA4335" d="M12 5.04c1.66 0 3.2.57 4.38 1.69l3.27-3.27C17.67 1.53 14.98 0 12 0 7.35 0 3.37 2.67 1.43 6.56l3.86 3C6.26 6.84 8.91 5.04 12 5.04z"/>
                                    <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.36H12v4.51h6.46c-.29 1.48-1.14 2.73-2.4 3.58l3.76 2.92c2.2-2.03 3.67-5.01 3.67-8.65z"/>
                                    <path fill="#FBBC05" d="M5.29 14.44a7.136 7.136 0 0 1 0-4.88l-3.86-3a11.967 11.967 0 0 0 0 10.88l3.86-3z"/>
                                    <path fill="#34A853" d="M12 24c3.24 0 5.97-1.07 7.96-2.91l-3.76-2.92c-1.1.74-2.5 1.18-4.2 1.18-3.09 0-5.74-1.8-6.71-4.52l-3.86 3C3.37 21.33 7.35 24 12 24z"/>
                                </svg>
                                <span>Google</span>
                            </a>
                        </div>

                        <!-- Security Shield Note -->
                        <div class="grid-col-full signup-security-note">
                            <i data-lucide="shield-check"></i>
                            <span>Your information is safe with us. We never share your details.</span>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('ghousiatraders/assets/js/signup.js') }}"></script>
@endpush
