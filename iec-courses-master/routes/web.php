<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CourseController;
use App\Livewire\Shoppingcart;
use App\Livewire\Checkout;
use App\Http\Controllers\PaymentController;
use App\Livewire\CourseName;
use App\Livewire\ManageCoursesLectures;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request; // Ensure this import is correct
use App\Http\Controllers\SmsController;
use App\Livewire\CoursesList;
use App\Livewire\CourseDetail;
use App\Livewire\LectureDetail;
use App\Livewire\CourseForm;
use App\Http\Controllers\CourseViewController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Admin\AnswerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoProxyController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LectureProgressController;
use App\Http\Controllers\User\CertificateController;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\DeviceController;
use App\Http\Middleware\XSS;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\Admin\SuggestionController as AdminSuggestionController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\PolaniController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Apply XSS protection middleware to all routes
Route::group(['middleware' => ['XSS']], function() {

Route::resource('users',UserController::class);

Route::get('/', [PolaniController::class, 'home'])->name('home');
Route::get('/shop', [PolaniController::class, 'collection'])->name('polani.collection');
Route::get('/women', [PolaniController::class, 'women'])->name('polani.women');
Route::get('/attars', [PolaniController::class, 'attars'])->name('polani.attars');
Route::get('/oud', [PolaniController::class, 'oud'])->name('polani.oud');
Route::get('/scented-candles', [PolaniController::class, 'scentedCandles'])->name('polani.scented-candles');
Route::get('/track-order', [PolaniController::class, 'trackOrder'])->name('polani.track-order');
Route::get('/product/{slug}', [PolaniController::class, 'product'])->name('polani.product');
Route::get('/about', [PolaniController::class, 'about'])->name('polani.about');
Route::get('/contact', [PolaniController::class, 'contact'])->name('polani.contact');
Route::get('/shipping-delivery', [PolaniController::class, 'shippingDelivery'])->name('polani.shipping');
Route::get('/returns-refunds', [PolaniController::class, 'returnsRefunds'])->name('polani.returns');
Route::get('/faq', [PolaniController::class, 'faq'])->name('polani.faq');
Route::post('/faq/ask', [PolaniController::class, 'storeFaqQuestion'])->name('polani.faq.ask');
Route::get('/terms-conditions', [PolaniController::class, 'termsConditions'])->name('polani.terms');
Route::get('/privacy-policy', [PolaniController::class, 'privacyPolicy'])->name('polani.privacy');
Route::get('/blogs', [PolaniController::class, 'blogs'])->name('polani.blogs');
Route::get('/blog/{slug}', [PolaniController::class, 'blogDetail'])->name('polani.blog.detail');
Route::post('/cart/add/{slug}', [PolaniController::class, 'addToCart'])->name('polani.cart.add');
Route::get('/page/{slug}', [PolaniController::class, 'showCustomPage'])->name('polani.custom-page');

// Public rating/review routes
Route::post('/products/{course}/rate', [App\Http\Controllers\RatingController::class, 'storeCourseRating'])->name('products.rate');
Route::post('/lectures/{lecture}/rate', [App\Http\Controllers\RatingController::class, 'storeLectureRating'])->name('lectures.rate');
Route::get('/products/{course}/ratings', [App\Http\Controllers\RatingController::class, 'getCourseRatings'])->name('products.ratings');
Route::get('/lectures/{lecture}/ratings', [App\Http\Controllers\RatingController::class, 'getLectureRatings'])->name('lectures.ratings');

// Public dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// API endpoint for countries (public, no auth required)
Route::get('/api/countries', function () {
    // Try to get from database first (if table exists and has data)
    try {
        if (\Schema::hasTable('countries')) {
            $countries = \App\Models\Country::active()->ordered()->get();
            if ($countries->count() > 0) {
                return response()->json($countries);
            }
        }
    } catch (\Exception $e) {
        // Fall back to JSON file
    }
    
    // Fallback to JSON file
    $jsonPath = public_path('assets/js/countrycode.json');
    if (file_exists($jsonPath)) {
        $countries = json_decode(file_get_contents($jsonPath), true);
        // Sort with Pakistan first
        usort($countries, function($a, $b) {
            if ($a['code'] === 'PK') return -1;
            if ($b['code'] === 'PK') return 1;
            return strcmp($a['name'], $b['name']);
        });
        return response()->json($countries);
    }
    return response()->json([], 404);
});

// API endpoint to get dial code for a country
Route::get('/api/countries/{code}/dial-code', function ($code) {
    // Try database first
    try {
        if (\Schema::hasTable('countries')) {
            $country = \App\Models\Country::where('code', strtoupper($code))->first();
            if ($country) {
                return response()->json(['dial_code' => $country->dial_code]);
            }
        }
    } catch (\Exception $e) {
        // Fall back to JSON file
    }
    
    // Fallback to JSON file
    $jsonPath = public_path('assets/js/countrycode.json');
    if (file_exists($jsonPath)) {
        $countries = json_decode(file_get_contents($jsonPath), true);
        foreach ($countries as $country) {
            if (strtoupper($country['code']) === strtoupper($code)) {
                return response()->json(['dial_code' => $country['dial_code']]);
            }
        }
    }
    
    // Default to Pakistan
    return response()->json(['dial_code' => '+92']);
});

// Protected dashboard features for authenticated users
Route::middleware(['auth', 'device.restriction'])->group(function() {
    Route::get('/my-dashboard', [DashboardController::class, 'authenticatedIndex'])
        ->name('authenticated.dashboard');
});

Route::get('/tables', function () {
    return view('tables');
})->name('tables')->middleware('auth');

// Wallet route removed - example page deleted

Route::get('/RTL', function () {
    return view('RTL');
})->name('RTL')->middleware('auth');

Route::get('/profile', function () {
    return view('account-pages.profile');
})->name('profile')->middleware('auth');

// Redirect /signin to /sign-in (use auth folder pages consistently)
Route::redirect('/signin', '/sign-in');

// Redirect /signup to /sign-up (use auth folder pages consistently)
Route::redirect('/signup', '/sign-up');

Route::get('/sign-up', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('sign-up');

Route::post('/sign-up', [RegisterController::class, 'store'])
    ->middleware(['guest', 'password.strength', 'XSS']);

Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');

Route::post('/sign-in', [LoginController::class, 'store'])
    ->middleware(['guest', 'XSS', 'throttle:5,1']);

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->middleware('guest')
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->middleware('guest')
    ->name('google.callback');

Route::get('/device-restriction-error', function () {
    return view('auth.device-restriction-error');
})->name('device.restriction.error');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

// Commented out guest login since we want users to sign up or sign in
// Route::get('/guest-login', [GuestController::class, 'login'])->name('guest.login');



// All debug and test routes removed for security compliance

Route::get('/account/profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/account/profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/account/users', [UserController::class, 'index'])->name('users-management')->middleware('auth');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create')->middleware('auth');
Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store')->middleware('auth');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/shopping-cart', [PolaniController::class, 'cart'])
    ->name('shopping-cart');
Route::get('/cart', function () {
    return redirect()->route('shopping-cart');
});
Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/payment/pending/{order}', [PaymentController::class, 'pending'])->name('payment.pending');

Route::get('/checkout', [PolaniController::class, 'checkout'])
    ->name('checkout');
Route::get('/course-name', CourseName::class)->middleware('auth');
Route::get('/manage-courses-lectures', ManageCoursesLectures::class)->name('manage.courses.lectures')->middleware('auth');

Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store']);

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/coupons', function () {
        return view('coupons');
    })->name('coupons');

    // Video Security Routes
    Route::post('/api/video/token', [App\Http\Controllers\VideoTokenController::class, 'generateVideoToken'])->name('video.token');
    Route::post('/api/video/data', [App\Http\Controllers\VideoTokenController::class, 'getVideoData'])->name('video.data');

});

