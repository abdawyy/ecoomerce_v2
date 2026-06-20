<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Apptraits;
use Carbon\Carbon;
use Illuminate\Http\Request;

class userController extends Controller
{
    use Apptraits;

    public $model = 'App\Models\User';

    public $url = '/admin/user';

    public function list(Request $request)
    {
        $query = User::query()
            ->withCount('order')
            ->withMax('order as last_order_at', 'created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        if ($request->boolean('has_orders')) {
            $query->has('order');
        }

        if ($request->boolean('new')) {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        }

        $users = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.user.list', compact('users'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        self::toggleStatus($user);

        return back()->with('success', 'Status toggled');
    }
}
