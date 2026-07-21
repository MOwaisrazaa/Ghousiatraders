<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Security headers middleware - adds all required security headers
     * without affecting UI/UX functionality.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strict-Transport-Security (HSTS)
        // Forces HTTPS for 1 year, includes subdomains
        // Only set in production to avoid issues in local development
        if (config('app.env') === 'production' || $request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Content-Security-Policy
        // Carefully configured to allow your site's functionality
        $csp = $this->buildContentSecurityPolicy();
        $response->headers->set('Content-Security-Policy', $csp);

        // Referrer-Policy
        // Controls how much referrer information is sent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy (formerly Feature-Policy)
        // Controls which browser features can be used
        $response->headers->set('Permissions-Policy', $this->buildPermissionsPolicy());

        // Additional security headers (if not already set by XSS middleware)
        if (!$response->headers->has('X-Content-Type-Options')) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }
        
        if (!$response->headers->has('X-Frame-Options')) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        }
        
        if (!$response->headers->has('X-XSS-Protection')) {
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }

        return $response;
    }

    /**
     * Build Content-Security-Policy header value
     * Configured to work with Laravel, Livewire, and common CDNs
     */
    protected function buildContentSecurityPolicy(): string
    {
        $policies = [
            // Default fallback - allow from same origin with eval for Livewire/Alpine
            "default-src 'self' 'unsafe-eval'",

            // Scripts - allow self, inline (for Livewire/Alpine), and common CDNs
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://code.jquery.com https://cdn.plyr.io https://www.youtube.com https://s.ytimg.com https://www.google.com",
            
            // Styles - allow self, inline styles, and common CDNs
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://unpkg.com https://cdn.plyr.io",
            
            // Images - allow self, data URIs, and HTTPS sources
            "img-src 'self' data: https: blob:",
            
            // Fonts - allow self and common font CDNs
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            
            // Connect - for AJAX, WebSocket (Livewire), etc.
            "connect-src 'self' wss: https:",
            
            // Media - audio/video
            "media-src 'self' https: blob:",
            
            // Objects - disable plugins like Flash
            "object-src 'none'",
            
            // Base URI - restrict base tag
            "base-uri 'self'",
            
            // Form actions - where forms can submit
            "form-action 'self'",
            
            // Frame ancestors - who can embed this site
            "frame-ancestors 'self'",
            
            // Frames - what this site can embed
            "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com https://www.google.com https://maps.google.com",
            
            // Upgrade insecure requests in production
            "upgrade-insecure-requests",
        ];

        return implode('; ', $policies);
    }

    /**
     * Build Permissions-Policy header value
     * Restricts access to browser features
     */
    protected function buildPermissionsPolicy(): string
    {
        $permissions = [
            // Camera - only allow from same origin if needed
            'camera=()',
            
            // Microphone - allow from same origin for voice Q&A feature
            'microphone=(self)',
            
            // Geolocation - disabled
            'geolocation=()',
            
            // Payment - disabled unless you have payment features
            'payment=()',
            
            // USB - disabled
            'usb=()',
            
            // Accelerometer - disabled
            'accelerometer=()',
            
            // Gyroscope - disabled
            'gyroscope=()',
            
            // Magnetometer - disabled
            'magnetometer=()',
            
            // Interest cohort (FLoC) - disabled for privacy
            'interest-cohort=()',
            
            // Fullscreen - allow from same origin
            'fullscreen=(self)',
            
            // Picture-in-picture - allow from same origin
            'picture-in-picture=(self)',
        ];

        return implode(', ', $permissions);
    }
}
