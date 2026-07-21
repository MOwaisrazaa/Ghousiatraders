<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\BlockSensitiveFiles::class,
        \App\Http\Middleware\PreventDirectoryListing::class,
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\CacheStaticAssets::class,
        \App\Http\Middleware\AllowBfcache::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // \App\Http\Middleware\CompressResponse::class, // Disabled - causes issues with Livewire
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\PreventUserParamManipulation::class,
            \App\Http\Middleware\XSS::class,
            // \App\Http\Middleware\LogTraffic::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'roles' => \App\Http\Middleware\CheckRoles::class,
        'check.role' => \App\Http\Middleware\CheckRoles::class,
        'XSS' => \App\Http\Middleware\XSS::class,
        'login.throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class.':5,1',
        'auth.throttle' => \App\Http\Middleware\ThrottleLogins::class.':5,1',
        'super.admin' => \App\Http\Middleware\CheckSuperAdmin::class,
        'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
        'super.admin.bypass' => \App\Http\Middleware\SuperAdminBypass::class,
        'device.restriction' => \App\Http\Middleware\DeviceRestrictionMiddleware::class,
        'prevent.user.param' => \App\Http\Middleware\PreventUserParamManipulation::class,
        'password.strength' => \App\Http\Middleware\PasswordStrengthMiddleware::class,
    ];
    protected $routeMiddleware = [
        'device.restriction' => \App\Http\Middleware\DeviceRestrictionMiddleware::class,
        // ... other middlewares
    ];

}
