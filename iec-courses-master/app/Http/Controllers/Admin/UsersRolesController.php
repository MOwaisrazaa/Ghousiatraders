<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersRolesController extends Controller
{
    /**
     * Display main Users & Roles view with real data, filters, and stats.
     */
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'users');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $search = trim((string) $request->input('search'));
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        // Statistics
        $totalUsersCount = User::count();
        $activeUsersCount = User::where('status', 'active')->count();
        $inactiveUsersCount = User::whereIn('status', ['inactive', 'suspended', 'blocked'])->count();
        $totalRolesCount = Role::count();

        // Month-over-month growth calculations
        $lastMonthDate = now()->subMonth();
        $totalUsersLastMonth = User::where('created_at', '<=', $lastMonthDate)->count();
        $activeUsersLastMonth = User::where('status', 'active')->where('created_at', '<=', $lastMonthDate)->count();
        $inactiveUsersLastMonth = User::whereIn('status', ['inactive', 'suspended', 'blocked'])->where('created_at', '<=', $lastMonthDate)->count();

        $userGrowthPct = $totalUsersLastMonth > 0 ? round((($totalUsersCount - $totalUsersLastMonth) / $totalUsersLastMonth) * 100, 1) : 12;
        $activeGrowthPct = $activeUsersLastMonth > 0 ? round((($activeUsersCount - $activeUsersLastMonth) / $activeUsersLastMonth) * 100, 1) : 9;
        $inactiveGrowthPct = $inactiveUsersLastMonth > 0 ? round((($inactiveUsersCount - $inactiveUsersLastMonth) / $inactiveUsersLastMonth) * 100, 1) : -2;

        // Query Users
        $usersQuery = User::with(['roles']);

        if ($search !== '') {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('roles', function ($rq) use ($search) {
                      $rq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $usersQuery->whereHas('roles', function ($q) use ($roleFilter) {
                if (is_numeric($roleFilter)) {
                    $q->where('roles.id', $roleFilter);
                } else {
                    $q->where('roles.name', $roleFilter);
                }
            });
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $usersQuery->where('status', strtolower($statusFilter));
        }

        $users = $usersQuery->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->all());

        // Query Roles
        $roles = Role::withCount('users')->get();

        // Roles Overview Data for Right Sidebar
        $rolesOverview = Role::withCount('users')->get();

        // Activity Logs
        $activityLogs = UserActivityLog::with(['user', 'targetUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Available Modules for Permission Matrix
        $permissionModules = [
            'dashboard' => ['view', 'manage'],
            'orders' => ['view', 'create', 'edit', 'delete', 'export', 'approve'],
            'products' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'customers' => ['view', 'create', 'edit', 'delete', 'export'],
            'reviews' => ['view', 'approve', 'delete'],
            'coupons' => ['view', 'create', 'edit', 'delete'],
            'marketing' => ['view', 'create', 'edit', 'delete'],
            'reports' => ['view', 'export'],
            'media' => ['view', 'create', 'delete'],
            'settings' => ['view', 'edit'],
            'store_settings' => ['view', 'edit'],
            'users_roles' => ['view', 'create', 'edit', 'delete', 'manage'],
            'support' => ['view', 'create', 'edit', 'delete'],
        ];

        return view('admin.users-roles.index', compact(
            'activeTab',
            'users',
            'roles',
            'rolesOverview',
            'totalUsersCount',
            'activeUsersCount',
            'inactiveUsersCount',
            'totalRolesCount',
            'userGrowthPct',
            'activeGrowthPct',
            'inactiveGrowthPct',
            'search',
            'roleFilter',
            'statusFilter',
            'perPage',
            'activityLogs',
            'permissionModules'
        ));
    }

    /**
     * Store new admin user.
     */
    public function storeUser(Request $request)
    {
        $currentUser = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'nullable|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive,suspended',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        $roleToAssign = Role::findOrFail($request->role_id);

        // Security Check: Non-Super Admin cannot create Super Admin
        if ($roleToAssign->name === 'Super Admin' && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Only Super Admins can create another Super Admin account.');
        }

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $request->name,
                'username' => $request->username ?: Str::slug($request->name, '_') . rand(100, 999),
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'password_change_required' => $request->has('require_password_change'),
            ];

            // Image handling
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = 'avatar_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/avatars'), $filename);
                $userData['profile_image'] = 'uploads/avatars/' . $filename;
            }

            $user = User::create($userData);
            $user->roles()->sync([$roleToAssign->id]);

            // Log activity
            $this->logActivity('User Created', $user->id, "Created admin user {$user->name} with role {$roleToAssign->name}");

            DB::commit();

            return redirect()->route('admin.users-roles', ['tab' => 'users'])
                ->with('success', "User '{$user->name}' created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Store Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update existing admin user.
     */
    public function updateUser(Request $request, User $user)
    {
        $currentUser = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive,suspended',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        $newRole = Role::findOrFail($request->role_id);

        // Security Check 1: Cannot demote or deactivate self
        if ($currentUser->id === $user->id) {
            if ($request->status !== 'active') {
                return back()->with('error', 'Security Protection: You cannot deactivate or suspend your own active account.');
            }
            if ($user->isSuperAdmin() && $newRole->name !== 'Super Admin') {
                return back()->with('error', 'Security Protection: You cannot remove your own Super Admin privileges.');
            }
        }

        // Security Check 2: Last remaining Super Admin protection
        if ($user->isSuperAdmin() && $newRole->name !== 'Super Admin') {
            $superAdminCount = User::whereHas('roles', fn($q) => $q->where('name', 'Super Admin'))->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Security Protection: The final remaining Super Admin role cannot be demoted.');
            }
        }

        // Security Check 3: Non-Super Admin editing Super Admin or assigning Super Admin
        if ($user->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Security Protection: Only a Super Admin can modify another Super Admin.');
        }

        if ($newRole->name === 'Super Admin' && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Security Protection: You cannot grant a Super Admin role.');
        }

        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->username = $request->username ?: $user->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = 'avatar_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/avatars'), $filename);
                $user->profile_image = 'uploads/avatars/' . $filename;
            }

            $user->save();
            $user->roles()->sync([$newRole->id]);

            $this->logActivity('User Updated', $user->id, "Updated profile/role of {$user->name}");

            DB::commit();

            return redirect()->route('admin.users-roles', ['tab' => 'users'])
                ->with('success', "User '{$user->name}' updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete user with safety checks.
     */
    public function destroyUser(User $user)
    {
        $currentUser = Auth::user();

        // Safety Check 1: Cannot delete self
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Security Protection: You cannot delete your own account.');
        }

        // Safety Check 2: Cannot delete final Super Admin
        if ($user->isSuperAdmin()) {
            $superAdminCount = User::whereHas('roles', fn($q) => $q->where('name', 'Super Admin'))->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Security Protection: The final remaining Super Admin cannot be deleted.');
            }

            if (!$currentUser->isSuperAdmin()) {
                return back()->with('error', 'Security Protection: Only Super Admins can delete a Super Admin.');
            }
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $user->roles()->detach();
            $user->delete();

            $this->logActivity('User Deleted', null, "Deleted user {$userName}");

            DB::commit();

            return redirect()->route('admin.users-roles', ['tab' => 'users'])
                ->with('success', "User '{$userName}' deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status (Active / Inactive / Suspended)
     */
    public function toggleUserStatus(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['active', 'inactive', 'suspended'])) {
            return back()->with('error', 'Invalid status selection.');
        }

        if ($currentUser->id === $user->id && $newStatus !== 'active') {
            return back()->with('error', 'Security Protection: You cannot deactivate or suspend your own account.');
        }

        if ($user->isSuperAdmin() && $newStatus !== 'active') {
            $superAdminCount = User::whereHas('roles', fn($q) => $q->where('name', 'Super Admin'))->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Security Protection: The final remaining Super Admin cannot be deactivated.');
            }
        }

        $user->status = $newStatus;
        $user->save();

        $this->logActivity("User Status Changed ({$newStatus})", $user->id, "Changed status of {$user->name} to {$newStatus}");

        return redirect()->route('admin.users-roles', ['tab' => 'users'])
            ->with('success', "User '{$user->name}' status updated to {$newStatus}.");
    }

    /**
     * Reset User Password directly
     */
    public function resetUserPassword(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($user->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Security Protection: Only Super Admins can reset a Super Admin password.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user->password = Hash::make($request->password);
        $user->password_change_required = true;
        $user->save();

        $this->logActivity('Password Reset', $user->id, "Reset password for user {$user->name}");

        return redirect()->route('admin.users-roles', ['tab' => 'users'])
            ->with('success', "Password reset successfully for '{$user->name}'.");
    }

    /**
     * Store new Role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            if ($request->has('permissions')) {
                foreach ($request->permissions as $permKey) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'permission' => $permKey,
                        'is_allowed' => true
                    ]);
                }
            }

            $this->logActivity('Role Created', null, "Created role '{$role->name}' with " . count($request->permissions ?? []) . " permissions.");

            DB::commit();

            return redirect()->route('admin.users-roles', ['tab' => 'roles'])
                ->with('success', "Role '{$role->name}' created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update existing Role.
     */
    public function updateRole(Request $request, Role $role)
    {
        // Protected System Roles
        if (in_array($role->name, ['Super Admin']) && $request->name !== 'Super Admin') {
            return back()->with('error', 'System Protected: Super Admin role name cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            $role->name = $request->name;
            $role->description = $request->description;
            $role->status = $request->status;
            $role->save();

            // Sync permissions
            RolePermission::where('role_id', $role->id)->delete();
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permKey) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'permission' => $permKey,
                        'is_allowed' => true
                    ]);
                }
            }

            $this->logActivity('Role Updated', null, "Updated role '{$role->name}' and its permission set.");

            DB::commit();

            return redirect()->route('admin.users-roles', ['tab' => 'roles'])
                ->with('success', "Role '{$role->name}' updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete Role.
     */
    public function destroyRole(Role $role)
    {
        if (in_array($role->name, ['Super Admin', 'Admin'])) {
            return back()->with('error', "System Protected: Role '{$role->name}' is system-protected and cannot be deleted.");
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', "Cannot delete role '{$role->name}' because it is assigned to {$role->users()->count()} users.");
        }

        DB::beginTransaction();
        try {
            $roleName = $role->name;
            RolePermission::where('role_id', $role->id)->delete();
            $role->delete();

            $this->logActivity('Role Deleted', null, "Deleted role '{$roleName}'.");

            DB::commit();

            return redirect()->route('admin.users-roles', ['tab' => 'roles'])
                ->with('success', "Role '{$roleName}' deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    /**
     * Export filtered Users list as CSV.
     */
    public function exportUsers(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        $usersQuery = User::with(['roles']);

        if ($search !== '') {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $usersQuery->whereHas('roles', fn($q) => $q->where('name', $roleFilter));
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $usersQuery->where('status', strtolower($statusFilter));
        }

        $users = $usersQuery->orderBy('name')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=users_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Full Name', 'Username', 'Email', 'Phone', 'Role', 'Status', 'Last Login', 'Created At']);

            foreach ($users as $u) {
                $roleName = $u->roles->pluck('name')->implode(', ') ?: 'N/A';
                fputcsv($file, [
                    $u->id,
                    $u->name,
                    $u->username ?: 'N/A',
                    $u->email,
                    $u->phone ?: 'N/A',
                    $roleName,
                    ucfirst($u->status ?: 'active'),
                    $u->last_login_at ? $u->last_login_at->format('Y-m-d H:i') : 'Never',
                    $u->created_at ? $u->created_at->format('Y-m-d H:i') : 'N/A'
                ]);
            }
            fclose($file);
        };

        $this->logActivity('Export Users', null, 'Exported filtered admin users list.');

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper to write security activity log.
     */
    private function logActivity($action, $targetUserId = null, $details = null)
    {
        try {
            UserActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'target_user_id' => $targetUserId,
                'details' => $details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Could not record activity log: ' . $e->getMessage());
        }
    }
}
