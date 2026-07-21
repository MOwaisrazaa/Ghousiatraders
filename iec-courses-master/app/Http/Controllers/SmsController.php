<?php

namespace App\Http\Controllers;

use App\Services\DibaSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class SmsController extends Controller
{
    protected DibaSmsService $dibaSmsService;

    public function __construct(DibaSmsService $dibaSmsService)
    {
        $this->dibaSmsService = $dibaSmsService;
    }

    /**
     * Test SMS service with a form
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function sendTestSms()
    {
        return view('sms.test');
    }

    /**
     * Send an SMS message using API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'message' => 'required|string',
            'country_code' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get parameters from request
        $mobile = $request->input('mobile');
        $message = $request->input('message');
        $countryCode = $request->input('country_code', '92');

        // Get configuration values
        $apiUrl = Config::get('services.diba_sms.api_url');
        $secretKey = Config::get('services.diba_sms.secret_key');

        // Format the mobile number exactly like in -diba route
        $formattedMobile = $countryCode . $mobile;

        // Log the request details
        Log::info('Sending SMS via API', [
            'mobile' => $formattedMobile,
            'message_length' => strlen($message)
        ]);

        try {
            // Make the request exactly like -diba route
            $response = Http::post($apiUrl, [
                'secret_token' => $secretKey,
                'country_code' => $countryCode,
                'mobile' => $formattedMobile,
                'message' => $message,
            ]);

            // Return the response in the exact same format as -diba
            return response()->json([
                'sent_to' => $formattedMobile,
                'status' => $response->status(),
                'response_body' => $response->json(),
            ]);
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'error' => $e->getMessage(),
                'mobile' => $formattedMobile
            ]);

            return response()->json([
                'sent_to' => $formattedMobile,
                'status' => 500,
                'response_body' => ['error' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Change the secret key for the SMS service
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeSecretKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_secret_token' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $newSecretToken = $request->input('new_secret_token');
        
        // Log the request for debugging
        Log::info('Change secret key request received', [
            'token_length' => strlen($newSecretToken),
        ]);

        $result = $this->dibaSmsService->changeSecretKey($newSecretToken);

        if ($result['success']) {
            // If successful, you might want to update the config for future requests
            // This requires more setup to persist the change to .env or database
            Log::info('Secret key changed successfully');
        }

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
