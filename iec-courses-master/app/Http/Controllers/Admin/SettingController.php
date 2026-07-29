<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display settings page.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'general');

        // Load settings values or set defaults
        $settings = [
            'store_name' => Setting::get('store_name', 'Ghousia Traders'),
            'store_email' => Setting::get('store_email', 'support@ghousiatraders.com'),
            'store_phone' => Setting::get('store_phone', '+92 300 1234567'),
            'store_currency' => Setting::get('store_currency', 'PKR'),
            'store_timezone' => Setting::get('store_timezone', 'Asia/Karachi'),
            'date_format' => Setting::get('date_format', 'F d, Y'),
            'time_format' => Setting::get('time_format', '12h'),
            'items_per_page' => Setting::get('items_per_page', 20),
            
            // Address
            'address_line_1' => Setting::get('address_line_1', '123, Main Boulevard, Gulberg III'),
            'address_line_2' => Setting::get('address_line_2', 'Near Liberty Market'),
            'city' => Setting::get('city', 'Lahore'),
            'state' => Setting::get('state', 'Punjab'),
            'country' => Setting::get('country', 'Pakistan'),
            'postal_code' => Setting::get('postal_code', '54000'),

            // Logo & Favicon paths
            'store_logo' => Setting::get('store_logo', 'assets/logo.png'),
            'store_favicon' => Setting::get('store_favicon', 'assets/favicon.png'),

            // Email/SMTP settings
            'from_name' => Setting::get('from_name', 'Ghousia Traders'),
            'from_email' => Setting::get('from_email', 'no-reply@ghousiatraders.com'),
            'mail_driver' => Setting::get('mail_driver', 'smtp'),
            'smtp_host' => Setting::get('smtp_host', 'smtp.mailtrap.io'),
            'smtp_port' => Setting::get('smtp_port', '2525'),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'smtp_username' => Setting::get('smtp_username', 'user123'),

            // Appearance theme
            'theme' => Setting::get('theme', 'light'),
            'primary_color' => Setting::get('primary_color', 'brown'),

            // 2FA status
            'two_factor_enabled' => Setting::get('two_factor_enabled', false),
        ];

        return view('admin.settings.index', compact('tab', 'settings'));
    }

    /**
     * Update generic configuration values.
     */
    public function update(Request $request)
    {
        $tab = $request->input('tab', 'general');

        if ($tab === 'general') {
            $validated = $request->validate([
                'store_name' => 'required|string|max:255',
                'store_email' => 'required|email|max:255',
                'store_phone' => 'required|string|max:30',
                'store_currency' => 'required|string',
                'store_timezone' => 'required|string',
                'date_format' => 'required|string',
                'time_format' => 'required|string',
                'items_per_page' => 'required|integer|min:1',
            ]);

            foreach ($validated as $key => $val) {
                Setting::set($key, $val);
            }

            // Sync with footer_settings for storefront footer/contact details
            $footer = \App\Models\FooterSetting::getSettings();
            $footer->update([
                'email' => $validated['store_email'],
                'phone' => $validated['store_phone'],
                'brand_name' => $validated['store_name'],
            ]);
        } elseif ($tab === 'address') {
            $validated = $request->validate([
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
            ]);

            foreach ($validated as $key => $val) {
                Setting::set($key, $val ?? '');
            }

            // Sync combined address with footer_settings
            $fullAddress = $validated['address_line_1'] . 
                           ($validated['address_line_2'] ? ', ' . $validated['address_line_2'] : '') . 
                           ', ' . $validated['city'] . 
                           ', ' . $validated['state'] . 
                           ', ' . $validated['country'] . 
                           ' - ' . $validated['postal_code'];
            
            $footer = \App\Models\FooterSetting::getSettings();
            $footer->update([
                'address' => $fullAddress,
            ]);
        } elseif ($tab === 'appearance') {
            $validated = $request->validate([
                'theme' => 'required|in:light,dark,system',
                'primary_color' => 'required|string',
            ]);

            foreach ($validated as $key => $val) {
                Setting::set($key, $val);
            }
        } else {
            // General support for other dynamic tabs (store_info, shipping, tax, notifications, API, Backup)
            $inputs = $request->except(['_token', 'tab']);
            foreach ($inputs as $key => $val) {
                Setting::set($key, $val);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Upload logo and favicon.
     */
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'favicon' => 'nullable|file|mimes:png,ico|max:512',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            Setting::set('store_logo', 'storage/' . $path);
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $path = $file->store('favicons', 'public');
            Setting::set('store_favicon', 'storage/' . $path);
        }

        return back()->with('success', 'Profile assets updated successfully.');
    }

    /**
     * Update security preferences.
     */
    public function security(Request $request)
    {
        $action = $request->input('security_action');
        $admin = auth()->user();

        if ($action === 'email') {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $admin->id,
                'password' => 'required',
            ]);

            if (!Hash::check($request->password, $admin->password)) {
                return back()->withErrors(['password' => 'Incorrect current password.']);
            }

            $admin->update(['email' => $request->email]);
            return back()->with('success', 'Admin email changed successfully.');

        } elseif ($action === 'password') {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|different:current_password',
                'confirm_password' => 'required|same:new_password',
            ]);

            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Incorrect current password.']);
            }

            $admin->update(['password' => Hash::make($request->new_password)]);
            return back()->with('success', 'Password updated successfully.');

        } elseif ($action === 'toggle_2fa') {
            $currentStatus = Setting::get('two_factor_enabled', false);
            Setting::set('two_factor_enabled', !$currentStatus);
            return back()->with('success', 'Two-factor Authentication status updated.');
        }

        return back()->with('error', 'Invalid security action.');
    }

    /**
     * Configure SMTP.
     */
    public function smtp(Request $request)
    {
        if ($request->has('send_test')) {
            $request->validate([
                'test_email' => 'required|email',
            ]);

            try {
                // Try sending a raw test email
                $testEmail = $request->test_email;
                Mail::raw('This is a test email from Ghousia Traders settings SMTP test configuration.', function($message) use ($testEmail) {
                    $message->to($testEmail)->subject('SMTP Mail Test');
                });
                return back()->with('success', 'Test email sent successfully to ' . $testEmail);
            } catch (\Exception $e) {
                return back()->with('error', 'Test email failed to send: ' . $e->getMessage());
            }
        }

        $validated = $request->validate([
            'from_name' => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'mail_driver' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|string',
            'smtp_encryption' => 'required|string',
            'smtp_username' => 'required|string',
        ]);

        if ($request->filled('smtp_password')) {
            // Securely encrypt and save the SMTP password
            Setting::set('smtp_password', encrypt($request->smtp_password));
        }

        foreach ($validated as $key => $val) {
            Setting::set($key, $val);
        }

        return back()->with('success', 'SMTP settings saved successfully.');
    }
}
