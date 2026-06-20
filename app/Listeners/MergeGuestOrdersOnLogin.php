<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\GuestAccountMergeService;
use Illuminate\Auth\Events\Login;

class MergeGuestOrdersOnLogin
{
    public function __construct(private GuestAccountMergeService $mergeService)
    {
    }

    public function handle(Login $event): void
    {
        if ($event->user instanceof User) {
            $this->mergeService->mergeForUser($event->user);
        }
    }
}
