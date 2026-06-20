<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;

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
        return $this->belongsTo(user::class);
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

        // Step 3: Apply promo discount
        $afterDiscount = $this->applyPromoDiscount($subtotal, $promoCode);

        // Step 4: Add city price
        return $afterDiscount + $cityPrice;
    }
    // Get cart items for either a user or guest
    protected function getCartItems($userId)
    {
        if ($userId) {
            return $this->with('product')->where('user_id', $userId)->get();
        }

        return self::getGuestCartItems();
    }

    // Calculate subtotal from cart items
    protected function calculateSubtotal($items)
    {
        $total = 0;

        foreach ($items as $item) {
            $price = $item->product->price ?? $item['price'];
            $sale = $item->product->sale ?? $item['sale'];

            $discountedPrice = $price - ($price * $sale / 100);
            $total += $discountedPrice * ($item->quantity ?? $item['quantity']);
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
            $cartItems = self::where('user_id', $userId)->with('product')->get();

            return $cartItems;

        } catch (\Exception $e) {
            // Log the error for debugging

            // Return null or an empty collection in case of error
            return collect();
        }
    }


}
