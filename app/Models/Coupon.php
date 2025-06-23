<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\User;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value_type', 'discount_value',
        'max_discount_amount', 'min_order_amount',
        'applicable_product_ids', 'applicable_category_ids',
        'only_for_new_users', 'is_exclusive', 'usage_limit', 'used_count',
        'per_user_limit', 'eligible_user_roles', 'start_date', 'end_date', 'active'
    ];

    protected $casts = [
        'applicable_product_ids' => 'array',
        'applicable_category_ids' => 'array',
        'eligible_user_roles' => 'array',
        'only_for_new_users' => 'boolean',
        'is_exclusive' => 'boolean',
        'active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isValidFor(User $user, float $orderAmount): bool
    {
        if (!$this->active) return false;

        if ($this->start_date && now()->lt($this->start_date)) return false;
        if ($this->end_date && now()->gt($this->end_date)) return false;
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) return false;

        if ($this->only_for_new_users && !$user->is_new) return false;

        if ($this->eligible_user_roles && !in_array($user->role, $this->eligible_user_roles)) return false;

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        // Check user-specific usage count
        $userUsage = $user->couponUsages()->where('coupon_id', $this->id)->count();
        if ($this->per_user_limit && $userUsage >= $this->per_user_limit) return false;

        return true;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($this->value_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            return min($discount, $this->max_discount_amount ?? $discount);
        }

        return min($this->discount_value, $orderAmount);
    }
}
