<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Only extremely specific endpoints should be here
        // and they should be carefully vetted

        // Test video endpoints (remove in production)
        'api/test/video/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        // Add SameSite attribute to cookies
        $response = parent::handle($request, $next);

        // Apply security settings to the CSRF cookie
        if ($response->headers->has('Set-Cookie')) {
            $cookies = $response->headers->getCookies();
            $response->headers->remove('Set-Cookie');

            foreach ($cookies as $cookie) {
                $secureFlag = true; // Always use secure flag in production

                // Only set secure to false if we're in a local environment
                if (app()->environment('local')) {
                    $secureFlag = false;
                }

                // Create a new cookie with the same attributes plus SameSite=Lax
                $newCookie = new Cookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpiresTime(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $secureFlag,
                    $cookie->isHttpOnly(),
                    $cookie->isRaw(),
                    'lax' // SameSite attribute
                );

                $response->headers->setCookie($newCookie);
            }
        }

        return $response;
    }
}
