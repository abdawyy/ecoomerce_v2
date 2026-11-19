<?php

namespace App\Jobs;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\orders;
use App\Support\InvoiceDownload;
use Illuminate\Support\Facades\Mail;

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

        $invoiceUrl = InvoiceDownload::url($order);

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
