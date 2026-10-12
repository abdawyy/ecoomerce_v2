# Meta Pixel on the Hayah storefront

Pixel ID: `3262657207254492`. It runs on the customer storefront only. Admin pages do not load it.

## Where the code lives

| Event | Fires when | File |
|-------|-----------|------|
| `PageView` | Every storefront page load | `resources/views/components/web/layout.blade.php` |
| `ViewContent` | A product page opens | `resources/views/product/show.blade.php` |
| `AddToCart` | `/cart/add` returns `success` | `resources/views/product/show.blade.php` (`addToCart()`) |
| `InitiateCheckout` | The checkout / shipping page opens | `resources/views/checkout/address.blade.php` |
| `Purchase` | The order receipt page opens for the first time | `resources/views/checkout/receipt.blade.php` |

## How it is wired

1. **Base code** – `components/web/layout.blade.php` loads `fbevents.js` in `<head>`, calls `fbq('init', …)` and `fbq('track', 'PageView')`.
2. **Helper** – the same file defines `window.HayahPixel.track(event, data, options)`. It wraps `fbq('track', …)` in a `try/catch`, so a blocked pixel (for example by an ad blocker) never breaks the page. The optional `options` argument is passed as `fbq`'s fourth argument, for example `{ eventID: '…' }`.
3. **Page events** – each page sends its own script through the layout's named slot:

   ```blade
   <x-slot:scripts>
   <script>
       window.HayahPixel.track('ViewContent', { … });
   </script>
   </x-slot:scripts>
   ```

   The layout prints that slot after the footer (after jQuery, Bootstrap and toastr), using `{!! $scripts ?? '' !!}`.

   > Do **not** use `@push('scripts')` inside `<x-web.layout>` for pixel code. The push runs inside the component slot and never reaches the layout's `@stack`, so the event silently does not fire.

## Event payloads

All values are in `EGP`.

- **ViewContent** – `content_ids: [productId]`, `content_type: 'product'`, `content_name`, `value` (the unit price after sale).
- **AddToCart** – the same fields plus `contents: [{ id, quantity }]`; `value` = unit price × quantity.
- **InitiateCheckout** – `value` (order total), `num_items`, `content_ids` (product IDs in the cart).
- **Purchase** – `value` (order total), `order_id`, `content_ids: [orderId]`, sent with `eventID: 'purchase-<orderId>'`.

## Purchase: once per order

Every order must produce exactly one `Purchase`. Three layers guard against duplicates:

1. **Server flag (main guard)** – `CheckoutController::receipt()` sets
   `trackPixelPurchase = cache()->add('pixel-purchase-sent-<orderId>', 1, 90 days)`.
   `cache()->add` returns `true` only the first time the key is written. On every later visit (refresh, new tab, back button, a reopened mobile or in-app browser, or a different device) the receipt renders without the `Purchase` script.
2. **Browser flag** – `localStorage['hayah_pixel_purchase_<orderId>']` blocks a second fire from the same browser. It survives closing the tab, unlike the old `sessionStorage` flag.
3. **Event ID** – `eventID: 'purchase-<orderId>'` lets Meta deduplicate the event. You will need this ID if Conversions API (server events) is added later.

> The server flag uses the Laravel cache (`CACHE_DRIVER`, default `file`). Running `php artisan cache:clear` resets the flags. After that, revisiting an old receipt could fire one more `Purchase` from a browser that has no `localStorage` flag.

## If Meta still shows more purchases than orders

The code now sends one `Purchase` per order. If Events Manager still shows double counts, the extra events come from Meta-side settings:

- **Events Manager → Data sources → your pixel → Settings → Event setup tool**: delete any `Purchase` configured on a URL (for example `/checkout/receipt`) or on a button click such as “Place order”. That fires a second `Purchase` on top of the code.
- **Automatic events / “Track events automatically without code”**: turn this off so Meta does not infer purchases from button clicks.
- **Partner integrations / a second pixel install** (Google Tag Manager, a plugin, a custom head script): make sure the pixel is installed only once. Meta Pixel Helper should show **one** pixel ID on the page.

## How to test

1. Install the **Meta Pixel Helper** Chrome extension, or use **Events Manager → Test events**.
2. Open a product: expect `PageView` and `ViewContent`.
3. Add to cart: expect `AddToCart`.
4. Go to checkout: expect `InitiateCheckout`.
5. Place an order: on the receipt page expect one `Purchase`.
6. Refresh the receipt and open it in a new tab: expect **no** new `Purchase`.

After editing any Blade file on the server, run `php artisan view:clear`.
