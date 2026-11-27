<?php

namespace Tests\Unit;

use App\Support\OrderStatus;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    public function test_it_canonicalizes_mixed_status_labels(): void
    {
        $this->assertSame(OrderStatus::PENDING, OrderStatus::normalize('pending'));
        $this->assertSame(OrderStatus::PROCESSING, OrderStatus::normalize('processing'));
        $this->assertSame(OrderStatus::OUT_FOR_DELIVERY, OrderStatus::normalize('out_for_delivery'));
        $this->assertSame(OrderStatus::OUT_FOR_DELIVERY, OrderStatus::normalize('out for delivery'));
        $this->assertSame(OrderStatus::COMPLETED, OrderStatus::normalize('completed'));
        $this->assertSame(OrderStatus::COMPLETED, OrderStatus::normalize('complate'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::normalize('cancelled'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::normalize('canceled'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::normalize('cancel'));
    }

    public function test_list_filters_are_per_status(): void
    {
        $this->assertSame(
            OrderStatus::aliases('Pending'),
            OrderStatus::listFilterValues('Pending')
        );
        $this->assertContains(OrderStatus::OUT_FOR_DELIVERY, OrderStatus::listFilterValues('out_for_delivery'));
        $this->assertNotContains(OrderStatus::PROCESSING, OrderStatus::listFilterValues('pending'));
        $this->assertContains(OrderStatus::OUT_FOR_DELIVERY, OrderStatus::pendingValues());
    }

    public function test_bell_total_excludes_new_users_and_guests(): void
    {
        $this->assertSame(5, \App\Support\AdminNav::notificationTotal([
            'pending_orders' => 2,
            'unread_messages' => 3,
            'new_users' => 10,
            'new_guests' => 8,
        ]));
    }
}
