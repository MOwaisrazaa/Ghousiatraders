<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSensitiveFiles
{
    /**
     * Handle an incoming request.
     * Replaces .htaccess file protection functionality
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();
        $path = strtolower($request->path());

        // Skip middleware for root path to avoid blocking
        if ($path === '' || $path === '/') {
            return $next($request);
        }

        // Block common admin panel paths that might redirect to external control panels
        // Note: 'admin' is NOT blocked as it's our application's admin panel
        $blockedAdminPaths = [
            'isp', 'controlpanel', 'cwp', 'cpanel', 'whm', 'webmin', 'plesk', 
            'directadmin', 'ispconfig', 'cyberpanel', 'vestacp', 'hestiacp',
            'froxlor', 'sentora', 'zpanel', 'kloxo', 'ajenti', 'usermin',
            'phpmyadmin', 'adminer', 'phppgadmin', 'roundcube', 'squirrelmail',
            'rainloop', 'afterlogic', 'server-manager', 'server-info', 
            'server-status', 'admin-panel', 'control-panel', 'web-panel',
            'hosting-panel', 'server-panel'
        ];

        // Check if current path matches any blocked admin paths
        foreach ($blockedAdminPaths as $blockedPath) {
            if ($path === $blockedPath || str_starts_with($path, $blockedPath . '/')) {
                abort(404, 'Page not found');
            }
        }

        // Block access to URLs with common server management ports
        if (preg_match('/:(2030|2031|2032|2083|2087|8080|8443|10000|20000)/', $uri)) {
            abort(404, 'Page not found');
        }

        // Block access to sensitive files (replacing .htaccess FilesMatch rules)
        $sensitiveFiles = [
            'composer.json', 'composer.lock', 'package.json', 'package-lock.json',
            'webpack.mix.js', 'artisan', 'server.php', 'phpunit.xml',
            '.env', '.env.example', '.gitignore', '.gitattributes'
        ];

        // Block access to sensitive file extensions
        $sensitiveExtensions = [
            '.log', '.sql', '.dump', '.backup', '.bak', '.config', '.conf',
            '.ini', '.xml', '.json', '.zip', '.tar', '.gz', '.rar', '.7z',
            '.key', '.pem', '.crt', '.p12', '.pfx'
        ];

        // Check for sensitive file extensions
        foreach ($sensitiveExtensions as $ext) {
            if (str_ends_with(strtolower($uri), $ext)) {
                abort(403, 'Access denied to sensitive files');
            }
        }

        // Block access to dot files (replacing .htaccess ^\.ht rule)
        if (preg_match('/\/\.[^\/]*$/', $uri)) {
            abort(403, 'Access denied to hidden files');
        }

        // Block access to sensitive files
        foreach ($sensitiveFiles as $file) {
            if (str_ends_with($uri, $file)) {
                abort(403, 'Access denied to sensitive files');
            }
        }

        // Block access to sensitive directories (replacing .htaccess directory rules)
        $sensitiveDirectories = [
            'app/', 'bootstrap/', 'config/', 'database/', 'resources/',
            'routes/', 'tests/', 'vendor/'
        ];

        foreach ($sensitiveDirectories as $dir) {
            if (str_starts_with($path, $dir)) {
                abort(403, 'Access denied to application directories');
            }
        }

        // Enhanced storage directory protection
        if (str_starts_with($path, 'storage/')) {
            return $this->handleStorageAccess($request, $path);
        }

        // Block access to build directories
        if (str_starts_with($path, 'build/') || str_contains($path, '/build/')) {
            abort(403, 'Access denied to build directories');
        }

        // Block PHP file execution in uploads directory
        if (str_contains($path, 'uploads/') && preg_match('/\.(php|phtml|php3|php4|php5|php7)$/i', $uri)) {
            abort(403, 'PHP execution not allowed in uploads directory');
        }

        // Block PHP file execution in storage directory  
        if (str_contains($path, 'storage/') && preg_match('/\.(php|phtml|php3|php4|php5|php7)$/i', $uri)) {
            abort(403, 'PHP execution not allowed in storage directory');
        }

        // Block directory listing attempts (but allow root path and admin paths)
        if (str_ends_with($uri, '/') && !str_ends_with($uri, 'index.php/') && !str_ends_with($uri, 'index.html/')) {
            // Allow root path (empty string for '/'), admin paths, and dashboard paths
            if ($path !== '' && $path !== 'admin' && !str_starts_with($path, 'admin/') && $path !== 'dashboard' && !str_starts_with($path, 'dashboard/')) {
                abort(403, 'Directory listing not allowed');
            }
        }

        // Limit HTTP methods (replacing .htaccess method restrictions)
        $allowedMethods = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
        if (!in_array($request->getMethod(), $allowedMethods)) {
            abort(405, 'Method not allowed');
        }

        return $next($request);
    }

    /**
     * Handle storage access with authentication and authorization
     */
    private function handleStorageAccess(Request $request, string $path): void
    {
        // Allow access to public storage files (like profile pictures)
        if (str_starts_with($path, 'storage/app/public/')) {
            return; // Continue with request
        }

        // Block access to courses and lectures for non-authenticated users
        if (str_starts_with($path, 'storage/courses/') || str_starts_with($path, 'storage/lectures/')) {
            if (!auth()->check()) {
                abort(401, 'Authentication required');
            }

            // Check if user has appropriate role or permissions
            $user = auth()->user();
            if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
                return; // Continue with request
            }

            // For regular users, you can implement additional authorization logic here
            // For now, we'll allow authenticated users to access their purchased content
            return; // Continue with request
        }

        // Block all other storage access
        abort(403, 'Access denied to storage files');
    }
}
