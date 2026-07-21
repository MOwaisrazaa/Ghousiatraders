<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CacheStaticAssets
{
    /**
     * Static asset extensions and their cache duration in seconds.
     * 1 year = 31536000 seconds
     */
    protected array $cacheableExtensions = [
        // Images
        'jpg' => 31536000,
        'jpeg' => 31536000,
        'png' => 31536000,
        'gif' => 31536000,
        'webp' => 31536000,
        'svg' => 31536000,
        'ico' => 31536000,
        
        // Fonts
        'woff' => 31536000,
        'woff2' => 31536000,
        'ttf' => 31536000,
        'otf' => 31536000,
        'eot' => 31536000,
        
        // CSS & JS
        'css' => 31536000,
        'js' => 31536000,
        
        // Other
        'pdf' => 31536000,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Get the file extension from the request path
        $path = $request->path();
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Check if this is a cacheable static asset
        if (isset($this->cacheableExtensions[$extension])) {
            $maxAge = $this->cacheableExtensions[$extension];
            
            // Add cache headers
            $response->headers->set('Cache-Control', "public, max-age={$maxAge}, immutable");
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
            
            // Add ETag for cache validation (based on file path and last modified time)
            if ($response instanceof BinaryFileResponse) {
                $file = $response->getFile();
                if ($file && $file->isFile()) {
                    $etag = md5($file->getMTime() . $file->getSize());
                    $response->headers->set('ETag', '"' . $etag . '"');
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $file->getMTime()) . ' GMT');
                }
            }
        }

        return $response;
    }
}

