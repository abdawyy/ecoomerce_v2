<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use SerializesModels;

    public $order;

    public ?string $invoiceUrl;

    public function __construct($order, ?string $invoiceUrl = null)
    {
        $this->order = $order;
        $this->invoiceUrl = $invoiceUrl;
    }

    public function build()
    {
        $siteName = app(\App\Services\BrandingService::class)->siteName();

        return $this->subject(__('email.order_subject', [
            'id' => $this->order->id,
            'site' => $siteName,
        ]))->view('email.order-confirmation');
    }
}
