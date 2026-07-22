@extends('ghousiatraders.layouts.app')

@section('title', 'Contact Us — Ghousia Traders')

@section('content')
    @php
        $footer = \App\Models\FooterSetting::getSettings();
    @endphp

    <main>
        <!-- Banner Section -->
        <section class="contact-hero">
            <div class="container">
                <div class="contact-hero-card">
                    <div class="contact-hero-content">
                        @include('ghousiatraders.components.breadcrumb', [
                            'current' => 'Contact Us'
                        ])
                        <h1>Contact Us</h1>
                        <p>We're here to help! Reach out to us for any questions, support, or feedback.</p>
                        
                        <div class="contact-highlights-bar">
                            <div class="contact-highlight-item">
                                <div class="highlight-icon-circle">
                                    <i data-lucide="phone-call"></i>
                                </div>
                                <div>
                                    <h4>Quick Support</h4>
                                    <p>We're here to help</p>
                                </div>
                            </div>
                            <div class="contact-highlight-item">
                                <div class="highlight-icon-circle">
                                    <i data-lucide="shield-check"></i>
                                </div>
                                <div>
                                    <h4>Trusted Service</h4>
                                    <p>100% Customer Satisfaction</p>
                                </div>
                            </div>
                            <div class="contact-highlight-item">
                                <div class="highlight-icon-circle">
                                    <i data-lucide="refresh-cw"></i>
                                </div>
                                <div>
                                    <h4>Fast Response</h4>
                                    <p>We reply within 24 hours</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-hero-image">
                        <img src="{{ asset('ghousiatraders/assets/contact-hero.png') }}" alt="Contact Us Banner" style="max-height: 280px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Form & Info Section -->
        <section class="contact-section-container">
            <div class="contact-grid">
                <!-- Left Panel: Get In Touch -->
                <div class="contact-info-panel">
                    <h3>Get In Touch</h3>
                    
                    <div class="info-block">
                        <div class="info-icon-wrapper">
                            <i data-lucide="phone"></i>
                        </div>
                        <div class="info-text">
                            <h4>Call Us</h4>
                            <p style="font-weight: 600;">{{ $footer->phone ?? '0321-1234567' }}</p>
                            <p>Mon - Sat: 10:00 AM - 8:00 PM</p>
                        </div>
                    </div>

                    <div class="info-block">
                        <div class="info-icon-wrapper">
                            <i data-lucide="mail"></i>
                        </div>
                        <div class="info-text">
                            <h4>Email Us</h4>
                            <p style="font-weight: 600;">{{ $footer->email ?? 'info@ghousiatraders.com' }}</p>
                            <p>We reply within 24 hours</p>
                        </div>
                    </div>

                    <div class="info-block">
                        <div class="info-icon-wrapper">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="info-text">
                            <h4>Visit Us</h4>
                            <p>{{ $footer->address ?? 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan' }}</p>
                        </div>
                    </div>

                    <div class="info-block">
                        <div class="info-icon-wrapper">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="info-text">
                            <h4>Working Hours</h4>
                            <p>Monday - Saturday: 10:00 AM - 8:00 PM</p>
                            <p>Sunday: Closed</p>
                        </div>
                    </div>

                    <div class="contact-socials-wrapper">
                        <h4>Follow Us</h4>
                        <div class="social-icons-row">
                            <a href="{{ $footer->facebook_url ?? '#' }}" class="social-btn facebook" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i data-lucide="facebook"></i></a>
                            <a href="{{ $footer->instagram_url ?? '#' }}" class="social-btn instagram" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i data-lucide="instagram"></i></a>
                            <a href="{{ $footer->tiktok_url ?? '#' }}" class="social-btn whatsapp" aria-label="TikTok" target="_blank" rel="noopener noreferrer"><i data-lucide="message-circle"></i></a>
                            <a href="{{ $footer->youtube_url ?? '#' }}" class="social-btn youtube" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><i data-lucide="youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Send Message Form -->
                <div class="contact-form-panel">
                    <h3>Send Us a Message</h3>
                    <form id="contactForm" class="checkout-form" onsubmit="event.preventDefault(); alert('Thank you for contacting us! We will get back to you shortly.'); this.reset();">
                        <div class="form-grid grid-2col" style="margin-bottom: 20px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="contactName">Your Name <span style="color: #E74C3C;">*</span></label>
                                <input type="text" id="contactName" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="contactEmail">Your Email <span style="color: #E74C3C;">*</span></label>
                                <input type="email" id="contactEmail" placeholder="Enter your email" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="contactSubject">Subject <span style="color: #E74C3C;">*</span></label>
                            <input type="text" id="contactSubject" placeholder="Enter subject" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 24px;">
                            <label for="contactMessage">Message <span style="color: #E74C3C;">*</span></label>
                            <textarea id="contactMessage" placeholder="Type your message here..." rows="6" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-family: inherit; resize: vertical;" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; width: auto; min-width: 180px;">
                            <i data-lucide="send" style="width: 16px; height: 16px;"></i> Send Message
                        </button>

                        <div class="form-privacy-disclaimer">
                            <i data-lucide="shield-check"></i>
                            <p>Your information is safe with us. We never share your details.</p>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Horizontal Feature Bar -->
        <section class="feature-bar-section">
            <div class="container feature-bar-container">
                <div class="feature-bar-grid">
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="award"></i></div>
                        <div class="f-bar-content">
                            <h4>100% Genuine Products</h4>
                            <p>Original and high quality</p>
                        </div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="truck"></i></div>
                        <div class="f-bar-content">
                            <h4>Fast Delivery</h4>
                            <p>Across Pakistan</p>
                        </div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="rotate-ccw"></i></div>
                        <div class="f-bar-content">
                            <h4>Easy Returns</h4>
                            <p>Within 7 Days</p>
                        </div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="shield-check"></i></div>
                        <div class="f-bar-content">
                            <h4>Secure Payments</h4>
                            <p>Safe & reliable</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
