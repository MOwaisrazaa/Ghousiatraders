@section('title', $course->name . ' - Islamic Finance Courses')
@section('meta_description', Str::limit(strip_tags($course->description), 160))
@section('meta_keywords', 'Islamic Finance, ' . $course->name . ', ' . $course->instructor)

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('course.detail', $course->slug) }}">
    <meta property="og:title" content="{{ $course->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($course->description), 160) }}">
    <meta property="og:image" content="{{ $course->image_path ? Storage::url($course->image_path) : asset('assets/img/default-course.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('course.detail', $course->slug) }}">
    <meta property="twitter:title" content="{{ $course->name }}">
    <meta property="twitter:description" content="{{ Str::limit(strip_tags($course->description), 160) }}">
    <meta property="twitter:image" content="{{ $course->image_path ? Storage::url($course->image_path) : asset('assets/img/default-course.png') }}">
@endpush

<div class="container py-5">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $course->name }}</li>
        </ol>
    </nav>

    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "{{ $course->name }}",
      "description": "{{ Str::limit(strip_tags($course->description), 160) }}",
      "provider": {
        "@type": "Organization",
        "name": "Islamic Finance Courses",
        "sameAs": "{{ config('app.url') }}"
      },
      "image": "{{ $course->image_path ? Storage::url($course->image_path) : asset('assets/img/default-course.png') }}",
      "offers": {
        "@type": "Offer",
        "category": "Paid",
        "priceCurrency": "PKR",
        "price": "{{ $course->is_free ? '0' : $course->weekly_price }}"
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $course->average_rating }}",
        "reviewCount": "{{ $course->rating_count > 0 ? $course->rating_count : 1 }}"
      },
      "instructor": {
        "@type": "Person",
        "name": "{{ $course->instructor ?? 'Instructor' }}"
      }
    }
    </script>

    <!-- Course Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">{{ $course->name }}</h1>
            <p class="lead mb-3">{{ Str::limit(strip_tags($course->description), 200) }}</p>

            <!-- Instructor, Reviews, etc -->
            <div class="d-flex flex-wrap align-items-center mb-3">
                <span class="me-3 mb-2">
                    <i class="fas fa-user-tie me-1"></i> {{ $course->instructor ?? 'Instructor Name' }}
                </span>
                <span class="me-3 mb-2">
                    <i class="fas fa-book me-1"></i> {{ count($course->lectures) }} lectures
                </span>
                <span class="me-3 mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $course->average_rating)
                            <i class="fas fa-star text-warning"></i>
                        @elseif ($i - 0.5 <= $course->average_rating)
                            <i class="fas fa-star-half-alt text-warning"></i>
                        @else
                            <i class="far fa-star text-warning"></i>
                        @endif
                    @endfor
                    <span class="ms-1">({{ number_format($course->average_rating, 1) }})</span>
                </span>
                <span class="mb-2">
                    <i class="fas fa-users me-1"></i> {{ $course->rating_count }} {{ Str::plural('review', $course->rating_count) }}
                </span>
            </div>

            <!-- Last Updated -->
            <p class="text-muted small">
                <i class="fas fa-history me-1"></i> Last updated {{ $course->updated_at->format('M Y') }}
            </p>
            @if($course->intro_video_url)
                            <!-- Course Intro Video -->
                            <div class="mb-5">
                                <h4 class="mb-3">Course Introduction</h4>
                                <div class="ratio ratio-16x9 w-75 mx-auto">
                                    <iframe
                                        src="{{ str_replace('watch?v=', 'embed/', $course->intro_video_url) }}?rel=0"
                                        title="Course Introduction Video"
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
                    <img src="{{ Storage::url($course->image_path) }}"
                        alt="{{ $course->name }}"
                        class="card-img-top">
                </div>

                <div class="card-body">
                    <!-- Price -->
                    @if($course->is_free)
                        <div class="alert alert-success text-center mb-3">
                            <h3 class="fw-bold mb-0"><i class="fas fa-gift me-2"></i>FREE COURSE</h3>
                        </div>
                    @else
                        <h3 class="fw-bold text-center mb-3">Rs {{ number_format($course->weekly_price, 2) }}</h3>
                    @endif

                    <!-- Add to Cart Button or Free Enrollment Button -->
                    <div class="d-grid gap-2">
                        @if(auth()->check() && $this->isPurchased($course->id, 'course'))
                            <button type="button" class="btn btn-success btn-lg" disabled>
                                <i class="fas fa-check-circle me-1"></i> Enrolled
                            </button>
                        @elseif(auth()->check() && $this->isPending($course->id, 'course'))
                            <button type="button" class="btn btn-warning btn-lg" disabled>
                                <i class="fas fa-clock me-1"></i> Pending Approval
                            </button>
                        @elseif($course->is_free && auth()->check())
                            <button type="button" wire:click="enrollInFreeCourse({{ $course->id }})" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-1"></i> Enroll Now (Free)
                            </button>
                        @elseif(auth()->check() && \App\Models\Shoppingcart::where('user_id', auth()->id())->where('course_id', $course->id)->exists())
                            <button type="button" class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-check me-1"></i> Added to Cart
                            </button>
                        @elseif(!auth()->check() && $course->is_free)
                            <a href="{{ route('sign-in') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i> Sign In to Enroll Free
                            </a>
                        @elseif(!auth()->check())
                            <a href="{{ route('sign-in') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i> Sign In to Purchase
                            </a>
                        @else
                            <button type="button" wire:click="addToCart({{ $course->id }})" class="btn btn-primary btn-lg">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                            </button>
                        @endif
                    </div>

                    <!-- Course Includes -->
                    <div class="mt-4">
                        <h3>This course includes:</h3>
                        <ul class="list-unstyled">
                            @forelse($course->getFeaturesByType('includes') as $feature)
                                @if(stripos($feature->feature_text, 'lifetime access') === false)
                                    <li class="mb-2"><i class="fas fa-check me-2"></i> {{ $feature->feature_text }}</li>
                                @endif
                            @empty
                                <li class="mb-2"><i class="fas fa-video me-2"></i> {{ count($course->lectures) }} on-demand lectures</li>
                                <li class="mb-2"><i class="fas fa-question-circle me-2"></i> Interactive quizzes and assessments</li>
                                <li class="mb-2"><i class="fas fa-comments me-2"></i> Access to Q&A and instructor support</li>
                                <li class="mb-2"><i class="fas fa-chart-line me-2"></i> Track your learning progress</li>
                                <li class="mb-2"><i class="fas fa-certificate me-2"></i> Certificate of completion</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Content Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" id="courseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculum" type="button" role="tab" aria-controls="curriculum" aria-selected="false">Curriculum</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                </li>
            </ul>

            <div class="tab-content" id="courseTabContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-4">About This Course</h3>
                            <div class="course-description mb-5">
                                {!! $course->description !!}
                            </div>

                            @if($course->isPurchaseRestricted())
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> This course can only be purchased as a complete package. Individual lectures are not available for separate purchase.
                            </div>
                            @endif

                            <!-- Course Instructor Highlight -->
                            @if($course->instructor)
                            <div class="bg-light p-3 rounded mb-4">
                                <div class="d-flex align-items-center">
                                    @php
                                        $profile = $instructorProfiles[$course->instructor] ?? null;
                                    @endphp

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
                                            width="60" height="60"
                                            class="instructor-avatar-img"
                                            alt="{{ $course->instructor }}">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 instructor-avatar-small">
                                            {{ strtoupper(substr($course->instructor, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="mb-1"><strong>Course taught by:</strong></p>
                                        <h4 class="mb-1">{{ $course->instructor }}</h4>
                                        <p class="text-muted mb-0 small">
                                            @if($course->lectures->pluck('instructor')->filter()->unique()->count() > 1)
                                                Main instructor, with guest instructors for specific lectures
                                            @else
                                                Main course instructor
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <h4 class="mb-3">What You'll Learn</h4>
                            <div class="row mb-5">
                                @php
                                    $learnFeatures = $course->getFeaturesByType('learn');
                                    $halfCount = ceil($learnFeatures->count() / 2);
                                    $firstHalf = $learnFeatures->take($halfCount);
                                    $secondHalf = $learnFeatures->skip($halfCount);
                                @endphp

                                <div class="col-md-6">
                                    <ul class="check-list">
                                        @forelse($firstHalf as $feature)
                                            <li>{{ $feature->feature_text }}</li>
                                        @empty
                                            <li>Master the fundamentals of the subject</li>
                                            <li>Build real-world projects for your portfolio</li>
                                            <li>Learn industry best practices</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="check-list">
                                        @forelse($secondHalf as $feature)
                                            <li>{{ $feature->feature_text }}</li>
                                        @empty
                                            <li>Get personalized feedback on your work</li>
                                            <li>Connect with a community of learners</li>
                                            <li>Earn a certificate upon completion</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            <h4 class="mb-3">Requirements</h4>
                            <ul class="mb-5">
                                @forelse($course->getFeaturesByType('requirement') as $feature)
                                    <li>{{ $feature->feature_text }}</li>
                                @empty
                                    <li>Basic understanding of the subject</li>
                                    <li>Computer with internet connection</li>
                                    <li>Dedication and commitment to learning</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="col-md-4">
                            <!-- Instructor Card -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h3 class="mb-0">{{ $course->lectures->pluck('instructor')->filter()->unique()->count() > 1 ? 'Instructors' : 'Instructor' }}</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Primary Course Instructor -->
                                    @if($course->instructor)
                                        @php
                                            $profile = $instructorProfiles[$course->instructor] ?? null;
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
                                                    alt="{{ $course->instructor }}">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 instructor-avatar-medium">
                                                    {{ strtoupper(substr($course->instructor, 0, 1)) }}
                                                </div>
                                            @endif
                                        <div>
                                                <h6 class="mb-1">{{ $course->instructor }}</h6>
                                                <p class="text-muted mb-0 small">{{ $profile['title'] ?? 'Course Instructor' }}</p>
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
                                    @endif

                                    <!-- Additional Instructors from Lectures -->
                                    @php
                                        $lectureInstructors = $course->lectures->pluck('instructor')->filter()->unique()->diff([$course->instructor])->values();
                                    @endphp

                                    @if($lectureInstructors->count() > 0)
                                        <hr class="my-3">
                                        <h6 class="mb-2">Additional Instructors:</h6>

                                        @foreach($lectureInstructors as $instructorName)
                                            @php
                                                $instructorProfile = $instructorProfiles[$instructorName] ?? null;
                                            @endphp
                                            <div class="d-flex align-items-center mb-3">
                                                @if($instructorProfile && isset($instructorProfile['image_path']) && $instructorProfile['image_path'])
                                                    @php
                                                        // Try both storage paths to ensure image shows up
                                                        $imagePath = $instructorProfile['image_path'];
                                                        $storageUrl = Storage::url($imagePath);
                                                        $assetUrl = asset('storage/' . $imagePath);
                                                    @endphp
                                                    <img src="{{ $storageUrl }}"
                                                        onerror="this.onerror=null; this.src='{{ $assetUrl }}';"
                                                        class="rounded-circle me-2"
                                                        width="50" height="50"
                                                        class="instructor-avatar-img"
                                                        alt="{{ $instructorName }}">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 instructor-avatar-placeholder-small">
                                                        {{ strtoupper(substr($instructorName, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $instructorName }}</h6>
                                                    <p class="text-muted mb-0 small">{{ $instructorProfile['title'] ?? 'Lecture Instructor' }}</p>
                                                </div>
                                            </div>

                                            @if($instructorProfile && !empty($instructorProfile['expertise']) && trim($instructorProfile['expertise']) !== '')
                                                @php
                                                    $expertiseItems = array_filter(explode(',', $instructorProfile['expertise']), fn($e) => trim($e) !== '');
                                                @endphp
                                                @if(count($expertiseItems) > 0)
                                                <div class="ms-5 mb-3">
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
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Curriculum Tab -->
                <div class="tab-pane fade" id="curriculum" role="tabpanel" aria-labelledby="curriculum-tab">
                    <h3 class="mb-4">Course Curriculum</h3>

                    <div class="accordion" id="accordionCurriculum">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Module 1: Getting Started
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionCurriculum">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($course->lectures as $lecture)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-play-circle me-2 text-primary"></i>
                                                <a href="{{ route('lecture.detail', ['course' => $course->slug, 'lecture' => $lecture->slug]) }}" class="text-decoration-none">
                                                    {{ $lecture->name }}
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark me-2">
                                                    {{ $lecture->duration ?? 'N/A' }}
                                                </span>
                                                
                                                @if($course->isPurchaseRestricted())
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-lock me-1"></i>Course Only
                                                    </span>
                                                @elseif(auth()->check() && $this->isPurchased($lecture->id, 'lecture'))
                                                    <button type="button" class="btn btn-sm btn-success" disabled>
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                @elseif(auth()->check() && $this->isPending($lecture->id, 'lecture'))
                                                    <button type="button" class="btn btn-sm btn-warning" disabled>
                                                        <i class="fas fa-clock"></i>
                                                    </button>
                                                @elseif(auth()->check() && \App\Models\Shoppingcart::where('user_id', auth()->id())->where('lecture_id', $lecture->id)->exists())
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @else
                                                    <button type="button" wire:click="addToCart({{ $lecture->id }}, 'lecture')" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <h3 class="mb-4">Student Reviews</h3>

                    <!-- Overall Rating -->
                    <div class="course-rating-summary mb-5">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="overall-rating">
                                    <h1 class="display-1 fw-bold mb-0">{{ number_format($course->average_rating, 1) }}</h1>
                                    <div class="stars mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $course->average_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif ($i - 0.5 <= $course->average_rating)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted">{{ $course->rating_count }} {{ Str::plural('review', $course->rating_count) }}</p>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="rating-bars">
                                    @php
                                        // Calculate percentage for each star rating
                                        $totalRatings = $course->rating_count > 0 ? $course->rating_count : 1;
                                        $starCounts = [
                                            5 => $course->ratings()->where('rating', 5)->count(),
                                            4 => $course->ratings()->where('rating', 4)->count(),
                                            3 => $course->ratings()->where('rating', 3)->count(),
                                            2 => $course->ratings()->where('rating', 2)->count(),
                                            1 => $course->ratings()->where('rating', 1)->count(),
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
                        @if($course->ratings()->where('is_approved', true)->where('show_publicly', true)->count() > 0)
                            @foreach($course->ratings()->where('is_approved', true)->where('show_publicly', true)->with('user')->latest()->get() as $rating)
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
                                <p class="text-muted">Be the first to review this course!</p>
                            </div>
                        @endif
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
<link rel="stylesheet" href="{{ asset('css/course-detail.css') }}">
<script src="{{ asset('js/course-detail.min.js') }}"></script>

</div>
