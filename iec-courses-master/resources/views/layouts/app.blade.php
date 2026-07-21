<!--
=========================================================
* Corporate UI - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/corporate-ui
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="dashboard-route" content="{{ route('dashboard') }}">

    <!-- Primary SEO Tags -->
    <title>@yield('title', 'Islamic Finance Courses Hub')</title>
    <meta name="description" content="@yield('meta_description', 'Master Islamic Finance with expert-led courses. Learn about Sharia-compliant banking, ethical investing, and the global Islamic economy.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Islamic Finance, Sharia Banking, Ethical Investing, Islamic Economics, Sukuk, Takaful')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Islamic Finance Courses Hub')">
    <meta property="og:description" content="@yield('meta_description', 'Master Islamic Finance with expert-led courses.')">
    <meta property="og:image" content="@yield('meta_image', asset('assets/img/hero-dashboard-1.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Islamic Finance Courses Hub')">
    <meta property="twitter:description" content="@yield('meta_description', 'Master Islamic Finance with expert-led courses.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('assets/img/hero-dashboard-1.png'))">

    <!-- Preconnect to external domains for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    @stack('preload')

    <!-- Critical CSS - Inline for fastest FCP -->
    <style>
        /* Critical above-the-fold styles */
        *,::after,::before{box-sizing:border-box}
        body{margin:0;font-family:'Open Sans',sans-serif;font-size:1rem;font-weight:400;line-height:1.5;color:#67748e;background-color:#f8f9fa;-webkit-text-size-adjust:100%}
        .g-sidenav-show{overflow-x:hidden}
        .bg-gray-100{background-color:#f8f9fa!important}
        .main-content{position:relative;min-height:100vh;contain:layout style;transform:translateZ(0)}
        .main-content>.container-fluid{min-height:100vh}
        .container-fluid{width:100%;padding-right:1.5rem;padding-left:1.5rem;margin-right:auto;margin-left:auto;contain:layout style}
        .row{display:flex;flex-wrap:wrap;margin-top:0;margin-right:-.75rem;margin-left:-.75rem;contain:layout}
        [wire\:id]{contain:layout}
        .col-12,.col-md-12{flex:0 0 auto;width:100%}
        .py-4{padding-top:1.5rem!important;padding-bottom:1.5rem!important}
        .mb-3{margin-bottom:1rem!important}
        .mb-5{margin-bottom:3rem!important}
        .font-weight-bold{font-weight:700!important}
        .h3{font-size:1.75rem}
        .carousel{position:relative}
        #heroCarousel{min-height:500px;height:500px;contain:layout}
        #heroCarousel .carousel-inner,#heroCarousel .carousel-item,#heroCarousel .carousel-item>.bg-cover{min-height:500px;height:500px}
        .carousel-inner{position:relative;width:100%;overflow:hidden}
        .carousel-item{position:relative;display:none;float:left;width:100%;margin-right:-100%;backface-visibility:hidden;transition:transform .6s ease-in-out}
        .carousel-item.active{display:block}
        @media(max-width:991.98px){#heroCarousel,#heroCarousel .carousel-inner,#heroCarousel .carousel-item,#heroCarousel .carousel-item>.bg-cover{min-height:400px;height:400px}}
        @media(max-width:575.98px){#heroCarousel,#heroCarousel .carousel-inner,#heroCarousel .carousel-item,#heroCarousel .carousel-item>.bg-cover{min-height:350px;height:350px}}
        .border-radius-lg{border-radius:.75rem}
        .overflow-hidden{overflow:hidden!important}
        .shadow-lg{box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important}
        .text-center{text-align:center!important}
        .text-white{color:#fff!important}
        .bg-cover{background-size:cover;background-position:center}
        .d-flex{display:flex!important}
        .flex-column{flex-direction:column!important}
        .justify-content-center{justify-content:center!important}
        .align-items-center{align-items:center!important}
        .h-100{height:100%!important}
        .p-5{padding:3rem!important}
        .position-relative{position:relative!important}
        .z-index-1{z-index:1!important}
        .display-4{font-size:3.5rem;font-weight:300;line-height:1.2}
        .btn{display:inline-block;font-weight:700;line-height:1.667;color:#67748e;text-align:center;text-decoration:none;vertical-align:middle;cursor:pointer;user-select:none;background-color:transparent;border:1px solid transparent;padding:.625rem 1.5rem;font-size:.875rem;border-radius:.5rem;transition:all .15s ease-in}
        .btn-white{color:#344767;background-color:#fff;border-color:#fff}
        .btn-lg{padding:.875rem 4rem;font-size:1rem;border-radius:.5rem}
        .navbar{position:relative;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;padding-top:.5rem;padding-bottom:.5rem}
        .card{position:relative;display:flex;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:0 solid rgba(0,0,0,.125);border-radius:1rem;contain:layout style paint}
        .card-body{flex:1 1 auto;padding:1rem 1rem}
        .card-img-top{width:100%;aspect-ratio:300/160;object-fit:cover;background-color:#f0f0f0;border-top-left-radius:calc(1rem - 0);border-top-right-radius:calc(1rem - 0)}
        .course-card{min-height:350px}
        .course-image-container{min-height:160px;height:160px;background-color:#f0f0f0}
        @media (min-width:768px){.col-md-4{flex:0 0 auto;width:33.33333333%}.d-md-flex{display:flex!important}}
    </style>

    <!-- Preload critical fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet"></noscript>

    <!-- Main CSS - Purged & Minified (129KB vs 484KB original) - Preload and load async -->
    <link rel="preload" href="{{ asset('assets/css/corporate-ui-dashboard.purged.min.css?v=1.0.2') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link id="pagestyle" href="{{ asset('assets/css/corporate-ui-dashboard.purged.min.css?v=1.0.2') }}" rel="stylesheet"></noscript>

    <!-- Bootstrap CSS - Load asynchronously to avoid render blocking (Est. savings: 160ms LCP) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>

    <!-- Non-critical CSS - Load async with media trick (using minified versions) -->
    <link href="{{ asset('assets/css/nucleo-icons.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <!-- Font Awesome - Optimized subset (131 icons only, 6.4KB vs 57KB) -->
    <link href="{{ asset('assets/css/fontawesome-fonts.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/fontawesome-subset.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/corporate-ui-fixes.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/livewire.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/livewire-dynamic.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/github-buttons.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/buttons-fix.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/custom-scrollbar.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/theme-gradients.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <!-- Auth pages CSS - load async to reduce critical request chain -->
    <link href="{{ asset('assets/css/extracted/account-signup.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/auth-signin-custom.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/auth-signup-custom.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/auth-layout-fix.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <!-- Hero Carousel Responsive Images -->
    <link href="{{ asset('assets/css/extracted/hero-carousel-responsive.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('css/admin-instructor-profiles.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/livewire-base-styles.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/dynamic-styles-fix.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/extracted/cls-fixes.css') }}" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- Conditional Livewire Styles (Only on pages that use Livewire) -->
    @php
        // Route detection for Livewire pages (matching JavaScript conditionals)
        $livewirePages = [
            'dashboard', 'checkout', 'course.detail', 'course.purchased-detail',
            'lecture.detail', 'lecture.standalone', 'lecture.standalone.legacy', 'lecture.purchased-detail', 'courses',
            'courses.create', 'admin.course.create',
            'admin.courses.index', 'admin.lectures.index', 'admin.orders.index',
            'admin.quizzes.index', 'admin.questions.index', 'admin.dashboard',
            'user.certificates.index', 'user.dashboard', 'quiz.start', 'cart',
        ];
        try {
            $currentRoute = request()->route();
            $routeName = $currentRoute ? $currentRoute->getName() : null;
        } catch (\Exception $e) {
            $routeName = null;
        }
        $needsLivewireStyles = false;
        if ($routeName) {
            foreach ($livewirePages as $page) {
                if (str_contains($routeName, $page)) {
                    $needsLivewireStyles = true;
                    break;
                }
            }
        }
    @endphp
    @if($needsLivewireStyles)
        @livewireStyles
    @endif

    <!-- Noscript fallbacks for non-critical CSS -->
    <noscript>
        <link href="{{ asset('assets/css/nucleo-icons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/fontawesome-fonts.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/fontawesome-subset.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/corporate-ui-fixes.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/livewire.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/livewire-dynamic.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/github-buttons.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/buttons-fix.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/custom-scrollbar.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/theme-gradients.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/account-signup.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/auth-signin-custom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/auth-signup-custom.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/auth-layout-fix.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/hero-carousel-responsive.css') }}" rel="stylesheet">
        <link href="{{ asset('css/admin-instructor-profiles.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/livewire-base-styles.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/dynamic-styles-fix.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/extracted/cls-fixes.css') }}" rel="stylesheet">
    </noscript>

    <script src="{{ asset('assets/js/extracted/dashboard-route.min.js') }}" defer></script>

    @stack('meta')
    @stack('styles')

</head>

<body class="g-sidenav-show  bg-gray-100 @php
        $topSidenavTransparent = ['signin', 'signup', 'sign-in', 'sign-up', 'password.request', 'password.reset'];
        try {
            $currentRoute = request()->route();
            $routeName = $currentRoute ? $currentRoute->getName() : null;
        } catch (\Exception $e) {
            $routeName = null;
        }
        if ($routeName && in_array($routeName, $topSidenavTransparent)) {
            echo 'auth-page';
        }
    @endphp">
    @php
        $topSidenavArray = ['wallet', 'profile'];
        $topSidenavTransparent = ['signin', 'signup', 'sign-in', 'sign-up', 'password.request', 'password.reset'];
        $topSidenavRTL = ['RTL'];
    @endphp

    <!-- Include Global Navbar only if not on sign-in/sign-up pages -->
    @php
        try {
            $currentRoute = request()->route();
            $routeName = $currentRoute ? $currentRoute->getName() : null;
        } catch (\Exception $e) {
            $routeName = null;
        }
    @endphp
    
    @if ($routeName && !in_array($routeName, $topSidenavTransparent))
        <x-app.navbar />
    @endif

    @if ($routeName && in_array($routeName, $topSidenavArray))
        <x-sidenav-top />
    @elseif($routeName && in_array($routeName, $topSidenavTransparent))

    @elseif($routeName && in_array($routeName, $topSidenavRTL))
    @else
        <!-- <x-app.sidebar /> -->
    @endif

    @hasSection('main-content')
        @yield('main-content')
    @else
        {{ $slot ?? '' }}
    @endif


    <!--   Core JS Files - All deferred for performance   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}" defer></script>
    <!-- Bootstrap JS - Async to not block render (non-critical) -->
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}" async></script>
    <!-- Plugin scripts loaded conditionally via @push('plugin-scripts') -->
    @stack('plugin-scripts')
    <!-- App Layout JavaScript (Minified) -->
    <script src="{{ asset('assets/js/extracted/app-layout.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/extracted/buttons-fix.min.js') }}" defer></script>

    <!--   Conditional Livewire Loading - Only on pages that use it (Phase 3 Optimization)   -->
    @php
        // Pages that actually use Livewire components
        $livewirePages = [
            'dashboard',
            'checkout',
            'course.detail',
            'course.purchased-detail',
            'lecture.detail',
            'lecture.standalone',
            'lecture.standalone.legacy',
            'lecture.purchased-detail',
            'courses',
            'courses.create',
            'admin.course.create',
            'admin.courses.index',
            'admin.lectures.index',
            'admin.orders.index',
            'admin.quizzes.index',
            'admin.questions.index',
            'admin.dashboard',
            'user.certificates.index',
            'user.dashboard',
            'quiz.start',
            'cart',
        ];

        // Get current route name
        try {
            $currentRoute = request()->route();
            $routeName = $currentRoute ? $currentRoute->getName() : null;
        } catch (\Exception $e) {
            $routeName = null;
        }

        // Check if current page uses Livewire
        $needsLivewire = false;
        if ($routeName) {
            foreach ($livewirePages as $page) {
                if (str_contains($routeName, $page)) {
                    $needsLivewire = true;
                    break;
                }
            }
        }
    @endphp

    @if($needsLivewire)
        <!-- Livewire JS - Loaded conditionally only on pages that use it (277KB saved on auth pages!) -->
        @livewireScripts
        <script src="{{ asset('assets/js/extracted/livewire-helpers.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/extracted/livewire-dynamic-style-fix.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/extracted/livewire-style-handler.min.js') }}" defer></script>
    @endif

    {{-- Account signup script only on signup pages (has device detection, form handling, and math CAPTCHA) --}}
    @php
        try {
            $currentRoute = request()->route();
            $routeName = $currentRoute ? $currentRoute->getName() : null;
        } catch (\Exception $e) {
            $routeName = null;
        }
        // Load account-signup.min.js ONLY on signup pages: 'sign-up' or 'signup' routes
        // IMPORTANT: Use === 'sign-up' NOT str_contains() to avoid matching 'sign-in'
        $isSignupPage = $routeName && ($routeName === 'sign-up' || $routeName === 'signup');
    @endphp
    @if($isSignupPage)
        <script src="{{ asset('assets/js/extracted/account-signup.min.js') }}" defer></script>
    @endif
    @stack('scripts')

</body>

</html>
