<?php

namespace App\Observers;

use App\Models\GuestUser;
use App\Services\AdminNotificationService;

class GuestUserNotificationObserver
{
    public function __construct(protected AdminNotificationService $notifications)
    {
    }

    public function created(GuestUser $guest): void
    {
        $this->notifications->broadcast([
            'type' => 'success',
            'message' => __('dashboard.new_guest_toast', [
                'name' => $guest->name,
            ]),
        ]);
    }
}
