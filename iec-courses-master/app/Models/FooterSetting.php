<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
        $defaults = [
            'brand_name' => 'Polani',
            'brand_tagline' => 'Fragrance',
            'brand_description' => 'Crafted with passion. Bottled with elegance. Made for moments that matter.',
            'facebook_url' => null,
            'instagram_url' => null,
            'tiktok_url' => null,
            'youtube_url' => null,
            'linkedin_url' => null,
            'address' => 'Dany Craft Tower, 1st Floor, Shop no. F6, M.A Jinnah Road, Karachi',
            'email' => 'polanifragnance@gmail.com',
            'phone' => '+92 324 9206345',
            'copyright_name' => 'Polani Fragrance',
            'copyright_url' => url('/'),
            'footer_text' => 'All rights reserved.',
        ];

        if (!Schema::hasTable('footer_settings')) {
            return new self($defaults);
        }

        return self::firstOrCreate([], $defaults);
    }
}
