<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get setting value by key.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            // Check if the value is JSON, decode if so
            $decoded = json_decode($setting->value, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $setting->value;
        }
        return $default;
    }

    /**
     * Set setting value by key.
     */
    public static function set($key, $value)
    {
        $valStr = is_array($value) || is_object($value) ? json_encode($value) : $value;
        self::updateOrCreate(['key' => $key], ['value' => $valStr]);
    }
}
