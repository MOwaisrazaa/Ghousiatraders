<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\FooterSetting;
use Illuminate\Support\Facades\Cache;

class StoreSettingsService
{
    protected static ?array $cachedSettings = null;

    /**
     * Central default values for all Ghousia Traders store settings.
     */
    public static function defaults(): array
    {
        return [
            // General
            'store_name' => 'Ghousia Traders',
            'store_email' => 'info@ghousiatraders.com',
            'store_phone' => '0321-1234567',
            'store_currency' => 'PKR',
            'store_timezone' => 'Asia/Karachi',
            'date_format' => 'F d, Y',
            'time_format' => '12h',
            'items_per_page' => '20',

            // Store Information - Business Details
            'public_store_name' => 'Ghousia Traders',
            'legal_business_name' => 'Ghousia Traders',
            'short_store_description' => 'Your trusted destination for premium baby care products and exciting ride-on toys.',
            'detailed_business_description' => 'Ghousia Traders provides high-quality baby care items, ride-on bikes, and toy cars across Pakistan. Quality you can trust, happiness they deserve.',
            'support_email' => '',
            'sales_email' => 'sales@ghousiatraders.com',
            'primary_phone' => '0321-1234567',
            'secondary_phone' => '0322-9876543',
            'whatsapp_number' => '0321-1234567',

            // Address
            'address_line_1' => 'Shop # 12, Main Market',
            'address_line_2' => 'DHA Phase 6',
            'city' => 'Lahore',
            'state' => 'Punjab',
            'country' => 'Pakistan',
            'postal_code' => '54000',
            'google_maps_url' => 'https://maps.google.com',
            'google_maps_embed_url' => '',

            // Business Hours
            'business_hours_mon_sat_open' => '10:00 AM',
            'business_hours_mon_sat_close' => '08:00 PM',
            'business_hours_sunday_status' => 'closed',
            'business_hours_sunday_open' => '11:00 AM',
            'business_hours_sunday_close' => '06:00 PM',
            'business_hours_custom_text' => "Monday - Saturday: 10:00 AM - 8:00 PM\nSunday: Closed",

            // Store Branding
            'store_logo' => 'ghousiatraders/assets/logo.png',
            'store_logo_light' => '',
            'store_logo_dark' => '',
            'store_favicon' => 'ghousiatraders/assets/favicon.png',
            'footer_logo' => '',
            'store_tagline' => 'Quality You Can Trust',
            'store_website_url' => 'www.ghousiatraders.com',
            'invoice_terms' => "Prices include applicable taxes where relevant.\nThis is a computer-generated invoice.\nNo signature is required.\nFor any queries, contact our support team.",
            'authorized_signature' => '',

            // Header Settings
            'topbar_free_shipping_text' => 'Free Shipping on Orders Over PKR 5,000',
            'topbar_quality_text' => '100% Genuine & Premium Quality',
            'topbar_support_text' => '',
            'header_support_phone' => '0321-1234567',
            'track_order_btn_label' => 'Track Order',
            'header_search_placeholder' => 'Search baby care products, ride-on bikes, toy cars...',
            'show_top_info_bar' => '1',

            // Footer Settings
            'footer_description' => 'Your trusted destination for premium baby care products and exciting ride-on toys. Quality you can trust, happiness they deserve.',
            'footer_phone' => '',
            'footer_email' => '',
            'footer_address' => '',
            'footer_business_hours' => '',
            'copyright_text' => 'All Rights Reserved.',
            'copyright_name' => 'Ghousia Traders',
            'newsletter_heading' => 'Stay Updated with Ghousia Traders',
            'newsletter_description' => 'Subscribe to our newsletter for exclusive offers, new arrivals, and parenting tips.',
            'newsletter_button_label' => 'Subscribe',
            'show_payment_logos' => '1',

            // Social Media
            'facebook_url' => 'https://facebook.com/ghousiatraders',
            'facebook_enabled' => '1',
            'instagram_url' => 'https://instagram.com/ghousiatraders',
            'instagram_enabled' => '1',
            'youtube_url' => 'https://youtube.com/ghousiatraders',
            'youtube_enabled' => '1',
            'tiktok_url' => 'https://tiktok.com/@ghousiatraders',
            'tiktok_enabled' => '1',
            'whatsapp_url' => 'https://wa.me/923211234567',
            'whatsapp_enabled' => '1',
            'twitter_url' => '',
            'twitter_enabled' => '0',
            'linkedin_url' => '',
            'linkedin_enabled' => '0',

            // Shipping Information
            'shipping_flat_rate' => '250',
            'shipping_free_threshold' => '5000',
            'shipping_estimate_days' => '3-5 Working Days',
            'shipping_coverage_text' => 'We deliver across all major cities and regions in Pakistan.',
            'courier_support_phone' => '0321-1234567',

            // Tax & Appearance
            'tax_rate_percent' => '0',
            'tax_pricing_mode' => 'exclusive',
            'theme' => 'light',
            'primary_color' => 'brown',
        ];
    }

