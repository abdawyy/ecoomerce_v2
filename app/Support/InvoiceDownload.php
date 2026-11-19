<?php

namespace App\Support;

use App\Models\orders;

class InvoiceDownload
{
    public static function url(orders $order, int $hours = 48): string
    {
        $expires = now()->addHours($hours)->timestamp;

        return route('checkout.receipt.invoice.signed', [
            'order' => $order->id,
            'expires' => $expires,
            'hmac' => self::hmac($order->id, $expires),
        ]);
    }

    public static function isValid(int $orderId, int|string $expires, string $hmac): bool
    {
        $expires = (int) $expires;
        if ($expires < time()) {
            return false;
        }

        return hash_equals(self::hmac($orderId, $expires), $hmac);
    }

    protected static function hmac(int $orderId, int $expires): string
    {
        return hash_hmac('sha256', $orderId.'|'.$expires, (string) config('app.key'));
    }
}
