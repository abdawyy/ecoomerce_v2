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
        return $this->subject('Order Confirmation — Hayah')
            ->view('email.order-confirmation');
    }
}