// Removed test and debug routes for CSP compliance

// Ultra-secure video token generation route - video ID never exposed to client
Route::get('/api/secure-video-token/test-video', function(Request $request) {
    // Video ID is stored server-side only - never sent from client
    $videoId = 'ZbqPuFUXvfA'; // This is the test video ID, stored securely on server

    // In production, you would get this from database based on user permissions
    // Example: $videoId = getUserAuthorizedVideo($request->user()->id, $courseId, $lectureId);

    // Create encrypted token that expires in 1 hour
    $data = [
        'video_id' => $videoId,
        'expires_at' => time() + 3600, // 1 hour
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'timestamp' => time()
    ];

    // Encrypt the data using Laravel's encryption
    $token = encrypt(json_encode($data));

    return response()->json(['token' => $token]);
})->name('secure.video.token.test');

// Legacy route for backward compatibility (if needed)
Route::post('/api/secure-video-token', function(Request $request) {
    return response()->json(['error' => 'Direct video ID submission not allowed for security'], 403);
})->name('secure.video.token.legacy');

// Secure video proxy route using encrypted tokens
Route::get('/secure-video-proxy/{token}', function($token) {
    try {
        // Decrypt the token
        $data = json_decode(decrypt($token), true);

        // Validate token hasn't expired
        if ($data['expires_at'] < time()) {
            abort(403, 'Token expired');
        }

        // Additional security: validate IP and user agent
        if ($data['ip'] !== request()->ip() || $data['user_agent'] !== request()->userAgent()) {
            abort(403, 'Invalid token');
        }

        $videoId = $data['video_id'];

        // Return a view that embeds the YouTube video
        $embedHtml = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Video Player</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #000;
            overflow: hidden;
        }
        iframe {
            width: 100vw;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>
    <iframe
        src="https://www.youtube.com/embed/' . $videoId . '?autoplay=0&controls=1&rel=0&showinfo=0&modestbranding=1&iv_load_policy=3&origin=' . urlencode(request()->getSchemeAndHttpHost()) . '"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen>
    </iframe>
</body>
</html>';

        return response($embedHtml)->header('Content-Type', 'text/html');

    } catch (Exception $e) {
        abort(404, 'Invalid token');
    }
})->name('secure.video.proxy');

// Continue with other protected routes
Route::middleware('auth')->group(function () {

    // User Dashboard Routes
    Route::get('/my-courses', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/my-quiz-results', [App\Http\Controllers\UserDashboardController::class, 'quizResults'])->name('user.quiz-results');
    Route::get('/my-courses/{course}', [App\Http\Controllers\CourseController::class, 'userCourseDetail'])
        ->name('user.course.detail');

    // Payment Details Routes
    Route::get('/payment-details', [App\Http\Controllers\User\PaymentDetailsController::class, 'index'])->name('user.payment-details');
    Route::get('/payment-details/order/{order}', [App\Http\Controllers\User\PaymentDetailsController::class, 'show'])->name('user.order.details');

    // Suggestion and Feedback Routes
    Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');
    Route::get('/my-suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');

    // Course Lecture View - for enrolled users
    Route::get('/my-course/{course}', [CourseViewController::class, 'showCourse'])->name('course.view');

    // Purchased Course Detail Page with sidebar
    Route::get('/my-course-detail/{course}', [CourseViewController::class, 'showPurchasedCourse'])->name('user.course.purchased');

    // Purchased Lecture Detail Page
    Route::get('/my-lecture-detail/{course}/{lecture}', [CourseViewController::class, 'showPurchasedLecture'])->name('user.lecture.purchased');

    // Standalone Lecture Detail Page (without course)
    Route::get('/my-lecture/{lecture}', [CourseViewController::class, 'showStandaloneLecture'])->name('user.lecture.standalone');

    // AJAX route for fetching lecture content
    Route::get('/my-course/{course}/lecture-content/{lecture}', [CourseViewController::class, 'getLectureContent'])->name('lecture.content');

    // Question and Answer routes for users
    Route::post('/questions', [App\Http\Controllers\QuestionController::class, 'store'])
        ->name('questions.store');
    Route::post('/questions/get', [App\Http\Controllers\QuestionController::class, 'getQuestions'])
        ->name('questions.get');
    Route::post('/questions/limits', [App\Http\Controllers\QuestionController::class, 'getQuestionLimits'])
        ->name('questions.limits');



    // User Quiz Routes
    Route::get('/courses/{course}/lectures/{lecture}/quizzes/{quiz}', [QuizController::class, 'show'])
        ->name('quiz.show');
    Route::post('/courses/{course}/lectures/{lecture}/quizzes/{quiz}/begin', [QuizController::class, 'beginQuiz'])
        ->name('quiz.begin');
    Route::get('/courses/{course}/lectures/{lecture}/quizzes/{quiz}/attempts/{attempt}/question', [QuizController::class, 'showQuestion'])
        ->name('quiz.question');
    Route::post('/courses/{course}/lectures/{lecture}/quizzes/{quiz}/attempts/{attempt}/submit-question', [QuizController::class, 'submitQuestionAnswer'])
        ->name('quiz.submit-question');
    Route::post('/quiz/attempts/{attempt}/submit-answer', [QuizController::class, 'submitAnswer'])
        ->name('quiz.submit-answer');
    Route::post('/quiz/attempts/{attempt}/complete', [QuizController::class, 'complete'])
        ->name('quiz.complete');
    Route::get('/courses/{course}/lectures/{lecture}/quizzes/{quiz}/attempts/{attempt}/result', [QuizController::class, 'result'])
        ->name('quiz.result');

    // Lecture progress tracking routes
    Route::post('/lecture-progress/update', [LectureProgressController::class, 'updateProgress'])
        ->name('lecture-progress.update');
    Route::post('/lecture-progress/get', [LectureProgressController::class, 'getProgress'])
        ->name('lecture-progress.get');
    Route::post('/lecture-progress/complete', [LectureProgressController::class, 'markCompleted'])
        ->name('lecture-progress.complete');

    // Removed test route for progress tracking for CSP compliance

    // Certificate routes for users
    Route::get('/certificates', [CertificateController::class, 'index'])
        ->name('user.certificates');
    Route::post('/courses/{course}/request-certificate', [CertificateController::class, 'requestCertificate'])
        ->name('user.request-certificate');
    Route::post('/lectures/{lecture}/request-certificate', [CertificateController::class, 'requestLectureCertificate'])
        ->name('user.request-lecture-certificate');
    Route::get('/certificates/{certificate}/view', [CertificateController::class, 'view'])
        ->name('user.certificate.view');
    Route::get('/certificates/{certificate}/stream', [CertificateController::class, 'stream'])
        ->name('user.certificate.stream');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])
        ->name('user.certificate.download');
});

