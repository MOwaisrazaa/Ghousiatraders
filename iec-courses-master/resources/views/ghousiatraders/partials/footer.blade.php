@php
    $footer = \App\Models\FooterSetting::getSettings();
@endphp

<!-- 9. Footer Section -->
<footer class="site-footer" id="contact">
    <div class="footer-container">
        <div class="footer-grid">
            
            <!-- Col 1: Brand Info -->
            <div class="footer-col brand-col">
                <a href="{{ route('home') }}" class="logo brand-logo-link">
                    <svg viewBox="0 0 320 80" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <!-- Premium Gold Metallic Gradient -->
                            <linearGradient id="goldGradFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FCE0AD" />
                                <stop offset="20%" stop-color="#DFAC4D" />
                                <stop offset="40%" stop-color="#C68B29" />
                                <stop offset="60%" stop-color="#FDF1D6" />
                                <stop offset="80%" stop-color="#DFAC4D" />
                                <stop offset="100%" stop-color="#8E5B10" />
                            </linearGradient>
                            <!-- Dark Gold/Bronze for 3D extrusion sides -->
                            <linearGradient id="bronzeGradFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#5A3E1B" />
                                <stop offset="50%" stop-color="#402B12" />
                                <stop offset="100%" stop-color="#2D1D0B" />
                            </linearGradient>
                            <filter id="shadowFooter" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="2" dy="3" stdDeviation="1.5" flood-color="#000000" flood-opacity="0.65" />
                            </filter>
                        </defs>
                        
                        <!-- Calligraphic Cursive 3D Text "Ghousia Traders" -->
                        <g filter="url(#shadowFooter)">
                            <text x="160" y="54" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGradFooter)" text-anchor="middle">Ghousia Traders</text>
                            <text x="160" y="53" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGradFooter)" text-anchor="middle">Ghousia Traders</text>
                            <text x="160" y="52" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGradFooter)" text-anchor="middle">Ghousia Traders</text>
                            <text x="160" y="51" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#goldGradFooter)" text-anchor="middle">Ghousia Traders</text>
                        </g>
                    </svg>
                </a>
                <p class="brand-desc">
                    {{ $footer->brand_description ?? 'Your trusted destination for premium baby care products and exciting ride-on toys. Quality you can trust, happiness they deserve.' }}
                </p>
                <div class="social-links">
                    <a href="{{ $footer->facebook_url ?? '#' }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                        <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="{{ $footer->instagram_url ?? '#' }}" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                        <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="{{ $footer->youtube_url ?? '#' }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                        <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                            <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17z"/>
                            <polygon points="10 15 15 12 10 9"/>
                        </svg>
                    </a>
                    <a href="{{ $footer->tiktok_url ?? '#' }}" aria-label="TikTok" target="_blank" rel="noopener noreferrer">
                        <svg class="tiktok-svg" viewBox="0 0 24 24" fill="currentColor" style="width: 18px; height: 18px; display: block;"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.89-.6-4.09-1.5-1.06-.8-1.8-1.95-2.22-3.23v8.52c0 2.45-.76 4.95-2.58 6.56-2.12 1.88-5.3 2.24-7.79 1.12-2.52-1.12-4.22-3.7-4.14-6.48.06-2.92 2.05-5.63 4.95-6.19 1.01-.2 2.07-.1 3.06.2v4.09c-.83-.26-1.74-.32-2.56-.05-1.14.37-2.01 1.47-2.04 2.68-.05 1.5.95 2.9 2.43 3.19 1.55.3 3.2-.55 3.59-2.05.07-.28.09-.57.09-.86V.02z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="footer-col">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('polani.babycare') }}">Baby Care</a></li>
                    <li><a href="{{ route('polani.bikes') }}">B/O Bikes</a></li>
                    <li><a href="{{ route('polani.cars') }}">B/O Cars</a></li>
                    <li><a href="{{ route('polani.collection') }}">Shop</a></li>
                    <li><a href="{{ route('polani.about') }}">About Us</a></li>
                    <li><a href="{{ route('polani.contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <!-- Col 3: Customer Service -->
            <div class="footer-col">
                <h4 class="footer-title">Customer Service</h4>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('users.profile') }}">My Account</a></li>
                    @else
                        <li><a href="{{ route('sign-in') }}">Login / Register</a></li>
                    @endauth
                    <li><a href="{{ route('polani.track-order') }}">Order Tracking</a></li>
                    <li><a href="{{ route('polani.wishlist') }}">Wishlist</a></li>
                    <li><a href="{{ route('polani.shipping-returns') }}">Return & Shipping Policy</a></li>
                    <li><a href="{{ route('polani.privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('polani.terms') }}">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact Us Info -->
            <div class="footer-col contact-col">
                <h4 class="footer-title">Contact Us</h4>
                <ul class="contact-info-list">
                    <li>
                        <i data-lucide="phone"></i>
                        <span>{{ $footer->phone ?? '0321-1234567' }}</span>
                    </li>
                    <li>
                        <i data-lucide="mail"></i>
                        <span>{{ $footer->email ?? 'info@ghousiatraders.com' }}</span>
                    </li>
                    <li>
                        <i data-lucide="map-pin"></i>
                        <span>{{ $footer->address ?? 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan' }}</span>
                    </li>
                    <li>
                        <i data-lucide="clock"></i>
                        <span>Mon - Sat: 10:00 AM - 8:00 PM <br>Sunday: Closed</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom Copyright & Payments -->
        <div class="footer-bottom">
            <div class="copyright">
                <p>&copy; {{ date('Y') }} {{ $footer->copyright_name ?? 'Ghousia Traders' }}. {{ $footer->footer_text ?? 'All Rights Reserved.' }}</p>
            </div>
            <div class="powered-by">
                <p style="margin: 0; font-size: 0.92rem; color: rgba(255, 255, 255, 0.75); font-weight: 500;">
                    Powered by <a href="https://snipezon.com" target="_blank" rel="noopener noreferrer" style="color: #DFAC4D; text-decoration: none; font-weight: 700; transition: color 0.3s ease;">snipezon.com</a>
                </p>
            </div>
        </div>
    </div>
</footer>
