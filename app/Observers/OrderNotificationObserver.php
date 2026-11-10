<?php

namespace App\Observers;

use App\Models\orders;
use App\Services\AdminNotificationService;

class OrderNotificationObserver
{
    public function __construct(protected AdminNotificationService $notifications)
    {
    }

    public function created(orders $order): void
    {
        $this->notifications->broadcast([
            'type' => 'info',
            'message' => __('dashboard.new_order_toast', [
                'id' => $order->id,
                'amount' => number_format((float) $order->total_amount, 0),
            ]),
        ]);
    }

    public function updated(orders $order): void
    {
        if ($order->wasChanged('status')) {
            $this->notifications->broadcast();
        }
    }
}
