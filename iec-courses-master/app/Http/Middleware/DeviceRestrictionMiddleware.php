<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Services\DeviceDetectionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeviceRestrictionMiddleware
{
    protected $deviceDetectionService;

    public function __construct(DeviceDetectionService $deviceDetectionService)
    {
        $this->deviceDetectionService = $deviceDetectionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Debug logging for admin status
            Log::info('Device restriction middleware check', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'isAdmin' => $user->isAdmin(),
                'isSuperAdmin' => $user->isSuperAdmin(),
                'roles' => $user->roles->pluck('name')->toArray(),
                'route' => $request->route()->getName(),
                'url' => $request->url()
            ]);

            // Skip restrictions for admin and superadmin
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                Log::info('Skipping device restrictions for admin user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                return $next($request);
            }

            // Temporary fix: Skip restrictions for specific user
            if ($user->email === 'talibebaqi@gmail.com') {
                Log::info('Skipping device restrictions for specific user (temporary fix)', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                return $next($request);
            }

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
                    try {
                        // Update last login time and device info (NEVER update device_id to avoid constraint violations)
                        $existingIpDevice->update([
                            'device_name' => $currentDeviceInfo['device_name'],
                            'browser' => $currentDeviceInfo['browser'],
                            'platform' => $currentDeviceInfo['platform'],
                            'device_type' => $currentDeviceType,
                            'last_login_at' => now()
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error updating device record', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
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
                        'current_ip_count' => $uniqueIpCount,
                        'max_allowed' => 5
                    ]);

                    // Check if device_id already exists for this user
                    $existingDevice = UserDevice::where('device_id', $deviceId)
                        ->where('user_id', $user->id)
                        ->first();

                    if ($existingDevice) {
                        try {
                            // Update existing device record with new IP
                            $existingDevice->update([
                                'device_name' => $currentDeviceInfo['device_name'],
                                'browser' => $currentDeviceInfo['browser'],
                                'platform' => $currentDeviceInfo['platform'],
                                'device_type' => $currentDeviceType,
                                'ip_address' => $currentIp,
                                'last_login_at' => now()
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error updating existing device', [
                                'user_id' => $user->id,
                                'device_id' => $deviceId,
                                'error' => $e->getMessage()
                            ]);
                        }
                    } else {
                        try {
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
                        } catch (\Exception $e) {
                            Log::error('Error creating new device record', [
                                'user_id' => $user->id,
                                'device_id' => $deviceId,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    // Show warning if approaching IP limit
                    if ($uniqueIpCount == 4) {
                        session()->flash('warning', '⚠️ IP Address Limit Warning');
                        session()->flash('warning_details', 'You are now using 5 out of 5 allowed IP addresses. This is your final allowed location. Future logins from new locations will be blocked for security reasons.');
                    }
                }
            } else {
                // No primary device found, set this device as primary
                // This handles the case for existing users who don't have a device record
                try {
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
                } catch (\Exception $e) {
                    Log::error('Error creating primary device record', [
                        'user_id' => $user->id,
                        'device_id' => $deviceId,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return $next($request);
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
