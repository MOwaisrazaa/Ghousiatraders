<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $page): Response
    {
        // Check if user is a super admin (set by SuperAdminBypass middleware)
        if ($request->attributes->get('is_super_admin', false)) {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Determine action type from HTTP method & request route
        $action = 'view';
        if ($request->isMethod('post')) {
            $action = 'create';
        } elseif ($request->isMethod('put') || $request->isMethod('patch')) {
            $action = 'edit';
        } elseif ($request->isMethod('delete')) {
            $action = 'delete';
        }

        if ($request->routeIs('*export*')) {
            $action = 'export';
        }

        $permissionKey = "{$page}.{$action}";

        if ($user->hasPermission($permissionKey) || $user->hasPermission("{$page}.manage") || $user->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'You do not have permission to access this resource.');
    }
}
