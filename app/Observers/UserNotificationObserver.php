<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AdminNotificationService;

class UserNotificationObserver
{
    public function __construct(protected AdminNotificationService $notifications)
    {
    }

    public function created(User $user): void
    {
        $this->notifications->broadcast([
            'type' => 'success',
            'message' => __('dashboard.new_user_toast', [
                'name' => $user->name,
            ]),
        ]);
    }
}