// Test SMS and YouTube routes removed for security



// Courses and Lectures Public Routes
Route::get('/courses', CoursesList::class)->name('courses');
// Route::get('/course/{id}', CourseDetail::class)->name('course.detail'); // Removed in favor of slug route
// Route::get('/course/{course}/lecture/{lecture}', LectureDetail::class)->name('lecture.detail'); // Removed inline favor of slug route

// Free Course Enrollment Routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::post('/free-course/{course}/enroll', [App\Http\Controllers\FreeCourseController::class, 'enrollCourse'])
        ->name('free-course.enroll');
    Route::post('/free-lecture/{lecture}/enroll', [App\Http\Controllers\FreeCourseController::class, 'enrollLecture'])
        ->name('free-lecture.enroll');
});

// Admin Routes
Route::middleware(['auth', 'check.role:Admin,Super Admin', 'super.admin.bypass'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::resource('users',UserController::class);

    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

    // Traffic Analytics
    Route::get('/analytics', [App\Http\Controllers\Admin\TrafficAnalyticsController::class, 'index'])
        ->name('admin.analytics');

    // Suggestions and Feedback management
    Route::get('/suggestions', [AdminSuggestionController::class, 'index'])->name('admin.suggestions.index');
    Route::get('/suggestions/{suggestion}', [AdminSuggestionController::class, 'show'])->name('admin.suggestions.show');
    Route::put('/suggestions/{suggestion}', [AdminSuggestionController::class, 'update'])->name('admin.suggestions.update');
    Route::delete('/suggestions/{suggestion}', [AdminSuggestionController::class, 'destroy'])->name('admin.suggestions.destroy');

    // FAQ Management
    Route::get('/faqs', [App\Http\Controllers\Admin\FaqController::class, 'index'])->name('admin.faqs.index');
    Route::get('/faqs/{faq}/edit', [App\Http\Controllers\Admin\FaqController::class, 'edit'])->name('admin.faqs.edit');
    Route::put('/faqs/{faq}', [App\Http\Controllers\Admin\FaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/faqs/{faq}', [App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('admin.faqs.destroy');

    // Device management routes
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::post('/devices/reset-user/{user}', [DeviceController::class, 'resetUserDevices'])->name('devices.reset.user');

    // Instructor Profiles management
    Route::get('/instructor-profiles', [App\Http\Controllers\Admin\AdminController::class, 'instructorProfiles'])
        ->middleware('admin.permission:instructor_profiles')
        ->name('admin.instructor-profiles');
    Route::get('/instructor-profiles/create', [App\Http\Controllers\Admin\AdminController::class, 'createInstructorProfile'])
        ->middleware('admin.permission:instructor_profiles')
        ->name('admin.instructor-profiles.create');
    Route::post('/instructor-profiles', [App\Http\Controllers\Admin\AdminController::class, 'storeInstructorProfile'])
        ->middleware('admin.permission:instructor_profiles')
        ->name('admin.instructor-profiles.store');
    Route::get('/instructor-profiles/{instructorProfile}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editInstructorProfile'])
        ->middleware('admin.permission:instructor_profiles')
        ->name('admin.instructor-profiles.edit');
    Route::put('/instructor-profiles/{instructorProfile}', [App\Http\Controllers\Admin\AdminController::class, 'updateInstructorProfile'])
        ->middleware('admin.permission:instructor_profiles')
        ->name('admin.instructor-profiles.update');
    Route::delete('/instructor-profiles/{instructorProfile}', [App\Http\Controllers\Admin\AdminController::class, 'destroyInstructorProfile'])
        ->middleware('admin.permission:instructor_profiles')
        ->name('admin.instructor-profiles.destroy');

    // Payment Methods management
    Route::get('/payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])
        ->middleware('admin.permission:payment_methods')
        ->name('admin.payment-methods.index');
    Route::get('/payment-methods/{paymentMethod}/edit', [App\Http\Controllers\Admin\PaymentMethodController::class, 'edit'])
        ->middleware('admin.permission:payment_methods')
        ->name('admin.payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])
        ->middleware('admin.permission:payment_methods')
        ->name('admin.payment-methods.update');
    Route::post('/payment-methods/{paymentMethod}/toggle-status', [App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])
        ->middleware('admin.permission:payment_methods')
        ->name('admin.payment-methods.toggle-status');
    Route::post('/payment-methods/update-order', [App\Http\Controllers\Admin\PaymentMethodController::class, 'updateOrder'])
        ->middleware('admin.permission:payment_methods')
        ->name('admin.payment-methods.update-order');

    // Products management
    Route::get('/products', [ProductController::class, 'index'])
        ->name('admin.products');
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])
        ->name('admin.products.store');
    Route::get('/products/{course}', [ProductController::class, 'show'])
        ->name('admin.products.show');
    Route::get('/products/{course}/edit', [ProductController::class, 'edit'])
        ->name('admin.products.edit');
    Route::put('/products/{course}', [ProductController::class, 'update'])
        ->name('admin.products.update');
    Route::delete('/products/{course}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    // Blogs management
    Route::get('/blogs', [App\Http\Controllers\Admin\BlogController::class, 'index'])
        ->name('admin.blogs.index');
    Route::get('/blogs/create', [App\Http\Controllers\Admin\BlogController::class, 'create'])
        ->name('admin.blogs.create');
    Route::post('/blogs', [App\Http\Controllers\Admin\BlogController::class, 'store'])
        ->name('admin.blogs.store');
    Route::get('/blogs/{blog}/edit', [App\Http\Controllers\Admin\BlogController::class, 'edit'])
        ->name('admin.blogs.edit');
    Route::put('/blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'update'])
        ->name('admin.blogs.update');
    Route::delete('/blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'destroy'])
        ->name('admin.blogs.destroy');

    // Legacy aliases for backward compatibility
    Route::get('/courses', [ProductController::class, 'index'])
        ->name('admin.courses');
    Route::get('/course/create', [ProductController::class, 'create'])
        ->name('admin.course.create');
    Route::post('/course/store', [ProductController::class, 'store'])
        ->name('admin.course.store');
    Route::get('/courses/{course}', [ProductController::class, 'show'])
        ->name('admin.courses.show');

    // Categories management
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)
        ->names(['index' => 'admin.categories.index', 'create' => 'admin.categories.create', 'store' => 'admin.categories.store',
                'show' => 'admin.categories.show', 'edit' => 'admin.categories.edit', 'update' => 'admin.categories.update',
                'destroy' => 'admin.categories.destroy'])
        ->middleware('admin.permission:courses');

    // Lectures management
    Route::get('/lectures', [App\Http\Controllers\Admin\AdminController::class, 'lectures'])
        ->middleware('admin.permission:lectures')
        ->name('admin.lectures');
    Route::get('/lectures/{lecture}', [App\Http\Controllers\Admin\AdminController::class, 'showLecture'])
        ->middleware('admin.permission:lectures')
        ->name('admin.lectures.show');

    // Roles management
    Route::get('/roles', [App\Http\Controllers\Admin\AdminController::class, 'roles'])
        ->middleware('admin.permission:roles')
        ->name('admin.roles');

    // Users management
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])
        ->middleware('admin.permission:users')
        ->name('admin.users');
    Route::get('/user/create', [UserController::class, 'create'])
        ->middleware('admin.permission:users')
        ->name('admin.user.create');

    // Coupons management
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class)
        ->names(['index' => 'admin.coupons.index', 'create' => 'admin.coupons.create', 'store' => 'admin.coupons.store',
                'show' => 'admin.coupons.show', 'edit' => 'admin.coupons.edit', 'update' => 'admin.coupons.update',
                'destroy' => 'admin.coupons.destroy'])
        ->middleware('admin.permission:coupons');

    // Orders & Payments management
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])
        ->middleware('admin.permission:orders')
        ->name('admin.orders');
    Route::get('/order/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])
        ->middleware('admin.permission:orders')
        ->name('admin.order.show');
    Route::post('/order/{order}/approve', [App\Http\Controllers\Admin\OrderController::class, 'approve'])
        ->middleware('admin.permission:orders')
        ->name('admin.order.approve');
    Route::post('/order/{order}/reject', [App\Http\Controllers\Admin\OrderController::class, 'reject'])
        ->middleware('admin.permission:orders')
        ->name('admin.order.reject');
    Route::post('/order/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
        ->middleware('admin.permission:orders')
        ->name('admin.order.update-status');

    // User Courses management
    Route::resource('user-courses', App\Http\Controllers\Admin\UserCourseController::class)
        ->names(['index' => 'admin.user-courses.index', 'create' => 'admin.user-courses.create', 'store' => 'admin.user-courses.store',
                'show' => 'admin.user-courses.show', 'edit' => 'admin.user-courses.edit', 'update' => 'admin.user-courses.update',
                'destroy' => 'admin.user-courses.destroy'])
        ->middleware('admin.permission:user_courses');

    // Admin Answer routes
    Route::get('/questions', [App\Http\Controllers\Admin\AnswerController::class, 'index'])
        ->middleware('admin.permission:questions')
        ->name('admin.questions.index');
    Route::get('/all-questions', [App\Http\Controllers\Admin\AnswerController::class, 'allQuestions'])
        ->middleware('admin.permission:questions')
        ->name('admin.questions.all');
    Route::post('/questions/{question}/reject', [App\Http\Controllers\Admin\AnswerController::class, 'rejectQuestion'])
        ->middleware('admin.permission:questions')
        ->name('admin.questions.reject');
    Route::post('/answers', [App\Http\Controllers\Admin\AnswerController::class, 'store'])
        ->middleware('admin.permission:questions')
        ->name('admin.answers.store');
    Route::post('/answers/{answer}/toggle-pin', [App\Http\Controllers\Admin\AnswerController::class, 'togglePin'])
        ->middleware('admin.permission:questions')
        ->name('admin.answers.toggle-pin');

    // Admin Permissions Management (only for admins, not super admins)
    Route::get('/permissions', [App\Http\Controllers\Admin\AdminController::class, 'permissions'])
        ->middleware('check.role:Super Admin')
        ->name('admin.permissions');
    Route::post('/permissions/update', [App\Http\Controllers\Admin\AdminController::class, 'updatePermissions'])
        ->middleware('check.role:Super Admin')
        ->name('admin.permissions.update');

    // Debug route removed for security compliance



    // Admin access to user lecture pages (for management purposes)
    Route::get('/my-course-detail/{course}', [CourseViewController::class, 'showPurchasedCourse'])->name('admin.user.course.purchased');
    Route::get('/my-lecture-detail/{course}/{lecture}', [CourseViewController::class, 'showPurchasedLecture'])->name('admin.user.lecture.purchased');

    // Admin Quiz Management Routes
    Route::middleware(['check.role:Admin,Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        // Quiz Management
        Route::resource('quizzes', App\Http\Controllers\Admin\QuizController::class);

        // Question Management
        Route::post('/quizzes/{quiz}/questions', [App\Http\Controllers\Admin\QuizController::class, 'storeQuestion'])
            ->name('quizzes.questions.store');
        Route::get('/quizzes/{quiz}/questions/create', [App\Http\Controllers\Admin\QuizController::class, 'createQuestion'])
            ->name('quiz-questions.create');
        Route::get('/quiz-questions/{question}/edit', [App\Http\Controllers\Admin\QuizController::class, 'editQuestion'])
            ->name('quiz-questions.edit');
        Route::put('/quiz-questions/{question}', [App\Http\Controllers\Admin\QuizController::class, 'updateQuestion'])
            ->name('quiz-questions.update');
        Route::delete('/quiz-questions/{question}', [App\Http\Controllers\Admin\QuizController::class, 'destroyQuestion'])
            ->name('quiz-questions.destroy');

        // Attempt Management
        Route::get('/quizzes/{quiz}/attempts', [App\Http\Controllers\Admin\QuizController::class, 'quizAttempts'])
            ->name('quizzes.attempts');
        Route::get('/quiz-attempts/{attempt}', [App\Http\Controllers\Admin\QuizController::class, 'viewAttempt'])
            ->name('quiz-attempts.show');
        Route::put('/quiz-attempts/{attempt}/grade', [App\Http\Controllers\Admin\QuizController::class, 'gradeAnswer'])
            ->name('quiz-attempts.grade');

        // Pending Quiz Reviews
        Route::get('/quiz-reviews/pending', [App\Http\Controllers\Admin\QuizController::class, 'pendingReviews'])
            ->name('quiz-reviews.pending');
        Route::post('/quiz-reviews/review', [App\Http\Controllers\Admin\QuizController::class, 'reviewAnswers'])
            ->name('quiz-reviews.review');
    });

    // Certificate Management (Super Admin only)
    Route::middleware(['check.role:Super Admin'])->group(function () {
        Route::get('/certificates', [App\Http\Controllers\Admin\CertificateController::class, 'index'])
            ->name('admin.certificates.index');
        Route::get('/certificate-requests/{id}', [App\Http\Controllers\Admin\CertificateController::class, 'showRequest'])
            ->name('admin.certificates.show-request');
        Route::post('/certificate-requests/{id}/approve', [App\Http\Controllers\Admin\CertificateController::class, 'approve'])
            ->name('admin.certificates.approve');
        Route::post('/certificate-requests/{id}/reject', [App\Http\Controllers\Admin\CertificateController::class, 'reject'])
            ->name('admin.certificates.reject');
        Route::get('/certificates/{id}/view', [App\Http\Controllers\Admin\CertificateController::class, 'viewCertificate'])
            ->name('admin.certificates.view');
        Route::get('/certificates/{id}/download', [App\Http\Controllers\Admin\CertificateController::class, 'downloadCertificate'])
            ->name('admin.certificates.download');
        Route::get('/certificates/{id}/edit', [App\Http\Controllers\Admin\CertificateController::class, 'editCertificate'])
            ->name('admin.certificates.edit');
        Route::post('/certificates/{id}/update', [App\Http\Controllers\Admin\CertificateController::class, 'updateCertificate'])
            ->name('admin.certificates.update');
    });

    // Accounting Management (Super Admin only)
    Route::middleware(['check.role:Super Admin'])->group(function () {
        Route::get('/accounting', [App\Http\Controllers\Admin\AccountingController::class, 'dashboard'])
            ->name('admin.accounting.dashboard');
        Route::get('/accounting/transactions', [App\Http\Controllers\Admin\AccountingController::class, 'transactions'])
            ->name('admin.accounting.transactions');
        Route::get('/accounting/transfers', [App\Http\Controllers\Admin\AccountingController::class, 'transfers'])
            ->name('admin.accounting.transfers');
        Route::get('/accounting/transfers/create', [App\Http\Controllers\Admin\AccountingController::class, 'createTransfer'])
            ->name('admin.accounting.transfers.create');
        Route::post('/accounting/transfers', [App\Http\Controllers\Admin\AccountingController::class, 'storeTransfer'])
            ->name('admin.accounting.transfers.store');
        Route::post('/accounting/transfers/{transfer}/approve', [App\Http\Controllers\Admin\AccountingController::class, 'approveTransfer'])
            ->name('admin.accounting.transfers.approve');
        Route::post('/accounting/transfers/{transfer}/complete', [App\Http\Controllers\Admin\AccountingController::class, 'completeTransfer'])
            ->name('admin.accounting.transfers.complete');
        Route::post('/accounting/transfers/{transfer}/reject', [App\Http\Controllers\Admin\AccountingController::class, 'rejectTransfer'])
            ->name('admin.accounting.transfers.reject');
        Route::get('/accounting/journal-entries', [App\Http\Controllers\Admin\AccountingController::class, 'journalEntries'])
            ->name('admin.accounting.journal-entries');
        Route::get('/accounting/journal-entries/create', [App\Http\Controllers\Admin\AccountingController::class, 'createJournalEntry'])
            ->name('admin.accounting.journal-entries.create');
        Route::post('/accounting/journal-entries', [App\Http\Controllers\Admin\AccountingController::class, 'storeJournalEntry'])
            ->name('admin.accounting.journal-entries.store');
        Route::post('/accounting/journal-entries/{entry}/verify', [App\Http\Controllers\Admin\AccountingController::class, 'verifyJournalEntry'])
            ->name('admin.accounting.journal-entries.verify');
        Route::post('/accounting/journal-entries/{entry}/complete', [App\Http\Controllers\Admin\AccountingController::class, 'completeJournalEntry'])
            ->name('admin.accounting.journal-entries.complete');
        Route::get('/accounting/usages', [App\Http\Controllers\Admin\AccountingController::class, 'usages'])
            ->name('admin.accounting.usages');
        Route::get('/accounting/usages/create', [App\Http\Controllers\Admin\AccountingController::class, 'createUsage'])
            ->name('admin.accounting.usages.create');
        Route::post('/accounting/usages', [App\Http\Controllers\Admin\AccountingController::class, 'storeUsage'])
            ->name('admin.accounting.usages.store');
        Route::post('/accounting/usages/{usage}/approve', [App\Http\Controllers\Admin\AccountingController::class, 'approveUsage'])
            ->name('admin.accounting.usages.approve');
        Route::post('/accounting/usages/{usage}/complete', [App\Http\Controllers\Admin\AccountingController::class, 'completeUsage'])
            ->name('admin.accounting.usages.complete');
        Route::get('/accounting/balance-sheet', [App\Http\Controllers\Admin\AccountingController::class, 'balanceSheet'])
            ->name('admin.accounting.balance-sheet');
        Route::get('/accounting/report', [App\Http\Controllers\Admin\AccountingController::class, 'report'])
            ->name('admin.accounting.report');
        Route::get('/accounting/accounts', [App\Http\Controllers\Admin\AccountingController::class, 'accounts'])
            ->name('admin.accounting.accounts');
        Route::get('/accounting/accounts/create', [App\Http\Controllers\Admin\AccountingController::class, 'createAccount'])
            ->name('admin.accounting.accounts.create');
        Route::post('/accounting/accounts', [App\Http\Controllers\Admin\AccountingController::class, 'storeAccount'])
            ->name('admin.accounting.accounts.store');
        Route::delete('/accounting/accounts/{account}', [App\Http\Controllers\Admin\AccountingController::class, 'deleteAccount'])
            ->name('admin.accounting.accounts.delete');
        Route::post('/accounting/accounts/{account}/toggle', [App\Http\Controllers\Admin\AccountingController::class, 'toggleAccount'])
            ->name('admin.accounting.accounts.toggle');
    });

    // Footer Settings Management
    Route::get('/footer', [App\Http\Controllers\Admin\FooterController::class, 'index'])
        ->name('admin.footer.index');
    Route::put('/footer', [App\Http\Controllers\Admin\FooterController::class, 'update'])
        ->name('admin.footer.update');

    // Banner Management
    Route::get('/banners', [App\Http\Controllers\Admin\CarouselController::class, 'index'])
        ->name('admin.banners.index');
    Route::get('/banners/create', [App\Http\Controllers\Admin\CarouselController::class, 'create'])
        ->name('admin.banners.create');
    Route::post('/banners', [App\Http\Controllers\Admin\CarouselController::class, 'store'])
        ->name('admin.banners.store');
    Route::get('/banners/{slide}/edit', [App\Http\Controllers\Admin\CarouselController::class, 'edit'])
        ->name('admin.banners.edit');
    Route::put('/banners/{slide}', [App\Http\Controllers\Admin\CarouselController::class, 'update'])
        ->name('admin.banners.update');
    Route::delete('/banners/{slide}', [App\Http\Controllers\Admin\CarouselController::class, 'destroy'])
        ->name('admin.banners.destroy');
    Route::post('/banners/reorder', [App\Http\Controllers\Admin\CarouselController::class, 'reorder'])
        ->name('admin.banners.reorder');

    Route::get('/carousel', [App\Http\Controllers\Admin\CarouselController::class, 'index'])
        ->name('admin.carousel.index');
    Route::get('/carousel/create', [App\Http\Controllers\Admin\CarouselController::class, 'create'])
        ->name('admin.carousel.create');
    Route::post('/carousel', [App\Http\Controllers\Admin\CarouselController::class, 'store'])
        ->name('admin.carousel.store');
    Route::get('/carousel/{slide}/edit', [App\Http\Controllers\Admin\CarouselController::class, 'edit'])
        ->name('admin.carousel.edit');
    Route::put('/carousel/{slide}', [App\Http\Controllers\Admin\CarouselController::class, 'update'])
        ->name('admin.carousel.update');
    Route::delete('/carousel/{slide}', [App\Http\Controllers\Admin\CarouselController::class, 'destroy'])
        ->name('admin.carousel.destroy');
    Route::post('/carousel/reorder', [App\Http\Controllers\Admin\CarouselController::class, 'reorder'])
        ->name('admin.carousel.reorder');

    // Homepage Sections Management
    Route::resource('sections', App\Http\Controllers\Admin\HomepageSectionController::class)
        ->names([
            'index' => 'admin.sections.index',
            'create' => 'admin.sections.create',
            'store' => 'admin.sections.store',
            'edit' => 'admin.sections.edit',
            'update' => 'admin.sections.update',
            'destroy' => 'admin.sections.destroy'
        ]);

    // Review management routes
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{rating}/toggle', [App\Http\Controllers\Admin\ReviewController::class, 'toggleStatus'])->name('admin.reviews.toggle');
    Route::delete('/reviews/{rating}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    // Navigation Pages Management
    Route::get('/pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('admin.pages.index');
    Route::get('/pages/create', [App\Http\Controllers\Admin\PageController::class, 'create'])->name('admin.pages.create');
    Route::post('/pages', [App\Http\Controllers\Admin\PageController::class, 'store'])->name('admin.pages.store');
    Route::get('/pages/{page}/edit', [App\Http\Controllers\Admin\PageController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{page}', [App\Http\Controllers\Admin\PageController::class, 'update'])->name('admin.pages.update');
    Route::delete('/pages/{page}', [App\Http\Controllers\Admin\PageController::class, 'destroy'])->name('admin.pages.destroy');
    Route::post('/pages/reorder', [App\Http\Controllers\Admin\PageController::class, 'reorder'])->name('admin.pages.reorder');
});

// Super Admin Routes
Route::middleware(['auth', 'super.admin'])->prefix('super-admin')->group(function () {
    // User-Admin Assignments
    Route::get('/assignments', [App\Http\Controllers\Admin\AdminUserAssignmentController::class, 'index'])->name('admin.assignments.index');
    Route::get('/assignments/create', [App\Http\Controllers\Admin\AdminUserAssignmentController::class, 'create'])->name('admin.assignments.create');
    Route::post('/assignments', [App\Http\Controllers\Admin\AdminUserAssignmentController::class, 'store'])->name('admin.assignments.store');
    Route::get('/assignments/{assignment}', [App\Http\Controllers\Admin\AdminUserAssignmentController::class, 'show'])->name('admin.assignments.show');
    Route::delete('/assignments/{assignment}', [App\Http\Controllers\Admin\AdminUserAssignmentController::class, 'destroy'])->name('admin.assignments.destroy');
    Route::post('/assignments/batch', [App\Http\Controllers\Admin\AdminUserAssignmentController::class, 'batchAssign'])->name('admin.assignments.batch');
});

// Search route
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

// Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Video proxy routes
Route::get('/video/embed/{token}', [VideoProxyController::class, 'embedVideo'])->name('video.embed');
Route::get('/video/get-embed-url', [VideoProxyController::class, 'getEmbedUrl'])->middleware('ajax');
Route::get('/video/stream/{token}', [VideoProxyController::class, 'getVideo'])->name('video.stream');

// Public catalog redirects to Polani store pages
Route::get('/courses', function () {
    return redirect()->route('polani.collection');
})->name('courses');

Route::get('/course/{slug}', function (string $slug) {
    return redirect()->route('polani.product', ['slug' => $slug]);
})->name('course.detail');

Route::get('/lecture/{course}/{lecture}', function () {
    return redirect()->route('polani.collection');
})->name('lecture.detail');

Route::get('/lecture/{lecture}', function () {
    return redirect()->route('polani.collection');
})->name('lecture.standalone');

Route::get('/lecture/standalone/{lecture}', function () {
    return redirect()->route('polani.collection');
})->name('lecture.standalone.legacy');
Route::get('/instructor-profiles', App\Livewire\InstructorProfiles::class)->name('instructor-profiles');

// Add a debug route to verify the configuration values
// All debug and test routes removed for security compliance

// Apply middleware to all authenticated routes
Route::middleware(['auth', 'device.restriction'])->group(function () {
    Route::get('/tables', function () {
        return view('tables');
    })->name('tables')->middleware('auth');

    // Wallet route removed - example page deleted

    Route::get('/RTL', function () {
        return view('RTL');
    })->name('RTL')->middleware('auth');

    Route::get('/profile', function () {
        return view('account-pages.profile');
    })->name('profile')->middleware('auth');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::get('/account/profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
    Route::get('/account/users', [UserController::class, 'index'])->name('users-management')->middleware('auth');
});

// Temporary route for certificate preview
Route::get('/test-certificate-view', function () {
    $user = new \stdClass();
    $user->name = 'John Doe (Preview)';

    return view('pdf.certificate', [
        'user' => $user,
        'courseName' => 'Preview Course Name',
        'certificateNumber' => 'PREVIEW-123456',
        'passingDate' => now()->format('F d, Y'),
        'hasQuizzes' => true
    ]);
});

// ============================================================================
// ⚠️  RESTORED TEST ROUTES FOR EMAIL AND SMS (DEVELOPMENT ONLY)
// ============================================================================
// WARNING: These routes are for testing/development only!
// SECURITY: Only enable in local development environment
// Originally removed from: initial commit (c2f4327)
// Restored for: Testing email and SMS functionality
// ============================================================================

// TEST EMAIL API ROUTE
Route::get('/test-email-api', function () {
    // ⚠️ SECURITY: This route exposes email configuration - use only in development
    if (!app()->isLocal()) {
        return response()->json(['error' => 'This route is only available in local development'], 403);
    }

    $apiUrl = config('services.custom_email.api_url');
    $secretToken = config('services.custom_email.secret_token');
    $fromEmail = config('services.custom_email.from_email');
    $replyToEmail = config('services.custom_email.reply_to_email');

    // Improved fallback logic for $toEmail
    $toEmail = request('to')
        ?: config('services.custom_email.test_email')
        ?: env('EMAIL_TEST_TO', null);

    // If all fallbacks fail
    if (!$toEmail) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'Recipient email address is missing. Pass ?to=email@example.com or set services.custom_email.test_email in config.',
        ], 400);
    }

    // Validate other config values
    if (!$apiUrl || !$secretToken || !$fromEmail || !$replyToEmail) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'Missing configuration. Check services.custom_email config.',
        ], 400);
    }

    $payload = [
        'secret_token' => $secretToken,
        'from'         => $fromEmail,
        'repto'        => $replyToEmail,
        'to'           => $toEmail,
        'subject'      => 'Testing Email from Laravel',
        'body'         => '<span>This is a <b>test</b> email to check the email API.</span>',
        'ctype'        => 'html',
    ];

    // Log the request payload
    Log::info('Sending Test Email with parameters:', $payload);

    $response = Http::post($apiUrl, $payload);

    return response()->json([
        'status' => 'Email test sent',
        'response' => $response->json(),
        'toEmail' => $toEmail,
    ]);
})->name('test.email.api');

