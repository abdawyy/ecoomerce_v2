<?php

namespace App\Jobs;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\orders;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendOrderConfirmation
{
    public function __construct(public int $orderId) {}

    public function handle(): void
    {
        $order = orders::with([
            'user',
            'guestUser',
            'discountCodes',
            'cities',
            'orderItems.product',
            'payments',
            'address',
        ])->find($this->orderId);

        if (! $order) {
            return;
        }

        $invoiceUrl = URL::temporarySignedRoute(
            'checkout.receipt.invoice',
            now()->addHours(48),
            ['order' => $order->id]
        );

        $customerEmail = $order->user->email ?? $order->guestUser->email ?? null;

        if ($customerEmail) {
            Mail::to($customerEmail)->send(new OrderConfirmationMail($order, $invoiceUrl));
        }

        $adminEmail = config('hayah.admin_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order, $invoiceUrl));
        }
    }
}
