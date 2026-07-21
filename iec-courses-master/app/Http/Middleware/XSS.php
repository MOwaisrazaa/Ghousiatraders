<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip XSS sanitization for Livewire requests (they have their own protection)
        // Also skip for admin routes that use Livewire components
        if (
            $request->header('X-Livewire') || 
            $request->is('livewire/*') ||
            $request->is('admin/courses/create*') ||
            $request->is('admin/courses/*/edit*') ||
            $request->is('admin/lectures/*/edit*') ||
            $request->is('admin/lectures/create*')
        ) {
            $response = $next($request);
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            return $response;
        }
        
        // Apply middleware to all routes except those that need to allow HTML
        if (
            !$request->is('talaq-fatawa/save-child') &&
            !$request->is('save-fatawa') &&
            !$request->is('mark-as-checked/*') &&
            !$request->is('download-word')
        ) {
            $input = $request->all();
            
            array_walk_recursive($input, function(&$value, $key) {
                if (is_string($value)) {
                    if ($key === 'long_description' || $key === 'description' || $key === 'content') {
                        // Keep HTML tags but remove script tags, event handlers, and javascript protocol for safety
                        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $value);
                        $value = preg_replace('/\bon\w+\s*=\s*["\'][^"\']*["\']/', '', $value);
                        $value = preg_replace('/javascript:/i', '', $value);
                    } else {
                        // Convert special characters to HTML entities for standard inputs
                        $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                        
                        // Additional cleaning for critical fields
                        if (preg_match('/password|token|key|secret/i', $value)) {
                            // Don't modify password fields
                        } else {
                            // Remove script tags
                            $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $value);
                            
                            // Remove any JavaScript event handlers (onclick, onload, etc)
                            $value = preg_replace('/\bon\w+\s*=\s*["\'][^"\']*["\']/', '', $value);
                            
                            // Remove javascript: protocol in URLs
                            $value = preg_replace('/javascript:/i', '', $value);
                        }
                    }
                }
            });
            
            $request->merge($input);
        }

        // Add X-XSS-Protection header
        $response = $next($request);
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        return $response;
    }
}
