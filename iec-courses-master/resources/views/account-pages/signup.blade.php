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
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8 signup-bg-image">
                                    <div class="my-auto text-start max-width-350 ms-7">
                                        <h1 class="mt-3 text-white font-weight-bolder">Start your <br> new journey in Islamic Finance.</h1>
                                        <p class="text-white text-lg mt-4 mb-4">Explore our comprehensive Islamic Finance courses and gain valuable knowledge to excel in this field.</p>
                                    </div>
                                    <div class="text-start position-absolute fixed-bottom ms-7">
                                        <h6 class="text-white text-sm mb-5">Copyright © {{ now()->year }} IEC DawateIslami. All Rights Reserved.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column mx-auto">

                            <div class="card card-plain mt-8 shadow-lg">
                            <div class="card-header pb-0 text-center bg-transparent">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary mb-3">
                                    <i class="fas fa-home me-1"></i> Back to Home
                                </a>
                                @if (session('message'))
                                    <div class="alert alert-warning">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <h3 class="font-weight-black text-dark mb-2">Join Our Islamic Finance Course</h3>
                                <p class="text-muted mb-3">Welcome! Please provide your details to begin your journey in Islamic Finance.</p>

                                {{-- Compact Device Security Badge --}}
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="badge p-3 rounded-3 shadow-sm device-badge">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-shield-alt text-white me-2"></i>
                                            <span class="text-white fw-bold">Secure Registration</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-desktop text-white me-2" id="device-icon"></i>
                                            <small class="text-white">
                                                Registering from: <span id="current-device" class="fw-bold">Detecting...</span>
                                            </small>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-white opacity-8">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Device restrictions apply for security
                                            </small>
                                        </div>
                                    </div>
                                </div>


                            </div>
                                <div class="card-body px-4">
                                    <form role="form" method="POST" action="/signup" class="needs-validation" novalidate>
                                        @csrf

                                        {{-- Name Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-user me-2 form-icon"></i>Full Name
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control form-control-lg border-2"
                                                placeholder="Enter your full name" value="{{old("name")}}" required
                                                class="signup-input">
                                            @error('name')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Email Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-envelope me-2 form-icon"></i>Email Address
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control form-control-lg border-2"
                                                placeholder="Enter your email address" value="{{old("email")}}" required
                                                class="signup-input">
                                            @error('email')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Country Selection Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-globe me-2 form-icon"></i>Country
                                            </label>
                                            @php
                                                $countriesJson = json_decode(file_get_contents(public_path('assets/js/countrycode.json')), true);
                                                // Sort countries alphabetically but put Pakistan first
                                                usort($countriesJson, function($a, $b) {
                                                    if ($a['code'] === 'PK') return -1;
                                                    if ($b['code'] === 'PK') return 1;
                                                    return strcmp($a['name'], $b['name']);
                                                });
                                            @endphp
                                            <select id="country" name="country" class="form-control form-control-lg border-2" required>
                                                <option value="">Select your country</option>
                                                @foreach($countriesJson as $countryItem)
                                                    <option value="{{ $countryItem['code'] }}" 
                                                            data-dial-code="{{ $countryItem['dial_code'] }}"
                                                            {{ old('country', 'PK') == $countryItem['code'] ? 'selected' : '' }}>
                                                        {{ $countryItem['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Phone Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-phone me-2 form-icon"></i>Phone Number
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-2" id="country-code-display">+92</span>
                                                <input type="tel" id="phone" name="phone" class="form-control form-control-lg border-2"
                                                    placeholder="Enter your phone number" value="{{ old('phone') }}" required
                                                    class="signup-input">
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <span id="country-format-hint">Enter your phone number without the country code</span>
                                            </small>
                                            @error('phone')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Password Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-lock me-2 form-icon"></i>Password
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" id="password" name="password" class="form-control form-control-lg border-2"
                                                    placeholder="Create a password" required
                                                    class="password-input">
                                                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" type="button" data-field="password">
                                                    <i class="fas fa-eye password-eye-icon" id="password-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Confirm Password Field --}}
                                        <div class="mb-3">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-lock me-2 form-icon"></i>Confirm Password
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg border-2"
                                                    placeholder="Confirm your password" required
                                                    class="password-input">
                                                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle-btn" type="button" data-field="password_confirmation">
                                                    <i class="fas fa-eye password-eye-icon" id="password_confirmation-eye"></i>
                                                </button>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Terms and Conditions --}}
                                        <div class="form-check d-flex align-items-center mb-4">
                                            <input class="form-check-input me-3" type="checkbox" name="terms" id="terms" required>
                                            <label class="form-check-label text-dark" for="terms">
                                                I agree to the
                                                <a href="javascript:;" class="fw-bold text-decoration-underline"
                                                   data-bs-toggle="modal" data-bs-target="#termsModal"
                                                   class="terms-link">
                                                    Terms and Conditions
                                                </a>
                                                and understand the device security restrictions.
                                            </label>
                                        </div>

                                        {{-- Captcha --}}
                                        <div class="mb-4">
                                            <label class="form-label text-dark fw-bold">
                                                <i class="fas fa-shield-alt me-2 form-icon"></i>Security Verification
                                            </label>
                                            <div class="d-flex align-items-center">
                                                <div class="captcha-container me-3" id="captcha-display">
                                                    <!-- Captcha will be generated here -->
                                                </div>
                                                <button type="button" class="btn btn-outline-secondary me-3 captcha-refresh-btn" title="Refresh Captcha">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <input type="text" id="captcha" name="captcha" class="form-control form-control-lg border-2"
                                                       placeholder="Enter captcha" required
                                                       class="captcha-input">
                                            </div>
                                            <input type="hidden" id="captcha-answer" name="captcha_answer">
                                            @error('captcha')
                                                <div class="text-danger text-sm mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="text-center">
                                            <button type="button" class="btn btn-lg w-100 shadow-lg text-white fw-bold btn-gradient-primary"
                                                    data-bs-toggle="modal" data-bs-target="#deviceConfirmModal">
                                                <i class="fas fa-user-plus me-2"></i>
                                                Create My Account
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-3 px-4 bg-light">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="text-muted me-2">Already have an account?</span>
                                        <a href="{{ route('sign-in') }}" class="btn btn-sm text-white btn-gradient-primary">
                                            <i class="fas fa-sign-in-alt me-1"></i>Sign In
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1 security-icon"></i>
                                            Your data is protected with enterprise-grade security
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Device Confirmation Modal -->
    <div class="modal fade" id="deviceConfirmModal" tabindex="-1" aria-labelledby="deviceConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header text-white border-0 modal-header-gradient">
                    <h5 class="modal-title text-white" id="deviceConfirmModalLabel">
                        <i class="fas fa-shield-alt me-2"></i>
                        Security Confirmation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center device-icon-container">
                            <i class="fas fa-desktop device-icon" id="modal-device-icon"></i>
                        </div>
                        <h6 class="mt-3 mb-2">Registering from <span id="modal-device-type" class="fw-bold device-type-text">Detecting...</span></h6>
                        <p class="text-muted mb-0">This will be your primary device for account access</p>
                    </div>

                    <div class="alert alert-info border-0 bg-light">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info me-3 mt-1"></i>
                            <div>
                                <h6 class="text-dark mb-2">Security Features:</h6>
                                <ul class="list-unstyled mb-0 text-sm">
                                    <li class="mb-1">✓ Device type restriction for enhanced security</li>
                                    <li class="mb-1">✓ Maximum 3 IP addresses allowed</li>
                                    <li class="mb-1">✓ Course content protection</li>
                                    <li class="mb-0">✓ Account access monitoring</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-headset me-1"></i>
                            Need help? Contact our support team anytime
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Form
                    </button>
                    <button type="button" class="btn px-4 text-white btn-gradient-primary confirm-submit-btn">
                        <i class="fas fa-check me-1"></i>
                        Confirm & Register
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- English Terms and Conditions -->
                    <div>
                        <h6>English</h6>
                        <p>
                            By subscribing to our course, you agree that you are not allowed to record, distribute, or share any of our course materials or lectures. All course content is the intellectual property of IEC DawateIslami. Unauthorized recording, copying, or distribution of our course content is strictly prohibited and may result in immediate termination of your access.
                        </p>
                    </div>
                    <hr>
                    <!-- Urdu Terms and Conditions -->
                    <div>
                        <h6>اردو</h6>
                        <p>
                            ہمارے کورس کی سبسکرپشن کرتے وقت، آپ اس بات سے اتفاق کرتے ہیں کہ آپ ہمارے کسی بھی کورس کے مواد یا لیکچرز کو ریکارڈ، تقسیم یا شیئر نہیں کر سکتے۔ تمام کورس کا مواد IEC DawateIslami کی دانشورانہ ملکیت ہے۔ ہمارے کورس کے مواد کی غیر مجاز ریکارڈنگ، نقل یا تقسیم سختی سے منع ہے اور اس کے نتیجے میں آپ کی رسائی فوری طور پر ختم کی جا سکتی ہے۔
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/extracted/account-signup.css') }}">
@endpush

@push('scripts')
    {{-- Country code sync is now handled by account-signup.js --}}
@endpush


