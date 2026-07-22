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
                    <a href="{{ $footer->facebook_url ?? '#' }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i data-lucide="facebook"></i></a>
                    <a href="{{ $footer->instagram_url ?? '#' }}" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i data-lucide="instagram"></i></a>
                    <a href="{{ $footer->youtube_url ?? '#' }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><i data-lucide="youtube"></i></a>
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
                    <li><a href="{{ route('polani.returns') }}">Returns & Exchanges</a></li>
                    <li><a href="{{ route('polani.shipping') }}">Shipping Policy</a></li>
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
            <div class="payments-accepted">
                <span class="payment-label">We Accept</span>
                <div class="payment-logos">
                    <!-- Visa -->
                    <svg class="pay-logo-img" viewBox="0 0 75 50" xmlns="http://www.w3.org/2000/svg" style="width:50px; height:30px; vertical-align:middle;">
                        <rect width="75" height="50" rx="4" fill="#FFFFFF" stroke="#D5D8DC" stroke-width="1"/>
                        <path d="M12 18 L18 34 H23 L29 18 H24 L21.5 29.5 L19 18 H12 Z" fill="#1A1F71"/>
                        <path d="M30 18 H34 V34 H30 Z" fill="#1A1F71"/>
                        <path d="M43.5 19.5 C42 18.5 40 18 38 18 C34 18 31.5 20 31.5 23.5 C31.5 28 37.5 28 37.5 30.5 C37.5 31.5 35.5 32 34 32 C32 32 30 31 29 30.5 L28 33.5 C29.5 34.5 32 35 34 35 C38.5 35 41.5 33 41.5 29.5 C41.5 25 35.5 24.5 35.5 22.5 C35.5 21.5 37 21 38.5 21 C40.5 21 42.5 21.5 43.5 22.5 L44.5 19.5 Z" fill="#1A1F71"/>
                        <path d="M52.5 18 H49 L42.5 34 H47.5 L48.5 31.5 H54.5 L55 34 H60 L56 18 H52.5 Z M50 28 L51.5 23 L53.5 28 H50 Z" fill="#1A1F71"/>
                        <path d="M12 18 L15 26 L16 18 Z" fill="#F7B600"/>
                    </svg>
                    <!-- Mastercard -->
                    <svg class="pay-logo-img" viewBox="0 0 75 50" xmlns="http://www.w3.org/2000/svg" style="width:50px; height:30px; vertical-align:middle;">
                        <rect width="75" height="50" rx="4" fill="#FFFFFF" stroke="#D5D8DC" stroke-width="1"/>
                        <circle cx="31" cy="25" r="14" fill="#EB001B" opacity="0.9"/>
                        <circle cx="44" cy="25" r="14" fill="#F79E1B" opacity="0.9"/>
                    </svg>
                    <!-- Meezan Bank -->
                    <img class="pay-logo-img" src="{{ asset('ghousiatraders/assets/meezan-logo.png') }}" alt="Meezan Bank" style="width:50px; height:30px; object-fit:contain; vertical-align:middle;">
                    <!-- Easypaisa -->
                    <img class="pay-logo-img" src="{{ asset('ghousiatraders/assets/easypaisa-logo.png') }}" alt="Easypaisa" style="width:50px; height:30px; object-fit:contain; vertical-align:middle;">
                    <!-- JazzCash -->
                    <img class="pay-logo-img" src="{{ asset('ghousiatraders/assets/jazzcash-logo.png') }}" alt="JazzCash" style="width:50px; height:30px; object-fit:contain; vertical-align:middle;">
                </div>
            </div>
        </div>
    </div>
</footer>
