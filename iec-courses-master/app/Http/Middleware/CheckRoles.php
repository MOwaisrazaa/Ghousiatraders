<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('sign-in')->withError('Please login to access this page');
        }
        
        $user_roles = $user->roles->pluck('name')->toArray();
        
        if (count(array_intersect($roles, $user_roles)) > 0) {
            return $next($request);    
        }
        
        // Log unauthorized access attempts
        \Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_roles' => $user_roles,
            'required_roles' => $roles,
            'url' => $request->url()
        ]);
        
        return redirect()->back()->withError('Unauthorized access');
    }
}
