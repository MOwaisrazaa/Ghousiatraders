@php
    $footer = \App\Models\FooterSetting::getSettings();
    $fbUrl = store_setting('facebook_enabled', '1') == '1' ? store_setting('facebook_url') : null;
    $igUrl = store_setting('instagram_enabled', '1') == '1' ? store_setting('instagram_url') : null;
    $ytUrl = store_setting('youtube_enabled', '1') == '1' ? store_setting('youtube_url') : null;
    $ttUrl = store_setting('tiktok_enabled', '1') == '1' ? store_setting('tiktok_url') : null;
    $waUrl = store_setting('whatsapp_enabled', '1') == '1' ? store_setting('whatsapp_url') : null;
    $twUrl = store_setting('twitter_enabled', '0') == '1' ? store_setting('twitter_url') : null;
    $liUrl = store_setting('linkedin_enabled', '0') == '1' ? store_setting('linkedin_url') : null;
@endphp

<!-- 9. Footer Section -->
<footer class="site-footer" id="contact">
    <div class="footer-container">
        <div class="footer-grid">
            
            <!-- Col 1: Brand Info -->
            <div class="footer-col brand-col">
                <a href="{{ route('home') }}" class="logo brand-logo-link">
                    @if(store_setting('footer_logo') && file_exists(public_path(store_setting('footer_logo'))))
                        <img src="{{ asset(store_setting('footer_logo')) }}" alt="{{ store_setting('public_store_name', 'Ghousia Traders') }}" style="max-height: 50px; object-fit: contain;">
                    @else
                        <svg viewBox="0 0 320 80" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="goldGradFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FCE0AD" />
                                    <stop offset="20%" stop-color="#DFAC4D" />
                                    <stop offset="40%" stop-color="#C68B29" />
                                    <stop offset="60%" stop-color="#FDF1D6" />
                                    <stop offset="80%" stop-color="#DFAC4D" />
                                    <stop offset="100%" stop-color="#8E5B10" />
                                </linearGradient>
                                <linearGradient id="bronzeGradFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#5A3E1B" />
                                    <stop offset="50%" stop-color="#402B12" />
                                    <stop offset="100%" stop-color="#2D1D0B" />
                                </linearGradient>
                                <filter id="shadowFooter" x="-20%" y="-20%" width="140%" height="140%">
                                    <feDropShadow dx="2" dy="3" stdDeviation="1.5" flood-color="#000000" flood-opacity="0.65" />
                                </filter>
                            </defs>
                            <g filter="url(#shadowFooter)">
                                <text x="160" y="54" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGradFooter)" text-anchor="middle">{{ store_setting('public_store_name', 'Ghousia Traders') }}</text>
                                <text x="160" y="53" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGradFooter)" text-anchor="middle">{{ store_setting('public_store_name', 'Ghousia Traders') }}</text>
                                <text x="160" y="52" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGradFooter)" text-anchor="middle">{{ store_setting('public_store_name', 'Ghousia Traders') }}</text>
                                <text x="160" y="51" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#goldGradFooter)" text-anchor="middle">{{ store_setting('public_store_name', 'Ghousia Traders') }}</text>
                            </g>
                        </svg>
                    @endif
                </a>
                <p class="brand-desc">
                    {{ store_setting('footer_description', store_setting('short_store_description', 'Your trusted destination for premium baby care products and exciting ride-on toys. Quality you can trust, happiness they deserve.')) }}
                </p>
                <div class="social-links">
                    @if(!empty($fbUrl) && $fbUrl !== '#')
                        <a href="{{ $fbUrl }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                            <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                    @endif
                    @if(!empty($igUrl) && $igUrl !== '#')
                        <a href="{{ $igUrl }}" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                            <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                            </svg>
                        </a>
                    @endif
                    @if(!empty($ytUrl) && $ytUrl !== '#')
                        <a href="{{ $ytUrl }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                            <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                                <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17z"/>
                                <polygon points="10 15 15 12 10 9"/>
                            </svg>
                        </a>
                    @endif
                    @if(!empty($ttUrl) && $ttUrl !== '#')
                        <a href="{{ $ttUrl }}" aria-label="TikTok" target="_blank" rel="noopener noreferrer">
                            <svg class="tiktok-svg" viewBox="0 0 24 24" fill="currentColor" style="width: 18px; height: 18px; display: block;"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.89-.6-4.09-1.5-1.06-.8-1.8-1.95-2.22-3.23v8.52c0 2.45-.76 4.95-2.58 6.56-2.12 1.88-5.3 2.24-7.79 1.12-2.52-1.12-4.22-3.7-4.14-6.48.06-2.92 2.05-5.63 4.95-6.19 1.01-.2 2.07-.1 3.06.2v4.09c-.83-.26-1.74-.32-2.56-.05-1.14.37-2.01 1.47-2.04 2.68-.05 1.5.95 2.9 2.43 3.19 1.55.3 3.2-.55 3.59-2.05.07-.28.09-.57.09-.86V.02z"/></svg>
                        </a>
                    @endif
                    @if(!empty($waUrl) && $waUrl !== '#')
                        <a href="{{ str_contains($waUrl, 'http') ? $waUrl : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waUrl) }}" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer">
                            <svg class="social-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display: block;">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                            </svg>
                        </a>
                    @endif
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
                    <li><a href="{{ route('polani.track-order') }}">{{ store_setting('track_order_btn_label', 'Order Tracking') }}</a></li>
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
                        <span>{{ store_setting('footer_phone', store_setting('primary_phone', store_setting('store_phone', '0321-1234567'))) }}</span>
                    </li>
                    <li>
                        <i data-lucide="mail"></i>
                        <span>{{ store_setting('footer_email', store_setting('support_email', store_setting('store_email', 'info@ghousiatraders.com'))) }}</span>
                    </li>
                    <li>
                        <i data-lucide="map-pin"></i>
                        <span>{{ store_setting('footer_address', store_setting('address_line_1', 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan')) }}</span>
                    </li>
                    <li>
                        <i data-lucide="clock"></i>
                        <span>{!! nl2br(e(store_setting('footer_business_hours', store_setting('business_hours_custom_text', "Mon - Sat: 10:00 AM - 8:00 PM\nSunday: Closed")))) !!}</span>
                    </li>
                </ul>
            </div>

        </div>



        <!-- Bottom Copyright & Powered By -->
        <div class="footer-bottom">
            <div class="copyright">
                <p>&copy; {{ date('Y') }} {{ store_setting('copyright_name', 'Ghousia Traders') }}. {{ store_setting('copyright_text', 'All Rights Reserved.') }}</p>
            </div>
            <div class="powered-by">
                <p style="margin: 0; font-size: 0.92rem; color: rgba(255, 255, 255, 0.75); font-weight: 500;">
                    Powered by <a href="https://snipezon.com" target="_blank" rel="noopener noreferrer" style="color: #DFAC4D; text-decoration: none; font-weight: 700; transition: color 0.3s ease;">snipezon.com</a>
                </p>
            </div>
        </div>
    </div>
</footer>
