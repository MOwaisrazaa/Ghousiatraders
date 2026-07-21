<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowBfcache
{
    /**
     * Allow back/forward cache (bfcache) by removing no-store header
     * while maintaining security through other means
     * 
     * The bfcache speeds up back/forward navigation by caching the page state.
     * Laravel adds Cache-Control: no-store by default with sessions, but this
     * prevents bfcache. We can safely allow it by:
     * 1. Using secure session cookies (httpOnly, sameSite)
     * 2. Regenerating session ID on login
     * 3. Using proper CSRF tokens
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Remove the no-store directive that prevents bfcache
        $cacheControl = $response->headers->get('Cache-Control');

        if ($cacheControl && strpos($cacheControl, 'no-store') !== false) {
            // Remove no-store completely, replace with private, no-cache, must-revalidate
            // This allows bfcache while maintaining security for private/session data
            $newCacheControl = str_replace('no-store', '', $cacheControl);

            // Clean up multiple spaces
            $newCacheControl = trim(preg_replace('/\s+/', ' ', $newCacheControl));

            // Add proper cache directives for bfcache compatibility
            if (empty($newCacheControl)) {
                $newCacheControl = 'private, no-cache, must-revalidate';
            } elseif (!strpos($newCacheControl, 'private')) {
                // Ensure private directive is set for session pages
                $newCacheControl = 'private, ' . $newCacheControl;
            }

            $response->headers->set('Cache-Control', $newCacheControl);
        }

        // Add headers that help with bfcache compatibility
        // Unload handler can prevent bfcache, so we avoid setting it
        // The page should not use unload/beforeunload events

        return $response;
    }
}

