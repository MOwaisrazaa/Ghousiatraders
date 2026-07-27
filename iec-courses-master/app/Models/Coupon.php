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
        'description',
        'min_order_amount',
        'max_discount_amount',
        'per_user_limit',
        'selected_products',
        'selected_categories',
        'excluded_products',
        'excluded_categories',
        'new_customers_only',
        'free_shipping'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'new_customers_only' => 'boolean',
        'free_shipping' => 'boolean',
        'selected_products' => 'json',
        'selected_categories' => 'json',
        'excluded_products' => 'json',
        'excluded_categories' => 'json',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2'
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

        // Check if current time is within the valid period
        if ($now->lt($validFrom) || $now->gt($validUntil)) {
            return false;
        }

        return true;
    }

    /**
     * Complete cart/checkout coupon validation logic.
     */
    public function isValidForCart($cartItems, $subtotal, $userId = null)
    {
        if (!$this->isValid()) {
            return ['valid' => false, 'error' => 'This coupon is not active or has expired.'];
        }

        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return ['valid' => false, 'error' => 'Minimum order amount of PKR ' . number_format($this->min_order_amount) . ' is required.'];
        }

        if ($userId && $this->per_user_limit) {
            $usedCount = Order::where('user_id', $userId)
                ->where('coupon_code', $this->code)
                ->where('status', '!=', 'cancelled')
                ->count();
            if ($usedCount >= $this->per_user_limit) {
                return ['valid' => false, 'error' => 'You have reached your usage limit for this coupon.'];
            }
        }

        // Product and category constraints checking
        $cartProductIds = [];
        $cartCategoryIds = [];
        if ($cartItems) {
            foreach ($cartItems as $item) {
                $productId = $item->course_id ?? $item['course_id'] ?? null;
                if ($productId) {
                    $cartProductIds[] = $productId;
                    $p = Course::find($productId);
                    if ($p && $p->category_id) {
                        $cartCategoryIds[] = $p->category_id;
                    }
                }
            }
        }

        $selectedProducts = is_array($this->selected_products) ? $this->selected_products : json_decode($this->selected_products ?? '[]', true);
        $selectedCategories = is_array($this->selected_categories) ? $this->selected_categories : json_decode($this->selected_categories ?? '[]', true);
        $excludedProducts = is_array($this->excluded_products) ? $this->excluded_products : json_decode($this->excluded_products ?? '[]', true);
        $excludedCategories = is_array($this->excluded_categories) ? $this->excluded_categories : json_decode($this->excluded_categories ?? '[]', true);

        // Exclusions check
        if (!empty($excludedProducts)) {
            foreach ($cartProductIds as $pid) {
                if (in_array($pid, $excludedProducts)) {
                    return ['valid' => false, 'error' => 'This coupon cannot be used with some items in your cart.'];
                }
            }
        }

        if (!empty($excludedCategories)) {
            foreach ($cartCategoryIds as $cid) {
                if (in_array($cid, $excludedCategories)) {
                    return ['valid' => false, 'error' => 'This coupon cannot be used with some categories in your cart.'];
                }
            }
        }

        // Inclusions check
        if (!empty($selectedProducts)) {
            $hasAllowedProduct = false;
            foreach ($cartProductIds as $pid) {
                if (in_array($pid, $selectedProducts)) {
                    $hasAllowedProduct = true;
                    break;
                }
            }
            if (!$hasAllowedProduct) {
                return ['valid' => false, 'error' => 'This coupon is only valid for selected products.'];
            }
        }

        if (!empty($selectedCategories)) {
            $hasAllowedCategory = false;
            foreach ($cartCategoryIds as $cid) {
                if (in_array($cid, $selectedCategories)) {
                    $hasAllowedCategory = true;
                    break;
                }
            }
            if (!$hasAllowedCategory) {
                return ['valid' => false, 'error' => 'This coupon is only valid for selected categories.'];
            }
        }

        if ($this->new_customers_only && $userId) {
            $hasPreviousOrders = Order::where('user_id', $userId)
                ->where('status', '!=', 'cancelled')
                ->exists();
            if ($hasPreviousOrders) {
                return ['valid' => false, 'error' => 'This coupon is only valid for new customers on their first purchase.'];
            }
        }

        return ['valid' => true];
    }

    public function calculateDiscount($total)
    {
        if (!$this->isValid()) {
            return 0;
        }

        $discount = 0;
        switch ($this->type) {
            case 'percentage':
                $discount = $total * ($this->value / 100);
                break;
            case 'fixed':
                $discount = min($this->value, $total);
                break;
            case 'free':
                $discount = $total;
                break;
            default:
                $discount = 0;
                break;
        }

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return $discount;
    }

    public function incrementUses()
    {
        $this->increment('uses_count');
    }
}
