<?php

namespace App\Http\Controllers;

use App\Models\discountCodes;
use App\Models\productItems;
use App\Models\products;
use App\Models\shoppingCart;
use App\Services\AnalyticsService;
use App\Traits\Apptraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    use Apptraits;

    public $model = 'App\Models\shoppingCart';

    public function add(Request $request)
    {
        $this->validateCartRequest($request);

        try {
            $productItem = $this->getProductItem($request);
            $availableStock = $productItem->quantity;
            $requestedQuantity = $request->quantity;

            if (auth()->check()) {
                $this->mergeGuestCartIntoDatabase(auth()->id());

                return $this->handleAuthCart($request, $productItem, $availableStock, $requestedQuantity);
            }

            return $this->handleGuestCart($request, $productItem, $availableStock, $requestedQuantity);
        } catch (\Exception $e) {
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
        $productItem = productItems::with('products')->where('id', $request->size_id)
            ->where('products_id', $request->product_id)
            ->first();

        if (! $productItem) {
            abort(response()->json([
                'success' => false,
                'message' => 'Product size not found.',
            ], 404));
        }

        return $productItem;
    }

    private function mergeGuestCartIntoDatabase(int $userId): void
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $item) {
            $productId = $item['product_id'] ?? null;
            $sizeId = $item['size_id'] ?? null;
            $quantity = (int) ($item['quantity'] ?? 1);

            if (! $productId || ! $sizeId) {
                continue;
            }

            $productItem = productItems::where('id', $sizeId)
                ->where('products_id', $productId)
                ->first();

            if (! $productItem) {
                continue;
            }

            $quantity = min($quantity, $productItem->quantity);

            $existingCartItem = shoppingCart::where('user_id', $userId)
                ->where('products_id', $productId)
                ->where('size_id', $sizeId)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->quantity = min(
                    $existingCartItem->quantity + $quantity,
                    $productItem->quantity
                );
                $existingCartItem->save();
            } else {
                shoppingCart::create([
                    'user_id' => $userId,
                    'products_id' => $productId,
                    'size_id' => $sizeId,
                    'quantity' => $quantity,
                ]);
            }
        }

        session()->forget('cart');
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

        $this->trackAddToCart($request, (int) $request->product_id, (int) $requestedQuantity);

        return response()->json([
            'success' => true,
            'message' => $message,
            'cartCount' => $cartCount,
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

        $this->trackAddToCart($request, (int) $request->product_id, (int) $requestedQuantity);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cartCount' => count($cart),
        ]);
    }

    private function trackAddToCart(Request $request, int $productId, int $quantity): void
    {
        app(AnalyticsService::class)->recordEvent(
            AnalyticsService::EVENT_ADD_TO_CART,
            $request,
            ['qty' => $quantity],
            $productId
        );
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
        $request->validate(['promo_code' => 'nullable|string|max:50']);

        $code = trim($request->input('promo_code', ''));

        if ($code === '') {
            session()->forget('promo_code');

            return redirect()->route('cart.index')->with('success', __('cart.promo_removed'));
        }

        $promo = discountCodes::where('code', $code)
            ->where('is_active', 1)
            ->where('expiry_date', '>', now())
            ->first();

        if (! $promo) {
            return redirect()->route('cart.index')->with('error', __('checkout.invalid_promo'));
        }

        session(['promo_code' => $code]);

        return redirect()->route('cart.index')->with('success', __('cart.promo_applied', ['percent' => $promo->discount_percentage]));
    }

    private function featuredProducts()
    {
        return products::with('productImages')
            ->where('is_active', 1)
            ->whereHas('category', fn ($q) => $q->where('is_active', 1))
            ->whereHas('type', fn ($q) => $q->where('is_active', 1))
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

        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->price - ($item->product->price * $item->product->sale / 100)) * $item->quantity;
        });

        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'featuredProducts' => $this->featuredProducts(),
            'appliedPromo' => session('promo_code'),
        ]);
    }

    private function renderGuestCart()
    {
        [$cartItems, $subtotal] = $this->buildGuestCartViewData();

        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'featuredProducts' => $this->featuredProducts(),
            'appliedPromo' => session('promo_code'),
        ]);
    }

    private function buildGuestCartViewData(): array
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

                $subtotal += ($product->price - ($product->price * $product->sale / 100)) * $item['quantity'];
            }
        }

        return [$cartItems, $subtotal];
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

        $productItem = productItems::where('id', $cartItem->size_id)
            ->where('products_id', $cartItem->products_id)
            ->firstOrFail();

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
        $productItem = productItems::where('id', $item['size_id'])
            ->where('products_id', $item['product_id'])
            ->first();

        if (! $productItem || $request->quantity > $productItem->quantity) {
            return redirect()->back()->with('error', __('cart.stock_limit', ['count' => $productItem->quantity ?? 0]));
        }

        $guestCart[$key]['quantity'] = $request->quantity;
        Session::put('cart', $guestCart);

        return redirect()->back()->with('success', __('cart.quantity_updated'));
    }
}
