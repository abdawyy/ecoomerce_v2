<?php

namespace Tests\Unit;

use App\Models\productItems;
use App\Models\products;
use App\Services\BrandingService;
use App\Services\SeoService;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class SeoProductJsonLdTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_json_ld_is_out_of_stock_when_quantities_are_zero(): void
    {
        $ld = $this->jsonLdForQuantities([0, 0]);

        $this->assertSame('https://schema.org/OutOfStock', $ld['offers']['availability']);
    }

    public function test_json_ld_is_in_stock_when_any_size_has_quantity(): void
    {
        $ld = $this->jsonLdForQuantities([0, 2]);

        $this->assertSame('https://schema.org/InStock', $ld['offers']['availability']);
    }

    protected function jsonLdForQuantities(array $quantities): array
    {
        $branding = Mockery::mock(BrandingService::class);
        $branding->shouldReceive('placeholderProductUrl')->andReturn('https://example.test/p.png');

        $product = new products([
            'name' => 'Linen Dress',
            'description' => 'A modest linen dress.',
            'price' => 200,
            'sale' => 10,
        ]);
        $product->setRelation('productImages', new Collection);
        $product->setRelation('productItems', new Collection(array_map(
            fn ($qty) => new productItems(['quantity' => $qty]),
            $quantities
        )));

        return (new SeoService)->productJsonLd($product, $branding, 'https://example.test/p/linen');
    }
}
