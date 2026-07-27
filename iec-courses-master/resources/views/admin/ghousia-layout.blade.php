<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - @yield('title', 'Ghousia Traders')</title>

    <!-- Plus Jakarta Sans & Great Vibes (for Calligraphic Logo) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playball&family=Pinyon+Script&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --gt-bg: #fffcf8;
            --gt-sidebar-bg: #fdfaf5;
            --gt-card-bg: #ffffff;
            --gt-text: #351b0d;
            --gt-text-muted: #8a7355;
            --gt-primary: #44240f;
            --gt-primary-light: #fff3df;
            --gt-border: rgba(215, 166, 74, 0.22);
            --gt-shadow: 0 4px 20px rgba(53, 27, 13, 0.03);
            --gt-success: #10b981;
            --gt-warning: #f59e0b;
            --gt-info: #3b82f6;
            --gt-danger: #ef4444;
            --gt-gray: #6b7280;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--gt-bg);
            color: var(--gt-text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Shell Layout & Box Sizing */
        .admin-shell {
            width: 100vw;
            min-height: 100vh;
            position: relative;
            box-sizing: border-box;
        }

        /* Left Sidebar styling (fixed, independently scrollable) */
        .admin-sidebar {
            background-color: var(--gt-sidebar-bg);
            border-right: 1.5px solid var(--gt-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 285px; /* Exact 285px sidebar width */
            z-index: 100;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-sizing: border-box;
        }

        .sidebar-header {
            padding: 24px 20px 16px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1.5px solid var(--gt-border);
            background: #ffffff;
        }

        .sidebar-logo-svg {
            width: 140px;
            height: 38px;
        }

        .sidebar-header-title {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .sidebar-header-title strong {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--gt-primary);
        }

        .sidebar-header-title span {
            font-size: 0.72rem;
            color: var(--gt-text-muted);
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            color: var(--gt-text);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .sidebar-nav-link-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-nav-link i {
            width: 18px;
            height: 18px;
            color: var(--gt-text-muted);
        }

        .sidebar-nav-link:hover {
            background-color: var(--gt-primary-light);
            color: var(--gt-primary);
        }

        .sidebar-nav-link.active {
            background: linear-gradient(135deg, var(--gt-primary), #633618);
            color: #fffaf3;
        }

        .sidebar-nav-link.active i {
            color: #fffaf3;
        }

        .sidebar-nav-badge {
            background-color: #fdf5e6;
            color: #b45309;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
            border: 1px solid rgba(215, 166, 74, 0.25);
        }

        .chevron-arrow {
            font-size: 0.75rem;
            color: var(--gt-text-muted);
            transition: transform 0.2s;
        }

        /* Business Promotion Card */
        .business-grow-card {
            background: #fff8ee;
            border: 1.5px dashed rgba(215, 166, 74, 0.4);
            border-radius: 14px;
            padding: 16px;
            margin: 14px 8px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .business-grow-card i {
            color: #d7a64a;
            width: 24px;
            height: 24px;
        }

        .business-grow-title {
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--gt-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .business-grow-desc {
            font-size: 0.75rem;
            color: var(--gt-text-muted);
            line-height: 1.4;
        }

        .business-grow-btn {
            background: var(--gt-primary);
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 0.78rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            width: 100%;
            transition: background 0.2s;
        }

        .business-grow-btn:hover {
            background: var(--gt-text);
        }

        /* Admin profile footer */
        .sidebar-profile-footer {
            padding: 16px;
            border-top: 1.5px solid var(--gt-border);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            position: relative;
        }

        .sidebar-profile-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid var(--gt-border);
            background-color: #fff8ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--gt-primary);
        }

        .admin-profile-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .admin-profile-meta strong {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--gt-primary);
        }

        .admin-profile-meta span {
            font-size: 0.7rem;
            color: var(--gt-text-muted);
        }

        /* Profile Popover Menu */
        .profile-popover-menu {
            position: absolute;
            bottom: 70px;
            left: 16px;
            right: 16px;
            background: #ffffff;
            border: 1.5px solid var(--gt-border);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            display: none;
            flex-direction: column;
            padding: 6px;
            z-index: 110;
        }

        .profile-popover-menu.show {
            display: flex;
        }

        .profile-popover-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            color: var(--gt-text);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            width: 100%;
        }

        .profile-popover-item:hover {
            background-color: var(--gt-primary-light);
            color: var(--gt-primary);
        }

        /* Right panel section */
        .admin-main-wrapper {
            margin-left: 285px; /* Aligned with sidebar width */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100vw - 285px); /* Exact requested width */
            transition: all 0.3s ease;
            box-sizing: border-box;
            background-color: var(--gt-bg);
            min-width: 0;
        }

        /* Sticky Top Header */
        .admin-topbar {
            position: sticky;
            top: 0;
            background: #ffffff;
            border-bottom: 1.5px solid var(--gt-border);
            height: 70px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 90;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            max-width: 500px;
        }

        .hamburger-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gt-primary);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .hamburger-btn:hover {
            background-color: var(--gt-primary-light);
        }

        .hamburger-btn i {
            width: 20px;
            height: 20px;
        }

        .search-pill-wrapper {
            position: relative;
            width: 100%;
        }

        .search-pill-input {
            width: 100%;
            background: #fffdf9;
            border: 1.5px solid var(--gt-border);
            border-radius: 99px;
            padding: 8px 45px 8px 16px;
            font-size: 0.85rem;
            color: var(--gt-text);
            outline: none;
            transition: all 0.2s;
        }

        .search-pill-input:focus {
            border-color: #d7a64a;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(215, 166, 74, 0.1);
        }

        .shortcut-hint {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.72rem;
            color: var(--gt-text-muted);
            background: var(--gt-primary-light);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(215, 166, 74, 0.2);
            pointer-events: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .view-store-btn {
            border: 1.5px solid var(--gt-border);
            background: transparent;
            color: var(--gt-primary);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 99px;
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            min-height: 38px;
        }

        .view-store-btn:hover {
            background-color: var(--gt-primary-light);
            border-color: #d7a64a;
        }

        .topbar-icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--gt-primary);
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .topbar-icon-btn:hover {
            background-color: var(--gt-primary-light);
        }

        .topbar-icon-btn i {
            width: 20px;
            height: 20px;
        }

        .unread-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background: var(--gt-danger);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #ffffff;
        }

        .topbar-divider {
            width: 1.5px;
            height: 24px;
            background-color: var(--gt-border);
        }

        .admin-user-header {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .avatar-initials {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: var(--gt-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .header-dropdown-arrow {
            font-size: 0.75rem;
            color: var(--gt-primary);
        }

        /* Content wrapper */
        .admin-content {
            display: flex !important;
            flex-direction: column !important;
            flex: 1;
            padding: 30px;
            background-color: var(--gt-bg);
        }

        /* Footer styling */
        .admin-footer {
            background-color: #ffffff;
            border-top: 1.5px solid var(--gt-border);
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--gt-text-muted);
        }

        .admin-footer-left {
            font-weight: 500;
        }

        .admin-footer-right {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .admin-footer-right i {
            color: var(--gt-success);
            width: 14px;
            height: 14px;
        }

        /* Collapsed Sidebar overrides */
        .admin-shell.sidebar-collapsed .admin-sidebar {
            left: -285px;
        }

        .admin-shell.sidebar-collapsed .admin-main-wrapper {
            margin-left: 0;
            width: 100vw;
        }

        /* Global Search results overlay */
        .global-search-results-overlay {
            position: fixed;
            top: 70px;
            left: 285px;
            right: 0;
            bottom: 0;
            background: rgba(53, 27, 13, 0.4);
            backdrop-filter: blur(4px);
            z-index: 105;
            display: none;
        }

        .admin-shell.sidebar-collapsed .global-search-results-overlay {
            left: 0;
        }

        .global-search-results-overlay.show {
            display: block;
        }

        .search-results-card {
            background: #ffffff;
            border: 1.5px solid var(--gt-border);
            border-top: none;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 15px 35px rgba(53, 27, 13, 0.15);
            max-width: 600px;
            margin-left: 76px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 20px;
        }

        .search-result-group-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--gt-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            margin-top: 14px;
        }

        .search-result-group-title:first-child {
            margin-top: 0;
        }

        .search-result-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            list-style: none;
        }

        .search-result-item a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border-radius: 8px;
            background: #fffcf8;
            color: var(--gt-text);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .search-result-item a:hover {
            background-color: var(--gt-primary-light);
            border-color: var(--gt-border);
        }

        .search-result-meta {
            font-size: 0.72rem;
            color: var(--gt-text-muted);
            font-weight: 500;
        }

        /* Mobile drawer overlay */
        .sidebar-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(53, 27, 13, 0.5);
            z-index: 99;
            display: none;
            backdrop-filter: blur(2px);
        }

        .sidebar-drawer-overlay.show {
            display: block;
        }

        /* Responsive Viewports */
        @media (max-width: 991px) {
            .admin-sidebar {
                left: -285px;
            }

            .admin-main-wrapper {
                margin-left: 0;
                width: 100vw;
            }

            .admin-shell.sidebar-collapsed .admin-sidebar {
                left: 0;
            }

            .global-search-results-overlay {
                left: 0;
            }

            .search-results-card {
                margin-left: 20px;
                margin-right: 20px;
            }
        }

        @media (max-width: 576px) {
            .admin-topbar {
                padding: 0 16px;
            }

            .topbar-right {
                gap: 10px;
            }

            .view-store-btn {
                display: none;
            }

            .admin-footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        /* Toast notification styling */
        .toast-notification {
            position: fixed;
            top: 24px;
            right: 24px;
            background: #ffffff;
            border: 1.5px solid var(--gt-success);
            border-left: 5px solid var(--gt-success);
            border-radius: 10px;
            padding: 16px 20px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.12);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--gt-text);
            animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .toast-notification.toast-error {
            border-color: var(--gt-danger);
            border-left-color: var(--gt-danger);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.12);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(120%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
    @stack('head')
</head>
<body>
    <div class="admin-shell" id="adminShell">
        
        <!-- Sidebar off-canvas overlay -->
        <div class="sidebar-drawer-overlay" id="sidebarDrawerOverlay"></div>

        <!-- 1. Left Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <!-- Cursive Calligraphic Logo SVG -->
                <svg viewBox="0 0 320 80" xmlns="http://www.w3.org/2000/svg" class="sidebar-logo-svg">
                    <defs>
                        <linearGradient id="goldGradSidebar" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8E5B10" />
                            <stop offset="50%" stop-color="#DFAC4D" />
                            <stop offset="100%" stop-color="#44240f" />
                        </linearGradient>
                    </defs>
                    <text x="160" y="55" font-family="'Great Vibes', 'Playball', 'Pinyon Script', cursive" font-size="46" font-weight="bold" fill="url(#goldGradSidebar)" text-anchor="middle">Ghousia Traders</text>
                </svg>
            </div>

            <!-- Navigation Items -->
            <div class="sidebar-menu-wrapper">
                
                <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="layout-dashboard"></i>
                        <span>Dashboard</span>
                    </div>
                </a>

                @php
                    $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                @endphp
                <a href="{{ route('admin.orders') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="shopping-cart"></i>
                        <span>Orders</span>
                    </div>
                    @if($pendingOrdersCount > 0)
                        <span class="sidebar-nav-badge">{{ $pendingOrdersCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.products') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="package"></i>
                        <span>Products</span>
                    </div>
                    <i data-lucide="chevron-down" class="chevron-arrow"></i>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="layers"></i>
                        <span>Categories</span>
                    </div>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="users"></i>
                        <span>Customers</span>
                    </div>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="star"></i>
                        <span>Reviews</span>
                    </div>
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="ticket"></i>
                        <span>Coupons</span>
                    </div>
                </a>



                <a href="#" class="sidebar-nav-link">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="bar-chart-3"></i>
                        <span>Reports</span>
                    </div>
                    <i data-lucide="chevron-down" class="chevron-arrow"></i>
                </a>

                <a href="#" class="sidebar-nav-link">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="settings"></i>
                        <span>Settings</span>
                    </div>
                    <i data-lucide="chevron-down" class="chevron-arrow"></i>
                </a>

                <a href="{{ route('admin.payment-methods.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.payment-methods*') ? 'active' : '' }}">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="store"></i>
                        <span>Store Settings</span>
                    </div>
                </a>

                <a href="{{ route('admin.users') }}" class="sidebar-nav-link">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="shield-check"></i>
                        <span>Users & Roles</span>
                    </div>
                </a>

                <a href="#" class="sidebar-nav-link">
                    <div class="sidebar-nav-link-left">
                        <i data-lucide="help-circle"></i>
                        <span>Support & Tickets</span>
                    </div>
                </a>

                <!-- Business Growth Card -->
                <div class="business-grow-card">
                    <i data-lucide="crown"></i>
                    <span class="business-grow-title">Grow Your Business</span>
                    <p class="business-grow-desc">Manage your store efficiently and increase your sales.</p>
                    <a href="#" class="business-grow-btn">View Reports</a>
                </div>

            </div>

            <!-- Admin Profile block -->
            <div class="sidebar-profile-footer" id="profileFooterBtn">
                <div class="sidebar-profile-info">
                    <div class="admin-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="admin-profile-meta">
                        <strong>{{ Auth::user()->name ?? 'Admin User' }}</strong>
                        <span>{{ Auth::user()->isSuperAdmin() ? 'Super Administrator' : 'Administrator' }}</span>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="chevron-arrow"></i>

                <!-- Profile Dropdown Popover Menu -->
                <div class="profile-popover-menu" id="profilePopoverMenu">
                    <a href="#" class="profile-popover-item"><i data-lucide="user" style="width:14px; height:14px;"></i> Profile</a>
                    <a href="#" class="profile-popover-item"><i data-lucide="settings" style="width:14px; height:14px;"></i> Account Settings</a>
                    <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                        @csrf
                        <button type="submit" class="profile-popover-item" style="color:var(--gt-danger);"><i data-lucide="log-out" style="width:14px; height:14px;"></i> Logout</button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- Right Main Panel -->
        <div class="admin-main-wrapper">
            
            <!-- 2. Top Header -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button type="button" class="hamburger-btn" id="sidebarToggleBtn" aria-label="Toggle Sidebar">
                        <i data-lucide="menu"></i>
                    </button>
                    <div class="search-pill-wrapper">
                        <form action="{{ route('admin.dashboard') }}" method="GET" id="adminGlobalSearchForm">
                            <input type="text" name="search" id="adminGlobalSearch" class="search-pill-input" placeholder="Search for orders, products, customers..." value="{{ $searchQuery ?? '' }}">
                            <span class="shortcut-hint">Ctrl + /</span>
                        </form>
                    </div>
                </div>
                <div class="topbar-right">
                    <a href="{{ route('home') }}" class="view-store-btn" target="_blank" rel="noopener">
                        <i data-lucide="eye"></i>
                        <span>View Store</span>
                    </a>
                    <button type="button" class="topbar-icon-btn">
                        <i data-lucide="bell"></i>
                        <span class="unread-badge">5</span>
                    </button>
                    <button type="button" class="topbar-icon-btn">
                        <i data-lucide="message-square"></i>
                        <span class="unread-badge">2</span>
                    </button>
                    <div class="topbar-divider"></div>
                    <div class="admin-user-header" id="headerProfileBtn">
                        <div class="avatar-initials">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                        </div>
                        <i data-lucide="chevron-down" class="header-dropdown-arrow"></i>
                    </div>
                </div>
            </header>

            <!-- Global Search Overlay results -->
            <div class="global-search-results-overlay @if(!empty($searchResults)) show @endif" id="searchOverlay">
                @if(!empty($searchResults))
                    <div class="search-results-card">
                        
                        <!-- Products Results -->
                        @if($searchResults['products']->isNotEmpty())
                            <h4 class="search-result-group-title">Products</h4>
                            <ul class="search-result-list">
                                @foreach($searchResults['products'] as $p)
                                    <li class="search-result-item">
                                        <a href="{{ route('admin.products') }}">
                                            <span>{{ $p->name }}</span>
                                            <span class="search-result-meta">PKR {{ number_format($p->weekly_price) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <!-- Orders Results -->
                        @if($searchResults['orders']->isNotEmpty())
                            <h4 class="search-result-group-title">Orders</h4>
                            <ul class="search-result-list">
                                @foreach($searchResults['orders'] as $o)
                                    <li class="search-result-item">
                                        <a href="{{ route('admin.orders') }}">
                                            <span>Order #GT-{{ $o->id }}</span>
                                            <span class="search-result-meta">Total: PKR {{ number_format($o->final_total) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <!-- Customers Results -->
                        @if($searchResults['customers']->isNotEmpty())
                            <h4 class="search-result-group-title">Customers</h4>
                            <ul class="search-result-list">
                                @foreach($searchResults['customers'] as $c)
                                    <li class="search-result-item">
                                        <a href="{{ route('admin.users') }}">
                                            <span>{{ $c->name }}</span>
                                            <span class="search-result-meta">{{ $c->email }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($searchResults['products']->isEmpty() && $searchResults['orders']->isEmpty() && $searchResults['customers']->isEmpty())
                            <div style="text-align: center; color: var(--gt-text-muted); font-size: 0.88rem; padding: 20px 0;">
                                <i data-lucide="search-code" style="width:24px; height:24px; margin-bottom:8px; color: var(--gt-text-muted);"></i>
                                <p>No match was found for "{{ $searchQuery }}".</p>
                            </div>
                        @endif

                    </div>
                @endif
            </div>

            <!-- Content Area -->
            <main class="admin-content">
                @if(session('success'))
                    <div class="toast-notification" id="successToast">
                        <i data-lucide="check-circle" style="color:var(--gt-success); width:18px; height:18px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="toast-notification toast-error" id="errorToast">
                        <i data-lucide="alert-circle" style="color:var(--gt-danger); width:18px; height:18px;"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- 9. Footer -->
            <footer class="admin-footer">
                <div class="admin-footer-left">
                    &copy; {{ date('Y') }} Ghousia Traders. All rights reserved.
                </div>
                <div class="admin-footer-right">
                    <i data-lucide="shield"></i>
                    <span>You are using the latest version</span>
                </div>
            </footer>

        </div>
    </div>

    <!-- Scripting for Toggles and Keyboards -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // Toggle Sidebar collapsing
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const adminShell = document.getElementById('adminShell');
            const sidebarDrawerOverlay = document.getElementById('sidebarDrawerOverlay');

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    adminShell.classList.toggle('sidebar-collapsed');
                    if (window.innerWidth <= 991) {
                        sidebarDrawerOverlay.classList.toggle('show');
                    }
                });
            }

            if (sidebarDrawerOverlay) {
                sidebarDrawerOverlay.addEventListener('click', () => {
                    adminShell.classList.remove('sidebar-collapsed');
                    sidebarDrawerOverlay.classList.remove('show');
                });
            }

            // Keyboard shortcut Ctrl + / to focus search
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey && e.key === '/') {
                    e.preventDefault();
                    const searchInput = document.getElementById('adminGlobalSearch');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            });

            // Toggle Profile Dropdown menu (footer)
            const profileFooterBtn = document.getElementById('profileFooterBtn');
            const profilePopoverMenu = document.getElementById('profilePopoverMenu');

            if (profileFooterBtn && profilePopoverMenu) {
                profileFooterBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profilePopoverMenu.classList.toggle('show');
                });
            }

            // Dismiss profile popover on outside clicks
            document.addEventListener('click', (e) => {
                if (profilePopoverMenu && !profileFooterBtn.contains(e.target)) {
                    profilePopoverMenu.classList.remove('show');
                }
            });

            // Toggle header initials click
            const headerProfileBtn = document.getElementById('headerProfileBtn');
            if (headerProfileBtn) {
                headerProfileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (profileFooterBtn) {
                        profileFooterBtn.click();
                    }
                });
            }

            // Search overlay dismiss
            const searchOverlay = document.getElementById('searchOverlay');
            if (searchOverlay) {
                searchOverlay.addEventListener('click', (e) => {
                    if (e.target === searchOverlay) {
                        searchOverlay.classList.remove('show');
                    }
                });
            }

            // Dismiss success/error toasts automatically after 4 seconds
            const successToast = document.getElementById('successToast');
            if (successToast) {
                setTimeout(() => {
                    successToast.style.opacity = '0';
                    successToast.style.transform = 'translateY(-10px)';
                    setTimeout(() => { successToast.remove(); }, 500);
                }, 4000);
            }

            const errorToast = document.getElementById('errorToast');
            if (errorToast) {
                setTimeout(() => {
                    errorToast.style.opacity = '0';
                    errorToast.style.transform = 'translateY(-10px)';
                    setTimeout(() => { errorToast.remove(); }, 500);
                }, 4000);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
