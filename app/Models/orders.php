<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\OrderStatus;
use App\Traits\Apptraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


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
        'address_id',
        'delivery_fee',
        'meta_event_id',
    ];
    protected $table = 'orders';


    // Optionally, specify default values for attributes
    protected $attributes = [
        'status' => OrderStatus::PENDING,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $order) {
            if ($order->status) {
                $order->status = OrderStatus::normalize($order->status);
            }
        });
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', OrderStatus::completedValues());
    }

    public function transitionTo(string $status): void
    {
        $from = OrderStatus::normalize($this->status);
        $to = OrderStatus::normalize($status);

        if ($from === $to) {
            return;
        }

        DB::transaction(function () use ($from, $to) {
            $order = self::query()->whereKey($this->id)->lockForUpdate()->first();
            if (! $order) {
                throw new \RuntimeException(__('orders.stock_item_missing'));
            }

            if ($from !== OrderStatus::CANCELLED && $to === OrderStatus::CANCELLED) {
                $this->adjustStock($order, 1);
            }

            if ($from === OrderStatus::CANCELLED && $to !== OrderStatus::CANCELLED) {
                $this->adjustStock($order, -1);
            }

            $this->status = $to;
            $this->save();
        });
    }

    public function restoreStock(): void
    {
        DB::transaction(function () {
            $order = self::query()->whereKey($this->id)->lockForUpdate()->first();
            if (! $order) {
                return;
            }

            $this->adjustStock($order, 1);
        });
    }

    protected function adjustStock(self $order, int $direction): void
    {
        $order->loadMissing('orderItems.product');

        foreach ($order->orderItems as $item) {
            $quantity = (int) $item->quantity;
            if ($quantity < 1) {
                continue;
            }

            $productItem = null;
            if ($item->size_id) {
                $productItem = productItems::where('id', $item->size_id)->lockForUpdate()->first();
            }
            if (! $productItem && $item->size) {
                $productItem = productItems::where('products_id', $item->products_id)
                    ->where('size', $item->size)
                    ->lockForUpdate()
                    ->first();
            }

            if (! $productItem) {
                if ($direction < 0) {
                    throw new \RuntimeException(__('orders.stock_item_missing'));
                }

                continue;
            }

            if ($direction < 0 && (int) $productItem->quantity < $quantity) {
                throw new \RuntimeException(__('orders.insufficient_stock', [
                    'product' => $item->product->name ?? ('#'.$item->products_id),
                    'available' => (int) $productItem->quantity,
                ]));
            }

            $productItem->quantity = (int) $productItem->quantity + ($direction * $quantity);
            $productItem->save();
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }
    public function discountCodes()
    {
        return $this->belongsTo(discountCodes::class, 'discount_code_id');

    }
    public function resolvedDeliveryFee(): float
    {
        return app(\App\Services\ShippingService::class)->orderDeliveryFee($this);
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
            $quantity = (int) ($cartItem['quantity'] ?? $cartItem->quantity);

            if ($quantity < 1) {
                throw new \Exception('Invalid item quantity.');
            }

            $product = products::with(['category', 'type'])->find($productsId);
            if (! $product || ! $product->isSellable()) {
                throw new \Exception('This product is no longer available.');
            }

            $productItem = productItems::where('products_id', $productsId)
                ->where('id', $sizeId)
                ->lockForUpdate()
                ->first();

            if (! $productItem) {
                throw new \Exception('Product item not found.');
            }

            if ($productItem->quantity < $quantity) {
                throw new \Exception("Insufficient stock for {$product->name}. Only {$productItem->quantity} available.");
            }

            $productItem->quantity -= $quantity;
            $productItem->save();

            $totalPrice = $product->discountedUnitPrice() * $quantity;

            $itemPayload = [
                'orders_id' => $this->id,
                'products_id' => $productsId,
                'quantity' => $quantity,
                'size' => $productItem->size,
                'price' => $totalPrice,
            ];
            if (Schema::hasColumn('order_items', 'size_id')) {
                $itemPayload['size_id'] = $sizeId;
            }

            orderItems::create($itemPayload);
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
