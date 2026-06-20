<?php

namespace App\Rules;

use App\Models\discountCodes;
use App\Models\orders;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ValidPromoCode implements Rule
{
    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true;
        }

        $promoCode = discountCodes::where('code', $value)
            ->where('is_active', 1)
            ->where('expiry_date', '>', now())
            ->first();

        if (! $promoCode) {
            return false;
        }

        $userId = Auth::id();

        if ($userId) {
            return ! orders::where('user_id', $userId)->exists();
        }

        $email = request()->input('email');
        if ($email) {
            return ! orders::whereHas('guestUser', function ($query) use ($email) {
                $query->where('email', $email);
            })->exists();
        }

        return true;
    }

    public function message()
    {
        return 'The provided promo code is either invalid, expired, inactive, or not valid for your order.';
    }
}
