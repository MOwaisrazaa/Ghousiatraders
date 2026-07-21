<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'dial_code',
        'emoji',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get only active countries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order then name
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'desc')->orderBy('name', 'asc');
    }

    /**
     * Get all active countries for dropdown
     */
    public static function getForDropdown()
    {
        return static::active()
            ->ordered()
            ->get(['id', 'name', 'code', 'dial_code', 'emoji']);
    }

    /**
     * Get dial code by country code
     */
    public static function getDialCode($countryCode)
    {
        $country = static::where('code', $countryCode)->first();
        return $country ? $country->dial_code : '+92'; // Default to Pakistan
    }
}
