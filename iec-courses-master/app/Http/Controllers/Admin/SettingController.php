<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\StoreSettingsService;
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

        // Load all database-backed settings via central StoreSettingsService
        $settings = StoreSettingsService::all();
        $paymentMethods = \App\Models\PaymentMethod::orderBy('sort_order')->get();

        return view('admin.settings.index', compact('tab', 'settings', 'paymentMethods'));
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

            StoreSettingsService::setMultiple($validated);

        } elseif ($tab === 'store_info') {
            $validated = $request->validate([
                'public_store_name' => 'nullable|string|max:255',
                'legal_business_name' => 'nullable|string|max:255',
                'short_store_description' => 'nullable|string',
                'detailed_business_description' => 'nullable|string',
                'support_email' => 'nullable|email|max:255',
                'sales_email' => 'nullable|email|max:255',
                'primary_phone' => 'nullable|string|max:30',
                'secondary_phone' => 'nullable|string|max:30',
                'whatsapp_number' => 'nullable|string|max:30',
                'address_line_1' => 'nullable|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:20',
                'google_maps_url' => 'nullable|url|max:500',
                'google_maps_embed_url' => 'nullable|string',
                'business_hours_mon_sat_open' => 'nullable|string|max:50',
                'business_hours_mon_sat_close' => 'nullable|string|max:50',
                'business_hours_sunday_status' => 'nullable|string|in:open,closed',
                'business_hours_sunday_open' => 'nullable|string|max:50',
                'business_hours_sunday_close' => 'nullable|string|max:50',
                'business_hours_custom_text' => 'nullable|string',
                'store_tagline' => 'nullable|string|max:255',
            ]);

            StoreSettingsService::setMultiple($validated);

        } elseif ($tab === 'header') {
            $validated = $request->validate([
                'topbar_free_shipping_text' => 'nullable|string|max:255',
                'shipping_free_threshold' => 'required|numeric|min:0',
                'topbar_quality_text' => 'nullable|string|max:255',
                'topbar_support_text' => 'nullable|string|max:255',
                'header_support_phone' => 'nullable|string|max:30',
                'track_order_btn_label' => 'nullable|string|max:50',
                'header_search_placeholder' => 'nullable|string|max:255',
                'show_top_info_bar' => 'nullable|in:0,1',
            ]);

            $validated['show_top_info_bar'] = $request->has('show_top_info_bar') ? '1' : '0';
            StoreSettingsService::setMultiple($validated);

        } elseif ($tab === 'footer') {
            $validated = $request->validate([
                'footer_description' => 'nullable|string',
                'footer_phone' => 'nullable|string|max:30',
                'footer_email' => 'nullable|email|max:255',
                'footer_address' => 'nullable|string',
                'footer_business_hours' => 'nullable|string',
                'copyright_name' => 'nullable|string|max:255',
                'copyright_text' => 'nullable|string|max:255',
                'newsletter_heading' => 'nullable|string|max:255',
                'newsletter_description' => 'nullable|string',
                'newsletter_button_label' => 'nullable|string|max:50',
                'show_payment_logos' => 'nullable|in:0,1',
            ]);

            $validated['show_payment_logos'] = $request->has('show_payment_logos') ? '1' : '0';
            StoreSettingsService::setMultiple($validated);

        } elseif ($tab === 'social') {
            $validated = $request->validate([
                'facebook_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'youtube_url' => 'nullable|url|max:255',
                'tiktok_url' => 'nullable|url|max:255',
                'whatsapp_url' => 'nullable|string|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
            ]);

            $toggles = [
                'facebook_enabled' => $request->has('facebook_enabled') ? '1' : '0',
                'instagram_enabled' => $request->has('instagram_enabled') ? '1' : '0',
                'youtube_enabled' => $request->has('youtube_enabled') ? '1' : '0',
                'tiktok_enabled' => $request->has('tiktok_enabled') ? '1' : '0',
                'whatsapp_enabled' => $request->has('whatsapp_enabled') ? '1' : '0',
                'twitter_enabled' => $request->has('twitter_enabled') ? '1' : '0',
                'linkedin_enabled' => $request->has('linkedin_enabled') ? '1' : '0',
            ];

            StoreSettingsService::setMultiple(array_merge($validated, $toggles));

        } elseif ($tab === 'shipping') {
            $validated = $request->validate([
                'shipping_flat_rate' => 'required|numeric|min:0',
                'shipping_free_threshold' => 'required|numeric|min:0',
                'shipping_estimate_days' => 'required|string|max:255',
                'shipping_coverage_text' => 'nullable|string',
                'courier_support_phone' => 'nullable|string|max:30',
            ]);

            StoreSettingsService::setMultiple($validated);

        } elseif ($tab === 'appearance') {
            $validated = $request->validate([
                'theme' => 'required|in:light,dark,system',
                'primary_color' => 'required|string',
            ]);

            StoreSettingsService::setMultiple($validated);

        } elseif ($tab === 'payment_methods') {
            $activeMethods = $request->input('active_methods', []);
            \App\Models\PaymentMethod::query()->update(['is_active' => false]);
            if (!empty($activeMethods)) {
                \App\Models\PaymentMethod::whereIn('id', array_keys($activeMethods))->update(['is_active' => true]);
            }
            return redirect()->to(url('/admin/settings?tab=payment_methods'))
                ->with('success', 'Payment gateway statuses updated successfully.');
        } else {
            $inputs = $request->except(['_token', 'tab']);
            StoreSettingsService::setMultiple($inputs);
        }

        return redirect()->to(url('/admin/settings?tab=' . $tab))
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Upload logo and favicon assets safely.
     */
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'logo_light' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'favicon' => 'nullable|file|mimes:png,ico,svg|max:512',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            StoreSettingsService::set('store_logo', 'storage/' . $path);
        }

        if ($request->hasFile('logo_light')) {
            $file = $request->file('logo_light');
            $path = $file->store('logos', 'public');
            StoreSettingsService::set('store_logo_light', 'storage/' . $path);
        }

        if ($request->hasFile('logo_dark')) {
            $file = $request->file('logo_dark');
            $path = $file->store('logos', 'public');
            StoreSettingsService::set('store_logo_dark', 'storage/' . $path);
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $path = $file->store('favicons', 'public');
            StoreSettingsService::set('store_favicon', 'storage/' . $path);
        }

        if ($request->hasFile('footer_logo')) {
            $file = $request->file('footer_logo');
            $path = $file->store('logos', 'public');
            StoreSettingsService::set('footer_logo', 'storage/' . $path);
        }

        return back()->with('success', 'Store branding assets uploaded and updated successfully.');
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
            $currentStatus = StoreSettingsService::get('two_factor_enabled', false);
            StoreSettingsService::set('two_factor_enabled', !$currentStatus);
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
            StoreSettingsService::set('smtp_password', encrypt($request->smtp_password));
        }

        StoreSettingsService::setMultiple($validated);

        return back()->with('success', 'SMTP settings saved successfully.');
    }
}
