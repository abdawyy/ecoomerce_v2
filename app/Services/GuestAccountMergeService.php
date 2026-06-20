<?php

namespace App\Services;

use App\Models\addresses;
use App\Models\GuestUser;
use App\Models\orders;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GuestAccountMergeService
{
    public function mergeForUser(User $user): int
    {
        $guests = GuestUser::where('email', $user->email)->get();

        if ($guests->isEmpty()) {
            return 0;
        }

        $mergedCount = 0;

        DB::transaction(function () use ($guests, $user, &$mergedCount) {
            foreach ($guests as $guest) {
                $mergedCount += orders::where('guest_id', $guest->id)
                    ->whereNull('user_id')
                    ->update([
                        'user_id' => $user->id,
                        'guest_id' => null,
                    ]);

                addresses::where('guest_id', $guest->id)
                    ->whereNull('user_id')
                    ->update([
                        'user_id' => $user->id,
                        'guest_id' => null,
                    ]);

                $guest->delete();
            }
        });

        return $mergedCount;
    }
}
