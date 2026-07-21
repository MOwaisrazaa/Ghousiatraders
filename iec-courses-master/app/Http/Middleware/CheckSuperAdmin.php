<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Super Admin access required.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page. Super Admin access required.');
        }

        return $next($request);
    }
}
