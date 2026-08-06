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
     * Scope a query to only include active payment methods.
     * Flexibly matches boolean, numeric, or string representation ('1', 'true', 'active', 'Active', 'ACTIVE', 'enabled', 'yes').
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereRaw("CAST(is_active AS CHAR) IN ('1', 'true', 'active', 'Active', 'ACTIVE', 'enabled', 'yes', 'Yes')");

            if (\Illuminate\Support\Facades\Schema::hasColumn('payment_methods', 'status')) {
                $q->orWhereRaw("CAST(status AS CHAR) IN ('1', 'true', 'active', 'Active', 'ACTIVE', 'enabled', 'yes', 'Yes')");
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('payment_methods', 'enabled')) {
                $q->orWhereRaw("CAST(enabled AS CHAR) IN ('1', 'true', 'active', 'Active', 'ACTIVE', 'enabled', 'yes', 'Yes')");
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('payment_methods', 'active')) {
                $q->orWhereRaw("CAST(active AS CHAR) IN ('1', 'true', 'active', 'Active', 'ACTIVE', 'enabled', 'yes', 'Yes')");
            }
        });
    }

    /**
     * Get active status flexibly as boolean.
     */
    public function getIsActiveAttribute($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int)$value === 1;
        }
        if (is_string($value)) {
            return in_array(strtolower(trim($value)), ['1', 'true', 'active', 'enabled', 'yes'], true);
        }
        return (bool)$value;
    }

    /**
     * Get logo image asset URL if available.
     */
    public function getLogoUrlAttribute()
    {
        if ($this->icon) {
            $icon = trim($this->icon);
            if (\Illuminate\Support\Str::startsWith($icon, ['http://', 'https://'])) {
                return $icon;
            }
            $cleanIcon = ltrim(str_replace('\\', '/', $icon), '/');
            if (file_exists(public_path($cleanIcon))) {
                return asset($cleanIcon);
            }
        }

        $key = strtolower($this->key);
        if ($key === 'cod') $key = 'cash';
        if ($key === 'bank') $key = 'banktransfer';
        if ($key === 'stripe') $key = 'card';

        $candidates = [
            "assets/payment-methods/{$key}.png",
            "assets/payment-methods/{$key}.svg",
            "ghousiatraders/assets/{$key}.png",
            "ghousiatraders/assets/{$key}.svg",
        ];

        foreach ($candidates as $cand) {
            if (file_exists(public_path($cand))) {
                return asset($cand);
            }
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
