<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Admin;
use App\Models\orders;
use App\Models\User;
use App\Support\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesStorefrontCatalog;
use Tests\TestCase;

class AdminOrderStatusTest extends TestCase
{
    use CreatesStorefrontCatalog;
    use RefreshDatabase;

    public function test_status_change_emails_the_customer_and_flashes_success(): void
    {
        Mail::fake();

        $admin = $this->makeAdmin();
        $user = User::factory()->create(['email' => 'buyer@example.com']);
        $order = orders::create([
            'user_id' => $user->id,
            'total_amount' => 180,
            'status' => OrderStatus::PENDING,
            'city_id' => $this->makeCity()->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->from(route('order.list'))
            ->put(route('order.changeStatus', $order->id), [
                'status' => OrderStatus::OUT_FOR_DELIVERY,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', __('admin.order_status_updated_email_sent'));

        $this->assertSame(OrderStatus::OUT_FOR_DELIVERY, $order->fresh()->status);

        Mail::assertSent(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && OrderStatus::normalize($mail->order->status) === OrderStatus::OUT_FOR_DELIVERY;
        });
        Mail::assertNotSent(OrderConfirmationMail::class);
    }

    public function test_status_change_without_customer_email_flashes_error_and_keeps_status(): void
    {
        Mail::fake();

        $admin = $this->makeAdmin();
        $order = orders::create([
            'user_id' => null,
            'guest_id' => null,
            'total_amount' => 90,
            'status' => OrderStatus::PENDING,
            'city_id' => $this->makeCity()->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->from(route('order.list'))
            ->put(route('order.changeStatus', $order->id), [
                'status' => OrderStatus::PROCESSING,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', __('admin.order_status_updated_email_failed'));

        $this->assertSame(OrderStatus::PROCESSING, $order->fresh()->status);
        Mail::assertNothingSent();
    }

    public function test_unchanged_status_does_not_email(): void
    {
        Mail::fake();

        $admin = $this->makeAdmin();
        $user = User::factory()->create();
        $order = orders::create([
            'user_id' => $user->id,
            'total_amount' => 90,
            'status' => OrderStatus::PROCESSING,
            'city_id' => $this->makeCity()->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->put(route('order.changeStatus', $order->id), [
                'status' => OrderStatus::PROCESSING,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', __('admin.order_status_updated'));

        Mail::assertNothingSent();
    }

    protected function makeAdmin(): Admin
    {
        return Admin::query()->create([
            'username' => 'statusadmin',
            'email' => 'statusadmin@example.com',
            'password' => bcrypt('secret12'),
            'is_active' => true,
            'is_hidden' => false,
        ]);
    }
}
