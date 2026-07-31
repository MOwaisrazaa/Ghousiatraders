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
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // 1. Super Admin always has full access
        if ($user->isSuperAdmin() || $request->attributes->get('is_super_admin', false)) {
            return $next($request);
        }

        // 2. Ensure user is an admin account
        if (!$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access this area.');
        }

        // 3. Determine action type from HTTP method & request route
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

        // 4. Check specific permission for non-super admin users
        if ($user->hasPermission($permissionKey) || $user->hasPermission("{$page}.manage") || $user->hasPermission($page)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'You do not have permission to access this resource.');
    }
}
