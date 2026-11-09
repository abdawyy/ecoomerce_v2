<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\GuestAccountMergeService;
use App\Services\GuestCartMergeService;
use Illuminate\Auth\Events\Login;

class MergeGuestOrdersOnLogin
{
    public function __construct(
        private GuestAccountMergeService $mergeService,
        private GuestCartMergeService $cartMergeService
    ) {
    }

    public function handle(Login $event): void
    {
        if ($event->user instanceof User) {
            $this->cartMergeService->mergeForUser((int) $event->user->id);
            $this->mergeService->mergeForUser($event->user);
        }
    }
}
