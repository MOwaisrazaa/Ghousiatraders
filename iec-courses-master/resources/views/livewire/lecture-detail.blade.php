@section('title', $lecture->name . ' - Islamic Finance Courses')
@section('meta_description', Str::limit(strip_tags($lecture->description), 160))
@section('meta_keywords', 'Islamic Finance, Lecture, ' . $lecture->name . ', ' . ($course ? $course->name : ''))

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $course ? route('lecture.detail', ['course' => $course->slug, 'lecture' => $lecture->slug]) : route('lecture.standalone', ['lecture' => $lecture->slug]) }}">
    <meta property="og:title" content="{{ $lecture->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($lecture->description), 160) }}">
    <meta property="og:image" content="{{ $lecture->image_path ? Storage::url($lecture->image_path) : asset('assets/img/default-course.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $course ? route('lecture.detail', ['course' => $course->slug, 'lecture' => $lecture->slug]) : route('lecture.standalone', ['lecture' => $lecture->slug]) }}">
    <meta property="twitter:title" content="{{ $lecture->name }}">
    <meta property="twitter:description" content="{{ Str::limit(strip_tags($lecture->description), 160) }}">
    <meta property="twitter:image" content="{{ $lecture->image_path ? Storage::url($lecture->image_path) : asset('assets/img/default-course.png') }}">
@endpush

