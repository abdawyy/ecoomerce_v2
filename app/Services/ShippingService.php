<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\orders;
use Illuminate\Support\Facades\Schema;

class ShippingService
{
    public function threshold(): float
    {
        if (! Schema::hasTable('site_settings') || ! Schema::hasColumn('site_settings', 'free_shipping_min_amount')) {
            return 0.0;
        }

        $amount = SiteSetting::current()->free_shipping_min_amount ?? 0;

        return $this->money((float) $amount);
    }

    public function isEnabled(): bool
    {
        return $this->threshold() > 0;
    }

    public function merchandiseAfterDiscount(float $subtotal, float $discountPercent): float
    {
        $subtotal = $this->money($subtotal);
        $discount = $this->money($subtotal * ((float) $discountPercent / 100));

        return $this->money($subtotal - $discount);
    }

    public function qualifies(float $merchandiseAfterDiscount): bool
    {
        $threshold = $this->threshold();

        return $threshold > 0 && $this->money($merchandiseAfterDiscount) >= $threshold;
    }

    public function resolveFee(float $cityPrice, float $merchandiseAfterDiscount): float
    {
        if ($this->qualifies($merchandiseAfterDiscount)) {
            return 0.0;
        }

        return $this->money($cityPrice);
    }

    public function remainingUntilFree(float $merchandiseAfterDiscount): float
    {
        $threshold = $this->threshold();
        if ($threshold <= 0) {
            return 0.0;
        }

        $remaining = $this->money($threshold - $this->money($merchandiseAfterDiscount));

        return $remaining > 0 ? $remaining : 0.0;
    }

    public function orderDeliveryFee(orders $order): float
    {
        $attributes = $order->getAttributes();
        if (array_key_exists('delivery_fee', $attributes) && $attributes['delivery_fee'] !== null) {
            return $this->money((float) $attributes['delivery_fee']);
        }

        return $this->money((float) ($order->cities->price ?? 0));
    }

    public function money(float $amount): float
    {
        return round($amount, 2);
    }
}
