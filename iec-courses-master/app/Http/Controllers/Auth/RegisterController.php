<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\DeviceDetectionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
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
        return view('auth.signup');
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

        // Load all valid country codes from JSON
        $jsonPath = public_path('assets/js/countrycode.json');
        $countriesJson = json_decode(file_get_contents($jsonPath), true);
        $validCountryCodes = array_column($countriesJson, 'code');
        $validCountries = implode(',', $validCountryCodes);

        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:100', 'regex:/^[A-Za-z\s\.\-]+$/'],
            'email' => ['required', 'email:rfc,dns', 'max:100', 'unique:users'],
            'password' => [
                'required',
                'min:6',
                'max:15',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#+=~])[A-Za-z\d@$!%*?&#+=~\.]{6,15}$/',
                'confirmed'
            ],
            'country' => ['required', 'in:' . $validCountries],
            'phone' => ['required', 'string', 'unique:users'],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Name is required',
            'name.regex' => 'Name may only contain letters, spaces, dots and hyphens',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
            'country.required' => 'Country is required',
            'country.in' => 'Invalid country selection',
            'phone.required' => 'Phone number is required',
            'terms.accepted' => 'You must accept the terms and conditions'
        ]);

        // Build country code mapping from JSON
        $countryCodes = [];
        $countryNames = [];
        foreach ($countriesJson as $country) {
            $dialCode = str_replace('+', '', $country['dial_code']);
            $countryCodes[$country['code']] = $dialCode;
            $countryNames[$country['code']] = $country['name'];
        }

        // Normalize phone number based on country
        $country = $request->country;
        $phoneInput = preg_replace('/[^0-9+]/', '', $request->phone);
        
        if (!isset($countryCodes[$country])) {
            return back()->withErrors(['country' => 'Invalid country selected.'])->withInput();
        }

        $countryCode = $countryCodes[$country];
        
        // Validate phone number format
        if (empty($phoneInput) || strlen($phoneInput) < 7) {
            return back()->withErrors(['phone' => 'Please enter a valid phone number.'])->withInput();
        }

        if ($country === 'PK') {
            // Special handling for Pakistan - convert local format to international
            if (str_starts_with($phoneInput, '0')) {
                // Convert 03152771063 to 923152771063
                $sanitizedPhone = '92' . substr($phoneInput, 1);
            } elseif (str_starts_with($phoneInput, '+92')) {
                // Already in +92 format
                $sanitizedPhone = substr($phoneInput, 1); // Remove + for storage
            } elseif (str_starts_with($phoneInput, '92')) {
                // Already in 92 format
                $sanitizedPhone = $phoneInput;
            } else {
                // User entered just the local number (e.g., 3152771063), prepend 92
                $sanitizedPhone = '92' . $phoneInput;
            }
        } else {
            // For other countries, prepend country code if not present
            if (str_starts_with($phoneInput, '+')) {
                $sanitizedPhone = substr($phoneInput, 1); // Remove + for storage
            } elseif (str_starts_with($phoneInput, $countryCode)) {
                $sanitizedPhone = $phoneInput;
            } else {
                // Prepend country code
                $sanitizedPhone = $countryCode . $phoneInput;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $sanitizedPhone
        ]);

        // Assign the "User" role to the newly registered user
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }
        
        // Generate device identifier - only for regular users, not admins
        $deviceId = $this->deviceDetectionService->generateDeviceId($request);
        $deviceInfo = $this->deviceDetectionService->getDeviceInfo();

        // Store or update device information
        UserDevice::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'user_id' => $user->id,
                'device_name' => $deviceInfo['device_name'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'ip_address' => $this->deviceDetectionService->getIpAddress($request),
                'last_login_at' => now(),
                'is_primary' => true
            ]
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
    
    /**
     * Check if phone number is from Pakistan
     *
     * @param string|null $phone
     * @return bool
     */
    private function isFromPakistan(?string $phone): bool
    {
        // Return false if phone is null or empty
        if (empty($phone)) {
            return false;
        }

        // Remove + if it exists
        $cleanPhone = $phone;
        if (str_starts_with($cleanPhone, '+')) {
            $cleanPhone = substr($cleanPhone, 1);
        }

        // Check if it starts with 92 (Pakistan country code)
        return str_starts_with($cleanPhone, '92');
    }
}
