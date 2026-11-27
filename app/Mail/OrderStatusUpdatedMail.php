<?php

namespace App\Mail;

use App\Support\OrderStatus;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $siteName = app(\App\Services\BrandingService::class)->siteName();
        $statusKey = 'email.status_'.OrderStatus::mailKey($this->order->status);
        $statusLabel = __($statusKey) === $statusKey
            ? OrderStatus::normalize($this->order->status)
            : __($statusKey);

        return $this->subject(__('email.status_subject', [
            'id' => $this->order->id,
            'status' => $statusLabel,
            'site' => $siteName,
        ]))->view('email.order-status', [
            'statusLabel' => $statusLabel,
        ]);
    }
}