<div class="container py-5">
    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "{{ $lecture->name }}",
      "description": "{{ Str::limit(strip_tags($lecture->description), 160) }}",
      "provider": {
        "@type": "Organization",
        "name": "Islamic Finance Courses",
        "sameAs": "{{ config('app.url') }}"
      },
      "image": "{{ $lecture->image_path ? Storage::url($lecture->image_path) : asset('assets/img/default-course.png') }}",
      "offers": {
        "@type": "Offer",
        "category": "Paid",
        "priceCurrency": "PKR",
        "price": "{{ $lecture->is_free ? '0' : $lecture->weekly_price }}"
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $lecture->average_rating }}",
        "reviewCount": "{{ $lecture->rating_count > 0 ? $lecture->rating_count : 1 }}"
      },
      "instructor": {
        "@type": "Person",
        "name": "{{ $lecture->instructor ?? 'Instructor' }}"
      }
    }
    </script>

    <!-- Lecture Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    @if($course)
                        <li class="breadcrumb-item"><a href="{{ route('course.detail', $course->slug) }}">{{ $course->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $lecture->name }}</li>
                </ol>
            </nav>

            <h1 class="fw-bold mb-2">{{ $lecture->name }}</h1>
            <p class="lead mb-3">{{ Str::limit(strip_tags($lecture->description), 200) }}</p>

            <!-- Instructor, Reviews, etc -->
            <div class="d-flex flex-wrap align-items-center mb-3">
                <span class="me-3 mb-2">
                    <i class="fas fa-user-tie me-1"></i> {{ $lecture->instructor ?: ($course ? $course->instructor : 'Instructor') }}
                </span>
                @if($course)
                <span class="me-3 mb-2">
                    <i class="fas fa-book me-1"></i> Part of: {{ $course->name }}
                </span>
                @endif
                <span class="me-3 mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $lecture->average_rating)
                            <i class="fas fa-star text-warning"></i>
                        @elseif ($i - 0.5 <= $lecture->average_rating)
                            <i class="fas fa-star-half-alt text-warning"></i>
                        @else
                            <i class="far fa-star text-warning"></i>
                        @endif
                    @endfor
                    <span class="ms-1">({{ number_format($lecture->average_rating, 1) }})</span>
                </span>
                <span class="mb-2">
                    <i class="fas fa-users me-1"></i> {{ $lecture->rating_count }} {{ Str::plural('review', $lecture->rating_count) }}
                </span>
            </div>

            <!-- Last Updated -->
            <p class="text-muted small">
                <i class="fas fa-history me-1"></i> Last updated {{ $lecture->updated_at->format('M Y') }}
            </p>
            
            @if($lecture->intro_video_url)
            <!-- Lecture Introduction Video -->
            <div class="mb-5">
                <h4 class="mb-3">Lecture Introduction</h4>
                <div class="ratio ratio-16x9 w-75 mx-auto">
                    <iframe 
                        src="{{ str_replace('watch?v=', 'embed/', $lecture->intro_video_url) }}?rel=0" 
                        title="Lecture Introduction Video"
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="course-image-container">
                    <img src="{{ $lecture->image_path ? Storage::url($lecture->image_path) : 'https://via.placeholder.com/300x200' }}"
                        alt="{{ $lecture->name }}"
                        class="card-img-top">
                </div>

                <div class="card-body">
                    <!-- Price -->
                    @if(!$lecture->belongsToRestrictedCourse())
                        @if($lecture->is_free)
                            <div class="alert alert-success text-center mb-3">
                                <h3 class="fw-bold mb-0"><i class="fas fa-gift me-2"></i>FREE LECTURE</h3>
                            </div>
                        @else
                            <h3 class="fw-bold text-center mb-3">Rs {{ number_format($lecture->weekly_price, 2) }}</h3>
                        @endif
                    @endif

                    <!-- Add to Cart Button or Free Enrollment Button -->
                    <div class="d-grid gap-2">
                        @if($lecture->belongsToRestrictedCourse())
                            <div class="alert alert-warning">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Course Only:</strong> This lecture can only be purchased as part of the complete course.
                            </div>
                            <a href="{{ route('course.detail', $course->slug) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-left me-1"></i> View Full Course
                            </a>
                        @elseif(auth()->check() && $this->isPurchased($lecture->id, 'lecture'))
                            <button type="button" class="btn btn-success btn-lg" disabled>
                                <i class="fas fa-check-circle me-1"></i> Enrolled
                            </button>
                        @elseif(auth()->check() && $this->isPending($lecture->id, 'lecture'))
                            <button type="button" class="btn btn-warning btn-lg" disabled>
                                <i class="fas fa-clock me-1"></i> Pending Approval
                            </button>
                        @elseif($lecture->is_free && auth()->check())
                            <button type="button" wire:click="enrollInFreeCourse({{ $lecture->id }}, 'lecture')" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-1"></i> Enroll Now (Free)
                            </button>
                        @elseif(auth()->check() && \App\Models\Shoppingcart::where('user_id', auth()->id())->where('lecture_id', $lecture->id)->exists())
                            <button type="button" class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-check me-1"></i> Added to Cart
                            </button>
                        @elseif(!auth()->check() && $lecture->is_free)
                            <a href="{{ route('sign-in') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i> Sign In to Enroll Free
                            </a>
                        @elseif(!auth()->check())
                            <a href="{{ route('sign-in') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i> Sign In to Purchase
                            </a>
                        @else
                            <button type="button" wire:click="addToCart({{ $lecture->id }})" class="btn btn-primary btn-lg">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                            </button>
                        @endif

                        @if($course)
                        <a href="{{ route('course.detail', $course->slug) }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Full Course
                        </a>
                        @else
                        <a href="{{ route('courses') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Browse All Courses
                        </a>
                        @endif
                    </div>

                    <!-- Lecture Includes -->
                    <div class="mt-4">
                        <h3>This lecture includes:</h3>
                        <ul class="list-unstyled">
                            @if($lecture->duration)
                                <li class="mb-2"><i class="fas fa-video me-2"></i> {{ $lecture->duration }} minutes on-demand video</li>
                            @endif
                            @if($lecture->youtube_url)
                                <li class="mb-2"><i class="fab fa-youtube me-2"></i> Online video content</li>
                            @endif
                            <li class="mb-2"><i class="fas fa-question-circle me-2"></i> Interactive quiz and assessments</li>
                            <li class="mb-2"><i class="fas fa-comments me-2"></i> Access to Q&A and support</li>
                            <li class="mb-2"><i class="fas fa-chart-line me-2"></i> Track your lecture progress</li>

                            @foreach($lecture->features as $feature)
                                <li class="mb-2">
                                    <i class="fas fa-check me-2"></i> {{ $feature->feature_text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lecture Content Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" id="lectureTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                </li>
            </ul>

            <div class="tab-content" id="lectureTabContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-4">About This Lecture</h3>
                            <div class="lecture-description mb-5">
                                {!! $lecture->description !!}
                            </div>

                            <h4 class="mb-3">What You'll Learn</h4>
                            <ul class="check-list mb-5">
                                @forelse($lecture->getFeaturesByType('learn') as $feature)
                                    <li>{{ $feature->feature_text }}</li>
                                @empty
                                    <li>Understand key concepts covered in this lecture</li>
                                    <li>Apply these concepts to real-world scenarios</li>
                                    <li>Build on your knowledge from previous lectures</li>
                                    <li>Prepare for upcoming topics in the course</li>
                                @endforelse
                            </ul>

                            <h4 class="mb-3">Prerequisites</h4>
                            <ul class="mb-5">
                                @forelse($lecture->getFeaturesByType('requirement') as $feature)
                                    <li>{{ $feature->feature_text }}</li>
                                @empty
                                    <li>Basic understanding of course fundamentals</li>
                                    <li>Completion of previous lectures (recommended)</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="col-md-4">
                            <!-- Instructor Card -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h3 class="mb-0">Instructor</h3>
                                </div>
                                <div class="card-body">
                                    @php
                                        $instructorName = $lecture->instructor;
                                        $profile = $instructorProfiles[$instructorName] ?? null;
                                    @endphp
                                    <div class="d-flex align-items-center mb-3">
                                        @if($profile && isset($profile['image_path']) && $profile['image_path'])
                                            @php
                                                // Try both storage paths to ensure image shows up
                                                $imagePath = $profile['image_path'];
                                                $storageUrl = Storage::url($imagePath);
                                                $assetUrl = asset('storage/' . $imagePath);
                                            @endphp
                                            <img src="{{ $storageUrl }}" 
                                                onerror="this.onerror=null; this.src='{{ $assetUrl }}';"
                                                class="rounded-circle me-3" 
                                                width="80" height="80"
                                                class="instructor-avatar-img"
                                                alt="{{ $instructorName }}">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 instructor-avatar-placeholder">
                                                {{ strtoupper(substr($instructorName, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $instructorName }}</h6>
                                            <p class="text-muted mb-0 small">
                                                {{ $profile['title'] ?? (($course && $lecture->instructor === $course->instructor) ? 'Course & Lecture Instructor' : 'Lecture Instructor') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($profile)
                                        @if(!empty($profile['bio']) && trim($profile['bio']) !== '')
                                            <p class="small mb-3">{{ $profile['bio'] }}</p>
                                        @endif
                                        
                                        @if(!empty($profile['expertise']) && trim($profile['expertise']) !== '')
                                            <div class="mb-3">
                                                <h6 class="mb-2 small fw-bold">Expertise:</h6>
                                                <div>
                                                    @foreach(array_filter(explode(',', $profile['expertise']), fn($e) => trim($e) !== '') as $expertise)
                                                        <span class="badge rounded-pill px-3 py-2 me-1 mb-1 expertise-badge">{{ trim($expertise) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!empty($profile['skills']) && trim($profile['skills']) !== '')
                                            <div class="mb-3">
                                                <h6 class="mb-2 small fw-bold">Skills:</h6>
                                                <div>
                                                    @foreach(array_filter(explode(',', $profile['skills']), fn($s) => trim($s) !== '') as $skill)
                                                        <span class="badge rounded-pill px-3 py-2 me-1 mb-1 skills-badge">{{ trim($skill) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!empty($profile['social_linkedin']) || !empty($profile['social_twitter']) || !empty($profile['social_website']))
                                        <div class="mt-3 d-flex gap-2">
                                            @if(!empty($profile['social_linkedin']))
                                                <a href="{{ $profile['social_linkedin'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fab fa-linkedin"></i>
                                                </a>
                                            @endif
                                            
                                            @if(!empty($profile['social_twitter']))
                                                <a href="{{ $profile['social_twitter'] }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            @endif
                                            
                                            @if(!empty($profile['social_website']))
                                                <a href="{{ $profile['social_website'] }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-globe"></i>
                                                </a>
                                            @endif
                                        </div>
                                        @endif
                                    @endif

                                    @if($course && $lecture->instructor !== $course->instructor && $course->instructor)
                                        @php
                                            $courseInstructorProfile = $instructorProfiles[$course->instructor] ?? null;
                                        @endphp
                                        <hr class="my-3">
                                        <h6 class="mb-2">Course Instructor:</h6>
                                        <div class="d-flex align-items-center">
                                            @if($courseInstructorProfile && isset($courseInstructorProfile['image_path']) && $courseInstructorProfile['image_path'])
                                                @php
                                                    // Try both storage paths to ensure image shows up
                                                    $imagePath = $courseInstructorProfile['image_path'];
                                                    $storageUrl = Storage::url($imagePath);
                                                    $assetUrl = asset('storage/' . $imagePath);
                                                @endphp
                                                <img src="{{ $storageUrl }}" 
                                                    onerror="this.onerror=null; this.src='{{ $assetUrl }}';"
                                                    class="rounded-circle me-2" 
                                                    width="50" height="50"
                                                    class="instructor-avatar-img"
                                                    alt="{{ $course->instructor }}">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 course-instructor-avatar-placeholder">
                                                    {{ strtoupper(substr($course->instructor, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $course->instructor }}</h6>
                                                <p class="text-muted mb-0 small">{{ $courseInstructorProfile['title'] ?? 'Main Course Instructor' }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($courseInstructorProfile && !empty($courseInstructorProfile['expertise']) && trim($courseInstructorProfile['expertise']) !== '')
                                            @php
                                                $expertiseItems = array_filter(explode(',', $courseInstructorProfile['expertise']), fn($e) => trim($e) !== '');
                                            @endphp
                                            @if(count($expertiseItems) > 0)
                                            <div class="ms-5 mt-2">
                                                <div class="small">
                                                    <strong>Expertise:</strong>
                                                    @foreach(array_slice($expertiseItems, 0, 3) as $expertise)
                                                        <span class="badge rounded-pill px-2 py-1 me-1 expertise-badge-small">{{ trim($expertise) }}</span>
                                                    @endforeach
                                                    @if(count($expertiseItems) > 3)
                                                        <span class="badge rounded-pill bg-light text-dark">+{{ count($expertiseItems) - 3 }} more</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Related Lectures Card (only show for course lectures) -->
                            @if($course && $course->lectures && $course->lectures->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h3 class="mb-0">Related Lectures</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($course->lectures->take(5) as $relatedLecture)
                                            <li class="list-group-item {{ $relatedLecture->id === $lecture->id ? 'bg-light' : '' }}">
                                                <a href="{{ route('lecture.detail', ['course' => $course->slug, 'lecture' => $relatedLecture->slug]) }}" class="text-decoration-none {{ $relatedLecture->id === $lecture->id ? 'text-primary fw-bold' : '' }}">
                                                    <i class="fas fa-play-circle me-2 {{ $relatedLecture->id === $lecture->id ? 'text-primary' : 'text-muted' }}"></i>
                                                    {{ $relatedLecture->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-4">Student Reviews</h3>

                            <!-- Overall Rating -->
                            <div class="course-rating-summary mb-5">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-4 mb-md-0">
                                        <div class="overall-rating">
                                            <h1 class="display-1 fw-bold mb-0">{{ number_format($lecture->average_rating, 1) }}</h1>
                                            <div class="stars mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $lecture->average_rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif ($i - 0.5 <= $lecture->average_rating)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-muted">{{ $lecture->rating_count }} {{ Str::plural('review', $lecture->rating_count) }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <div class="rating-bars">
                                            @php
                                                // Calculate percentage for each star rating
                                                $totalRatings = $lecture->rating_count > 0 ? $lecture->rating_count : 1;
                                                $starCounts = [
                                                    5 => $lecture->ratings()->where('rating', 5)->count(),
                                                    4 => $lecture->ratings()->where('rating', 4)->count(),
                                                    3 => $lecture->ratings()->where('rating', 3)->count(),
                                                    2 => $lecture->ratings()->where('rating', 2)->count(),
                                                    1 => $lecture->ratings()->where('rating', 1)->count(),
                                                ];
                                            @endphp
                                            
                                            @for ($i = 5; $i >= 1; $i--)
                                                @php
                                                    $percentage = ($starCounts[$i] / $totalRatings) * 100;
                                                @endphp
                                                <div class="rating-bar d-flex align-items-center mb-2">
                                                    <div class="stars me-2 rating-stars-container">
                                                        {{ $i }} <i class="fas fa-star text-warning"></i>
                                                    </div>
                                                    <div class="progress flex-grow-1 me-3 rating-progress-bar">
                                                        <div class="progress-bar bg-warning" role="progressbar" data-width="{{ $percentage }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <span class="text-muted small">{{ $starCounts[$i] }}</span>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reviews List -->
                            <div class="reviews-list">
                                @if($lecture->ratings()->where('is_approved', true)->where('show_publicly', true)->count() > 0)
                                    @foreach($lecture->ratings()->where('is_approved', true)->where('show_publicly', true)->with('user')->latest()->get() as $rating)
                                        <div class="review-item mb-4 p-4 border rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <h4 class="mb-0">{{ $rating->user->name }}</h4>
                                                    <div class="stars my-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $rating->rating)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="text-muted">
                                                    <small>{{ $rating->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>
                                            
                                            @if($rating->comment)
                                                <p class="review-text mb-0">{{ $rating->comment }}</p>
                                            @else
                                                <p class="text-muted fst-italic mb-0">No comment provided</p>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5">
                                        <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                                        <h4>No Reviews Yet</h4>
                                        <p class="text-muted">Be the first to review this lecture!</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h3 class="mb-0">Rating Overview</h3>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <h1 class="display-4 fw-bold me-3">4.0</h1>
                                        <div>
                                            <div class="mb-1">
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="far fa-star text-warning"></i>
                                            </div>
                                            <p class="text-muted mb-0">Lecture Rating</p>
                                        </div>
                                    </div>

                                    <!-- Rating Breakdown -->
                                    <div class="mb-2 d-flex align-items-center">
                                        <div class="me-2">5 stars</div>
                                        <div class="progress flex-grow-1 rating-progress-small">
                                            <div class="progress-bar bg-warning progress-bar-65" role="progressbar"></div>
                                        </div>
                                        <div class="ms-2">65%</div>
                                    </div>
                                    <div class="mb-2 d-flex align-items-center">
                                        <div class="me-2">4 stars</div>
                                        <div class="progress flex-grow-1 rating-progress-small">
                                            <div class="progress-bar bg-warning progress-bar-25" role="progressbar"></div>
                                        </div>
                                        <div class="ms-2">25%</div>
                                    </div>
                                    <div class="mb-2 d-flex align-items-center">
                                        <div class="me-2">3 stars</div>
                                        <div class="progress flex-grow-1 rating-progress-small">
                                            <div class="progress-bar bg-warning progress-bar-5" role="progressbar"></div>
                                        </div>
                                        <div class="ms-2">5%</div>
                                    </div>
                                    <div class="mb-2 d-flex align-items-center">
                                        <div class="me-2">2 stars</div>
                                        <div class="progress flex-grow-1 rating-progress-small">
                                            <div class="progress-bar bg-warning progress-bar-3" role="progressbar"></div>
                                        </div>
                                        <div class="ms-2">3%</div>
                                    </div>
                                    <div class="mb-2 d-flex align-items-center">
                                        <div class="me-2">1 star</div>
                                        <div class="progress flex-grow-1 rating-progress-small">
                                            <div class="progress-bar bg-warning progress-bar-2" role="progressbar"></div>
                                        </div>
                                        <div class="ms-2">2%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success and Error Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <style>
        .course-image-container {
            height: 200px;
            overflow: hidden;
        }

        .course-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .check-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
            list-style-type: none;
        }

        .check-list li:before {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #28a745;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/lecture-detail.css') }}">
    <script src="{{ asset('js/lecture-detail.min.js') }}"></script>
</div>
