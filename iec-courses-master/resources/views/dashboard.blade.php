@section('title', 'Islamic Finance Courses - Dashboard')
@section('meta_description', 'Your dashboard for Islamic Finance courses. Track progress, explore new courses, and manage your certificates.')
@section('meta_keywords', 'Dashboard, Student Portal, Islamic Finance Learning, Course Progress')

@push('preload')
@if($carouselSlides->isNotEmpty())
    @php $firstSlide = $carouselSlides->first(); @endphp
    <!-- Preload responsive hero image for mobile viewport -->
    <link rel="preload" as="image" href="{{ $firstSlide->getImagePath('400w', 'webp') }}" media="(max-width: 600px)" fetchpriority="high">
    <!-- Preload desktop version as fallback -->
    <link rel="preload" as="image" href="{{ $firstSlide->getImagePath('1200w', 'webp') }}" media="(min-width: 1024px)" fetchpriority="high">
    <!-- Preload tablet version -->
    <link rel="preload" as="image" href="{{ $firstSlide->getImagePath('800w', 'webp') }}" media="(min-width: 600px) and (max-width: 1024px)" fetchpriority="high">
@endif
@endpush

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('dashboard') }}">
    <meta property="og:title" content="Dashboard - IEC Islamic Finance Courses">
    <meta property="og:description" content="Your personalized dashboard for tracking Islamic Finance learning progress.">
    <meta property="og:image" content="{{ asset('assets/img/hero-dashboard-1.webp') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('dashboard') }}">
    <meta property="twitter:title" content="Dashboard - IEC Islamic Finance Courses">
    <meta property="twitter:description" content="Your personalized dashboard for tracking Islamic Finance learning progress.">
    <meta property="twitter:image" content="{{ asset('assets/img/hero-dashboard-1.png') }}">
@endpush

