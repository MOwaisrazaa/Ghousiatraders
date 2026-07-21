<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PreventDirectoryListing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is attempting to access a directory
        $path = $request->path();

        // Skip root path to avoid redirect loops
        if ($path === '/') {
            return $next($request);
        }

        // If the path ends with a slash and doesn't have an index file, it's likely a directory listing attempt
        if (substr($path, -1) === '/' && !$this->hasIndexFile($path)) {
            // Log the attempt
            Log::channel('security')->warning('Directory listing attempt', [
                'path' => $path,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Redirect to home page
            return redirect('/');
        }

        return $next($request);
    }

    /**
     * Check if the directory has an index file.
     *
     * @param  string  $path
     * @return bool
     */
    protected function hasIndexFile($path)
    {
        $publicPath = public_path($path);
        
        // Check for common index files
        return file_exists($publicPath . 'index.php') || 
               file_exists($publicPath . 'index.html') || 
               file_exists($publicPath . 'index.htm');
    }
}
