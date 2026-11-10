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
        $this->assertSame(OrderStatus::COMPLETED, OrderStatus::normalize('completed'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::normalize('cancelled'));
        $this->assertSame(OrderStatus::CANCELLED, OrderStatus::normalize('canceled'));
    }

    public function test_pending_list_filter_includes_processing(): void
    {
        $this->assertSame(
            OrderStatus::pendingValues(),
            OrderStatus::listFilterValues('Pending')
        );
        $this->assertContains(OrderStatus::PROCESSING, OrderStatus::listFilterValues('pending'));
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
