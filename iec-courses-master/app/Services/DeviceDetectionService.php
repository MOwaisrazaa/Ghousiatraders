<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class DeviceDetectionService
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Generate a unique device identifier based on user agent, platform and other factors
     *
     * @param Request $request
     * @return string
     */
    public function generateDeviceId(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        $platform = $this->agent->platform();
        $browser = $this->agent->browser();
        $deviceType = $this->getDeviceType();
        $acceptLanguage = $request->header('Accept-Language');
        $acceptEncoding = $request->header('Accept-Encoding');

        // Combine more factors to generate a more unique identifier
        $deviceData = implode('|', [
            $userAgent,
            $platform,
            $browser,
            $deviceType,
            $acceptLanguage,
            $acceptEncoding,
            // Add screen resolution if available (would need JavaScript)
            $request->header('Sec-CH-UA-Platform', ''),
            $request->header('Sec-CH-UA', ''),
        ]);

        // Generate a hash of the device data
        return hash('sha256', $deviceData);
    }

    /**
     * Get the device information
     *
     * @return array
     */
    public function getDeviceInfo()
    {
        return [
            'device_name' => $this->getDeviceName(),
            'browser' => $this->agent->browser(),
            'platform' => $this->agent->platform(),
            'device_type' => $this->getDeviceType(),
        ];
    }

    /**
     * Get device name
     *
     * @return string
     */
    protected function getDeviceName()
    {
        $platform = $this->agent->platform();
        $browser = $this->agent->browser();
        $deviceType = $this->getDeviceType();

        return $deviceType . ' - ' . $platform . ' - ' . $browser;
    }

    /**
     * Get device type
     *
     * @return string
     */
    protected function getDeviceType()
    {
        if ($this->agent->isDesktop()) {
            return 'Desktop';
        }

        if ($this->agent->isTablet()) {
            return 'Tablet';
        }

        if ($this->agent->isMobile()) {
            return 'Mobile';
        }

        return 'Unknown';
    }

    /**
     * Get the real IP address even when behind a proxy
     *
     * @param Request $request
     * @return string
     */
    public function getIpAddress(Request $request)
    {
        // Check for proxy headers first
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if ($request->server($header)) {
                // HTTP_X_FORWARDED_FOR can contain multiple IPs, get the first one
                $ips = explode(',', $request->server($header));
                $ip = trim($ips[0]);

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        // If no valid IP found in headers, use the default REMOTE_ADDR
        $ip = $request->ip();

        // If we're in local development (127.0.0.1 or ::1), use a fallback IP
        if ($ip == '127.0.0.1' || $ip == '::1') {
            // Option 1: Use a fixed IP for development
            if (app()->environment('local', 'development', 'testing')) {
                // For development, use a fixed IP to simulate a real one
                return env('FALLBACK_IP_ADDRESS', '203.0.113.1'); // Use IP from TEST-NET-3 block
            }

            // Option 2: Try to get the public IP from an external service
            try {
                $publicIp = @file_get_contents('https://api.ipify.org');
                if ($publicIp && filter_var($publicIp, FILTER_VALIDATE_IP)) {
                    return $publicIp;
                }
            } catch (\Exception $e) {
                // If the external service fails, continue with the local IP
            }
        }

        return $ip;
    }
}
