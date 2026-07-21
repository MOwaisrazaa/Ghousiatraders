@push('styles')
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1a4a8e 0%, #3a7bd5 100%);
        }

        .navbar-glass {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-glass.scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .logo-container {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .logo-container {
            transform: scale(1.1) rotate(5deg);
        }

        .nav-link {
            font-weight: 600;
            color: #1a1a1a !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #1a4a8e;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 70%;
        }

        .nav-link:hover {
            color: #1a4a8e !important;
        }

        .search-wrapper .input-group {
            background: #f1f2f6;
            border-radius: 50px;
            overflow: hidden;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .search-wrapper .input-group:focus-within {
            background: #fff;
            border-color: #1a4a8e;
            box-shadow: 0 0 0 4px rgba(26, 74, 142, 0.1);
        }

        .navbar-search-results {
            border-radius: 1.25rem;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-top: 0.75rem;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
        }

        .navbar .btn-primary {
            background: var(--primary-gradient) !important;
            border: none !important;
            border-radius: 50px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(26, 74, 142, 0.2) !important;
            transition: all 0.3s ease !important;
        }

        .navbar .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 74, 142, 0.3) !important;
        }

        .navbar .btn-outline-primary {
            border: 2px solid #1a4a8e !important;
            color: #1a4a8e !important;
            border-radius: 50px !important;
            padding: 0.5rem 1.4rem !important;
            font-weight: 700 !important;
            transition: all 0.3s ease !important;
        }

        .navbar .btn-outline-primary:hover {
            background: #1a4a8e !important;
            color: #fff !important;
            transform: translateY(-2px);
        }

        .cart-icon-wrapper {
            transition: transform 0.3s ease;
        }

        .cart-icon-wrapper:hover {
            transform: scale(1.1);
        }
    </style>
@endpush

@push('scripts')
<script>
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.navbar-glass');
        if (window.scrollY > 20) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>
@endpush

<nav class="navbar navbar-expand-lg navbar-light navbar-glass sticky-top">
    <div class="container-fluid px-3">
        <!-- Logo and Brand -->
        <a class="navbar-brand d-flex align-items-center navbar-brand-link" href="{{ route('dashboard') }}">
            <span class="d-flex align-items-center justify-content-center rounded-circle p-1 me-2 logo-container">
                <picture>
                    <source srcset="{{ asset('assets/img/logos/iec-Logo.webp') }}" type="image/webp">
                    <img src="{{ asset('assets/img/logos/iec-Logo.png') }}" alt="IEC Logo" width="40" height="40" style="height: 40px; width: auto;">
                </picture>
            </span>
            <div class="d-flex flex-column brand-text-container">
                <span class="fw-bold navbar-accent d-none d-md-block brand-title">IEC Courses</span>
                <span class="text-muted small d-none d-md-block brand-subtitle">Islamic Economics &amp; Finance</span>
                <span class="fw-bold navbar-accent d-block d-md-none brand-title-mobile">IEC</span>
            </div>
        </a>

        <!-- Mobile toggler button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars text-dark"></i>
        </button>

        <!-- Collapsible navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search form for mobile -->
            <div class="d-lg-none my-3 position-relative">
                <div class="search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text border-0 search-input-group-text">
                            <i class="fas fa-search navbar-accent"></i>
                        </span>
                        <input class="form-control border-0" id="navbar-search-mobile" placeholder="Search courses..." type="text" autocomplete="off">
                    </div>
                    <!-- Mobile search results dropdown -->
                    <div class="navbar-search-results" id="search-results-container-mobile">
                        <div class="navbar-search-empty d-none">
                            <div class="text-center p-3">No results found</div>
                        </div>
                        <div class="navbar-search-loading d-none">
                            <div class="text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="navbar-search-results-list d-none">
                            <div class="p-3">
                                <div class="search-section courses-section mb-3">
                                    <h6 class="text-uppercase text-muted mb-2 font-weight-bold">Courses</h6>
                                    <div class="courses-list"></div>
                                </div>
                                <div class="search-section lectures-section">
                                    <h6 class="text-uppercase text-muted mb-2 font-weight-bold">Lectures</h6>
                                    <div class="lectures-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop search -->
            <div class="mx-auto d-none d-lg-block position-relative desktop-search-container">
                <div class="search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text border-0 search-input-group-text">
                            <i class="fas fa-search navbar-accent"></i>
                        </span>
                        <input class="form-control border-0" id="navbar-search-input" placeholder="Search courses and lectures..." type="text" autocomplete="off">
                    </div>
                    <!-- Search results dropdown -->
                    <div class="navbar-search-results" id="search-results-container">
                        <div class="navbar-search-empty d-none">
                            <div class="text-center p-3">No results found</div>
                        </div>
                        <div class="navbar-search-loading d-none">
                            <div class="text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="navbar-search-results-list d-none">
                            <div class="p-3">
                                <div class="search-section courses-section mb-3">
                                    <h6 class="text-uppercase text-muted mb-2 font-weight-bold">Courses</h6>
                                    <div class="courses-list"></div>
                                </div>
                                <div class="search-section lectures-section">
                                    <h6 class="text-uppercase text-muted mb-2 font-weight-bold">Lectures</h6>
                                    <div class="lectures-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @auth
                <!-- Cart Icon for authenticated users (hidden on admin pages) -->
                @if(!request()->routeIs('admin.*'))
                <li class="nav-item mb-2 mb-lg-0">
                    <div class="d-flex justify-content-center cart-icon-wrapper">
                        <livewire:cart-icon wire:key="cart-icon-auth" />
                    </div>
                </li>
                @endif

                <!-- My Courses -->
                <li class="nav-item mb-2 mb-lg-0">
                    <a class="nav-link d-flex align-items-center justify-content-center justify-content-lg-start" href="{{ route('user.dashboard') }}">
                        <i class="fa fa-graduation-cap me-2"></i>
                        <span class="d-inline d-lg-inline">My Courses</span>
                    </a>
                </li>

                <!-- Instructors -->
                <li class="nav-item mb-2 mb-lg-0">
                    <a class="nav-link d-flex align-items-center justify-content-center justify-content-lg-start" href="{{ route('instructor-profiles') }}">
                        <i class="fa fa-chalkboard-teacher me-2"></i>
                        <span class="d-inline d-lg-inline">Instructors</span>
                    </a>
                </li>

                <!-- Admin Panel -->
                @if(auth()->user()->isAdmin())
                <li class="nav-item mb-2 mb-lg-0">
                    <a class="nav-link d-flex align-items-center justify-content-center justify-content-lg-start" href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-tachometer-alt me-2"></i>
                        @if(auth()->user()->isSuperAdmin())
                            <span class="badge me-1 d-none d-lg-inline" style="background-color: #c82333; color: white;">Super</span>
                        @endif
                        <span class="d-inline d-lg-inline">
                            @if(auth()->user()->isSuperAdmin())
                                Super Admin
                            @else
                                Admin
                            @endif
                        </span>
                    </a>
                </li>
                @endif

                <!-- Logout -->
                <li class="nav-item mb-2 mb-lg-0">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="nav-link d-flex align-items-center justify-content-center justify-content-lg-start border-0 bg-transparent w-100">
                            <i class="fa fa-sign-out-alt me-2"></i>
                            <span class="d-inline d-lg-inline">Logout</span>
                        </button>
                    </form>
                </li>
                @else
                <!-- Guest user navigation -->

                <!-- Mobile only items -->
                @if(!request()->routeIs('admin.*'))
                <li class="nav-item mb-2 mb-lg-0 d-lg-none">
                    <div class="d-flex justify-content-center mb-2">
                        <livewire:cart-icon wire:key="cart-icon-guest-mobile" />
                    </div>
                </li>
                @endif

                <li class="nav-item mb-2 mb-lg-0 d-lg-none">
                    <a href="{{ route('instructor-profiles') }}" class="nav-link d-flex align-items-center justify-content-center">
                        <i class="fa fa-chalkboard-teacher me-2"></i>
                        <span>Instructors</span>
                    </a>
                </li>

                <li class="nav-item mb-2 mb-lg-0 d-lg-none">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <a href="{{ route('sign-in') }}" class="btn btn-sm btn-outline-primary px-3">
                            Sign In
                        </a>
                        <a href="{{ route('sign-up') }}" class="btn btn-sm btn-primary px-3">
                            Sign Up
                        </a>
                    </div>
                </li>

                <!-- Desktop only - all items in individual nav-items for proper horizontal layout -->
                @if(!request()->routeIs('admin.*'))
                <li class="nav-item d-none d-lg-block">
                    <div class="cart-icon-wrapper">
                        <livewire:cart-icon wire:key="cart-icon-guest-desktop" />
                    </div>
                </li>
                @endif

                <li class="nav-item d-none d-lg-block">
                    <a href="{{ route('instructor-profiles') }}" class="nav-link d-flex align-items-center">
                        <i class="fa fa-chalkboard-teacher me-2"></i>
                        <span>Instructors</span>
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a href="{{ route('sign-in') }}" class="btn btn-sm btn-outline-primary me-2">
                        Sign In
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a href="{{ route('sign-up') }}" class="btn btn-sm btn-primary">
                        Sign Up
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('navbar-search-input');
    const mobileSearchInput = document.getElementById('navbar-search-mobile');
    const resultsContainer = document.getElementById('search-results-container');
    const mobileResultsContainer = document.getElementById('search-results-container-mobile');

    let searchTimeout;

    // Initialize search for both desktop and mobile
    if (searchInput) {
        initializeSearch(searchInput, resultsContainer);
    }
    if (mobileSearchInput) {
        initializeSearch(mobileSearchInput, mobileResultsContainer);
    }

    function initializeSearch(input, container) {
        const loadingElement = container.querySelector('.navbar-search-loading');
        const emptyElement = container.querySelector('.navbar-search-empty');
        const resultsListElement = container.querySelector('.navbar-search-results-list');
        const coursesListElement = container.querySelector('.courses-list');
        const lecturesListElement = container.querySelector('.lectures-list');

        // Handle search input
        input.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);

            if (query.length >= 2) {
                showLoading();

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300); // Debounce search to avoid too many requests
            } else {
                hideResults();
            }
        });

        // Focus in search input
        input.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                showResults();
            }
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.search-wrapper')) {
                hideResults();
            }
        });

        // Show search results container with loading state
        function showLoading() {
            container.classList.add('show');
            loadingElement.classList.remove('d-none');
            emptyElement.classList.add('d-none');
            resultsListElement.classList.add('d-none');
        }

        // Show empty results state
        function showEmpty() {
            loadingElement.classList.add('d-none');
            emptyElement.classList.remove('d-none');
            resultsListElement.classList.add('d-none');
        }

        // Show results list
        function showResults() {
            container.classList.add('show');
            loadingElement.classList.add('d-none');
            emptyElement.classList.add('d-none');
            resultsListElement.classList.remove('d-none');
        }

        // Hide all results
        function hideResults() {
            container.classList.remove('show');
        }

        // Perform the search via AJAX
        function performSearch(query) {
            fetch(`/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Process results
                    coursesListElement.innerHTML = '';
                    lecturesListElement.innerHTML = '';

                    const courseResults = data.courses || [];
                    const lectureResults = data.lectures || [];

                    // Render course results
                    if (courseResults.length > 0) {
                        courseResults.forEach(course => {
                            const item = document.createElement('a');
                            item.href = course.url;
                            item.className = 'search-result-item d-flex align-items-center py-2 px-3';
                            item.innerHTML = `
                                <i class="fas fa-graduation-cap text-primary me-2"></i>
                                <span>${course.name}</span>
                            `;
                            coursesListElement.appendChild(item);
                        });
                        container.querySelector('.courses-section').classList.remove('d-none');
                    } else {
                        container.querySelector('.courses-section').classList.add('d-none');
                    }

                    // Render lecture results
                    if (lectureResults.length > 0) {
                        lectureResults.forEach(lecture => {
                            const item = document.createElement('a');
                            item.href = lecture.url;
                            item.className = 'search-result-item d-flex align-items-center py-2 px-3';
                            item.innerHTML = `
                                <i class="fas fa-video text-info me-2"></i>
                                <span>${lecture.name}</span>
                            `;
                            lecturesListElement.appendChild(item);
                        });
                        container.querySelector('.lectures-section').classList.remove('d-none');
                    } else {
                        container.querySelector('.lectures-section').classList.add('d-none');
                    }

                    // Show appropriate state based on results
                    if (courseResults.length === 0 && lectureResults.length === 0) {
                        showEmpty();
                    } else {
                        showResults();
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showEmpty();
                });
        }
    }
});
</script>

<style>
.search-wrapper {
    max-width: 500px;
}

.navbar-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    margin-top: 0.5rem;
    max-height: 400px;
    overflow-y: auto;
    display: none;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.navbar-search-results.show {
    display: block;
}

.search-result-item {
    color: #444;
    transition: all 0.2s ease;
    border-radius: 0.375rem;
    margin-bottom: 0.25rem;
    text-decoration: none;
}

.search-result-item:hover {
    background-color: #f8f9fa;
    color: #5e72e4;
}

.search-result-item:last-child {
    margin-bottom: 0;
}

.navbar-search-empty {
    color: #6c757d;
    font-style: italic;
}

.input-group-text {
    color: #adb5bd;
}

.search-section h6 {
    font-size: 0.75rem;
    letter-spacing: 0.05rem;
}

/* Mobile Navbar Improvements */
@media (max-width: 991.98px) {
    .navbar-collapse {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: 0.5rem;
        padding: 1rem;
        border: 1px solid #e9ecef;
    }

    .navbar-nav {
        width: 100%;
    }

    .navbar-nav .nav-item {
        text-align: center;
        border-bottom: 1px solid #f8f9fa;
        padding: 0.5rem 0;
        width: 100%;
    }

    .navbar-nav .nav-item:last-child {
        border-bottom: none;
    }

    .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        width: 100%;
        display: flex !important;
        justify-content: center !important;
        align-items: center;
    }

    .navbar-nav .nav-link:hover {
        background-color: #f8f9fa;
        color: #2563eb !important;
    }

    /* Mobile search improvements */
    .search-wrapper {
        width: 100%;
    }

    .navbar-search-results {
        left: 0;
        right: 0;
        width: 100%;
    }

    /* Guest user buttons styling for mobile */
    .nav-item .d-flex.gap-2 {
        justify-content: center !important;
        flex-wrap: nowrap !important;
        gap: 0.5rem !important;
    }

    .nav-item .btn-sm {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        white-space: nowrap;
    }
}

/* Desktop navbar improvements */
@media (min-width: 992px) {
    .navbar-nav {
        align-items: center;
        flex-direction: row;
    }

    .navbar-nav .nav-item {
        margin: 0 0.25rem;
        display: flex;
        align-items: center;
    }

    .navbar-nav .nav-link {
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .navbar-nav .nav-link:hover {
        background-color: rgba(37, 99, 235, 0.1);
        color: #2563eb !important;
    }

    /* Guest user buttons styling for desktop */
    .navbar-nav .nav-item .btn {
        margin: 0;
        white-space: nowrap;
    }

    /* Ensure livewire cart icon displays inline */
    .navbar-nav .nav-item livewire\:cart-icon,
    .navbar-nav .nav-item [wire\:id] {
        display: inline-block;
    }
}

/* Navbar toggler improvements */
.navbar-toggler {
    border: none !important;
    padding: 0.25rem 0.5rem;
    background: transparent !important;
    box-shadow: none !important;
}

.navbar-toggler:focus {
    box-shadow: none !important;
    outline: none !important;
}

.navbar-toggler:hover {
    background-color: rgba(0, 0, 0, 0.05) !important;
}

/* Ensure buttons don't wrap */
.nav-item .btn-sm {
    white-space: nowrap;
    min-width: auto;
}

/* Custom navbar toggler styling */
.navbar-toggler i {
    font-size: 1.2rem;
    transition: all 0.2s ease;
}

.navbar-toggler:hover i {
    color: #2563eb !important;
}

/* Responsive logo adjustments */
@media (max-width: 575.98px) {
    .navbar-brand {
        font-size: 0.9rem;
    }

    .navbar-brand span[style*="width:40px"] {
        width: 35px !important;
        height: 35px !important;
    }

    .navbar-brand svg {
        width: 20px !important;
        height: 20px !important;
    }
}

/* Ensure proper spacing and alignment */
.navbar-nav .nav-link {
    white-space: nowrap;
}

/* Better button styling for mobile */
@media (max-width: 991.98px) {
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .gap-2 {
        gap: 0.5rem !important;
    }
}

/* Improve navbar toggler visibility */
.navbar-toggler {
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.navbar-toggler:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

/* Force navbar brand text to blue after late-loading theme CSS */
.navbar.navbar-light .navbar-brand,
.navbar.navbar-light .navbar-brand * {
    color: #2563eb !important;
}
</style>
@endpush
