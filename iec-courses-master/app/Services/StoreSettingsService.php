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
            'legal_business_name' => 'Ghousia Traders Private Ltd',
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
            'store_tagline' => 'Quality you can trust, happiness they deserve.',

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
                    $dbSettings[$k] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $v;
                }
                return array_merge($defaults, $dbSettings);
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
            return $all[$key];
        }

        if ($default !== null) {
            return $default;
        }

        $defaults = self::defaults();
        return $defaults[$key] ?? null;
    }

    /**
     * Persist a setting value in database and flush cache.
     */
    public static function set(string $key, $value): void
    {
        Setting::set($key, $value);
        self::clearCache();
        self::syncFooterSetting();
    }

    /**
     * Persist multiple setting key-values in database and flush cache.
     */
    public static function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
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
