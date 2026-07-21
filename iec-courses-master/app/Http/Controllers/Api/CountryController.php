<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    /**
     * Get all active countries for dropdown
     */
    public function index(): JsonResponse
    {
        $countries = Country::getForDropdown();
        
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * Get dial code for a specific country
     */
    public function getDialCode(string $code): JsonResponse
    {
        $country = Country::where('code', strtoupper($code))->first();
        
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Country not found',
                'dial_code' => '+92' // Default to Pakistan
            ]);
        }

        return response()->json([
            'success' => true,
            'dial_code' => $country->dial_code,
            'country' => $country
        ]);
    }
}