// TEST SMS - DIBA API
Route::get('/test-sms-diba', function () {
    // ⚠️ SECURITY: This route exposes SMS credentials - use only in development
    if (!app()->isLocal()) {
        return response()->json(['error' => 'This route is only available in local development'], 403);
    }

    $apiUrl = Config::get('services.diba_sms.api_url');
    $secretKey = Config::get('services.diba_sms.secret_key');
    $username = Config::get('services.diba_sms.username');

    if (!$apiUrl || !$secretKey || !$username) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'SMS configuration incomplete. Check services.diba_sms config.',
        ], 400);
    }

    // Test parameters
    $countryCode = '92';
    $mobile = request('mobile', '3152771063');
    $message = request('message', 'This is a test message from Laravel');

    Log::info('Sending Test SMS', [
        'mobile' => $mobile,
        'message' => $message,
    ]);

    $response = Http::post($apiUrl, [
        'username' => $username,
        'secret_token' => $secretKey,
        'country_code' => $countryCode,
        'mobile' => $mobile,
        'message' => $message,
    ]);

    return response()->json([
        'statusCode' => $response->status(),
        'body' => $response->json(),
        'testMobile' => $mobile,
    ]);
})->name('test.sms.diba');

// TEST SMS - CHANGE SECRET TOKEN
Route::get('/test-change-secret', function () {
    // ⚠️ SECURITY: This route allows changing SMS secrets - use only in development
    if (!app()->isLocal()) {
        return response()->json(['error' => 'This route is only available in local development'], 403);
    }

    $username = Config::get('services.diba_sms.username');
    $newSecretToken = request('token', 'A5bTa8UUFIZUa6Ym0F84sH7RPrxnW0eo3TUZVY8xnoAhDMBU7PtM1NWVAgyU');

    Log::warning('Testing Secret Token Change', ['username' => $username]);

    $response = Http::post('https://smsg.dibaadm.com/api/modernized-secret', [
        'username' => $username,
        'secret_token' => $newSecretToken,
    ]);

    return response()->json([
        'statusCode' => $response->status(),
        'body' => $response->json(),
    ]);
})->name('test.change.secret');

