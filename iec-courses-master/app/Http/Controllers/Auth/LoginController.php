<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use App\Services\DeviceDetectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{
    protected $deviceDetectionService;

    public function __construct(DeviceDetectionService $deviceDetectionService)
    {
        $this->deviceDetectionService = $deviceDetectionService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signin');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Normalize email input
        if ($request->has('email')) {
            $request->merge([
                'email' => strtolower(trim($request->email)),
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Add remember me functionality
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();

            // Skip device tracking for admin and superadmin
            if (!$user->isAdmin() && !$user->isSuperAdmin()) {
                // Check device restrictions
                $deviceId = $this->deviceDetectionService->generateDeviceId($request);
                $currentIp = $this->deviceDetectionService->getIpAddress($request);
                $currentDeviceInfo = $this->deviceDetectionService->getDeviceInfo();
                $currentDeviceType = $currentDeviceInfo['device_type'];

                // Check if user has a primary device
                $primaryDevice = $user->primaryDevice();

                if ($primaryDevice) {
                    // Check if user has already reached max IP addresses (5 IPs allowed)
                    $uniqueIpCount = $user->devices()->distinct('ip_address')->count('ip_address');
                    $existingIpDevice = $user->devices()->where('ip_address', $currentIp)->first();

                    // Allow if current IP is already in the allowed list (up to 5 IPs)
                    if ($existingIpDevice) {
                        // Update last login time and device info
                        $existingIpDevice->update([
                            'device_name' => $currentDeviceInfo['device_name'],
                            'browser' => $currentDeviceInfo['browser'],
                            'platform' => $currentDeviceInfo['platform'],
                            'device_type' => $currentDeviceType,
                            'device_id' => $deviceId,
                            'last_login_at' => now()
                        ]);
                    }
                    // If IP not already in list, check if we can add a new one
                    elseif ($uniqueIpCount >= 5) {
                        // Log the access attempt
                        Log::warning('Login attempt from unauthorized IP address', [
                            'user_id' => $user->id,
                            'ip' => $currentIp,
                            'device_id' => $deviceId,
                            'current_ip_count' => $uniqueIpCount
                        ]);

                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('device.restriction.error')
                            ->with('error', "🌐 Maximum IP Addresses Reached")
                            ->with('error_details', "For security reasons, your account can only be accessed from a maximum of 5 different internet connections (IP addresses). You have already used all 5 allowed connections. Please login from one of your previously used locations or contact our support team for assistance.")
                            ->with('error_type', 'ip_limit_reached')
                            ->with('support_contact', 'support@polanifragrance.com');
                    } else {
                        // User hasn't reached the IP limit yet, log a notice and allow
                        Log::notice('Login from new IP but under limit', [
                            'user_id' => $user->id,
                            'ip' => $currentIp,
                            'device_id' => $deviceId,
                            'device_type' => $currentDeviceType,
                            'current_ip_count' => $uniqueIpCount
                        ]);

                        // Check if device_id already exists for this user
                        $existingDevice = UserDevice::where('device_id', $deviceId)
                            ->where('user_id', $user->id)
                            ->first();

                        if ($existingDevice) {
                            // Update existing device record with new IP
                            $existingDevice->update([
                                'device_name' => $currentDeviceInfo['device_name'],
                                'browser' => $currentDeviceInfo['browser'],
                                'platform' => $currentDeviceInfo['platform'],
                                'device_type' => $currentDeviceType,
                                'ip_address' => $currentIp,
                                'last_login_at' => now()
                            ]);
                        } else {
                            // Create new device record
                            UserDevice::create([
                                'user_id' => $user->id,
                                'device_name' => $currentDeviceInfo['device_name'],
                                'browser' => $currentDeviceInfo['browser'],
                                'platform' => $currentDeviceInfo['platform'],
                                'device_type' => $currentDeviceType,
                                'device_id' => $deviceId,
                                'ip_address' => $currentIp,
                                'last_login_at' => now(),
                                'is_primary' => false
                            ]);
                        }
                    }
                } else {
                    // No primary device found, set this device as primary
                    // This handles the case for existing users who don't have a device record yet
                    UserDevice::create([
                        'user_id' => $user->id,
                        'device_name' => $currentDeviceInfo['device_name'],
                        'browser' => $currentDeviceInfo['browser'],
                        'platform' => $currentDeviceInfo['platform'],
                        'device_type' => $currentDeviceType,
                        'device_id' => $deviceId,
                        'ip_address' => $currentIp,
                        'last_login_at' => now(),
                        'is_primary' => true
                    ]);
                }
            }

            // Update the user's updated_at time with Pakistan timezone
            $pakistanTime = Carbon::now('Asia/Karachi');
            $user->updated_at = $pakistanTime;
            $user->save();

            // Check user roles and redirect accordingly
            if ($user->isSuperAdmin()) {
                // Log the redirect attempt
                Log::info('Super admin login - redirecting to admin dashboard', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                // Redirect super admin to admin dashboard
                return redirect('/admin')->with('success', 'Welcome back, Super Admin!');
            } elseif ($user->isAdmin()) {
                // Log the redirect attempt
                Log::info('Admin login - redirecting to admin dashboard', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                // Redirect admin to admin dashboard
                return redirect('/admin')->with('success', 'Welcome back, Admin!');
            } else {
                return redirect()->intended(route('home'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sign-in');
    }

    /**
     * Extract device type from device name
     *
     * @param string $deviceName
     * @return string
     */
    private function getDeviceTypeFromName($deviceName)
    {
        if (strpos($deviceName, 'Desktop') !== false) {
            return 'Desktop';
        }
        if (strpos($deviceName, 'Mobile') !== false) {
            return 'Mobile';
        }
        if (strpos($deviceName, 'Tablet') !== false) {
            return 'Tablet';
        }
        return 'Unknown';
    }
}
