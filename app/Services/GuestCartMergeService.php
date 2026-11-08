<?php

namespace App\Services;

use App\Models\productItems;
use App\Models\products;
use App\Models\shoppingCart;

class GuestCartMergeService
{
    public function mergeForUser(int $userId): void
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart) || ! is_array($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $item) {
            $productId = $item['product_id'] ?? null;
            $sizeId = $item['size_id'] ?? null;
            $quantity = (int) ($item['quantity'] ?? 0);

            if (! $productId || ! $sizeId || $quantity < 1) {
                continue;
            }

            $product = products::query()->with(['category', 'type'])->find($productId);
            if (! $product || ! $product->isSellable()) {
                continue;
            }

            $productItem = productItems::where('id', $sizeId)
                ->where('products_id', $productId)
                ->first();

            if (! $productItem) {
                continue;
            }

            $available = max(0, (int) $productItem->quantity);
            if ($available < 1) {
                continue;
            }

            $quantity = min($quantity, $available);

            $existingCartItem = shoppingCart::where('user_id', $userId)
                ->where('products_id', $productId)
                ->where('size_id', $sizeId)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->quantity = min(
                    (int) $existingCartItem->quantity + $quantity,
                    $available
                );
                $existingCartItem->save();
            } else {
                shoppingCart::create([
                    'user_id' => $userId,
                    'products_id' => $productId,
                    'size_id' => $sizeId,
                    'quantity' => $quantity,
                ]);
            }
        }

        session()->forget('cart');
    }
}
