@section('title', 'Browse Islamic Finance Courses - IEC')
@section('meta_description', 'Browse our extensive collection of Islamic Finance courses and lectures. Learn from industry experts about Sharia banking, ethical investing, and more.')
@section('meta_keywords', 'Islamic Finance Courses, Sharia Banking, Halal Investment, Online Islamic Courses')

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('courses') }}">
    <meta property="og:title" content="Browse Islamic Finance Courses - IEC">
    <meta property="og:description" content="Browse our extensive collection of Islamic Finance courses and lectures. Learn from industry experts about Sharia banking, ethical investing, and more.">
    <meta property="og:image" content="{{ asset('assets/img/default-course.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('courses') }}">
    <meta property="twitter:title" content="Browse Islamic Finance Courses - IEC">
    <meta property="twitter:description" content="Browse our extensive collection of Islamic Finance courses and lectures. Learn from industry experts about Sharia banking, ethical investing, and more.">
    <meta property="twitter:image" content="{{ asset('assets/img/default-course.png') }}">
@endpush

<div class="container-fluid" style="min-height: 800px;">
    <style>
        .filter-sidebar {
            background: #fff;
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 100px;
        }

        .filter-title {
            font-weight: 800;
            color: #1a4a8e;
            font-size: 1.25rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .filter-group {
            margin-bottom: 2rem;
        }

        .filter-group-title {
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #636e72;
            margin-bottom: 1.25rem;
        }

        /* Custom Radio Buttons */
        .custom-filter-radio {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-filter-radio:hover {
            transform: translateX(5px);
            color: #1a4a8e;
        }

        .custom-filter-radio input {
            display: none;
        }

        .radio-box {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #dfe6e9;
            border-radius: 50%;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .custom-filter-radio input:checked + .radio-box {
            border-color: #1a4a8e;
            background: #1a4a8e;
        }

        .radio-box::after {
            content: '';
            width: 0.5rem;
            height: 0.5rem;
            background: #fff;
            border-radius: 50%;
            opacity: 0;
            transition: all 0.2s ease;
        }

        .custom-filter-radio input:checked + .radio-box::after {
            opacity: 1;
        }

        .radio-label {
            font-weight: 500;
            color: #2d3436;
        }

        .custom-filter-radio input:checked ~ .radio-label {
            color: #1a4a8e;
            font-weight: 700;
        }

        /* Course Card Refinement */
        .course-card-premium {
            background: #fff;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            border: 1px solid #f1f2f6;
            display: flex;
            flex-direction: column;
        }

        .course-card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: rgba(26, 74, 142, 0.2);
        }

        .course-img-wrapper {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .course-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .course-card-premium:hover .course-img-wrapper img {
            transform: scale(1.1);
        }

        .premium-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .badge-full {
            background: rgba(26, 74, 142, 0.85);
            color: #fff;
        }

        .badge-lecture {
            background: rgba(46, 204, 113, 0.85);
            color: #fff;
        }

        .course-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .course-type-small {
            font-size: 0.75rem;
            font-weight: 700;
            color: #636e72;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            display: block;
        }

        .course-title-premium {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2d3436;
            line-height: 1.4;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 3.2rem;
            transition: color 0.3s ease;
        }

        .course-card-premium:hover .course-title-premium {
            color: #1a4a8e;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #636e72;
        }

        .instructor-info i {
            color: #1a4a8e;
            margin-right: 0.5rem;
        }

        .rating-stars {
            color: #f1c40f;
            font-size: 0.8rem;
            margin-bottom: 1.25rem;
        }

        .card-footer-premium {
            margin-top: auto;
            border-top: 1px solid #f8f9fa;
            padding-top: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-tag {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1a4a8e;
        }

        .price-free {
            color: #27ae60;
        }

        .cart-action-btn {
            background: #1a4a8e;
            color: #fff;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-action-btn:hover {
            background: #0d2d5e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 74, 142, 0.3);
            color: #fff;
        }

        .cart-action-btn:disabled {
            background: #dfe6e9;
            color: #b2bec3;
            transform: none;
            box-shadow: none;
        }

        .details-link {
            font-size: 1rem;
            font-weight: 900;
            color: #1a4a8e;
            text-decoration: underline;
            text-decoration-thickness: 2.5px;
            text-underline-offset: 4px;
            text-shadow: 0 2px 4px rgba(26, 74, 142, 0.15);
            transition: all 0.3s ease;
        }

        .details-link:hover {
            color: #0d2d5e;
            text-decoration-thickness: 3px;
            text-shadow: 0 4px 8px rgba(26, 74, 142, 0.25);
            transform: translateX(2px);
        }
    </style>

    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "Islamic Finance Courses Collection",
      "description": "Browse our extensive collection of Islamic Finance courses and lectures.",
      "url": "{{ route('courses') }}"
    }
    </script>

    <div class="row px-3">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="filter-sidebar">
                <h3 class="filter-title">Filters</h3>
                
                <div class="filter-group">
                    <h4 class="filter-group-title">Content Type</h4>
                    
                    <label class="custom-filter-radio">
                        <input type="radio" name="courseType" value="all" 
                            wire:click="filterByType('all')" {{ $selectedType === 'all' ? 'checked' : '' }}>
                        <span class="radio-box"></span>
                        <span class="radio-label">All Content</span>
                    </label>

                    <label class="custom-filter-radio">
                        <input type="radio" name="courseType" value="fullCourses" 
                            wire:click="filterByType('fullCourses')" {{ $selectedType === 'fullCourses' ? 'checked' : '' }}>
                        <span class="radio-box"></span>
                        <span class="radio-label">Full Courses</span>
                    </label>

                    <label class="custom-filter-radio">
                        <input type="radio" name="courseType" value="lectures" 
                            wire:click="filterByType('lectures')" {{ $selectedType === 'lectures' ? 'checked' : '' }}>
                        <span class="radio-box"></span>
                        <span class="radio-label">Individual Lectures</span>
                    </label>
                </div>

                <div class="filter-group">
                    <h4 class="filter-group-title">Categories</h4>
                    
                    <label class="custom-filter-radio">
                        <input type="radio" name="categoryFilter" value="all" 
                            wire:click="filterByCategory('all')" {{ $selectedCategory === 'all' ? 'checked' : '' }}>
                        <span class="radio-box"></span>
                        <span class="radio-label">All Categories</span>
                    </label>

                    @foreach($categories as $category)
                    <label class="custom-filter-radio">
                        <input type="radio" name="categoryFilter" value="{{$category->id}}" 
                            wire:click="filterByCategory({{$category->id}})" {{ $selectedCategory == $category->id ? 'checked' : '' }}>
                        <span class="radio-box"></span>
                        <span class="radio-label">{{$category->name}}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9 col-md-8">
            <!-- Header for Instructor Filter -->
            @if($selectedInstructor)
                <div class="alert alert-info d-flex justify-content-between align-items-center rounded-4 mb-4" style="background: #e3f2fd; border: none; color: #1a4a8e;">
                    <div>
                        <i class="fas fa-filter me-2"></i>
                        Showing courses and lectures by: <strong>{{ $selectedInstructor }}</strong>
                    </div>
                    <a href="{{ route('courses') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            @endif

            <div class="row">
                <!-- Combined Grid for Courses and Lectures -->
                
                {{-- 1. Full Courses --}}
                @if($selectedType === 'all' || $selectedType === 'fullCourses')
                    @foreach ($courses as $course)
                    <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                        <div class="course-card-premium">
                            <div class="course-img-wrapper">
                                <x-responsive-image
                                    :src="$course->image_path ? Storage::url($course->image_path) : 'https://via.placeholder.com/600x400'"
                                    :alt="$course->name"
                                    width="600"
                                    height="400"
                                    sizes="(max-width: 576px) 100vw, (max-width: 991px) 50vw, 30vw"
                                />
                                <div class="premium-badge badge-full">Full Course</div>
                            </div>
                            <div class="course-content">
                                <span class="course-type-small">Course</span>
                                <h3 class="course-title-premium">{{ $course->name }}</h3>
                                
                                <div class="instructor-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ $course->instructor ?? 'IEC Expert' }}</span>
                                </div>

                                <div class="rating-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $course->average_rating)
                                            <i class="fas fa-star"></i>
                                        @elseif ($i - 0.5 <= $course->average_rating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted ms-1">({{ number_format($course->average_rating, 1) }})</span>
                                </div>

                                <div class="card-footer-premium">
                                    <div class="price-tag {{ $course->is_free ? 'price-free' : '' }}">
                                        @if($course->is_free)
                                            FREE
                                        @else
                                            Rs {{ number_format($course->weekly_price, 0) }}
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('course.detail', ['slug' => $course->slug ?? $course->id]) }}" class="details-link" aria-label="View details for {{ $course->name }} course">Details</a>

                                        @if($course->is_free)
                                            <button wire:click="enrollInFreeCourse({{ $course->id }}, 'course')" class="cart-action-btn"
                                                {{ (auth()->check() && $this->isPurchased($course->id, 'course')) ? 'disabled' : '' }}>
                                                <i class="fas fa-play"></i>
                                                {{ (auth()->check() && $this->isPurchased($course->id, 'course')) ? 'Enrolled' : 'Enroll' }}
                                            </button>
                                        @else
                                            <button wire:click="addToCart({{ $course->id }})" class="cart-action-btn"
                                                {{ (auth()->check() && ($this->isPurchased($course->id, 'course') || $this->isInCart($course->id, 'course'))) ? 'disabled' : '' }}>
                                                <i class="fas fa-cart-plus"></i>
                                                @if(auth()->check() && $this->isPurchased($course->id, 'course')) Joined
                                                @elseif(auth()->check() && $this->isInCart($course->id, 'course')) In Cart
                                                @else Enroll
                                                @endif
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

                {{-- 2. Standalone Lectures --}}
                @if($selectedType === 'all' || $selectedType === 'lectures')
                    @foreach ($lectures as $lecture)
                    <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                        <div class="course-card-premium">
                            <div class="course-img-wrapper">
                                <x-responsive-image
                                    :src="$lecture->image_path ? Storage::url($lecture->image_path) : 'https://via.placeholder.com/600x400'"
                                    :alt="$lecture->name"
                                    width="600"
                                    height="400"
                                    sizes="(max-width: 576px) 100vw, (max-width: 991px) 50vw, 30vw"
                                />
                                <div class="premium-badge badge-lecture">Lecture</div>
                            </div>
                            <div class="course-content">
                                <span class="course-type-small">Single Lecture</span>
                                <h3 class="course-title-premium">{{ $lecture->name }}</h3>
                                
                                <div class="instructor-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ $lecture->instructor ?? 'IEC Expert' }}</span>
                                </div>

                                <div class="rating-stars">
                                    <!-- Simplified ratings for brevity -->
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                    <span class="text-muted ms-1">({{ number_format($lecture->average_rating, 1) }})</span>
                                </div>

                                <div class="card-footer-premium">
                                    <div class="price-tag {{ $lecture->is_free ? 'price-free' : '' }}">
                                        @if($lecture->is_free)
                                            FREE
                                        @else
                                            Rs {{ number_format($lecture->weekly_price, 0) }}
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        @if($lecture->course)
                                            <a href="{{ route('lecture.detail', ['course' => $lecture->course->slug ?? $lecture->course->id, 'lecture' => $lecture->slug ?? $lecture->id]) }}" class="details-link" aria-label="View details for {{ $lecture->name }} lecture">Details</a>
                                        @else
                                            <a href="{{ route('lecture.standalone', ['lecture' => $lecture->slug ?? $lecture->id]) }}" class="details-link" aria-label="View details for {{ $lecture->name }} lecture">Details</a>
                                        @endif

                                        @if($lecture->is_free)
                                            <button wire:click="enrollInFreeCourse({{ $lecture->id }}, 'lecture')" class="cart-action-btn"
                                                {{ (auth()->check() && $this->isPurchased($lecture->id, 'lecture')) ? 'disabled' : '' }}>
                                                <i class="fas fa-play"></i>
                                                {{ (auth()->check() && $this->isPurchased($lecture->id, 'lecture')) ? 'Enrolled' : 'Enroll' }}
                                            </button>
                                        @else
                                            <button wire:click="addToCart({{ $lecture->id }}, 'lecture')" class="cart-action-btn"
                                                {{ (auth()->check() && ($this->isPurchased($lecture->id, 'lecture') || $this->isInCart($lecture->id, 'lecture'))) ? 'disabled' : '' }}>
                                                <i class="fas fa-play"></i>
                                                @if(auth()->check() && $this->isPurchased($lecture->id, 'lecture')) Enrolled
                                                @elseif(auth()->check() && $this->isInCart($lecture->id, 'lecture')) In Cart
                                                @else Enroll
                                                @endif
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <!-- Empty Search Results -->
            @if($courses->isEmpty() && $lectures->isEmpty())
                <div class="text-center py-5">
                    <img src="{{ asset('assets/img/illustrations/empty-cart.svg') }}" alt="No results" style="max-width: 250px;" class="mb-4">
                    <h3 class="font-weight-bold">No results found</h3>
                    <p class="text-muted">Try adjusting your filters to find what you're looking for.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notifications -->
    @if (session()->has('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-3 border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-danger alert-dismissible fade show shadow-lg rounded-3 border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>



