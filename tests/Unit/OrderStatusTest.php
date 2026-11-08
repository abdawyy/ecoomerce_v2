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
}
