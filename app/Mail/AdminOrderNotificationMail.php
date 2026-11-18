<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotificationMail extends Mailable
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
        return $this->locale('en')
            ->subject(__('email.admin_order_subject', ['id' => $this->order->id], 'en'))
            ->view('email.admin-order-notification');
    }
}
