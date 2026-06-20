<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Models\addresses;
use App\Models\orders;
use App\Models\Review;
use App\Services\GuestAccountMergeService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active.user']);
    }

    public function dashboard(GuestAccountMergeService $guestMerge)
    {
        $user = Auth::user();
        $mergedOrders = $guestMerge->mergeForUser($user);

        $latestOrder = orders::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->first();
        $orderCount = orders::where('user_id', $user->id)->count();

        if ($mergedOrders > 0) {
            session()->flash('success', __('account.guest_orders_merged', ['count' => $mergedOrders]));
        }

        return view('account.dashboard', compact('user', 'latestOrder', 'orderCount'));
    }

    public function orders()
    {
        $orders = orders::with(['cities', 'discountCodes'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function orderShow(int $id)
    {
        $order = $this->findUserOrder($id);

        return view('account.orders.show', compact('order'));
    }

    public function invoice(int $id, PdfService $pdf)
    {
        $order = $this->findUserOrder($id);

        return $pdf->streamInvoice($order);
    }

    public function addresses()
    {
        $addresses = addresses::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('account.addresses.index', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone_number' => 'required|string|max:20',
        ]);

        $isFirst = ! addresses::where('user_id', Auth::id())->exists();

        addresses::create(array_merge($data, [
            'user_id' => Auth::id(),
            'is_default' => $isFirst,
        ]));

        return redirect()->route('account.addresses')->with('success', __('account.address_saved'));
    }

    public function updateAddress(Request $request, int $id)
    {
        $address = addresses::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone_number' => 'required|string|max:20',
        ]);

        $address->update($data);

        return redirect()->route('account.addresses')->with('success', __('account.address_updated'));
    }

    public function setDefaultAddress(int $id)
    {
        $address = addresses::where('user_id', Auth::id())->findOrFail($id);

        addresses::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('account.addresses')->with('success', __('account.default_address_set'));
    }

    public function destroyAddress(int $id)
    {
        $address = addresses::where('user_id', Auth::id())->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = addresses::where('user_id', Auth::id())->orderByDesc('updated_at')->first();
            $next?->update(['is_default' => true]);
        }

        return redirect()->route('account.addresses')->with('success', __('account.address_deleted'));
    }

    public function profile()
    {
        return view('account.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->fill($data)->save();

        return redirect()->route('account.profile')->with('success', __('account.profile_updated'));
    }

    public function updatePassword(Request $request, UpdateUserPassword $updater)
    {
        $updater->update(Auth::user(), $request->only([
            'current_password',
            'password',
            'password_confirmation',
        ]));

        return redirect()->route('account.profile')->with('success', __('account.password_updated'));
    }

    public function reviews()
    {
        $reviews = Review::with('product')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('account.reviews', compact('reviews'));
    }

    protected function findUserOrder(int $id): orders
    {
        return orders::with([
            'orderItems.product',
            'cities',
            'discountCodes',
            'address',
            'payments',
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    }
}
