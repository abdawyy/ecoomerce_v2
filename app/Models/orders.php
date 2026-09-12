<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Apptraits;
use Illuminate\Support\Facades\DB;


class orders extends Model
{
    use Apptraits;
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'discount_code_id',
        'city_id',
        'guest_id',
        'address_id'




    ];
    protected $table = 'orders';


    // Optionally, specify default values for attributes
    protected $attributes = [
        'status' => 'Pending',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }
    public function discountCodes()
    {
        return $this->belongsTo(discountCodes::class, 'discount_code_id');

    }
    public function cities()
    {
        return $this->belongsTo(Cities::class, 'city_id');

    }
    public function orderDiscounts()
    {
        return $this->hasMany(orderDiscounts::class);

    }
    public function orderItems()
    {
        return $this->hasMany(orderItems::class, 'orders_id');

    }
    public function payments()
    {
        return $this->hasMany(payments::class, 'orders_id');

    }
    public function guestUser()
    {
        return $this->belongsTo(GuestUser::class, 'guest_id');
    }
    public function address()
    {
        return $this->belongsTo(addresses::class, 'address_id');
    }
    public function addOrderItems($userId, $isGuest = false)
    {
        $ownsTransaction = DB::transactionLevel() === 0;

        if ($ownsTransaction) {
            DB::beginTransaction();
        }

        try {
            $cartItems = $isGuest
                ? self::getGuestCartItems()
                : shoppingCart::getCartItemsByUserId($userId);

            if (empty($cartItems) || (is_countable($cartItems) && count($cartItems) === 0)) {
                if ($ownsTransaction) {
                    DB::rollBack();
                }

                return ['success' => false, 'message' => 'Cart is empty.'];
            }

            $this->deductProductQuantitiesAndCreateOrderItems($cartItems);
            $this->clearCart($userId, $isGuest);

            if ($ownsTransaction) {
                DB::commit();
            }

            return ['success' => true, 'message' => 'Order items added successfully.'];
        } catch (\Exception $e) {
            if ($ownsTransaction) {
                DB::rollBack();
            }

            return ['success' => false, 'message' => 'Failed to add order items: '.$e->getMessage()];
        }
    }

    protected function deductProductQuantitiesAndCreateOrderItems($cartItems)
    {
        foreach ($cartItems as $cartItem) {
            $productsId = $cartItem['product_id'] ?? $cartItem->products_id;
            $sizeId = $cartItem['size_id'] ?? $cartItem->size_id;
            $quantity = $cartItem['quantity'] ?? $cartItem->quantity;


            $productItem = productItems::where('products_id', $productsId)
                ->where('id', $sizeId)
                ->lockForUpdate()
                ->first();

            if (! $productItem) {
                throw new \Exception('Product item not found.');
            }

            if ($productItem->quantity < $quantity) {
                $productId = is_array($cartItem) ? $cartItem['product_id'] : $cartItem->products_id;
                $productName = products::find($productId)?->name ?? 'Product';
                throw new \Exception("Insufficient stock for {$productName}. Only {$productItem->quantity} available.");
            }

            $productId = is_array($cartItem) ? $cartItem['product_id'] : $cartItem->products_id;
            $product = products::findOrFail($productId);

            $productItem->quantity -= $quantity;
            $productItem->save();

            $price = ($product->price ?? 0) - (($product->price ?? 0) * ($product->sale ?? 0) / 100);
            $totalPrice = $price * $quantity;


            orderItems::create([
                'orders_id' => $this->id,
                'products_id' => $productsId,
                'quantity' => $quantity,
                'size' => $productItem->size,
                'price' => $totalPrice,
            ]);
        }
    }

    protected function clearCart($userId, $isGuest)
    {
        if ($isGuest) {
            session()->forget('cart');
        } else {
            shoppingCart::where('user_id', $userId)->delete();
        }
    }



}
