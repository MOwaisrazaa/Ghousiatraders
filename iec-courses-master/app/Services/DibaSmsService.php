<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class DibaSmsService
{
    protected string $apiUrl;
    protected string $secretKey;
    protected string $username;

    public function __construct()
    {
        // Use production credentials if in production environment, otherwise use development credentials
        $configKey = app()->isProduction() ? 'services.production_sms' : 'services.diba_sms';

        $this->apiUrl = config($configKey . '.api_url', 'https://smsg.dibaadm.com/api/broadcast/message');
        $this->secretKey = config($configKey . '.secret_key', 'A5bTa8UUFIZUa6Ym0F84sH7RPrxnW0eo3TUZVY8xnoAhDMBU7PtM1NWVAgyU');
        $this->username = config($configKey . '.username', 'IEC_Course_test');

        Log::info('DibaSmsService initialized', [
            'environment' => app()->environment(),
            'config' => $configKey,
            'username' => $this->username
        ]);
    }

    /**
     * Send SMS to a mobile number
     *
     * @param string $mobile Mobile number
     * @param string $message Message content
     * @param string $countryCode Country code (default: 92)
     * @return array Response from the SMS service
     */
    public function sendSms(string $mobile, string $message, string $countryCode = '92'): array
    {
        try {
            // Log the request parameters for debugging
            Log::info('Sending SMS with parameters', [
                'mobile' => $mobile,
                'message' => $message,
                'countryCode' => $countryCode,
                'apiUrl' => $this->apiUrl,
                'username' => $this->username
            ]);

            // Format the mobile number to include country code
            // Ensure we don't duplicate the country code if it's already included
            if (strpos($mobile, $countryCode) !== 0) {
                $formattedMobile = $countryCode . $mobile;
            } else {
                $formattedMobile = $mobile;
            }

            Log::info('Formatted mobile number', [
                'original' => $mobile,
                'formatted' => $formattedMobile
            ]);

            $params = [
                'secret_token' => $this->secretKey,
                'country_code' => $countryCode,
                'mobile' => $formattedMobile, // Use full number format like 923452692785
                'message' => $message,
            ];

            $response = Http::post($this->apiUrl, $params);
            
            // Log the raw response for debugging
            Log::info('SMS service response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Try to parse JSON response if possible
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                $result = ['raw_response' => $response->body()];
                Log::warning('Failed to parse JSON response from SMS service', [
                    'body' => $response->body(),
                    'error' => $e->getMessage()
                ]);
            }
            
            if (!$response->successful()) {
                Log::error('Diba SMS sending failed', [
                    'response' => $result,
                    'status' => $response->status(),
                    'mobile' => $mobile,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to send SMS - HTTP Status: ' . $response->status(),
                    'data' => $result,
                ];
            }
            
            return [
                'success' => true,
                'message' => 'SMS sent successfully',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in Diba SMS service', [
                'message' => $e->getMessage(),
                'mobile' => $mobile,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Change the secret key
     *
     * @param string $newSecretToken New secret token
     * @return array Response from the SMS service
     */
    public function changeSecretKey(string $newSecretToken): array
    {
        try {
            // Log the request for debugging
            Log::info('Changing SMS secret key', [
                'username' => $this->username,
                'new_token' => substr($newSecretToken, 0, 5) . '...' // Log a part of the token for security
            ]);

            $params = [
                'username' => $this->username,
                'secret_token' => $newSecretToken,
            ];

            $secretChangeUrl = 'https://smsg.dibaadm.com/api/modernized-secret';
            
            Log::info('Making request to change secret key', [
                'url' => $secretChangeUrl,
                'username' => $this->username
            ]);

            $response = Http::post($secretChangeUrl, $params);
            
            // Log the raw response for debugging
            Log::info('Secret key change response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Try to parse JSON response if possible
            try {
                $result = $response->json();
            } catch (\Exception $e) {
                $result = ['raw_response' => $response->body()];
                Log::warning('Failed to parse JSON response from SMS service', [
                    'body' => $response->body(),
                    'error' => $e->getMessage()
                ]);
            }
            
            if (!$response->successful()) {
                Log::error('Failed to change Diba SMS secret key', [
                    'response' => $result,
                    'status' => $response->status(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to change secret key - HTTP Status: ' . $response->status(),
                    'data' => $result,
                ];
            }
            
            // If successful, update the secret key in the service
            if ($response->successful()) {
                // This is just for the current request - for persistence, you'd need to update config or env
                $this->secretKey = $newSecretToken;
                Log::info('Secret key updated in service');
            }
            
            return [
                'success' => true,
                'message' => 'Secret key changed successfully',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in changing Diba SMS secret key', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
} 