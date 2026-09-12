<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GuestUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\discountCodesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\userController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\SiteSeoController;
use App\Http\Controllers\SeoPublicController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\AdminGuideController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/legal', function () {
    return view('legal');
})->name('legal');

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/lang/{lang}', function ($lang) {
    if (!in_array($lang, ['en', 'ar'])) {
        $lang = 'en';
    }

    session()->put('locale', $lang);

    // Get previous URL
    $previous = url()->previous();

    // Check if previous URL is on our domain
    if (parse_url($previous, PHP_URL_HOST) === request()->getHost()) {
        return redirect($previous);
    }

    // Otherwise, redirect to home
    return redirect('/');
});




Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/send-contact', [ContactController::class, 'send'])->middleware('throttle:10,1')->name('contact.send');
Route::get('product/show/{id}', [ProductController::class, 'productWebShow'])->name('product.show');
Route::get('p/{slug}', [ProductController::class, 'productBySlug'])->name('product.slug');
Route::get('/sitemap.xml', [SeoPublicController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoPublicController::class, 'robots'])->name('robots');
Route::match(['get', 'post'], 'product/list/category/{id?}', [ProductController::class, 'productWebList'])->name('product.List');
Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}/download', [GuideController::class, 'download'])->name('guides.download');


Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login']);

if (config('hayah.allow_admin_register')) {
    Route::get('admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('admin/register', [AdminController::class, 'register']);
}

// Protect admin dashboard and other routes
Route::middleware(['admin'])->group(function () {

    //admin routes

    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/dashboard/stats', [AdminController::class, 'dashboardStats'])->name('admin.dashboard.stats');
    Route::get('admin/list', [AdminController::class, 'list'])->name('admin.list');
    Route::post('admin/list/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('admin/settings/branding', [SiteSettingsController::class, 'edit'])->name('admin.settings.branding');
    Route::post('admin/settings/branding', [SiteSettingsController::class, 'update'])->name('admin.settings.branding.update');
    Route::get('admin/settings/pdf-preview', [SiteSettingsController::class, 'pdfPreview'])->name('admin.settings.pdf.preview');

    Route::get('admin/guides/list', [AdminGuideController::class, 'list'])->name('guides.admin.list');
    Route::get('admin/guides/manual/download', [AdminGuideController::class, 'downloadManual'])->name('guides.admin.manual');
    Route::get('admin/guides/sample/preview', [AdminGuideController::class, 'previewSample'])->name('guides.admin.sample.preview');
    Route::get('admin/guides/preview/{id}', [AdminGuideController::class, 'previewGuide'])->name('guides.admin.preview');
    Route::match(['get', 'post'], 'admin/guides/edit/{id?}', [AdminGuideController::class, 'edit'])->name('guides.admin.edit');
    Route::post('admin/guides/delete/{id}', [AdminGuideController::class, 'delete'])->name('guides.admin.delete');
    Route::post('admin/guides/status/{id}', [AdminGuideController::class, 'toggleStatus'])->name('guides.admin.toggleStatus');
    Route::get('admin/settings/seo', [SiteSeoController::class, 'edit'])->name('admin.settings.seo');
    Route::post('admin/settings/seo', [SiteSeoController::class, 'update'])->name('admin.settings.seo.update');

    Route::get('admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('admin/analytics/live', [AnalyticsController::class, 'live'])->name('admin.analytics.live');
    Route::get('admin/analytics/export', [AnalyticsController::class, 'export'])->name('admin.analytics.export');
    Route::get('admin/analytics/product/{id}', [AnalyticsController::class, 'productDetail'])->name('admin.analytics.product');

    Route::post('/admin/status/{id}', [AdminController::class, 'toggleUserStatus'])->name('admin.toggleStatus');

    //types routes
    Route::match(['get', 'post'], 'admin/type/edit/{id?}', [TypeController::class, 'edit'])->name('type.edit');
    Route::get('admin/type/list', [TypeController::class, 'list'])->name('type.list');
    Route::post('admin/type/delete/{id}', [TypeController::class, 'delete'])->name('type.delete');
    Route::post('/admin/type/status/{id}', [TypeController::class, 'toggleUserStatus'])->name('admin.type.toggleStatus');

    // categories routes
    Route::match(['get', 'post'], 'admin/categories/edit/{id?}', [categoriesController::class, 'edit'])->name('categories.edit');
    Route::get('admin/categories/list', [categoriesController::class, 'list'])->name('categories.list');
    Route::post('admin/categories/delete/{id}', [categoriesController::class, 'delete'])->name('categories.delete');
    Route::post('/admin/categories/status/{id}', [categoriesController::class, 'toggleUserStatus'])->name('admin.categories.toggleStatus');

    // discount codes routes
    Route::match(['get', 'post'], 'admin/discountCodes/edit/{id?}', [discountCodesController::class, 'edit'])->name('discountCodes.edit');
    Route::get('admin/discountCodes/list', [discountCodesController::class, 'list'])->name('discountCodes.list');
    Route::post('admin/discountCodes/delete/{id}', [discountCodesController::class, 'delete'])->name('discountCodes.delete');
    Route::post('/admin/discountCodes/status/{id}', [discountCodesController::class, 'toggleUserStatus'])->name('admin.discountCodes.toggleStatus');


    // product routes
    Route::match(['get', 'post'], 'admin/products/edit/{id?}', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('admin/products/list', [ProductController::class, 'list'])->name('products.list');
    Route::post('admin/products/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');
    Route::post('admin/products/image/delete/{id}', [ProductController::class, 'deleteImage'])->name('image.delete');
    Route::post('/admin/products/status/{id}', [ProductController::class, 'toggleUserStatus'])->name('admin.products.toggleStatus');
    Route::post('/admin/products/toggleHighestStatus/{id}', [ProductController::class, 'toggleHighestStatus'])->name('admin.products.toggleHighestStatus');

    // cities routes
    Route::match(['get', 'post'], 'admin/cities/edit/{id?}', [CitiesController::class, 'edit'])->name('cities.edit');
    Route::get('admin/cities/list', [CitiesController::class, 'list'])->name('cities.list');
    Route::post('/admin/cities/status/{id}', [CitiesController::class, 'toggleUserStatus'])->name('admin.cities.toggleStatus');

    //user
    Route::get('admin/users/list', [userController::class, 'list'])->name('users.list');

    //orders
    Route::get('admin/order/list', [AdminController::class, 'orderList'])->name('order.list');
    // Show order details
    Route::get('/admin/order/edit/{id}', [AdminController::class, 'orderShow'])->name('order.show');
    Route::get('/admin/order/{id}/invoice', [AdminController::class, 'downloadInvoice'])->name('order.invoice');
    // Update order status
    Route::put('admin/order/{id}/status', [AdminController::class, 'changeOrderStatus'])->name('order.changeStatus');

    Route::get('/admin/user/edit/{id}', [AdminController::class, 'userShow'])->name('user.show');
    Route::post('/admin/user/status/{id}', [userController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');



    Route::get('/admin/contact/list', [ContactController::class, 'list'])->name('admin.contact.list');

    Route::get('/admin/reviews/{productId}', [ReviewController::class, 'list'])->name('admin.reviews.byProduct');
    Route::post('/admin/reviews/status/{id}', [ReviewController::class, 'toggleUserStatus'])->name('admin.reviews.toggleStatus');



    Route::get('admin/guest/list', [GuestUserController::class, 'list'])->name('admin.guest.list');
    Route::get('/admin/guest/edit/{id}', [GuestUserController::class, 'guestShow'])->name('admin.guest.show');
















    // Other admin routes...
});
// Route::get('/admin/dashboard', function () {
//     return view('admin.index');
// });







Route::match(['get', 'post'], '/cart/add', [CartController::class, 'add']);
Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo');
Route::post('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->middleware('auth')->name('cart.update');
Route::post('/cart/guest/{key}/quantity', [CartController::class, 'updateGuestQuantity'])->name('cart.guest.update');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::redirect('/checkout/address', '/checkout', 301);
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/{id}', [CartController::class, 'delete'])->middleware('auth')->name('cart.delete');
Route::get('/checkout/address', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/order', [CheckoutController::class, 'order'])->middleware('throttle:10,1')->name('checkout.order');
Route::post('/checkout/receipt/{order}/notify', [CheckoutController::class, 'notifyReceipt'])->middleware('throttle:12,1')->name('checkout.receipt.notify');
Route::get('/checkout/receipt/{order}/invoice', [CheckoutController::class, 'receiptInvoice'])->name('checkout.receipt.invoice');
Route::get('/checkout/receipt/{order}', [CheckoutController::class, 'receipt'])->name('checkout.receipt');
Route::post('/cart/guest/{key}', [CartController::class, 'deleteGuest'])->name('cart.guest.delete');


Route::middleware(['auth', 'active.user'])->group(function () {
    Route::redirect('/dashboard', '/account');
    Route::redirect('/user/profile', '/account/profile');
});

Route::middleware(['auth', 'active.user'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [AccountController::class, 'invoice'])->name('orders.invoice');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{id}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::patch('/addresses/{id}/default', [AccountController::class, 'setDefaultAddress'])->name('addresses.default');
    Route::delete('/addresses/{id}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AccountController::class, 'updatePassword'])->name('profile.password');
    Route::get('/reviews', [AccountController::class, 'reviews'])->name('reviews');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'active.user'])->group(function () {

    // cart routes & checkout routes

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

});