// TEST SMS - SEND TEST MESSAGE (SmsController)
Route::get('/test-sms', [SmsController::class, 'sendTestSms'])->name('test.sms');

// TEST EMAIL CONFIG
Route::get('/test-email-config', function () {
    // ⚠️ SECURITY: This route exposes email configuration
    if (!app()->isLocal()) {
        return response()->json(['error' => 'This route is only available in local development'], 403);
    }

    $apiUrl = Config::get('services.custom_email.api_url');
    $secretToken = Config::get('services.custom_email.secret_token');
    $fromEmail = Config::get('services.custom_email.from_email');
    $replyToEmail = Config::get('services.custom_email.reply_to_email');
    $testEmail = Config::get('services.custom_email.test_email');

    return response()->json([
        'api_url' => $apiUrl,
        'secret_token' => $secretToken ? '***hidden***' : 'NOT SET',
        'from_email' => $fromEmail,
        'reply_to_email' => $replyToEmail,
        'test_email' => $testEmail,
    ]);
})->name('test.email.config');

// ============================================================================
// ⚠️  PRODUCTION TEST ROUTES FOR EMAIL AND SMS
// ============================================================================
// WARNING: These routes are for testing production APIs only!
// SECURITY: Only enable in production environment
// Created for: Testing IEC_Course_live production credentials
// ============================================================================