<x-app-layout>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1a4a8e 0%, #3a7bd5 100%);
            --secondary-gradient: linear-gradient(135deg, #c5a059 0%, #e2c08d 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
            --card-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .hero-slide-wrapper {
            position: relative;
            overflow: hidden;
            height: 500px;
        }

        .hero-slide-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 8s ease-out;
            transform: scale(1);
        }

        .carousel-item.active .hero-slide-img {
            transform: scale(1.1);
        }

        .hero-slide-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .hero-glass-card {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 3rem;
            max-width: 800px;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-cta-btn {
            background: var(--primary-gradient);
            color: white !important;
            border: none;
            border-radius: 50px;
            padding: 1rem 2.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .hero-cta-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #3a7bd5 0%, #1a4a8e 100%);
        }

        .section-header {
            margin-bottom: 3rem;
            position: relative;
        }

        .section-header h2 {
            font-weight: 800;
            color: #1a4a8e;
            font-size: 2.25rem;
            margin-bottom: 0.5rem;
        }

        .section-header p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .section-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--secondary-gradient);
            margin-top: 1rem;
            border-radius: 2px;
        }

        .course-card-modern {
            border: none;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            height: 100%;
        }

        .course-card-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        .course-card-modern .card-img-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .course-card-modern .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .course-card-modern:hover .card-img-top {
            transform: scale(1.1);
        }

        .course-badge-modern {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            color: #1a4a8e;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .course-card-modern .card-body {
            padding: 1.5rem;
        }

        .course-card-modern .card-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: #2d3436;
            margin-bottom: 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 3rem;
        }

        .course-instructor {
            font-size: 0.875rem;
            color: #636e72;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .course-instructor i {
            margin-right: 0.5rem;
            color: #1a4a8e;
        }

        .course-rating {
            margin-bottom: 1.25rem;
        }

        .course-price-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .course-price {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1a4a8e;
        }

        .view-course-btn {
            background: #f8f9fa;
            color: #1a4a8e;
            border: 2px solid #1a4a8e;
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .view-course-btn:hover {
            background: #1a4a8e;
            color: #fff;
        }

        .welcome-section {
            padding: 0.5rem 0;
        }

        .welcome-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stats-badge {
            background: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: 1rem;
        }

        .stats-badge i {
            color: #c5a059;
        }

        .stats-badge span {
            font-weight: 700;
            color: #2d3436;
        }

        /* Fix the big gap issue */
        .main-content > .container-fluid {
            min-height: auto !important;
            padding-top: 0 !important;
        }

        .welcome-section {
            padding: 1rem 0 !important;
            margin-bottom: 0 !important;
        }

        .hero-slider-row {
            margin-top: 0 !important;
        }
    </style>

    <main class="main-content position-relative border-radius-lg overflow-visible">
        <div class="container-fluid pt-2 pb-4">
            <!-- JSON-LD Schema (WebSite & Organization) -->
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@graph": [
                {
                  "@type": "WebSite",
                  "name": "IEC Islamic Finance Courses",
                  "url": "{{ config('app.url') }}",
                  "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ config('app.url') }}/courses?search={search_term_string}",
                    "query-input": "required name=search_term_string"
                  }
                },
                {
                  "@type": "Organization",
                  "name": "IEC Islamic Finance",
                  "url": "{{ config('app.url') }}",
                  "logo": "{{ asset('assets/img/logo-ct-dark.png') }}",
                  "sameAs": [
                    "https://www.facebook.com/yourpage",
                    "https://twitter.com/yourhandle",
                    "https://www.linkedin.com/company/yourcompany"
                  ]
                }
              ]
            }
            </script>

            <!-- Welcome Section -->
            <div class="row align-items-center welcome-section px-3">
                <div class="col-md-8 d-flex align-items-center">
                    @auth
                        <div class="welcome-avatar me-3">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h1 class="font-weight-bold mb-0">Welcome back, {{ Auth::user()->name }}</h1>
                            <p class="text-muted mb-0">Continue your journey in Sharia-compliant education.</p>
                        </div>
                    @else
                        <div>
                            <h1 class="font-weight-bold mb-0">Welcome to IEC Courses</h1>
                            <p class="text-muted mb-0">The premier platform for Islamic Economics & Finance.</p>
                        </div>
                    @endauth
                </div>
                <div class="col-md-4 d-flex justify-content-md-end mt-md-0">
                    <div class="stats-badge d-none d-lg-flex">
                        <i class="fas fa-book-open"></i>
                        <span>{{ \App\Models\Course::count() }} Courses | {{ \App\Models\Lecture::count() }} Lectures</span>
                    </div>
                </div>
            </div>

            <!-- Hero Slider -->
            <div class="row mb-3 hero-slider-row">
                <div class="col-12">
                    <div id="heroCarousel" class="carousel slide border-radius-lg overflow-hidden shadow-lg" data-bs-ride="carousel" data-bs-interval="6000">
                        <div class="carousel-indicators">
                            @foreach($carouselSlides as $index => $slide)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                                    class="{{ $loop->first ? 'active' : '' }}"
                                    aria-current="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($carouselSlides as $slide)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }} hero-slide-wrapper">
                                <x-responsive-hero-image
                                    imageName="{{ $slide->image_name }}"
                                    :alt="$slide->title"
                                    :lazy="!$loop->first" />
                                <div class="hero-slide-content">
                                    <div class="mask bg-gradient-dark opacity-6"></div>
                                    <div class="hero-glass-card">
                                        <h2 class="hero-title">{{ $slide->title }}</h2>
                                        <p class="hero-subtitle">{{ $slide->subtitle }}</p>
                                        <a href="{{ $slide->cta_url }}" class="hero-cta-btn">{{ $slide->cta_text }}</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- End Hero Slider -->

            <div class="row courses-container mt-4">
                <!-- Latest Courses Section -->
                @if(isset($latestCourses) && $latestCourses->count() > 0)
                <div class="col-12 mb-5">
                    <div class="section-header px-3">
                        <h2>Latest Courses</h2>
                        <p>Our newest additions to help you stay ahead.</p>
                    </div>
                    
                    <div class="row px-3">
                        @foreach($latestCourses as $course)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card-modern">
                                <div class="card-img-container">
                                    <x-responsive-image
                                        :src="$course->image_path ? Storage::url($course->image_path) : 'https://via.placeholder.com/600x400'"
                                        :alt="$course->name"
                                        class="card-img-top"
                                        width="600"
                                        height="400"
                                        sizes="(max-width: 576px) 100vw, (max-width: 768px) 50vw, 33vw"
                                    />
                                    <div class="course-badge-modern">
                                        Full Course
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title" title="{{ $course->name }}">{{ $course->name }}</h3>
                                    
                                    <div class="course-instructor">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $course->instructor ?? 'IEC Expert' }}</span>
                                    </div>

                                    <div class="course-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $course->average_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif ($i - 0.5 <= $course->average_rating)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="small text-muted ms-2">({{ number_format($course->average_rating, 1) }})</span>
                                    </div>

                                    <div class="course-price-container">
                                        <div class="course-price">
                                            @if($course->is_free)
                                                <span class="text-success">Free</span>
                                            @else
                                                Rs {{ number_format($course->weekly_price, 0) }}
                                            @endif
                                        </div>
                                        <a href="{{ route('course.detail', ['slug' => $course->slug ?? $course->id]) }}" class="view-course-btn" title="View {{ $course->name }}" aria-label="View {{ $course->name }} course">
                                            View Course
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Main Courses List (Filters and Cards) -->
                <div class="col-12 px-3">
                    <div class="section-header">
                        <h2>Explore All Programs</h2>
                        <p>Filter and find the perfect course for your goals.</p>
                    </div>
                    <div class="courseslist-container" style="min-height: 800px;">
                        <livewire:courseslist />
                    </div>
                </div>
            </div>

            <x-app.footer />
        </div>
    </main>

@push('scripts')
<script>
// Responsive hero carousel image handling and micro-animations
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('heroCarousel');
    if (carousel) {
        // Preload next slide images on carousel slide event
        carousel.addEventListener('slide.bs.carousel', function(e) {
            const nextSlide = e.relatedTarget;
            const nextImg = nextSlide.querySelector('.hero-slide-img');

            if (nextImg && nextImg.tagName === 'IMG') {
                const src = nextImg.src;
                if (src) {
                    const img = new Image();
                    img.src = src;
                }
            }
        });
    }
});
</script>
@endpush

</x-app-layout>
