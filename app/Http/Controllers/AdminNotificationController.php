<?php

namespace App\Http\Controllers;

use App\Services\AdminNotificationService;
use App\Support\AdminNav;

class AdminNotificationController extends Controller
{
    public function counts(AdminNotificationService $notifications)
    {
        $counts = $notifications->counts();

        return response()->json([
            'counts' => $counts,
            'bell_total' => AdminNav::notificationTotal($counts),
        ]);
    }

    public function feed(AdminNotificationService $notifications)
    {
        return response()->json([
            'items' => $notifications->feedPayload(15),
        ]);
    }
}