// Helper function to check if running on production domain
$isProductionDomain = function () {
    $host = request()->getHost();
    $productionDomains = ['courses.iecdawateislami.com', 'www.courses.iecdawateislami.com'];
    return in_array($host, $productionDomains);
};

// TEST PRODUCTION EMAIL API ROUTE
Route::get('/test-prod-email-api', function () use ($isProductionDomain) {
    // ⚠️ SECURITY: Only available in production environment OR on production domain
    if (app()->isLocal() && !$isProductionDomain()) {
        return response()->json([
            'error' => 'This route is only available in production environment or on production domain (courses.iecdawateislami.com)'
        ], 403);
    }

    $apiUrl = config('services.production_email.api_url');
    $secretToken = config('services.production_email.secret_token');
    $fromEmail = config('services.production_email.from_email');
    $replyToEmail = config('services.production_email.reply_to_email');

    // Get recipient email from request or use a test email
    $toEmail = request('to');

    // If no email provided
    if (!$toEmail) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'Recipient email address is required. Pass ?to=email@example.com',
        ], 400);
    }

    // Validate other config values
    if (!$apiUrl || !$secretToken || !$fromEmail || !$replyToEmail) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'Missing production email configuration. Check services.production_email config.',
        ], 400);
    }

    $payload = [
        'secret_token' => $secretToken,
        'from'         => $fromEmail,
        'repto'        => $replyToEmail,
        'to'           => $toEmail,
        'subject'      => 'Testing Production Email from Laravel',
        'body'         => '<span>This is a <b>production test</b> email to verify the production email API is working correctly.</span>',
        'ctype'        => 'html',
    ];

    // Log the request payload
    Log::info('Sending Production Test Email', ['to' => $toEmail]);

    $response = Http::post($apiUrl, $payload);

    return response()->json([
        'status' => 'Production email test sent',
        'response' => $response->json(),
        'toEmail' => $toEmail,
    ]);
})->name('test.prod.email.api');

