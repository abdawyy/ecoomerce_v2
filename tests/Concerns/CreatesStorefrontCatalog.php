<?php

namespace Tests\Concerns;

use App\Models\addresses;
use App\Models\Category;
use App\Models\Cities;
use App\Models\discountCodes;
use App\Models\productItems;
use App\Models\products;
use App\Models\Type;
use App\Models\User;

trait CreatesStorefrontCatalog
{
    protected function makeType(array $overrides = []): Type
    {
        return Type::create(array_merge([
            'name' => 'Apparel',
            'is_active' => 1,
        ], $overrides));
    }

    protected function makeCategory(?Type $type = null, array $overrides = []): Category
    {
        $type ??= $this->makeType();

        return Category::create(array_merge([
            'name' => 'Dresses',
            'type_id' => $type->id,
            'is_active' => 1,
        ], $overrides));
    }

    protected function makeProduct(?Category $category = null, array $overrides = []): products
    {
        $category ??= $this->makeCategory();

        return products::create(array_merge([
            'name' => 'Linen Dress',
            'description' => 'A modest linen dress.',
            'price' => 200,
            'sale' => 10,
            'color' => 'black',
            'type_id' => $category->type_id,
            'category_id' => $category->id,
            'is_active' => 1,
        ], $overrides));
    }

    protected function makeSize(products $product, array $overrides = []): productItems
    {
        return productItems::create(array_merge([
            'products_id' => $product->id,
            'size' => 'M',
            'quantity' => 5,
        ], $overrides));
    }

    protected function makeCity(array $overrides = []): Cities
    {
        return Cities::create(array_merge([
            'name' => 'Cairo',
            'price' => 40,
            'is_active' => 1,
        ], $overrides));
    }

    protected function makePromo(array $overrides = []): discountCodes
    {
        return discountCodes::create(array_merge([
            'code' => 'WELCOME10',
            'discount_percentage' => 10,
            'expiry_date' => now()->toDateString(),
            'is_active' => 1,
        ], $overrides));
    }

    protected function makeAddress(User $user, array $overrides = []): addresses
    {
        return addresses::create(array_merge([
            'user_id' => $user->id,
            'address_line1' => 'Old street 1',
            'city' => (string) ($overrides['city'] ?? $this->makeCity()->id),
            'country' => 'Egypt',
            'phone_number' => '01000000000',
        ], $overrides));
    }

    protected function guestCartPayload(products $product, productItems $size, int $quantity = 1): array
    {
        $key = $product->id.'_'.$size->id;

        return [
            $key => [
                'product_id' => $product->id,
                'size_id' => $size->id,
                'quantity' => $quantity,
                'name' => $product->name,
                'price' => 999,
                'sale' => 0,
                'key' => $key,
            ],
        ];
    }
}
