<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of all users' devices for admin management
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            // Super admin can see all devices
            $devices = UserDevice::with('user')
                ->orderBy('is_primary', 'desc')
                ->orderBy('last_login_at', 'desc')
                ->get();
        } else {
            // Regular admin can only see devices of assigned users
            $assignedUserIds = $user->assignedUsers->pluck('user.id')->toArray();
            $assignedUserIds[] = $user->id; // Include admin's own devices

            $devices = UserDevice::with('user')
                ->whereIn('user_id', $assignedUserIds)
                ->orderBy('is_primary', 'desc')
                ->orderBy('last_login_at', 'desc')
                ->get();
        }

        return view('devices.index', compact('devices'));
    }

    /**
     * Remove a device from user's allowed devices (Admin function)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $device = UserDevice::with('user')->find($id);

        if (!$device) {
            return back()->with('error', 'Device not found.');
        }

        // Super admin can delete any device, regular admin can only delete devices of assigned users
        if (!$currentUser->isSuperAdmin()) {
            $assignedUserIds = $currentUser->assignedUsers->pluck('user.id')->toArray();
            $assignedUserIds[] = $currentUser->id; // Include admin's own devices

            if (!in_array($device->user_id, $assignedUserIds)) {
                return back()->with('error', 'You do not have permission to manage this device.');
            }
        }

        // Don't allow deleting primary device
        if ($device->is_primary) {
            return back()->with('error', 'Cannot delete primary device. User must have at least one device.');
        }

        $userName = $device->user->name;
        $device->delete();

        return back()->with('success', "Device removed successfully from {$userName}'s account.");
    }

    /**
     * Reset all device restrictions for a specific user
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function resetUserDevices($userId)
    {
        $currentUser = Auth::user();
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Check if current user has permission to reset devices for this user
        if (!$currentUser->isSuperAdmin()) {
            $assignedUserIds = $currentUser->assignedUsers->pluck('user.id')->toArray();
            if (!in_array($user->id, $assignedUserIds)) {
                return back()->with('error', 'You do not have permission to reset devices for this user.');
            }
        }

        // Remove all devices for the user
        $deviceCount = $user->devices()->count();
        $user->devices()->delete();

        return back()->with('success', "Successfully reset device restrictions for {$user->name}. Removed {$deviceCount} device(s). User can now login from any device and will set a new primary device.");
    }
}
