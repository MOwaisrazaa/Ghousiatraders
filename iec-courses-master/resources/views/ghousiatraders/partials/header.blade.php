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
            <i data-lucide="headset" class="top-bar-icon"></i>
            <span>Customer Support: 0321-1234567</span>
        </div>
    </div>
</div>

<!-- 2. Main Header / Navigation -->
<header class="main-header">
    <div class="header-container main-header-row">
        <!-- Mobile Menu Toggle (Left on mobile, hidden on desktop) -->
        <button class="action-btn mobile-menu-toggle" id="menuToggle" aria-label="Toggle Menu">
            <i data-lucide="menu"></i>
        </button>

        <!-- Left Side: Brand Logo -->
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

        <!-- Center: Desktop Search Bar -->
        <div class="desktop-search-container">
            <form class="header-search-form" action="{{ route('polani.collection') }}" method="GET">
                <div class="header-search-input-wrapper">
                    <i data-lucide="search" class="search-input-icon"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search baby care products, ride-on bikes, toy cars..." class="header-search-input" id="headerSearchInput">
                    <button type="submit" class="header-search-submit-btn">Search</button>
                </div>
            </form>
        </div>

        <!-- Right Side: Actions -->
        <div class="header-actions">
            <!-- Track Order Outlined Button -->
            <a href="{{ route('polani.track-order') }}" class="track-order-outline-btn {{ request()->routeIs('polani.track-order') ? 'active' : '' }}">
                <i data-lucide="truck"></i>
                <span>Track Order</span>
            </a>

            <!-- Account Icon -->
            @auth
                <div class="profile-dropdown-container">
                    <a href="#" class="header-action-item" id="profileDropdownBtn" aria-label="Account" title="Profile: {{ auth()->user()->name }}">
                        <div class="action-btn">
                            <i data-lucide="user"></i>
                        </div>
                        <span class="action-label">Account</span>
                    </a>
                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
                        <a href="{{ route('users.profile') }}" class="profile-dropdown-item">
                            <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                            <span>My Profile</span>
                        </a>
                        <div class="profile-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="profile-dropdown-item logout-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('sign-in') }}" class="header-action-item {{ request()->routeIs('sign-in') ? 'active' : '' }}" aria-label="Account" title="Sign In">
                    <div class="action-btn">
                        <i data-lucide="user"></i>
                    </div>
                    <span class="action-label">Account</span>
                </a>
            @endauth

            <!-- Wishlist Icon -->
            <a href="{{ route('polani.wishlist') }}" class="header-action-item {{ request()->routeIs('polani.wishlist') ? 'active' : '' }}" aria-label="Wishlist" id="wishlistBtn">
                <div class="action-btn badge-btn">
                    <i data-lucide="heart"></i>
                    <span class="badge" id="wishlistCount">0</span>
                </div>
                <span class="action-label">Wishlist</span>
            </a>

            <!-- Cart Icon -->
            <a href="{{ route('shopping-cart') }}" class="header-action-item {{ request()->routeIs('shopping-cart') ? 'active' : '' }}" aria-label="Cart" id="cartBtn">
                <div class="action-btn badge-btn">
                    <i data-lucide="shopping-cart"></i>
                    <span class="badge" id="cartCount" data-cart-badge>{{ $cartCount ?? 0 }}</span>
                </div>
                <span class="action-label">Cart</span>
            </a>
        </div>
    </div>

    <!-- Mobile Search Row (visible only on mobile/tablet) -->
    <div class="mobile-search-row">
        <form class="header-search-form" action="{{ route('polani.collection') }}" method="GET">
            <div class="header-search-input-wrapper">
                <i data-lucide="search" class="search-input-icon"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search baby care products, ride-on bikes, toy cars..." class="header-search-input" id="mobileSearchInput">
                <button type="submit" class="header-search-submit-btn">Search</button>
            </div>
        </form>
    </div>

    <!-- 3. Floating Navigation Bar -->
    <div class="floating-nav-container">
        <div class="floating-nav-capsule">
            <div class="nav-item">
                <a href="{{ route('home') }}" class="floating-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i data-lucide="home"></i>
                    <span>Home</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('polani.babycare') }}" class="floating-nav-link {{ request()->routeIs('polani.babycare') ? 'active' : '' }}">
                    <i data-lucide="baby"></i>
                    <span>Baby Care</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('polani.bikes') }}" class="floating-nav-link {{ request()->routeIs('polani.bikes') ? 'active' : '' }}">
                    <i data-lucide="bike"></i>
                    <span>B/O Bikes</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('polani.cars') }}" class="floating-nav-link {{ request()->routeIs('polani.cars') ? 'active' : '' }}">
                    <i data-lucide="car"></i>
                    <span>B/O Cars</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('polani.collection') }}" class="floating-nav-link {{ request()->routeIs('polani.collection') ? 'active' : '' }}">
                    <i data-lucide="shopping-bag"></i>
                    <span>Shop</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('polani.about') }}" class="floating-nav-link {{ request()->routeIs('polani.about') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    <span>About Us</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('polani.contact') }}" class="floating-nav-link {{ request()->routeIs('polani.contact') ? 'active' : '' }}">
                    <i data-lucide="mail"></i>
                    <span>Contact Us</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer Overlay & Content -->
<div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
<div class="mobile-drawer" id="mobileDrawer">
    <div class="mobile-drawer-header">
        <span class="mobile-drawer-title">Menu Navigation</span>
        <button class="mobile-drawer-close-btn" id="mobileDrawerCloseBtn" aria-label="Close Menu">
            <i data-lucide="x"></i>
        </button>
    </div>
    <div class="mobile-drawer-body">
        <nav class="mobile-drawer-nav">
            <a href="{{ route('home') }}" class="mobile-drawer-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i data-lucide="home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('polani.babycare') }}" class="mobile-drawer-link {{ request()->routeIs('polani.babycare') ? 'active' : '' }}">
                <i data-lucide="baby"></i>
                <span>Baby Care</span>
            </a>
            <a href="{{ route('polani.bikes') }}" class="mobile-drawer-link {{ request()->routeIs('polani.bikes') ? 'active' : '' }}">
                <i data-lucide="bike"></i>
                <span>B/O Bikes</span>
            </a>
            <a href="{{ route('polani.cars') }}" class="mobile-drawer-link {{ request()->routeIs('polani.cars') ? 'active' : '' }}">
                <i data-lucide="car"></i>
                <span>B/O Cars</span>
            </a>
            <a href="{{ route('polani.collection') }}" class="mobile-drawer-link {{ request()->routeIs('polani.collection') ? 'active' : '' }}">
                <i data-lucide="shopping-bag"></i>
                <span>Shop</span>
            </a>
            <a href="{{ route('polani.about') }}" class="mobile-drawer-link {{ request()->routeIs('polani.about') ? 'active' : '' }}">
                <i data-lucide="users"></i>
                <span>About Us</span>
            </a>
            <a href="{{ route('polani.contact') }}" class="mobile-drawer-link {{ request()->routeIs('polani.contact') ? 'active' : '' }}">
                <i data-lucide="mail"></i>
                <span>Contact Us</span>
            </a>
            
            <div class="mobile-drawer-divider"></div>
            
            <a href="{{ route('polani.track-order') }}" class="mobile-drawer-link mobile-track-order-link {{ request()->routeIs('polani.track-order') ? 'active' : '' }}">
                <i data-lucide="truck"></i>
                <span>Track Order</span>
            </a>
        </nav>
    </div>
</div>
