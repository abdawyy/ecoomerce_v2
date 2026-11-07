<?php

namespace Tests\Unit;

use App\Models\orderItems;
use App\Models\orders;
use App\Models\products;
use App\Services\MetaPixelService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class MetaPixelServiceTest extends TestCase
{
    public function test_product_custom_data_uses_product_id_not_variant(): void
    {
        $service = new MetaPixelService;
        $product = new products(['price' => 200, 'sale' => 10]);
        $product->id = 42;

        $payload = $service->productCustomData($product, 2, 'evt-1');

        $this->assertSame(['42'], $payload['content_ids']);
        $this->assertSame('product', $payload['content_type']);
        $this->assertSame('EGP', $payload['currency']);
        $this->assertSame(360.0, $payload['value']);
        $this->assertSame('evt-1', $payload['event_id']);
        $this->assertSame('42', $payload['contents'][0]['id']);
        $this->assertSame(2, $payload['contents'][0]['quantity']);
    }

    public function test_order_custom_data_uses_order_item_product_ids_and_total(): void
    {
        $service = new MetaPixelService;
        $order = new orders(['total_amount' => 275.5]);
        $item = new orderItems(['products_id' => 7, 'quantity' => 2, 'price' => 200]);
        $order->setRelation('orderItems', new Collection([$item]));

        $payload = $service->orderCustomData($order, 'purchase-id');

        $this->assertSame(['7'], $payload['content_ids']);
        $this->assertSame(275.5, $payload['value']);
        $this->assertSame('purchase-id', $payload['event_id']);
        $this->assertSame(100.0, $payload['contents'][0]['item_price']);
    }
}
