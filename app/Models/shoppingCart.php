<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;
use App\Services\ShippingService;

class shoppingCart extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
        'user_id',
        'products_id',
        'size_id',
        'quantity',




    ];
    protected $table = 'shopping_cart';
    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(products::class, 'products_id');
    }
    public function productItems()
    {
        return $this->belongsTo(productItems::class, 'size_id');
    }

    // Calculate the total price for a specific user
    public function totalPrice($userId = null, $cityPrice, $promoCode = 0)
    {
        // Step 1: Retrieve the cart items (guest or authenticated user)
        $cartItems = $this->getCartItems($userId);

        // Step 2: Calculate the subtotal
        $subtotal = $this->calculateSubtotal($cartItems);

        $shipping = app(ShippingService::class);
        $afterDiscount = $shipping->merchandiseAfterDiscount((float) $subtotal, (float) $promoCode);
        $fee = $shipping->resolveFee((float) $cityPrice, $afterDiscount);

        return $shipping->money($afterDiscount + $fee);
    }

    public function merchandiseTotal($userId = null, $promoCode = 0): float
    {
        $shipping = app(ShippingService::class);
        $subtotal = $this->subtotal($userId);

        return $shipping->merchandiseAfterDiscount($subtotal, (float) $promoCode);
    }

    public function subtotal($userId = null): float
    {
        return app(ShippingService::class)->money((float) $this->calculateSubtotal($this->getCartItems($userId)));
    }

    // Get cart items for either a user or guest
    protected function getCartItems($userId)
    {
        if ($userId) {
            return $this->with(['product.category', 'product.type'])->where('user_id', $userId)->get();
        }

        return self::getGuestCartItems();
    }

    // Calculate subtotal from cart items
    protected function calculateSubtotal($items)
    {
        $total = 0;

        foreach ($items as $item) {
            $product = is_object($item) && isset($item->product) && is_object($item->product)
                ? $item->product
                : products::query()->with(['category', 'type'])->find(is_array($item) ? ($item['product_id'] ?? null) : ($item->products_id ?? $item->product_id ?? null));

            if (! $product || ! $product->isSellable()) {
                continue;
            }

            $quantity = (int) (is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0));
            if ($quantity < 1) {
                continue;
            }

            $total += $product->discountedUnitPrice() * $quantity;
        }

        return $total;
    }

    // Apply promo discount
    protected function applyPromoDiscount($amount, $promoPercent)
    {
        return $amount - ($amount * $promoPercent / 100);
    }


    public static function getCartItemsByUserId($userId)
    {
        try {
            // Retrieve all cart items for the given user ID
            $cartItems = self::where('user_id', $userId)->with(['product.category', 'product.type'])->get();

            return $cartItems;

        } catch (\Exception $e) {
            // Log the error for debugging

            // Return null or an empty collection in case of error
            return collect();
        }
    }


}
