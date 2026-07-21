<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserAssignmentController extends Controller
{
    /**
     * Display a listing of admin-user assignments.
     */
    public function index()
    {
        $user = Auth::user();

        // Super admin sees all assignments
        if ($user->isSuperAdmin()) {
            $assignments = AdminUserAssignment::with(['admin', 'user'])->get();
            $admins = User::whereHas('roles', function($query) {
                $query->where('name', 'Admin');
            })->get();
            $users = User::whereHas('roles', function($query) {
                $query->where('name', 'User');
            })->get();
        } else {
            // Regular admin only sees their own assignments
            $assignments = AdminUserAssignment::where('admin_id', $user->id)
                ->with(['admin', 'user'])
                ->get();
            $admins = [];
            $users = User::whereDoesntHave('assignedAdmin')
                ->whereHas('roles', function($query) {
                    $query->where('name', 'User');
                })
                ->get();
        }

        return view('admin.assignments.index', compact('assignments', 'admins', 'users'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $admins = User::whereHas('roles', function($query) {
                $query->where('name', 'Admin');
            })->get();
        } else {
            $admins = User::where('id', $user->id)->get();
        }

        // Get users that aren't already assigned to an admin
        $users = User::whereDoesntHave('assignedAdmin')
            ->whereHas('roles', function($query) {
                $query->where('name', 'User');
            })
            ->get();

        return view('admin.assignments.create', compact('admins', 'users'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_id' => $user->isSuperAdmin() ? 'required|exists:users,id' : '',
        ]);

        // If not super admin, use current user's ID
        $adminId = $user->isSuperAdmin() ? $request->admin_id : $user->id;

        // Create the assignment
        AdminUserAssignment::create([
            'admin_id' => $adminId,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'User assigned successfully.');
    }

    /**
     * Show details for a specific assignment.
     */
    public function show($id)
    {
        $assignment = AdminUserAssignment::with(['admin', 'user'])->findOrFail($id);

        // Only super admin or the admin who owns the assignment can view it
        if (!Auth::user()->isSuperAdmin() && $assignment->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.assignments.show', compact('assignment'));
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy($id)
    {
        $assignment = AdminUserAssignment::findOrFail($id);

        // Only super admin or the admin who owns the assignment can delete it
        if (!Auth::user()->isSuperAdmin() && $assignment->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment removed successfully.');
    }

    /**
     * Batch assign multiple users to an admin.
     */
    public function batchAssign(Request $request)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'admin_id' => $user->isSuperAdmin() ? 'required|exists:users,id' : '',
        ]);

        // If not super admin, use current user's ID
        $adminId = $user->isSuperAdmin() ? $request->admin_id : $user->id;

        // Create assignments for each user
        foreach ($request->user_ids as $userId) {
            // Skip if user already has an admin assigned
            if (AdminUserAssignment::where('user_id', $userId)->exists()) {
                continue;
            }

            AdminUserAssignment::create([
                'admin_id' => $adminId,
                'user_id' => $userId,
            ]);
        }

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Users assigned successfully.');
    }
}
