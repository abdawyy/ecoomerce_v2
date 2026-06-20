<?php

namespace App\Http\Controllers;

use App\Models\GuestUser;
use App\Services\AdminCustomerProfileService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestUserController extends Controller
{
    public $url = '/admin/guest';

    public function list(Request $request)
    {
        $query = GuestUser::query()
            ->withCount('orders')
            ->withMax('orders as last_order_at', 'created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('has_orders')) {
            $query->has('orders');
        }

        if ($request->boolean('new')) {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        }

        $guests = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.guest.list', compact('guests'));
    }

    public function guestShow($id, AdminCustomerProfileService $profiles)
    {
        $guest = GuestUser::find($id);

        if (! $guest) {
            return redirect()->back()->with('error', 'guest not found');
        }

        $profile = $profiles->fromGuest($guest);

        return view('admin.guest.show', compact('profile'));
    }
}
