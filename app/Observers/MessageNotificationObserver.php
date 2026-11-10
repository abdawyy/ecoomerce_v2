<?php

namespace App\Observers;

use App\Models\Message;
use App\Services\AdminNotificationService;

class MessageNotificationObserver
{
    public function __construct(protected AdminNotificationService $notifications)
    {
    }

    public function created(Message $message): void
    {
        $this->notifications->broadcast([
            'type' => 'warning',
            'message' => __('dashboard.new_message_toast', [
                'name' => $message->name,
            ]),
        ]);
    }
}
