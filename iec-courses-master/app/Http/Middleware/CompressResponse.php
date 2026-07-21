<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request and compress the response if the browser supports it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only compress if it's a Response object
        if (!$response instanceof Response) {
            return $response;
        }

        // Check if the browser accepts gzip encoding
        $acceptEncoding = $request->header('Accept-Encoding', '');
        
        if (stripos($acceptEncoding, 'gzip') === false) {
            return $response;
        }

        // Get the content
        $content = $response->getContent();

        // Don't compress empty responses or already compressed responses
        if (empty($content) || $response->headers->has('Content-Encoding')) {
            return $response;
        }

        // Don't compress binary files (images, PDFs, etc.)
        $contentType = $response->headers->get('Content-Type', '');
        $compressibleTypes = [
            'text/html',
            'text/plain',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'application/xml',
            'text/xml',
            'application/xhtml+xml',
        ];

        $isCompressible = false;
        foreach ($compressibleTypes as $type) {
            if (stripos($contentType, $type) !== false) {
                $isCompressible = true;
                break;
            }
        }

        // Also compress if no content type is set (usually HTML)
        if (!$isCompressible && !empty($contentType)) {
            return $response;
        }

        // Don't compress small responses (less than 1KB)
        if (strlen($content) < 1024) {
            return $response;
        }

        // Compress the content
        $compressed = gzencode($content, 6);

        if ($compressed === false) {
            return $response;
        }

        // Only use compressed version if it's actually smaller
        if (strlen($compressed) >= strlen($content)) {
            return $response;
        }

        // Set the compressed content and headers
        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', strlen($compressed));
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }
}

