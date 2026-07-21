<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventUserParamManipulation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for admin/super admin users
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())) {
            return $next($request);
        }
        
        // If there's a user_id parameter and it doesn't match the authenticated user
        if ($request->has('user_id') && Auth::check() && $request->user_id != Auth::id()) {
            return abort(403, 'Unauthorized action.');
        }
        
        // If there's a username parameter and it doesn't match the authenticated user
        if ($request->has('username') && Auth::check() && $request->username != Auth::user()->username) {
            return abort(403, 'Unauthorized action.');
        }
        
        // If there's a user parameter 
        if ($request->has('user') && !in_array($request->method(), ['GET'])) {
            if (Auth::check() && is_numeric($request->user) && $request->user != Auth::id()) {
                return abort(403, 'Unauthorized action.');
            }
        }
        
        // Check the route parameters for user or similar parameters
        $route = $request->route();
        if ($route && isset($route->parameters['user'])) {
            if (Auth::check() && is_numeric($route->parameters['user']) && $route->parameters['user'] != Auth::id()) {
                if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
                    return abort(403, 'Unauthorized action.');
                }
            }
        }
        
        return $next($request);
    }
} 