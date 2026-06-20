<?php

namespace Tests\Unit;

use App\Models\orderItems;
use App\Models\orders;
use App\Services\BrandingService;
use App\Services\PdfService;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class PdfServiceTotalsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_invoice_totals_calculates_subtotal_discount_and_grand_total(): void
    {
        $branding = Mockery::mock(BrandingService::class);
        $service = new PdfService($branding);

        $order = new orders(['total_amount' => 180]);
        $order->setRelation('orderItems', new Collection([
            new orderItems(['price' => 100, 'quantity' => 1]),
            new orderItems(['price' => 50, 'quantity' => 2]),
        ]));
        $order->setRelation('cities', (object) ['price' => 30]);
        $order->setRelation('discountCodes', (object) ['discount_percentage' => 10]);

        $totals = $service->invoiceTotals($order);

        $this->assertSame(150.0, $totals['subtotal']);
        $this->assertSame(30.0, $totals['delivery']);
        $this->assertSame(15.0, $totals['discountAmount']);
        $this->assertSame(10.0, $totals['discountPercent']);
        $this->assertSame(180.0, $totals['grandTotal']);
    }
}
