<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'description',
        'icon',
        'instructions',
        'is_active',
        'sort_order',
        'details',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'details' => 'array',
    ];

    /**
     * Get logo image asset URL if available.
     */
    public function getLogoUrlAttribute()
    {
        $key = $this->key;
        if ($key === 'cod') $key = 'cash';
        if ($key === 'bank') $key = 'banktransfer';
        if ($key === 'stripe') $key = 'card';

        $pngPath = public_path("assets/payment-methods/{$key}.png");
        if (file_exists($pngPath)) {
            return asset("assets/payment-methods/{$key}.png");
        }

        $svgPath = public_path("assets/payment-methods/{$key}.svg");
        if (file_exists($svgPath)) {
            return asset("assets/payment-methods/{$key}.svg");
        }

        return null;
    }

    /**
     * Get detail value by key safely.
     */
    public function getDetail($key, $default = null)
    {
        return $this->details[$key] ?? $default;
    }
}
