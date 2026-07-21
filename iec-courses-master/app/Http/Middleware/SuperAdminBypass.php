<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class SuperAdminBypass
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is a super admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $isSuperAdmin = $superAdminRole && $user->roles()->where('role_id', $superAdminRole->id)->exists();

        // Store the result in the request for other middleware to use
        $request->attributes->add(['is_super_admin' => $isSuperAdmin]);

        return $next($request);
    }
}
