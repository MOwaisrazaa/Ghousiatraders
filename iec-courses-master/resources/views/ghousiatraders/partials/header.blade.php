<!-- 1. Top Utility Bar -->
<div class="top-bar">
    <div class="top-bar-container">
        <div class="top-bar-item">
            <i data-lucide="truck" class="top-bar-icon"></i>
            <span>Free Shipping on Orders Over PKR 5,000</span>
        </div>
        <div class="top-bar-item">
            <i data-lucide="shield-check" class="top-bar-icon"></i>
            <span>100% Genuine & Premium Quality</span>
        </div>
        <div class="top-bar-item">
            <i data-lucide="phone-call" class="top-bar-icon"></i>
            <span>Customer Support: 0321-1234567</span>
        </div>
    </div>
</div>

<!-- 2. Main Header / Navigation -->
<header class="main-header">
    <!-- Line 1 (Top Line): Compact Search Bar (Centered) + Track Order (Right) -->
    <div class="header-sub-bar">
        <div class="header-container header-sub-container">
            <!-- Search Input Form -->
            <form class="header-search-form" action="{{ route('polani.collection') }}" method="GET">
                <div class="header-search-input-wrapper">
                    <i data-lucide="search" class="search-input-icon"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search baby care products, ride-on bikes, toy cars..." class="header-search-input" id="headerSearchInput">
                    <button type="submit" class="header-search-submit-btn">Search</button>
                </div>
            </form>

            <!-- Track Order Button -->
            <a href="{{ route('polani.track-order') }}" class="track-order-link {{ request()->routeIs('polani.track-order') ? 'active' : '' }}">
                <i data-lucide="truck"></i>
                <span>Track Order</span>
            </a>
        </div>
    </div>

    <!-- Line 2 (Bottom Line): Main Navbar Container (Logo + Nav Menu + User Icons) -->
    <div class="header-container">
        <!-- Mobile Menu Toggle (Left) -->
        <button class="action-btn mobile-menu-toggle" id="menuToggle" aria-label="Toggle Menu">
            <i data-lucide="menu"></i>
        </button>

        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="logo brand-logo-link">
            <svg viewBox="0 0 320 80" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- Premium Gold Metallic Gradient -->
                    <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFF3D6" />
                        <stop offset="20%" stop-color="#F5D393" />
                        <stop offset="40%" stop-color="#E5B252" />
                        <stop offset="60%" stop-color="#FFF7E6" />
                        <stop offset="80%" stop-color="#F5D393" />
                        <stop offset="100%" stop-color="#C68C2E" />
                    </linearGradient>
                    <!-- Warm Radiant Gold/Bronze for 3D extrusion sides -->
                    <linearGradient id="bronzeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#9C7338" />
                        <stop offset="50%" stop-color="#7A5626" />
                        <stop offset="100%" stop-color="#5B3C14" />
                    </linearGradient>
                    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="2" dy="3" stdDeviation="1.5" flood-color="#000000" flood-opacity="0.65" />
                    </filter>
                </defs>
                
                <!-- Calligraphic Cursive 3D Text "Ghousia Traders" -->
                <g filter="url(#shadow)">
                    <!-- 3D Extrusion Layers (Dark Bronze) -->
                    <text x="160" y="54" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGrad)" text-anchor="middle">Ghousia Traders</text>
                    <text x="160" y="53" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGrad)" text-anchor="middle">Ghousia Traders</text>
                    <text x="160" y="52" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#bronzeGrad)" text-anchor="middle">Ghousia Traders</text>
                    
                    <!-- Front Face (Gold Gradient) -->
                    <text x="160" y="51" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="44" font-weight="bold" fill="url(#goldGrad)" text-anchor="middle">Ghousia Traders</text>
                </g>
            </svg>
        </a>

        <!-- Navigation Links -->
        <nav class="nav-menu" id="navMenu">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('polani.babycare') }}" class="nav-link {{ request()->routeIs('polani.babycare') ? 'active' : '' }}">Baby Care</a>
            <a href="{{ route('polani.bikes') }}" class="nav-link {{ request()->routeIs('polani.bikes') ? 'active' : '' }}">B/O Bikes</a>
            <a href="{{ route('polani.cars') }}" class="nav-link {{ request()->routeIs('polani.cars') ? 'active' : '' }}">B/O Cars</a>
            <a href="{{ route('polani.collection') }}" class="nav-link {{ request()->routeIs('polani.collection') ? 'active' : '' }}">Shop</a>
            <a href="{{ route('polani.about') }}" class="nav-link {{ request()->routeIs('polani.about') ? 'active' : '' }}">About Us</a>
            <a href="{{ route('polani.contact') }}" class="nav-link {{ request()->routeIs('polani.contact') ? 'active' : '' }}">Contact Us</a>
        </nav>

        <!-- Utility Actions -->
        <div class="header-actions">
            @auth
                <a href="{{ route('users.profile') }}" class="action-btn" aria-label="Account" title="Profile: {{ auth()->user()->name }}">
                    <i data-lucide="user"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="action-btn" aria-label="Logout" title="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-lucide="log-out"></i>
                </a>
            @else
                <a href="{{ route('sign-in') }}" class="action-btn {{ request()->routeIs('sign-in') ? 'active' : '' }}" aria-label="Account" title="Sign In">
                    <i data-lucide="user"></i>
                </a>
            @endauth
            
            <a href="{{ route('polani.wishlist') }}" class="action-btn badge-btn {{ request()->routeIs('polani.wishlist') ? 'active' : '' }}" aria-label="Wishlist" id="wishlistBtn">
                <i data-lucide="heart"></i>
                <span class="badge" id="wishlistCount">0</span>
            </a>
            <a href="{{ route('shopping-cart') }}" class="action-btn badge-btn {{ request()->routeIs('shopping-cart') ? 'active' : '' }}" aria-label="Cart" id="cartBtn">
                <i data-lucide="shopping-cart"></i>
                <span class="badge" id="cartCount" data-cart-badge>{{ $cartCount ?? 0 }}</span>
            </a>
        </div>
    </div>
</header>

<!-- Search Overlay -->
<div class="search-overlay" id="searchOverlay">
    <div class="search-bar">
        <form action="{{ route('polani.collection') }}" method="GET" style="width: 100%; display: flex; align-items: center;">
            <input type="text" name="q" placeholder="Search baby care products, ride-on cars..." id="searchInput">
            <button type="submit" style="display:none;"></button>
        </form>
        <button class="search-close" id="searchClose">&times;</button>
    </div>
</div>
