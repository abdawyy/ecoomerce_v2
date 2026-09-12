<?php

namespace App\Jobs;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\orders;
use App\Services\PdfService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmation
{
    use Dispatchable;

    public function __construct(public int $orderId) {}

    public function handle(PdfService $pdf): void
    {
        $order = orders::with([
            'user',
            'guestUser',
            'discountCodes',
            'cities',
            'orderItems.product',
            'orderItems.productItems',
            'payments',
            'address',
        ])->find($this->orderId);

        if (! $order) {
            return;
        }

        $pdfPaths = [];
        try {
            $pdfPaths = $pdf->saveInvoices($order);
        } catch (\Throwable $e) {
            report($e);
        }

        $customerEmail = $order->user->email ?? $order->guestUser->email ?? null;

        try {
            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderConfirmationMail($order, $pdfPaths));
            }
            $adminEmail = config('hayah.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order, $pdfPaths));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
