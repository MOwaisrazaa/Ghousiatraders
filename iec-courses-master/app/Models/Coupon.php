<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'uses_count',
        'valid_from',
        'valid_until',
        'is_active',
        'description'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_uses !== null && $this->uses_count >= $this->max_uses) {
            return false;
        }

        $now = now();
        $validFrom = $this->valid_from;
        $validUntil = $this->valid_until;

        // Debug information
        \Log::info('Coupon Validation Check:', [
            'coupon_id' => $this->id,
            'code' => $this->code,
            'now' => $now->format('Y-m-d H:i:s'),
            'valid_from' => $validFrom->format('Y-m-d H:i:s'),
            'valid_until' => $validUntil->format('Y-m-d H:i:s'),
            'is_active' => $this->is_active,
            'uses_count' => $this->uses_count,
            'max_uses' => $this->max_uses
        ]);

        // Check if current time is within the valid period
        if ($now->lt($validFrom) || $now->gt($validUntil)) {
            \Log::info('Coupon is not valid - Date check failed');
            return false;
        }

        return true;
    }

    public function calculateDiscount($total)
    {
        if (!$this->isValid()) {
            return 0;
        }

        switch ($this->type) {
            case 'percentage':
                return $total * ($this->value / 100);
            case 'fixed':
                return min($this->value, $total);
            case 'free':
                return $total;
            default:
                return 0;
        }
    }

    public function incrementUses()
    {
        $this->increment('uses_count');
    }
}
