<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TrafficLog;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

class LogTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Exclude specific paths pattern
        if ($request->is('admin*', 'livewire*', '_debugbar*', 'api*', 'storage*', '*.xml', '*.txt', 'favicon.ico')) {
             return $next($request);
        }
        
        // Only log GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Exclude AJAX requests to avoid spamming logs with partial updates
        if ($request->ajax() || $request->wantsJson()) {
             return $next($request);
        }

        try {
            $agent = new Agent();
            $agent->setUserAgent($request->header('User-Agent'));
            
            // Simple country extraction from headers (Cloudflare, etc)
            $country = $request->header('CF-IPCountry') ?? null;
            
            // Determine device type
            $deviceType = 'desktop';
            if ($agent->isTablet()) {
                $deviceType = 'tablet';
            } elseif ($agent->isMobile()) { // isMobile checks for phone OR tablet usually, but we check tablet first
                $deviceType = 'mobile';
            }

            TrafficLog::create([
                'ip_address' => $request->ip(),
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
                'referer' => $request->header('referer') ? substr($request->header('referer'), 0, 1500) : null,
                'user_agent' => substr($request->header('User-Agent'), 0, 500),
                'device_type' => $deviceType,
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
                'country' => $country,
            ]);
        } catch (\Exception $e) {
            // Fail silently to not impact user experience
            Log::error('Traffic Logging Error: ' . $e->getMessage());
        }

        return $next($request);
    }
}