// TEST PRODUCTION SMS - DIBA API
Route::get('/test-prod-sms', function () use ($isProductionDomain) {
    // ⚠️ SECURITY: Only available in production environment OR on production domain
    if (app()->isLocal() && !$isProductionDomain()) {
        return response()->json([
            'error' => 'This route is only available in production environment or on production domain (courses.iecdawateislami.com)'
        ], 403);
    }

    $apiUrl = Config::get('services.production_sms.api_url');
    $secretKey = Config::get('services.production_sms.secret_key');
    $username = Config::get('services.production_sms.username');

    if (!$apiUrl || !$secretKey || !$username) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'Production SMS configuration incomplete. Check services.production_sms config.',
        ], 400);
    }

    // Test parameters
    $countryCode = request('country_code', '92');
    $mobile = request('mobile');
    $message = request('message', 'This is a production test message from Laravel');

    // Validate mobile number is provided
    if (!$mobile) {
        return response()->json([
            'status' => '❌ Error',
            'message' => 'Mobile number is required. Pass ?mobile=XXXXXXXXXX',
        ], 400);
    }

    Log::info('Sending Production Test SMS', [
        'mobile' => $mobile,
        'country_code' => $countryCode,
    ]);

    $response = Http::post($apiUrl, [
        'username' => $username,
        'secret_token' => $secretKey,
        'country_code' => $countryCode,
        'mobile' => $mobile,
        'message' => $message,
    ]);

    return response()->json([
        'statusCode' => $response->status(),
        'body' => $response->json(),
        'testMobile' => $mobile,
    ]);
})->name('test.prod.sms');

