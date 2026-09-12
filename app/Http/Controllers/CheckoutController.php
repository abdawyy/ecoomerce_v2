<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderConfirmation;
use App\Models\addresses;
use App\Models\Cities;
use App\Models\discountCodes;
use App\Models\GuestUser;
use App\Models\orders;
use App\Models\payments;
use App\Models\products;
use App\Models\shoppingCart;
use App\Rules\ValidPromoCode;
use App\Services\PdfService;
use App\Traits\Apptraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutController extends Controller
{
    use Apptraits;

    public $addressModel = 'App\Models\addresses';

    public $guestModel = 'App\Models\GuestUser';

    public function index()
    {
        if (auth()->check()) {
            return $this->renderAuthIndex();
        }

        return $this->renderGuestIndex();
    }

    public function order(Request $request)
    {
        if ($request->input('checkout_token') !== session('checkout_token')) {
            return redirect()->route('checkout')->with('error', __('checkout.invalid_submission'));
        }

        if (Auth::check()) {
            return $this->processAuthOrder($request);
        }

        return $this->processGuestOrder($request);
    }

    protected function processAuthOrder(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'country' => 'nullable|string|max:100',
            'phone_number' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'promo_code' => ['nullable', 'string', 'max:50', new ValidPromoCode],
        ]);

        $userId = Auth::id();

        if (! $this->cartHasItems($userId, false)) {
            return redirect()->route('cart.index')->with('error', __('checkout.cart_empty'));
        }

        $promoCodeValue = discountCodes::where('code', $validatedData['promo_code'] ?? null)->first();
        $city = Cities::where('id', $validatedData['city'])->firstOrFail();

        try {
            $order = DB::transaction(function () use ($request, $validatedData, $userId, $promoCodeValue, $city) {
                $addressQuery = addresses::where('user_id', $userId)->first();
                $payload = $this->addressPayload($request, $userId, null);
                $address = $addressQuery
                    ? tap($addressQuery)->update($payload)
                    : addresses::create($payload);

                $cart = new shoppingCart;
                $total = $cart->totalPrice($userId, $city->price, $promoCodeValue->discount_percentage ?? 0);

                $order = orders::create([
                    'user_id' => $userId,
                    'total_amount' => $total,
                    'status' => 'Pending',
                    'discount_code_id' => $promoCodeValue->id ?? null,
                    'city_id' => $city->id,
                    'address_id' => $address->id,
                ]);

                $result = $order->addOrderItems($userId);
                if (! $result['success']) {
                    throw new RuntimeException($result['message'] ?? 'Failed to add items to order.');
                }

                payments::createCashPayment($order->id, $total);

                return ['order' => $order, 'total' => $total];
            });
        } catch (RuntimeException $e) {
            session(['checkout_token' => Str::uuid()->toString()]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            session(['checkout_token' => Str::uuid()->toString()]);

            return redirect()->back()->withInput()->with('error', 'Failed to place order. Please try again.');
        }

        return $this->completeOrder($order['order']->id, $order['total'], $city->price);
    }

    protected function processGuestOrder(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|max:255',
            'full_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|integer|exists:cities,id',
            'country' => 'nullable|string|max:100',
            'phone_number' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'promo_code' => ['nullable', 'string', 'max:50', new ValidPromoCode],
        ]);

        if (! $this->cartHasItems(null, true)) {
            return redirect()->route('cart.index')->with('error', __('checkout.cart_empty'));
        }

        $promoCodeValue = discountCodes::where('code', $validatedData['promo_code'] ?? null)->first();
        $city = Cities::where('id', $validatedData['city'])->firstOrFail();

        try {
            $order = DB::transaction(function () use ($request, $validatedData, $promoCodeValue, $city) {
                $guestUser = GuestUser::where('email', $validatedData['email'])->first();
                if ($guestUser) {
                    $guestUser->update(['name' => $validatedData['full_name']]);
                } else {
                    $guestUser = GuestUser::create([
                        'email' => $validatedData['email'],
                        'name' => $validatedData['full_name'],
                    ]);
                }

                $address = addresses::create(array_merge(
                    $this->addressPayload($request, null, $guestUser->id),
                    ['guest_id' => $guestUser->id]
                ));

                $cart = new shoppingCart;
                $total = $cart->totalPrice(null, $city->price, $promoCodeValue->discount_percentage ?? 0);

                $order = orders::create([
                    'user_id' => null,
                    'total_amount' => $total,
                    'status' => 'Pending',
                    'discount_code_id' => $promoCodeValue->id ?? null,
                    'city_id' => $city->id,
                    'address_id' => $address->id,
                    'guest_id' => $guestUser->id,
                ]);

                $result = $order->addOrderItems(null, true);
                if (! $result['success']) {
                    throw new RuntimeException($result['message'] ?? 'Failed to add items to order.');
                }

                payments::createCashPayment($order->id, $total);

                return ['order' => $order, 'total' => $total, 'guest' => $guestUser];
            });
        } catch (RuntimeException $e) {
            session(['checkout_token' => Str::uuid()->toString()]);

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            session(['checkout_token' => Str::uuid()->toString()]);

            return redirect()->back()->withInput()->with('error', 'Failed to place order. Please try again.');
        }

        return $this->completeOrder($order['order']->id, $order['total'], $city->price);
    }

    protected function addressPayload(Request $request, ?int $userId, ?int $guestId): array
    {
        return array_filter([
            'user_id' => $userId,
            'guest_id' => $guestId,
            'address_line1' => $request->input('address_line1'),
            'address_line2' => $request->input('address_line2'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'phone_number' => $request->input('phone_number'),
        ], fn ($value) => $value !== null);
    }

    protected function cartHasItems(?int $userId, bool $isGuest): bool
    {
        if ($isGuest) {
            return count(session()->get('cart', [])) > 0;
        }

        return shoppingCart::where('user_id', $userId)->exists();
    }

    protected function completeOrder(int $orderId, float $total, $deliveryFees)
    {
        session()->forget('checkout_token');
        session([
            'placed_order_id' => $orderId,
            'placed_order_total' => $total,
            'placed_order_delivery' => $deliveryFees,
        ]);

        return redirect()->route('checkout.receipt', $orderId);
    }

    public function receipt(int $order)
    {
        $this->authorizeReceipt($order);

        $orderModel = orders::query()
            ->with(['guestUser:id,email,name', 'cities:id,price'])
            ->findOrFail($order);

        $total = session('placed_order_total', $orderModel->total_amount);
        $deliveryFees = session('placed_order_delivery', $orderModel->cities->price ?? 0);

        return view('checkout.receipt', [
            'orderID' => $orderModel->id,
            'totalPrice' => $total,
            'deliveryFees' => $deliveryFees,
            'isGuestCheckout' => $orderModel->user_id === null,
            'guestEmail' => $orderModel->guestUser->email ?? null,
            'guestName' => $orderModel->guestUser->name ?? null,
            'invoiceUrl' => URL::temporarySignedRoute('checkout.receipt.invoice', now()->addHours(48), ['order' => $orderModel->id]),
            'notifyUrl' => route('checkout.receipt.notify', $orderModel->id),
            'emailSent' => false,
            'emailPending' => true,
        ]);
    }

    public function notifyReceipt(int $order)
    {
        $this->authorizeReceipt($order);

        $cacheKey = 'order-confirmation-sent-'.$order;
        if (cache()->has($cacheKey)) {
            return response()->json(['ok' => true]);
        }

        cache()->put($cacheKey, 1, now()->addDay());

        try {
            (new SendOrderConfirmation($order))->handle();
        } catch (\Throwable $e) {
            cache()->forget($cacheKey);
            report($e);
        }

        return response()->json(['ok' => true]);
    }

    protected function authorizeReceipt(int $orderId): void
    {
        if ((int) session('placed_order_id') === $orderId) {
            return;
        }

        if (Auth::check() && orders::where('id', $orderId)->where('user_id', Auth::id())->exists()) {
            return;
        }

        abort(403);
    }

    public function receiptInvoice(Request $request, int $order, PdfService $pdf)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $orderModel = orders::findOrFail($order);

        return $pdf->streamInvoice($orderModel);
    }

    private function renderAuthIndex()
    {
        $cartItems = shoppingCart::with('product.productImages', 'productItems')
            ->where('user_id', Auth::id())
            ->get();

        $savedAddresses = addresses::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        return view('checkout.address', array_merge(
            $this->checkoutViewData($cartItems, Auth::id()),
            compact('savedAddresses')
        ));
    }

    private function renderGuestIndex()
    {
        [$cartItems, $subtotal] = $this->buildGuestCartViewData();

        return view('checkout.address', array_merge(
            $this->checkoutViewData($cartItems, null, $subtotal),
            ['savedAddresses' => collect()]
        ));
    }

    protected function checkoutViewData($cartItems, ?int $userId, ?float $guestSubtotal = null): array
    {
        $subtotal = $guestSubtotal ?? $cartItems->sum(function ($item) {
            return $this->lineTotal($item->product ?? null, (int) ($item->quantity ?? 1));
        });

        $cityId = old('city');
        $city = $cityId ? Cities::find($cityId) : null;
        $deliveryFee = $city->price ?? 0;
        $promoCode = old('promo_code') ?? session('promo_code');
        $promo = $promoCode
            ? discountCodes::where('code', $promoCode)->where('is_active', 1)->where('expiry_date', '>', now())->first()
            : null;
        $discountPercent = $promo->discount_percentage ?? 0;
        $discountAmount = $subtotal * ($discountPercent / 100);

        $cart = new shoppingCart;
        $total = $cart->totalPrice($userId, $deliveryFee, $discountPercent);

        $checkoutToken = session('checkout_token') ?? Str::uuid()->toString();
        session(['checkout_token' => $checkoutToken]);

        return compact(
            'cartItems',
            'subtotal',
            'total',
            'deliveryFee',
            'discountPercent',
            'discountAmount',
            'checkoutToken'
        );
    }

    protected function lineTotal($product, int $quantity): float
    {
        if (! $product) {
            return 0;
        }

        $price = $product->price ?? 0;
        $sale = $product->sale ?? 0;
        $unit = $price - ($price * $sale / 100);

        return $unit * $quantity;
    }

    protected function buildGuestCartViewData(): array
    {
        $sessionCart = session()->get('cart', []);
        $cartItems = collect();
        $subtotal = 0;
        $combined = [];

        foreach ($sessionCart as $item) {
            $key = $item['product_id'].'_'.$item['size_id'];

            if (! isset($combined[$key])) {
                $combined[$key] = $item;
            } else {
                $combined[$key]['quantity'] += $item['quantity'];
            }
        }

        $productIds = collect($combined)->pluck('product_id')->unique()->filter()->all();
        $productsById = products::with('productImages')->whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($combined as $key => $item) {
            $product = $productsById->get($item['product_id']);

            if ($product) {
                $cartItems->push((object) [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'sale' => $item['sale'],
                    'size_id' => $item['size_id'],
                    'key' => $key,
                ]);

                $subtotal += $this->lineTotal($product, $item['quantity']);
            }
        }

        return [$cartItems, $subtotal];
    }

    protected function loadOrderWithRelations($orderId)
    {
        return orders::with([
            'user',
            'user.address',
            'guestUser',
            'guestUser.address',
            'discountCodes',
            'cities',
            'orderItems.product',
            'orderItems.productItems',
            'payments',
            'address',
        ])->findOrFail($orderId);
    }
}
