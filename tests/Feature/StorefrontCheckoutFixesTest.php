<?php

namespace Tests\Feature;

use App\Models\addresses;
use App\Models\discountCodes;
use App\Models\orderItems;
use App\Models\orders;
use App\Models\shoppingCart;
use App\Models\User;
use App\Services\GuestCartMergeService;
use App\Services\SeoService;
use App\Support\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesStorefrontCatalog;
use Tests\TestCase;

class StorefrontCheckoutFixesTest extends TestCase
{
    use CreatesStorefrontCatalog;
    use RefreshDatabase;

    public function test_cart_add_rejects_get_requests(): void
    {
        $this->get('/cart/add')->assertStatus(405);
    }

    public function test_inactive_product_cannot_be_added_to_cart(): void
    {
        $product = $this->makeProduct(null, ['is_active' => 0]);
        $size = $this->makeSize($product);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ])->assertStatus(422)->assertJson(['success' => false]);
    }

    public function test_guest_cart_merges_into_database_on_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $product = $this->makeProduct();
        $size = $this->makeSize($product);

        $this->withSession(['cart' => $this->guestCartPayload($product, $size, 2)])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect();

        $this->assertAuthenticatedAs($user);
        $this->assertSame([], session('cart', []));
        $this->assertDatabaseHas('shopping_cart', [
            'user_id' => $user->id,
            'products_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 2,
        ]);
    }

    public function test_guest_cart_merge_service_caps_at_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();
        $size = $this->makeSize($product, ['quantity' => 1]);

        session(['cart' => $this->guestCartPayload($product, $size, 9)]);
        app(GuestCartMergeService::class)->mergeForUser($user->id);

        $this->assertSame(1, (int) shoppingCart::where('user_id', $user->id)->value('quantity'));
        $this->assertNull(session('cart'));
    }

    public function test_cart_promo_uses_live_prices_and_discount(): void
    {
        $product = $this->makeProduct(null, ['price' => 200, 'sale' => 0]);
        $size = $this->makeSize($product);
        $this->makePromo(['code' => 'SAVE10', 'discount_percentage' => 10]);

        $this->withSession([
            'cart' => $this->guestCartPayload($product, $size, 2),
        ])->post(route('cart.promo'), [
            'promo_code' => 'SAVE10',
        ])->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('LE 400.00')
            ->assertSee('LE 360.00');
    }

    public function test_promo_is_valid_on_its_expiry_calendar_day(): void
    {
        $this->makePromo([
            'code' => 'TODAY',
            'expiry_date' => now()->toDateString(),
        ]);
        $this->makePromo([
            'code' => 'YESTERDAY',
            'expiry_date' => now()->subDay()->toDateString(),
        ]);

        $this->assertNotNull(discountCodes::findUsable('TODAY'));
        $this->assertNull(discountCodes::findUsable('YESTERDAY'));
    }

    public function test_checkout_updates_the_selected_saved_address(): void
    {
        $user = User::factory()->create(['is_active' => 1]);
        $product = $this->makeProduct();
        $size = $this->makeSize($product);
        $city = $this->makeCity();
        $first = $this->makeAddress($user, ['city' => (string) $city->id, 'address_line1' => 'First street']);
        $second = $this->makeAddress($user, ['city' => (string) $city->id, 'address_line1' => 'Second street']);

        shoppingCart::create([
            'user_id' => $user->id,
            'products_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->withSession(['checkout_token' => 'tok-1'])
            ->post(route('checkout.order'), [
                'checkout_token' => 'tok-1',
                'full_name' => $user->name,
                'address_line1' => 'Updated second',
                'city' => $city->id,
                'country' => 'Egypt',
                'phone_number' => '01111111111',
                'saved_address_id' => $second->id,
            ])
            ->assertRedirect();

        $this->assertSame('First street', $first->fresh()->address_line1);
        $this->assertSame('Updated second', $second->fresh()->address_line1);
        $this->assertSame($second->id, (int) orders::first()->address_id);
    }

    public function test_empty_saved_address_id_creates_a_new_address(): void
    {
        $user = User::factory()->create(['is_active' => 1]);
        $product = $this->makeProduct();
        $size = $this->makeSize($product);
        $city = $this->makeCity();
        $existing = $this->makeAddress($user, ['city' => (string) $city->id, 'address_line1' => 'Keep me']);

        shoppingCart::create([
            'user_id' => $user->id,
            'products_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->withSession(['checkout_token' => 'tok-2'])
            ->post(route('checkout.order'), [
                'checkout_token' => 'tok-2',
                'full_name' => $user->name,
                'address_line1' => 'Brand new street',
                'city' => $city->id,
                'country' => 'Egypt',
                'phone_number' => '01222222222',
            ])
            ->assertRedirect();

        $this->assertSame('Keep me', $existing->fresh()->address_line1);
        $this->assertSame(2, addresses::where('user_id', $user->id)->count());
        $this->assertNotSame($existing->id, (int) orders::first()->address_id);
    }

    public function test_cancelling_an_order_restores_stock_once(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();
        $size = $this->makeSize($product, ['quantity' => 3]);
        $city = $this->makeCity();

        $order = orders::create([
            'user_id' => $user->id,
            'total_amount' => 180,
            'status' => OrderStatus::PENDING,
            'city_id' => $city->id,
        ]);
        orderItems::create([
            'orders_id' => $order->id,
            'products_id' => $product->id,
            'quantity' => 2,
            'size_id' => $size->id,
            'size' => $size->size,
            'price' => 360,
        ]);

        $order->transitionTo(OrderStatus::CANCELLED);
        $this->assertSame(5, (int) $size->fresh()->quantity);
        $this->assertSame(OrderStatus::CANCELLED, $order->fresh()->status);

        $order->transitionTo(OrderStatus::CANCELLED);
        $this->assertSame(5, (int) $size->fresh()->quantity);
    }

    public function test_leaving_cancelled_deducts_stock_and_refuses_when_short(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();
        $size = $this->makeSize($product, ['quantity' => 1]);
        $city = $this->makeCity();

        $order = orders::create([
            'user_id' => $user->id,
            'total_amount' => 90,
            'status' => OrderStatus::CANCELLED,
            'city_id' => $city->id,
        ]);
        orderItems::create([
            'orders_id' => $order->id,
            'products_id' => $product->id,
            'quantity' => 2,
            'size_id' => $size->id,
            'size' => $size->size,
            'price' => 180,
        ]);

        try {
            $order->transitionTo(OrderStatus::PROCESSING);
            $this->fail('Expected insufficient stock to block the status change.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString($product->name, $e->getMessage());
        }

        $this->assertSame(1, (int) $size->fresh()->quantity);
        $this->assertSame(OrderStatus::CANCELLED, $order->fresh()->status);

        $size->update(['quantity' => 4]);
        $order->transitionTo(OrderStatus::PROCESSING);

        $this->assertSame(2, (int) $size->fresh()->quantity);
        $this->assertSame(OrderStatus::PROCESSING, $order->fresh()->status);
    }

    public function test_missing_product_and_category_return_404(): void
    {
        $this->get(route('product.show', 99999))->assertNotFound();
        $this->get(route('product.List', ['id' => 99999]))->assertNotFound();
    }

    public function test_product_open_graph_uses_product_copy(): void
    {
        $product = $this->makeProduct(null, ['name' => 'OG Dress', 'description' => 'Share this dress.']);
        $this->makeSize($product);
        $product = $product->fresh(['productItems', 'productImages', 'category', 'type']);

        $seo = app(SeoService::class)->forProduct($product)->resolve();

        $this->assertStringContainsString('OG Dress', (string) $seo['og_title']);
        $this->assertStringContainsString('Share this dress', (string) $seo['og_description']);

        $html = $this->get(route('product.show', $product->id))->assertOk()->getContent();
        $this->assertSame(1, preg_match_all('/<title>/', $html));
        $this->assertStringContainsString('Share this dress', $html);
    }

    public function test_pdp_arabic_strings_are_translated(): void
    {
        $product = $this->makeProduct();
        $this->makeSize($product);

        $this->withSession(['locale' => 'ar'])
            ->get(route('product.show', $product->id))
            ->assertOk()
            ->assertSee('أضف إلى السلة', false)
            ->assertSee('الوصف', false)
            ->assertSee('آراء العملاء', false);
    }

    public function test_reviews_require_a_completed_purchase(): void
    {
        $user = User::factory()->create(['is_active' => 1]);
        $product = $this->makeProduct();
        $size = $this->makeSize($product);
        $city = $this->makeCity();

        $this->actingAs($user)->postJson('/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great dress',
        ])->assertStatus(403);

        $order = orders::create([
            'user_id' => $user->id,
            'total_amount' => 180,
            'status' => OrderStatus::COMPLETED,
            'city_id' => $city->id,
        ]);
        orderItems::create([
            'orders_id' => $order->id,
            'products_id' => $product->id,
            'quantity' => 1,
            'size_id' => $size->id,
            'size' => $size->size,
            'price' => 180,
        ]);

        $this->actingAs($user)->postJson('/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great dress',
        ])->assertOk();
    }

    public function test_guest_cart_merges_on_register(): void
    {
        $product = $this->makeProduct();
        $size = $this->makeSize($product);

        $this->withSession(['cart' => $this->guestCartPayload($product, $size, 1)])
            ->post('/register', [
                'name' => 'Guest Buyer',
                'email' => 'guestbuyer@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect();

        $user = User::whereEmail('guestbuyer@example.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('shopping_cart', [
            'user_id' => $user->id,
            'products_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ]);
    }
}
