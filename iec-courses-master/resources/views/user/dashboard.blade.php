
<x-app-layout>
        <div class="container pt-3 pb-0">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-lg border-0 mb-0">
                    <div class="card-header bg-gradient-primary py-3 px-4">
            <div class="row">
                            <div class="col-md-8 animate-up">
                                <h3 class="text-white mb-0">My Learning Dashboard</h3>
                                <p class="text-white opacity-8 mb-0">Welcome back, {{ Auth::user()->name }}</p>
                            </div>
                            <div class="col-md-4 text-end d-flex align-items-center justify-content-end gap-2 banner-actions">
                                <button type="button" class="btn btn-glass btn-sm" data-bs-toggle="modal" data-bs-target="#suggestionModal">
                                    <i class="fas fa-lightbulb me-1"></i> Suggestion & Feedback
                                </button>
                                <a href="{{ route('user.payment-details') }}" class="btn btn-glass btn-sm">
                                    <i class="fas fa-receipt me-1"></i> Payment Details
                                </a>
                                <a href="{{ route('courses') }}" class="btn btn-glass btn-sm">
                                    <i class="fas fa-search me-1"></i> Browse New Courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

        <!-- Progress Stats Cards -->
        <div class="row mb-3 mt-2">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 card-stats">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-xs mb-1 text-uppercase font-weight-bold opacity-7">Active Courses</p>
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ count($purchasedItems) }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape-stats bg-gradient-primary shadow-primary text-center rounded-circle p-3">
                                    <i class="fas fa-book text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 card-stats">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-xs mb-1 text-uppercase font-weight-bold opacity-7">Pending Orders</p>
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ count($pendingItems) }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape-stats bg-gradient-warning shadow-warning text-center rounded-circle p-3">
                                    <i class="fas fa-hourglass-half text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 card-stats">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-xs mb-1 text-uppercase font-weight-bold opacity-7">Quiz Results</p>
                                    <h3 class="font-weight-bolder mb-0">
                                        {{ $quizStats['total'] ?? 0 }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape-stats bg-gradient-info shadow-info text-center rounded-circle p-3">
                                    <i class="fas fa-chart-bar text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('user.certificates') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 hover-card card-stats header-certificate-card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-xs mb-1 text-uppercase font-weight-bold text-dark opacity-7">My Certificates</p>
                                        <h3 class="font-weight-bolder mb-0 text-dark">
                                            @php
                                                $certificateCount = Auth::user()->certificates()->count();
                                            @endphp
                                            {{ $certificateCount }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape-stats bg-gradient-success shadow-success text-center rounded-circle p-3">
                                        <i class="fas fa-certificate text-white text-lg opacity-10"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 section-card">
                        <div class="card-header bg-white p-4 d-flex align-items-center border-0 rounded-top">
                            <h5 class="mb-0 flex-grow-1 header-title">
                                <span class="icon-box-sm bg-primary-soft me-2"><i class="fas fa-graduation-cap text-primary"></i></span>
                                My Courses
                            </h5>
                            <div class="btn-group glass-btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary active" id="view-grid">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="view-list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if(count($purchasedItems) > 0)
                            <!-- Grid View -->
                            <div id="grid-view">
                                <div class="row g-3 w-100">
                                    @foreach($purchasedItems as $item)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100 shadow-sm border-0 course-card">
                                            <div class="position-relative">
                                                <img src="{{ $item['type'] == 'course' ?
                                                    (isset($item['item']->image_path) ? Storage::url($item['item']->image_path) : 'https://via.placeholder.com/300x200?text=Course+Image') :
                                                    (isset($item['item']->image_path) ? Storage::url($item['item']->image_path) : (isset($item['item']->course->image_path) ? Storage::url($item['item']->course->image_path) : 'https://via.placeholder.com/300x200?text=Lecture+Image')) }}"
                                                    class="card-img-top course-card-img" alt="{{ $item['type'] == 'course' ? $item['item']->name : $item['item']->name }}">

                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge badge-pill badge-fancy px-3 py-2">
                                                        <i class="fas fa-{{ $item['type'] == 'course' ? 'book' : 'file-alt' }} me-1"></i>
                                                        {{ strtoupper($item['type']) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold mb-2">{{ $item['type'] == 'course' ? $item['item']->name : $item['item']->name }}</h6>
                                                
                                                @if($item['type'] == 'lecture' && $item['item']->course)
                                                    <p class="text-muted small mb-2">
                                                        <i class="fas fa-book me-1"></i> From: {{ $item['item']->course->name }}
                                                    </p>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i> {{ $item['order_date']->format('M d, Y') }}
                                                    </small>

                                                    @if(isset($item['expires_at']) && $item['expires_at'])
                                                        <small class="{{ now()->gt($item['expires_at']) ? 'text-danger' : 'text-warning' }}">
                                                            <i class="fas fa-{{ now()->gt($item['expires_at']) ? 'exclamation-circle' : 'clock' }} me-1"></i>
                                                            {{ now()->gt($item['expires_at']) ? 'Expired' : 'Expires' }}
                                                        </small>
                                                    @endif
                                                </div>

                                                @php
                                                    // Calculate progress percentage
                                                    if ($item['type'] == 'course') {
                                                        $progressPercent = $item['item']->getProgressPercentageForUser(Auth::id());
                                                        $completedLectures = $item['item']->getCompletedLectureCountForUser(Auth::id());
                                                        $totalLectures = $item['item']->total_lecture_count;
                                                    } else {
                                                        $progress = $item['item']->getProgressForUser(Auth::id());
                                                        $progressPercent = $progress ? $progress->progress_percent : 0;
                                                        $completedLectures = 0;
                                                        $totalLectures = 1;
                                                    }
                                                @endphp

                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <small class="text-muted fw-bold">Learning Progress</small>
                                                        <small class="fw-bold text-success">{{ number_format($progressPercent, 0) }}%</small>
                                                    </div>
                                                    <div class="progress progress-thin">
                                                        <div class="progress-bar bg-success dynamic-progress-bar" role="progressbar"
                                                             data-width="{{ $progressPercent }}"
                                                             aria-valuenow="{{ $progressPercent }}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                </div>

                                                <div class="d-grid gap-2">
                                                    @if($item['type'] == 'course')
                                                        <a href="{{ route('user.course.purchased', $item['item']->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-play-circle me-1"></i> Continue Learning
                                                        </a>
                                                        
                                                        @php
                                                            $course = $item['item'];
                                                            $user = Auth::user();
                                                            $existingCert = $user->certificates()->where('course_id', $course->id)->whereNull('lecture_id')->first();
                                                            $hasCertificate = $existingCert ? true : false;
                                                            $certificateId = $existingCert ? $existingCert->id : null;
                                                            $hasPendingRequest = $user->hasPendingCertificateRequest($course->id);
                                                            $canRequestCertificate = $user->canRequestCertificate($course->id);
                                                            
                                                            // Check quiz status and course completion
                                                            $allQuizzesPassed = true;
                                                            $quizMessage = '';
                                                            
                                                            // Count completed lectures with quizzes
                                                            $lecturesWithQuizzes = 0;
                                                            $completedQuizzes = 0;
                                                            
                                                            foreach($course->lectures as $lecture) {
                                                                if($lecture->quiz) {
                                                                    $lecturesWithQuizzes++;

                                                                    // Check if user has ever passed this quiz (not just the latest attempt)
                                                                    if($lecture->quiz->isPassed($user->id)) {
                                                                        $completedQuizzes++;
                                                                    } else {
                                                                        // Check if there's a pending review attempt
                                                                        $quizAttempt = $lecture->quiz->attempts()->where('user_id', $user->id)->latest()->first();

                                                                        if(!$quizAttempt) {
                                                                            $allQuizzesPassed = false;
                                                                            $quizMessage = 'Please complete all quizzes to request certificate';
                                                                            break;
                                                                        } elseif($quizAttempt->isPendingReview()) {
                                                                            $allQuizzesPassed = false;
                                                                            $quizMessage = 'Some quizzes are pending review';
                                                                            break;
                                                                        } else {
                                                                            $allQuizzesPassed = false;
                                                                            $quizMessage = 'Please pass all quizzes to request certificate';
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // If all quizzes with lectures are passed, allow certificate
                                                            if($lecturesWithQuizzes > 0 && $completedQuizzes === $lecturesWithQuizzes) {
                                                                $allQuizzesPassed = true;
                                                            }
                                                        @endphp
                                                        
                                                        @if($hasCertificate)
                                                            <a href="{{ route('user.certificate.view', $certificateId) }}" class="btn btn-success btn-sm">
                                                                <i class="fas fa-certificate me-1"></i> View Certificate
                                                            </a>
                                                        @elseif($hasPendingRequest)
                                                            <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                <i class="fas fa-clock me-1"></i> Certificate Pending
                                                            </button>
                                                        @elseif($canRequestCertificate)
                                                            <form action="{{ route('user.request-certificate', $course->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                                    <i class="fas fa-certificate me-1"></i> View Certificate
                                                                </button>
                                                            </form>
                                                        @else
                                                            {{-- Show disabled button with appropriate message --}}
                                                            @php
                                                                // Determine the specific reason for lock
                                                                if ($progressPercent < 90) {
                                                                    $lockMessage = 'Please complete at least 90% of the course';
                                                                } elseif(!$allQuizzesPassed) {
                                                                    $lockMessage = $quizMessage;
                                                                } else {
                                                                    $lockMessage = 'Course requirements not met';
                                                                }
                                                            @endphp
                                                            <button type="button" class="btn btn-secondary btn-sm" disabled title="{{ $lockMessage }}">
                                                                <i class="fas fa-lock me-1"></i> Certificate Locked
                                                            </button>
                                                            <small class="text-muted mt-1 d-block">{{ $lockMessage }}</small>
                                                        @endif
                                                    @else
                                                        @if($item['item']->course)
                                                            <a href="{{ route('user.lecture.purchased', ['course' => $item['item']->course->id, 'lecture' => $item['item']->id]) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-play-circle me-1"></i> View Lecture
                                                            </a>
                                                        @else
                                                            <a href="{{ route('user.lecture.standalone', ['lecture' => $item['item']->id]) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-play-circle me-1"></i> View Lecture
                                                            </a>
                                                        @endif
                                                        
                                                        @php
                                                            $lecture = $item['item'];
                                                            $user = Auth::user();
                                                            $quiz = $lecture->quiz;
                                                            $courseId = $lecture->course?->id;
                                                            
                                                            $existingLectureCert = $courseId 
                                                                ? $user->certificates()->where('course_id', $courseId)->where('lecture_id', $lecture->id)->first() 
                                                                : $user->certificates()->whereNull('course_id')->where('lecture_id', $lecture->id)->first();
                                                                
                                                            $hasLectureCertificate = $existingLectureCert ? true : false;
                                                            $lectureCertificateId = $existingLectureCert ? $existingLectureCert->id : null;

                                                            $hasPendingLectureRequest = \App\Models\CertificateRequest::where('user_id', $user->id)->where('lecture_id', $lecture->id)->when($courseId, function($q) use ($courseId) { return $q->where('course_id', $courseId); }, function($q) { return $q->whereNull('course_id'); })->whereIn('status', ['pending', 'in_review'])->exists();
                                                            
                                                            // Check quiz status and lecture completion for lecture
                                                            $quizPassed = false;
                                                            $quizMessage = '';
                                                            $lectureProgress = $lecture->getProgressForUser($user->id);
                                                            $lectureProgressPercent = $lectureProgress ? $lectureProgress->progress_percent : 0;
                                                            
                                                            // Check if lecture is at least 90% complete
                                                            if($lectureProgressPercent < 90) {
                                                                $quizMessage = 'Please complete at least 90% of the lecture';
                                                            } elseif($quiz) {
                                                                // Check if user has ever passed this quiz (not just the latest attempt)
                                                                if($quiz->isPassed($user->id)) {
                                                                    $quizPassed = true;
                                                                } else {
                                                                    // Check if there's a pending review attempt
                                                                    $quizAttempt = $quiz->attempts()->where('user_id', $user->id)->latest()->first();
                                                                    if(!$quizAttempt) {
                                                                        $quizMessage = 'Please complete the quiz to request certificate';
                                                                    } elseif($quizAttempt->isPendingReview()) {
                                                                        $quizMessage = 'Quiz is pending review';
                                                                    } else {
                                                                        $quizMessage = 'Please pass the quiz to request certificate';
                                                                    }
                                                                }
                                                            } else {
                                                                $quizPassed = true; // No quiz required
                                                            }
                                                        @endphp
                                                        
                                                        @if($hasLectureCertificate)
                                                            <a href="{{ route('user.certificate.view', $lectureCertificateId) }}" class="btn btn-success btn-sm">
                                                                <i class="fas fa-certificate me-1"></i> View Certificate
                                                            </a>
                                                        @elseif($hasPendingLectureRequest)
                                                            <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                <i class="fas fa-clock me-1"></i> Certificate Pending
                                                            </button>
                                                        @elseif($quizPassed && $lectureProgressPercent >= 90)
                                                            <form action="{{ route('user.request-lecture-certificate', $lecture->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                                    <i class="fas fa-certificate me-1"></i> View Certificate
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-secondary btn-sm" disabled title="{{ $quizMessage }}">
                                                                <i class="fas fa-lock me-1"></i> Certificate Locked
                                                            </button>
                                                            <small class="text-muted mt-1 d-block">{{ $quizMessage }}</small>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- List View -->
                            <div class="table-responsive d-none" id="list-view">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Content</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Purchase Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Progress</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchasedItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="{{ $item['type'] == 'course' ?
                                                                (isset($item['item']->image_path) ? Storage::url($item['item']->image_path) : 'https://via.placeholder.com/300x200?text=Course+Image') :
                                                                (isset($item['item']->image_path) ? Storage::url($item['item']->image_path) : (isset($item['item']->course->image_path) ? Storage::url($item['item']->course->image_path) : 'https://via.placeholder.com/300x200?text=Lecture+Image')) }}"
                                                                class="avatar avatar-sm me-3" alt="{{ $item['item']->name }}">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $item['type'] == 'course' ? $item['item']->name : $item['item']->name }}</h6>
                                                            @if($item['type'] == 'lecture' && $item['item']->course)
                                                                <small class="text-muted">From: {{ $item['item']->course->name }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $item['type'] == 'course' ? 'primary' : 'info' }} rounded-pill">{{ ucfirst($item['type']) }}</span>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $item['order_date']->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    @if(isset($item['expires_at']) && $item['expires_at'])
                                                        <span class="badge bg-{{ now()->gt($item['expires_at']) ? 'danger' : 'warning' }}">
                                                            {{ now()->gt($item['expires_at']) ? 'Expired' : 'Expires: ' . $item['expires_at']->format('M d, Y') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        // Calculate progress percentage
                                                        if ($item['type'] == 'course') {
                                                            $progressPercent = $item['item']->getProgressPercentageForUser(Auth::id());
                                                            $completedLectures = $item['item']->getCompletedLectureCountForUser(Auth::id());
                                                            $totalLectures = $item['item']->total_lecture_count;
                                                        } else {
                                                            $progress = $item['item']->getProgressForUser(Auth::id());
                                                            $progressPercent = $progress ? $progress->progress_percent : 0;
                                                            $completedLectures = 0;
                                                            $totalLectures = 1;
                                                        }

                                                        // Determine text color class
                                                        if ($progressPercent > 75) {
                                                            $textColorClass = 'text-success';
                                                        } elseif ($progressPercent > 25) {
                                                            $textColorClass = 'text-primary';
                                                        } else {
                                                            $textColorClass = 'text-muted';
                                                        }
                                                    @endphp
                                                    <div class="d-flex align-items-center flex-column">
                                                        <div class="progress progress-mini">
                                                            <div class="progress-bar bg-success dynamic-progress-bar" role="progressbar"
                                                                data-width="{{ $progressPercent }}"
                                                                aria-valuenow="{{ $progressPercent }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="text-xs mt-1 {{ $textColorClass }}">
                                                            {{ number_format($progressPercent, 0) }}%
                                                            @if($item['type'] == 'course')
                                                                <span>({{ $completedLectures }}/{{ $totalLectures }})</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    @if($item['type'] == 'course')
                                                        <a href="{{ route('user.course.purchased', $item['item']->id) }}" class="btn btn-link text-primary mb-0">
                                                            <i class="fas fa-play-circle me-1"></i> Continue
                                                        </a>

                                                        @php
                                                            $course = $item['item'];
                                                            $user = Auth::user();
                                                            $existingCert = $user->certificates()->where('course_id', $course->id)->whereNull('lecture_id')->first();
                                                            $hasCertificate = $existingCert ? true : false;
                                                            $certificateId = $existingCert ? $existingCert->id : null;
                                                            $hasPendingRequest = $user->hasPendingCertificateRequest($course->id);
                                                            $canRequestCertificate = $user->canRequestCertificate($course->id);
                                                            
                                                            // Check quiz status and course completion
                                                            $allQuizzesPassed = true;
                                                            $quizMessage = '';
                                                            
                                                            // Count completed lectures with quizzes
                                                            $lecturesWithQuizzes = 0;
                                                            $completedQuizzes = 0;
                                                            
                                                            foreach($course->lectures as $lecture) {
                                                                if($lecture->quiz) {
                                                                    $lecturesWithQuizzes++;

                                                                    // Check if user has ever passed this quiz (not just the latest attempt)
                                                                    if($lecture->quiz->isPassed($user->id)) {
                                                                        $completedQuizzes++;
                                                                    } else {
                                                                        // Check if there's a pending review attempt
                                                                        $quizAttempt = $lecture->quiz->attempts()->where('user_id', $user->id)->latest()->first();

                                                                        if(!$quizAttempt) {
                                                                            $allQuizzesPassed = false;
                                                                            $quizMessage = 'Complete all quizzes';
                                                                            break;
                                                                        } elseif($quizAttempt->isPendingReview()) {
                                                                            $allQuizzesPassed = false;
                                                                            $quizMessage = 'Quizzes pending review';
                                                                            break;
                                                                        } else {
                                                                            $allQuizzesPassed = false;
                                                                            $quizMessage = 'Pass all quizzes';
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // If all quizzes with lectures are passed, allow certificate
                                                            if($lecturesWithQuizzes > 0 && $completedQuizzes === $lecturesWithQuizzes) {
                                                                $allQuizzesPassed = true;
                                                            }
                                                        @endphp

                                                        @if($hasCertificate)
                                                            <a href="{{ route('user.certificate.view', $certificateId) }}" class="btn btn-link text-success mb-0">
                                                                <i class="fas fa-certificate me-1"></i> Certificate
                                                            </a>
                                                        @elseif($hasPendingRequest)
                                                            <span class="badge bg-warning">Certificate Request Pending</span>
                                                        @elseif($canRequestCertificate)
                                                            <form action="{{ route('user.request-certificate', $course->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-secondary mb-0">
                                                                    <i class="fas fa-certificate me-1"></i> Request Certificate
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-secondary" title="{{ $quizMessage }}">
                                                                <i class="fas fa-lock me-1"></i> {{ $quizMessage }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        @if($item['item']->course)
                                                            <a href="{{ route('user.lecture.purchased', ['course' => $item['item']->course->id, 'lecture' => $item['item']->id]) }}" class="btn btn-link text-primary mb-0">
                                                                <i class="fas fa-play-circle me-1"></i> View
                                                            </a>
                                                        @else
                                                            <a href="{{ route('user.lecture.standalone', ['lecture' => $item['item']->id]) }}" class="btn btn-link text-primary mb-0">
                                                                <i class="fas fa-play-circle me-1"></i> View
                                                            </a>
                                                        @endif
                                                        
                                                        @php
                                                            $lecture = $item['item'];
                                                            $user = Auth::user();
                                                            $quiz = $lecture->quiz;
                                                            $courseId = $lecture->course?->id;
                                                            
                                                            $existingLectureCert = $courseId 
                                                                ? $user->certificates()->where('course_id', $courseId)->where('lecture_id', $lecture->id)->first() 
                                                                : $user->certificates()->whereNull('course_id')->where('lecture_id', $lecture->id)->first();
                                                                
                                                            $hasLectureCertificate = $existingLectureCert ? true : false;
                                                            $lectureCertificateId = $existingLectureCert ? $existingLectureCert->id : null;
                                                            
                                                            $hasPendingLectureRequest = \App\Models\CertificateRequest::where('user_id', $user->id)->where('lecture_id', $lecture->id)->when($courseId, function($q) use ($courseId) { return $q->where('course_id', $courseId); }, function($q) { return $q->whereNull('course_id'); })->whereIn('status', ['pending', 'in_review'])->exists();
                                                            
                                                            // Check quiz status and lecture completion for lecture
                                                            $quizPassed = false;
                                                            $quizMessage = '';
                                                            $lectureProgress = $lecture->getProgressForUser($user->id);
                                                            $lectureProgressPercent = $lectureProgress ? $lectureProgress->progress_percent : 0;
                                                            
                                                            // Check if lecture is at least 90% complete
                                                            if($lectureProgressPercent < 90) {
                                                                $quizMessage = 'Complete 90% of lecture';
                                                            } elseif($quiz) {
                                                                // Check if user has ever passed this quiz (not just the latest attempt)
                                                                if($quiz->isPassed($user->id)) {
                                                                    $quizPassed = true;
                                                                } else {
                                                                    // Check if there's a pending review attempt
                                                                    $quizAttempt = $quiz->attempts()->where('user_id', $user->id)->latest()->first();
                                                                    if(!$quizAttempt) {
                                                                        $quizMessage = 'Complete quiz';
                                                                    } elseif($quizAttempt->isPendingReview()) {
                                                                        $quizMessage = 'Quiz pending review';
                                                                    } else {
                                                                        $quizMessage = 'Pass quiz';
                                                                    }
                                                                }
                                                            } else {
                                                                $quizPassed = true; // No quiz required
                                                            }
                                                        @endphp
                                                        
                                                        @if($hasLectureCertificate)
                                                            <a href="{{ route('user.certificate.view', $lectureCertificateId) }}" class="btn btn-success btn-sm">
                                                                <i class="fas fa-certificate me-1"></i> View Certificate
                                                            </a>
                                                        @elseif($hasPendingLectureRequest)
                                                            <span class="badge bg-warning">Certificate Pending</span>
                                                        @elseif($quizPassed && $lectureProgressPercent >= 90)
                                                            <form action="{{ route('user.request-lecture-certificate', $lecture->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-secondary mb-0">
                                                                    <i class="fas fa-certificate me-1"></i> Request Certificate
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-secondary" title="{{ $quizMessage }}">
                                                                <i class="fas fa-lock me-1"></i> {{ $quizMessage }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-book-open fa-4x text-muted opacity-5"></i>
                                </div>
                                <h4 class="font-weight-normal">No courses yet</h4>
                                <p class="text-muted mb-4">You don't have any purchased courses in your account yet.</p>
                                <a href="{{ route('courses') }}" class="btn btn-primary">Browse Courses</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Quiz Results Section -->
    @if(isset($quizAttempts) && count($quizAttempts) > 0)
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                    <div class="card shadow-sm border-0 section-card">
                        <div class="card-header bg-white p-4 d-flex align-items-center border-0 rounded-top">
                            <h5 class="mb-0 flex-grow-1 header-title">
                                <span class="icon-box-sm bg-info-soft me-2"><i class="fas fa-chart-line text-info"></i></span>
                                Quiz Results
                            </h5>
                            <a href="{{ route('user.quiz-results') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                <i class="fas fa-eye me-1"></i> View All Results
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-4 g-3">
                                <div class="col-md-3 col-6 text-center">
                                    <div class="p-3 stats-rounded bg-success-soft">
                                        <h4 class="text-success mb-1 fw-bold">{{ $quizStats['passed'] ?? 0 }}</h4>
                                        <small class="text-uppercase text-xs fw-bold text-success opacity-8">Passed</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <div class="p-3 stats-rounded bg-danger-soft">
                                        <h4 class="text-danger mb-1 fw-bold">{{ $quizStats['failed'] ?? 0 }}</h4>
                                        <small class="text-uppercase text-xs fw-bold text-danger opacity-8">Failed</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <div class="p-3 stats-rounded bg-warning-soft">
                                        <h4 class="text-warning mb-1 fw-bold">{{ $quizStats['pending'] ?? 0 }}</h4>
                                        <small class="text-uppercase text-xs fw-bold text-warning opacity-8">Pending</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <div class="p-3 stats-rounded bg-info-soft">
                                        <h4 class="text-info mb-1 fw-bold">{{ $quizStats['total'] ?? 0 }}</h4>
                                        <small class="text-uppercase text-xs fw-bold text-info opacity-8">Total</small>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quiz</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Course</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Score</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quizAttempts->take(5) as $attempt)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $attempt->quiz->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $attempt->quiz->lecture->name }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $attempt->quiz->lecture->course?->name ?? 'Standalone Lecture' }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $attempt->score ?? 0 }}/{{ $attempt->total_points ?? 0 }}
                                                        <span class="text-secondary">({{ $attempt->percentage_score ?? 0 }}%)</span>
                                                    </p>
                                                </td>
                                                <td>
                                                    @if($attempt->isPassed())
                                                        <span class="badge bg-success">Passed</span>
                                                    @elseif($attempt->isFailed())
                                                        <span class="badge bg-danger">Failed</span>
                                                    @elseif($attempt->isPendingReview())
                                                        <span class="badge bg-warning">Pending Review</span>
                                                    @else
                                                        <span class="badge bg-secondary">In Progress</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'In Progress' }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    @if($attempt->quiz->lecture->course)
                                                        @if($attempt->isCompleted())
                                                            <a href="{{ route('quiz.result', [
                                                                'course' => $attempt->quiz->lecture->course->id,
                                                                'lecture' => $attempt->quiz->lecture->id,
                                                                'quiz' => $attempt->quiz->id,
                                                                'attempt' => $attempt->id
                                                            ]) }}" class="btn btn-link text-primary mb-0">
                                                                <i class="fas fa-eye me-1"></i> View Result
                                                            </a>
                                                        @else
                                                            <a href="{{ route('quiz.show', [
                                                                'course' => $attempt->quiz->lecture->course->id,
                                                                'lecture' => $attempt->quiz->lecture->id,
                                                                'quiz' => $attempt->quiz->id
                                                            ]) }}" class="btn btn-link text-warning mb-0">
                                                                <i class="fas fa-play me-1"></i> Continue
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Standalone Quiz</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(count($pendingItems) > 0)
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 col-md-12 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white p-3">
                            <h5 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>Pending Approvals</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="alert alert-info border-0 bg-info bg-gradient text-white" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>Payment Processing:</strong> These items are waiting for admin payment approval. You'll get access once your payment is approved.
                            </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Content</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Order Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Order ID</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pendingItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="{{ $item['type'] == 'course' ?
                                                                (isset($item['item']->image_path) ? Storage::url($item['item']->image_path) : 'https://via.placeholder.com/300x200?text=Course+Image') :
                                                                (isset($item['item']->image_path) ? Storage::url($item['item']->image_path) : (isset($item['item']->course->image_path) ? Storage::url($item['item']->course->image_path) : 'https://via.placeholder.com/300x200?text=Lecture+Image')) }}"
                                                                class="avatar avatar-sm me-3" alt="{{ $item['item']->name }}">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $item['type'] == 'course' ? $item['item']->name : $item['item']->name }}</h6>
                                                </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $item['type'] == 'course' ? 'primary' : 'info' }} rounded-pill">{{ ucfirst($item['type']) }}</span>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $item['order_date']->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">#{{ $item['order_id'] }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('payment.pending', ['order' => $item['order_id']]) }}" class="btn btn-link text-warning mb-0">
                                                        <i class="fas fa-eye me-1"></i> View Status
                                                    </a>
                                                </td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

        <x-app.footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gridView = document.getElementById('grid-view');
            const listView = document.getElementById('list-view');
            const gridBtn = document.getElementById('view-grid');
            const listBtn = document.getElementById('view-list');

            if (gridBtn && listBtn && gridView && listView) {
                // Grid button click
                gridBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Show only grid view
                    gridView.classList.remove('d-none');
                    listView.classList.add('d-none');

                    // Update button states
                    gridBtn.classList.add('active');
                    listBtn.classList.remove('active');
                });

                // List button click
                listBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Show only list view
                    gridView.classList.add('d-none');
                    listView.classList.remove('d-none');

                    // Update button states
                    listBtn.classList.add('active');
                    gridBtn.classList.remove('active');
                });
            }
        });
    </script>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .hover-card {
            transition: all 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
        }
        .icon-shape {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .card-header.bg-gradient-primary {
            background-color: #7928CA !important;
            background-image: url('/dashboard_highlight.png?v={{ time() }}') !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            border: none !important;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0,0,0,0.15);
        }

        .card-stats {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 20px !important;
            border: 1px solid rgba(0,0,0,0.03) !important;
            background: white;
        }

        .card-stats:hover {
            transform: translateY(-7px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        }

        .icon-shape-stats {
            width: 54px;
            height: 54px;
            border-radius: 16px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .icon-shape-stats i {
            font-size: 1.4rem;
        }

        .header-title {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #2d3436;
        }

        .course-card {
            border-radius: 24px !important;
            border: 1px solid #f0f2f5 !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #ffffff;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        }

        .course-card-img {
            border-top-left-radius: 24px !important;
            border-top-right-radius: 24px !important;
            height: 190px !important;
            object-fit: cover;
        }

        .progress-thin {
            height: 8px !important;
            border-radius: 12px;
            background-color: #f1f3f5;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        }

        .dynamic-progress-bar {
            border-radius: 12px;
            background: linear-gradient(90deg, #67c23a 0%, #8be461 100%);
            box-shadow: 0 2px 4px rgba(103, 194, 58, 0.2);
        }

        .banner-actions .btn {
            font-weight: 700;
            padding: 0.7rem 1.4rem;
            text-transform: none;
            letter-spacing: 0.3px;
        }

        .animate-up {
            animation: cubic-bezier(0.2, 0, 0.2, 1) 0.8s slideUp forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-certificate-card {
            border-left: 4px solid #67c23a !important;
        }

        /* Stats colors overrides */
        .bg-gradient-primary { border-left: 4px solid #7928CA !important; }
        .bg-gradient-warning { border-left: 4px solid #f19937 !important; }
        .bg-gradient-info { border-left: 4px solid #55a6f8 !important; }

        @media (max-width: 768px) {
            .banner-actions {
                justify-content: center !important;
                margin-top: 1.5rem;
                width: 100%;
            }
            .banner-actions .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        .badge-fancy {
            background: rgba(255, 255, 255, 0.9);
            color: #2d3436;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-weight: 800;
            letter-spacing: 0.5px;
            font-size: 0.65rem;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .numbers h3 {
            font-size: 1.75rem;
            letter-spacing: -1px;
        }

        .section-card {
            border-radius: 20px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            overflow: hidden;
        }

        .icon-box-sm {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .bg-primary-soft { background-color: rgba(121, 40, 202, 0.1); }
        .bg-info-soft { background-color: rgba(23, 162, 184, 0.1); }
        .bg-success-soft { background-color: rgba(45, 206, 137, 0.1); }
        .bg-danger-soft { background-color: rgba(245, 57, 57, 0.1); }
        .bg-warning-soft { background-color: rgba(251, 207, 51, 0.1); }

        .stats-rounded {
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .stats-rounded:hover {
            transform: scale(1.05);
        }

        .glass-btn-group {
            background: rgba(0,0,0,0.03);
            padding: 4px;
            border-radius: 10px;
        }

        .glass-btn-group .btn {
            border: none !important;
            border-radius: 8px !important;
            padding: 5px 12px;
        }

        .glass-btn-group .btn.active {
            background-color: white !important;
            color: #7928CA !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        }

        .quiz-table th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #edf2f7 !important;
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 0.25rem;
            object-fit: cover;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Mobile-first responsive design for course cards */
        #grid-view {
            display: block !important;
        }

        #grid-view .row {
            display: flex !important;
            flex-wrap: wrap !important;
            width: 100% !important;
            gap: 1.25rem !important;
        }

        #grid-view .col-12 {
            display: block !important;
            flex: 0 0 100% !important;
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        #grid-view .course-card {
            display: block !important;
            visibility: visible !important;
            min-height: 350px;
        }

        @media (min-width: 768px) {
            #grid-view .col-12,
            #grid-view .col-md-6 {
                flex: 0 0 calc(50% - 0.625rem) !important;
                width: calc(50% - 0.625rem) !important;
                max-width: calc(50% - 0.625rem) !important;
            }
        }

        @media (min-width: 1024px) {
            #grid-view .col-12,
            #grid-view .col-md-6,
            #grid-view .col-lg-4 {
                flex: 0 0 calc(33.333333% - 0.833rem) !important;
                width: calc(33.333333% - 0.833rem) !important;
                max-width: calc(33.333333% - 0.833rem) !important;
            }
        }

    </style>
</x-app-layout>


@include('suggestions.modal')
