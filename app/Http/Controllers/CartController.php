<?php

namespace App\Http\Controllers;

use App\Models\discountCodes;
use App\Models\productItems;
use App\Models\products;
use App\Models\shoppingCart;
use App\Rules\ValidPromoCode;
use App\Services\AnalyticsService;
use App\Services\GuestCartMergeService;
use App\Services\MetaPixelService;
use App\Services\ShippingService;
use App\Traits\Apptraits;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartController extends Controller
{
    use Apptraits;

    public $model = 'App\Models\shoppingCart';

    public function add(Request $request)
    {
        $this->validateCartRequest($request);

        try {
            $productItem = $this->getProductItem($request);
            $availableStock = (int) $productItem->quantity;
            $requestedQuantity = (int) $request->quantity;

            if (auth()->check()) {
                app(GuestCartMergeService::class)->mergeForUser(auth()->id());

                return $this->handleAuthCart($request, $productItem, $availableStock, $requestedQuantity);
            }

            return $this->handleGuestCart($request, $productItem, $availableStock, $requestedQuantity);
        } catch (ValidationException|HttpResponseException|HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the product to the cart.',
            ], 500);
        }
    }

    private function validateCartRequest(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'size_id' => 'required|integer|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
        ]);
    }

    private function getProductItem(Request $request)
    {
        $productItem = productItems::with(['products.category', 'products.type'])
            ->where('id', $request->size_id)
            ->where('products_id', $request->product_id)
            ->first();

        if (! $productItem) {
            abort(response()->json([
                'success' => false,
                'message' => 'Product size not found.',
            ], 404));
        }

        $product = $productItem->products;
        if (! $product || ! $product->isSellable()) {
            abort(response()->json([
                'success' => false,
                'message' => 'This product is not available.',
            ], 422));
        }

        return $productItem;
    }

    private function handleAuthCart($request, $productItem, $availableStock, $requestedQuantity)
    {
        $userId = auth()->id();

        $existingCartItem = shoppingCart::where('user_id', $userId)
            ->where('products_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->first();

        $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $newTotalQuantity = $existingQuantity + $requestedQuantity;

        if ($newTotalQuantity > $availableStock) {
            return $this->stockLimitResponse($productItem, $availableStock);
        }

        if ($existingCartItem) {
            $existingCartItem->quantity = $newTotalQuantity;
            $existingCartItem->save();
            $message = 'Product quantity updated successfully.';
        } else {
            shoppingCart::create([
                'user_id' => $userId,
                'products_id' => $request->product_id,
                'size_id' => $request->size_id,
                'quantity' => $requestedQuantity,
            ]);
            $message = 'Product added to cart successfully.';
        }

        $cartCount = shoppingCart::where('user_id', $userId)->count();

        $meta = $this->trackAddToCart($request, (int) $request->product_id, (int) $requestedQuantity);

        return response()->json([
            'success' => true,
            'message' => $message,
            'cartCount' => $cartCount,
            'meta' => $meta,
        ]);
    }

    private function handleGuestCart($request, $productItem, $availableStock, $requestedQuantity)
    {
        $cart = session()->get('cart', []);
        $baseKey = $request->product_id.'_'.$request->size_id;

        $existingKey = collect($cart)->search(function ($item) use ($request) {
            return $item['product_id'] == $request->product_id && $item['size_id'] == $request->size_id;
        });

        $key = is_string($existingKey) ? $existingKey : $baseKey;

        $existingQuantity = isset($cart[$key]) ? $cart[$key]['quantity'] : 0;
        $newTotalQuantity = $existingQuantity + $requestedQuantity;

        if ($newTotalQuantity > $availableStock) {
            return $this->stockLimitResponse($productItem, $availableStock);
        }

        $cart[$key] = [
            'product_id' => $request->product_id,
            'size_id' => $request->size_id,
            'quantity' => $newTotalQuantity,
            'name' => optional($productItem->products)->name,
            'price' => $productItem->products->price,
            'sale' => $productItem->products->sale,
            'key' => $key,
        ];

        session()->put('cart', $cart);

        $meta = $this->trackAddToCart($request, (int) $request->product_id, (int) $requestedQuantity);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cartCount' => count($cart),
            'meta' => $meta,
        ]);
    }

    private function trackAddToCart(Request $request, int $productId, int $quantity): array
    {
        try {
            app(AnalyticsService::class)->recordEvent(
                AnalyticsService::EVENT_ADD_TO_CART,
                $request,
                ['qty' => $quantity],
                $productId
            );

            $product = products::find($productId);
            if (! $product) {
                return [];
            }

            $meta = app(MetaPixelService::class);
            $payload = $meta->productCustomData($product, $quantity);
            $meta->sendCapi('AddToCart', $payload, $request);

            return $payload;
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    private function stockLimitResponse($productItem, $availableStock)
    {
        $productName = optional($productItem->products)->name ?? 'Product';

        return response()->json([
            'success' => false,
            'message' => "Cannot add more than available stock for: {$productName}. Only {$availableStock} left.",
        ]);
    }

    public function index()
    {
        if (auth()->check()) {
            return $this->renderAuthCart();
        }

        return $this->renderGuestCart();
    }

    public function applyPromo(Request $request)
    {
        $code = trim((string) $request->input('promo_code', ''));

        if ($code === '') {
            session()->forget('promo_code');

            return redirect()->route('cart.index')->with('success', __('cart.promo_removed'));
        }

        $request->merge(['promo_code' => $code]);
        $request->validate([
            'promo_code' => ['required', 'string', 'max:50', new ValidPromoCode],
        ]);

        $promo = discountCodes::findUsable($code);
        if (! $promo) {
            return redirect()->route('cart.index')->with('error', __('checkout.invalid_promo'));
        }

        session(['promo_code' => $code]);

        return redirect()->route('cart.index')->with('success', __('cart.promo_applied', ['percent' => $promo->discount_percentage]));
    }

    private function resolvedPromo(): ?discountCodes
    {
        $code = session('promo_code');
        if (! $code) {
            return null;
        }

        $promo = discountCodes::findUsable($code);
        if (! $promo || ! (new ValidPromoCode)->passes('promo_code', $code)) {
            session()->forget('promo_code');

            return null;
        }

        return $promo;
    }

    private function cartTotals(?int $userId): array
    {
        $shipping = app(ShippingService::class);
        $cart = new shoppingCart;
        $subtotal = $cart->subtotal($userId);
        $promo = $this->resolvedPromo();
        $discountPercent = (float) ($promo->discount_percentage ?? 0);
        $discountAmount = $shipping->money($subtotal * ($discountPercent / 100));
        $merchandise = $shipping->merchandiseAfterDiscount($subtotal, $discountPercent);

        return array_merge([
            'subtotal' => $subtotal,
            'total' => $merchandise,
            'discountPercent' => $discountPercent,
            'discountAmount' => $discountAmount,
            'appliedPromo' => $promo?->code ?? session('promo_code'),
            'featuredProducts' => $this->featuredProducts(),
        ], $this->cartShippingHints($merchandise));
    }

    private function cartShippingHints(float $merchandise): array
    {
        $shipping = app(ShippingService::class);

        return [
            'freeShippingMin' => $shipping->threshold(),
            'freeShippingQualifies' => $shipping->qualifies($merchandise),
            'freeShippingRemaining' => $shipping->remainingUntilFree($merchandise),
        ];
    }

    private function featuredProducts()
    {
        return products::with('productImages')
            ->sellable()
            ->orderByDesc('is_highest')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();
    }

    private function renderAuthCart()
    {
        $cartItems = shoppingCart::with('product.productImages', 'productItems')
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', array_merge(
            $this->cartTotals(auth()->id()),
            compact('cartItems')
        ));
    }

    private function renderGuestCart()
    {
        [$cartItems] = $this->buildGuestCartViewData();

        return view('cart.index', array_merge(
            $this->cartTotals(null),
            compact('cartItems')
        ));
    }

    private function buildGuestCartViewData(): array
    {
        $sessionCart = session()->get('cart', []);
        $cartItems = collect();
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
        $productsById = products::with(['productImages', 'category', 'type'])->whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($combined as $key => $item) {
            $product = $productsById->get($item['product_id']);

            if ($product && $product->isSellable()) {
                $cartItems->push((object) [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size_id' => $item['size_id'],
                    'key' => $key,
                ]);
            }
        }

        return [$cartItems];
    }

    public function delete($id)
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to manage your cart.');
        }

        $cartItem = shoppingCart::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $cartItem) {
            return redirect()->back()->with('error', 'Failed to delete your item');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'your item deleted successfully.');
    }

    public function deleteGuest(Request $request, $key)
    {
        $guestCart = Session::get('cart', []);

        if (isset($guestCart[$key])) {
            unset($guestCart[$key]);
            Session::put('cart', $guestCart);

            return redirect()->back()->with('success', 'Item removed from cart.');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = shoppingCart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $productItem = productItems::with(['products.category', 'products.type'])
            ->where('id', $cartItem->size_id)
            ->where('products_id', $cartItem->products_id)
            ->firstOrFail();

        $product = $productItem->products;
        if (! $product || ! $product->isSellable()) {
            return redirect()->back()->with('error', 'This product is not available.');
        }

        if ($request->quantity > $productItem->quantity) {
            return redirect()->back()->with('error', __('cart.stock_limit', ['count' => $productItem->quantity]));
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->back()->with('success', __('cart.quantity_updated'));
    }

    public function updateGuestQuantity(Request $request, $key)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $guestCart = Session::get('cart', []);

        if (! isset($guestCart[$key])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $item = $guestCart[$key];
        $productItem = productItems::with(['products.category', 'products.type'])
            ->where('id', $item['size_id'])
            ->where('products_id', $item['product_id'])
            ->first();

        $product = $productItem?->products;
        if (! $productItem || ! $product || ! $product->isSellable() || $request->quantity > $productItem->quantity) {
            return redirect()->back()->with('error', __('cart.stock_limit', ['count' => $productItem->quantity ?? 0]));
        }

        $guestCart[$key]['quantity'] = $request->quantity;
        Session::put('cart', $guestCart);

        return redirect()->back()->with('success', __('cart.quantity_updated'));
    }
}