// TEST PRODUCTION EMAIL CONFIG
Route::get('/test-prod-email-config', function () use ($isProductionDomain) {
    // ⚠️ SECURITY: Only available in production environment OR on production domain
    if (app()->isLocal() && !$isProductionDomain()) {
        return response()->json([
            'error' => 'This route is only available in production environment or on production domain (courses.iecdawateislami.com)'
        ], 403);
    }

    $apiUrl = Config::get('services.production_email.api_url');
    $secretToken = Config::get('services.production_email.secret_token');
    $fromEmail = Config::get('services.production_email.from_email');
    $replyToEmail = Config::get('services.production_email.reply_to_email');

    return response()->json([
        'api_url' => $apiUrl,
        'secret_token' => $secretToken ? '***hidden***' : 'NOT SET',
        'from_email' => $fromEmail,
        'reply_to_email' => $replyToEmail,
        'account' => 'IEC_Course_email_live',
    ]);
})->name('test.prod.email.config');

// TEST PRODUCTION SMS CONFIG
Route::get('/test-prod-sms-config', function () use ($isProductionDomain) {
    // ⚠️ SECURITY: Only available in production environment OR on production domain
    if (app()->isLocal() && !$isProductionDomain()) {
        return response()->json([
            'error' => 'This route is only available in production environment or on production domain (courses.iecdawateislami.com)'
        ], 403);
    }

    $apiUrl = Config::get('services.production_sms.api_url');
    $secretKey = Config::get('services.production_sms.secret_key');
    $username = Config::get('services.production_sms.username');

    return response()->json([
        'api_url' => $apiUrl,
        'secret_key' => $secretKey ? '***hidden***' : 'NOT SET',
        'username' => $username,
        'account' => 'IEC_Course_live',
    ]);
})->name('test.prod.sms.config');

// ============================================================================
// END OF TEST ROUTES
// ============================================================================

}); // End of XSS protection middleware group