    /**
     * Normalize multiline string values by converting literal entity representations
     * (e.g. &#13;&#10;, &#13;, &#10;, &amp;#13;&amp;#10;) and Windows/Mac line endings (\r\n, \r)
     * into standard Unix line breaks (\n).
     */
    public static function normalizeValue($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        // 1. Unescape any literal or double-encoded HTML entities for line breaks (&#13;, &#10;, &#13;&#10;, &amp;#13;&amp;#10;)
        $value = preg_replace('/&(?:amp;)?#(?:13|0*13|x0*d);?/i', '', $value);
        $value = preg_replace('/&(?:amp;)?#(?:10|0*10|x0*a);?/i', "\n", $value);

        // 2. Normalize Windows (\r\n) and Mac (\r) line endings to standard Unix \n
        $value = str_replace(["\r\n", "\r"], "\n", $value);

        return $value;
    }

    /**
     * Fetch all settings using a single cached query per request.
     */
    public static function all(): array
    {
        if (self::$cachedSettings !== null) {
            return self::$cachedSettings;
        }

        self::$cachedSettings = Cache::remember('store_settings_cache_v3', 3600, function () {
            $defaults = self::defaults();
            try {
                $dbSettings = Setting::all()->pluck('value', 'key')->toArray();
                foreach ($dbSettings as $k => $v) {
                    $decoded = json_decode($v, true);
                    $val = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $v;
                    $dbSettings[$k] = is_string($val) ? self::normalizeValue($val) : $val;
                }
                $merged = array_merge($defaults, $dbSettings);
                foreach ($merged as $mk => $mv) {
                    if (is_string($mv)) {
                        $merged[$mk] = self::normalizeValue($mv);
                    }
                }
                return $merged;
            } catch (\Exception $e) {
                return $defaults;
            }
        });

        return self::$cachedSettings;
    }

    /**
     * Retrieve a setting value by key with safe fallback.
     */
    public static function get(string $key, $default = null)
    {
        $all = self::all();
        if (array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '') {
            return is_string($all[$key]) ? self::normalizeValue($all[$key]) : $all[$key];
        }

        if ($default !== null) {
            return is_string($default) ? self::normalizeValue($default) : $default;
        }

        $defaults = self::defaults();
        $fallback = $defaults[$key] ?? null;
        return is_string($fallback) ? self::normalizeValue($fallback) : $fallback;
    }

    /**
     * Persist a setting value in database and flush cache.
     */
    public static function set(string $key, $value): void
    {
        $normalized = is_string($value) ? self::normalizeValue($value) : $value;
        Setting::set($key, $normalized);
        self::clearCache();
        self::syncFooterSetting();
    }

    /**
     * Persist multiple setting key-values in database and flush cache.
     */
    public static function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $normalized = is_string($value) ? self::normalizeValue($value) : $value;
            Setting::set($key, $normalized);
        }
        self::clearCache();
        self::syncFooterSetting();
    }

    /**
     * Clear and refresh the settings cache.
     */
    public static function clearCache(): void
    {
        self::$cachedSettings = null;
        Cache::forget('store_settings_cache_v3');
    }

    /**
     * Get single formatted store address string.
     */
    public static function getFormattedAddress(): string
    {
        $addrParts = array_filter([
            self::get('address_line_1'),
            self::get('address_line_2'),
            self::get('city'),
            self::get('state'),
            self::get('country'),
        ]);
        return self::get('footer_address') ?: (implode(', ', $addrParts) ?: 'Shop # 12, Main Market, DHA Phase 6, Lahore, Punjab, Pakistan');
    }

    /**
     * Synchronize legacy FooterSetting model table for full backward compatibility.
     */
    public static function syncFooterSetting(): void
    {
        try {
            $footer = FooterSetting::firstOrCreate([]);
            $phone = self::get('footer_phone') ?: (self::get('primary_phone') ?: self::get('store_phone', '0321-1234567'));
            $email = self::get('footer_email') ?: (self::get('support_email') ?: self::get('store_email', 'info@ghousiatraders.com'));
            
            $addrParts = array_filter([
                self::get('address_line_1'),
                self::get('address_line_2'),
                self::get('city'),
                self::get('state'),
                self::get('country')
            ]);
            $addr = self::get('footer_address') ?: implode(', ', $addrParts);

            $footer->update([
                'brand_name' => self::get('public_store_name', self::get('store_name', 'Ghousia Traders')),
                'brand_tagline' => self::get('store_tagline', 'Quality you can trust, happiness they deserve.'),
                'brand_description' => self::get('footer_description', self::get('short_store_description')),
                'facebook_url' => (self::get('facebook_enabled') == '1' && self::get('facebook_url')) ? self::get('facebook_url') : null,
                'instagram_url' => (self::get('instagram_enabled') == '1' && self::get('instagram_url')) ? self::get('instagram_url') : null,
                'tiktok_url' => (self::get('tiktok_enabled') == '1' && self::get('tiktok_url')) ? self::get('tiktok_url') : null,
                'youtube_url' => (self::get('youtube_enabled') == '1' && self::get('youtube_url')) ? self::get('youtube_url') : null,
                'linkedin_url' => (self::get('linkedin_enabled') == '1' && self::get('linkedin_url')) ? self::get('linkedin_url') : null,
                'address' => $addr,
                'email' => $email,
                'phone' => $phone,
                'copyright_name' => self::get('copyright_name', 'Ghousia Traders'),
                'footer_text' => self::get('copyright_text', 'All Rights Reserved.'),
            ]);
        } catch (\Exception $e) {
            // Ignore during setup
        }
    }
}
