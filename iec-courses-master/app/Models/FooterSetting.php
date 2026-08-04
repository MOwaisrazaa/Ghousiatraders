<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\StoreSettingsService;

class FooterSetting extends Model
{
    protected $fillable = [
        'brand_name',
        'brand_tagline',
        'brand_description',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
        'linkedin_url',
        'address',
        'email',
        'phone',
        'copyright_name',
        'copyright_url',
        'footer_text',
    ];

    public static function getSettings()
    {
        $phone = store_setting('footer_phone') ?: store_setting('primary_phone', store_setting('store_phone', '0321-1234567'));
        $email = store_setting('footer_email') ?: store_setting('support_email', store_setting('store_email', 'info@ghousiatraders.com'));
        $address = store_setting('footer_address') ?: store_setting('address_line_1', 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan');

        return new self([
            'brand_name' => store_setting('public_store_name', store_setting('store_name', 'Ghousia Traders')),
            'brand_tagline' => store_setting('store_tagline', 'Quality you can trust, happiness they deserve.'),
            'brand_description' => store_setting('footer_description', store_setting('short_store_description')),
            'facebook_url' => store_setting('facebook_enabled', '1') == '1' ? store_setting('facebook_url') : null,
            'instagram_url' => store_setting('instagram_enabled', '1') == '1' ? store_setting('instagram_url') : null,
            'tiktok_url' => store_setting('tiktok_enabled', '1') == '1' ? store_setting('tiktok_url') : null,
            'youtube_url' => store_setting('youtube_enabled', '1') == '1' ? store_setting('youtube_url') : null,
            'linkedin_url' => store_setting('linkedin_enabled', '0') == '1' ? store_setting('linkedin_url') : null,
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
            'copyright_name' => store_setting('copyright_name', 'Ghousia Traders'),
            'copyright_url' => url('/'),
            'footer_text' => store_setting('copyright_text', 'All Rights Reserved.'),
        ]);
    }
}
