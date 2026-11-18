<?php

namespace Database\Seeders;

use App\Models\productImages;
use App\Models\SiteSetting;
use App\Services\BrandingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StorefrontContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBranding();
        $this->seedTypes();
        $this->seedCities();
        $this->seedCollectionImages();
    }

    private function seedBranding(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $values = [
            'site_name' => 'truelabelegypt',
            'accent_color' => '#000000',
            'invoice_notes_en' => "Payment method: cash on delivery\r\nDelivery within 3–5 business days\r\nQuestions? Contact us using the email or phone in the header.",
            'invoice_notes_ar' => "طريقة الدفع: نقداً عند الاستلام\r\nالتوصيل خلال 3–5 أيام عمل\r\nللاستفسار: تواصل عبر البريد أو الهاتف في أعلى الفاتورة.",
            'cart_policy_title_en' => 'Sales & Inspection Policy',
            'cart_policy_title_ar' => 'سياسة البيع والمعاينة',
            'cart_policy_body_en' => "We carefully select our products and clearly explain materials, details, and sizes with full transparency on the website.\r\n\r\nYou can inspect the product on delivery and check the material, details, and size before completing receipt.\r\n\r\nOur goal is a reliable, clear, and reassuring shopping experience — your trust and satisfaction are the foundation of how we work.",
            'cart_policy_body_ar' => "نحرص على تقديم منتجات مختارة بعناية، مع توضيح الخامات والتفاصيل والمقاسات بكل دقة وشفافية على الموقع.\r\n\r\nيمكنك معاينة المنتج عند الاستلام والتأكد من الخامة والتفاصيل والمقاس قبل إتمام الاستلام.\r\n\r\nهدفنا أن تحصلي على تجربة شراء موثوقة، واضحة ومطمئنة، لأن ثقتك ورضاك هما أساس تعاملنا.",
            'home_category_1_id' => 4,
            'home_category_2_id' => 5,
            'home_tile_1_title_en' => 'Men',
            'home_tile_1_title_ar' => 'بنطال',
            'home_tile_2_title_en' => 'Nights shirts',
            'home_tile_2_title_ar' => 'قمصان نوم',
            'free_shipping_min_amount' => '2500.00',
            'instagram_url' => 'https://www.instagram.com/',
            'facebook_url' => 'https://www.facebook.com/',
            'tiktok_url' => 'https://www.tiktok.com/',
            'youtube_url' => 'https://www.youtube.com/',
        ];

        $imageValues = [
            'logo_path' => 'branding/logo_1794344647.png',
            'hero_image_path' => 'branding/hero_image_1790763530.jpeg',
            'hero_image_mobile_path' => 'branding/hero_image_mobile_sample.jpg',
            'category_image_1_path' => 'branding/category_image_1_1789379414.jpeg',
            'category_image_2_path' => 'branding/category_image_2_1789379414.jpeg',
        ];

        foreach ($imageValues as $column => $path) {
            if ($this->publishSeedFile($path)) {
                $values[$column] = $path;
            }
        }

        $settings = SiteSetting::current();

        foreach ($values as $column => $value) {
            if ($value === null || $value === '' || ! Schema::hasColumn('site_settings', $column)) {
                continue;
            }

            if (in_array($column, ['home_category_1_id', 'home_category_2_id'], true)
                && Schema::hasTable('categories')
                && ! DB::table('categories')->where('id', $value)->exists()) {
                continue;
            }

            $settings->{$column} = $value;
        }

        $settings->save();
        app(BrandingService::class)->clearCache();
    }

    private function seedTypes(): void
    {
        if (! Schema::hasTable('type')) {
            return;
        }

        $now = now();

        foreach ([1 => 'men', 2 => 'women'] as $id => $name) {
            $exists = DB::table('type')->where('id', $id)->exists();
            $payload = [
                'name' => $name,
                'is_active' => 1,
                'updated_at' => $now,
            ];

            if ($exists) {
                DB::table('type')->where('id', $id)->update($payload);
                continue;
            }

            DB::table('type')->insert($payload + [
                'id' => $id,
                'created_at' => $now,
            ]);
        }

        if (Schema::hasTable('categories')) {
            DB::table('categories')->whereNotIn('type_id', [1, 2])->update(['type_id' => 2]);
        }

        if (Schema::hasTable('products')) {
            DB::table('products')
                ->whereNotNull('type_id')
                ->whereNotIn('type_id', [1, 2])
                ->update(['type_id' => 2]);
        }

        DB::table('type')->whereNotIn('id', [1, 2])->delete();
    }

    private function seedCities(): void
    {
        if (! Schema::hasTable('cities')) {
            return;
        }

        $path = database_path('seeders/data/cities.json');
        if (! is_file($path)) {
            return;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            if (! isset($row['id'], $row['name'])) {
                continue;
            }

            DB::table('cities')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'price' => $row['price'] ?? 0,
                    'is_active' => $row['is_active'] ?? 1,
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ]
            );
        }
    }

    private function seedCollectionImages(): void
    {
        if (! Schema::hasTable('product_images') || ! Schema::hasTable('products')) {
            return;
        }

        $path = database_path('seeders/files/collection_images.json');
        if (! is_file($path)) {
            return;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            return;
        }

        $keepImageIds = [];
        $orderedProductIds = [];
        $now = now();

        foreach (array_values($rows) as $index => $row) {
            $sourceId = (int) ($row['products_id'] ?? 0);
            $targetId = $index + 1;
            $imagePath = (string) ($row['images'] ?? '');
            if ($sourceId < 1 || $imagePath === '') {
                continue;
            }

            $productId = $this->resolveProductId($sourceId, $targetId);
            if ($productId === null) {
                continue;
            }

            if (! $this->publishSeedFile($imagePath)) {
                continue;
            }

            $existing = productImages::query()
                ->where('products_id', $productId)
                ->orderBy('id')
                ->first();

            if ($existing) {
                $existing->images = $imagePath;
                $existing->save();
                $keepImageIds[] = $existing->id;
            } else {
                $keepImageIds[] = productImages::query()->create([
                    'products_id' => $productId,
                    'images' => $imagePath,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->id;
            }

            $orderedProductIds[] = $productId;
        }

        $orderedProductIds = array_values(array_unique($orderedProductIds));

        if ($orderedProductIds === []) {
            return;
        }

        if ($keepImageIds !== []) {
            productImages::query()->whereNotIn('id', $keepImageIds)->delete();
        }

        $this->pruneExtraProducts($orderedProductIds);
        $this->remapCategories($orderedProductIds);
        $this->remapProducts($orderedProductIds);
        $this->collapseToTwoCategories();
    }

    private function pruneExtraProducts(array $keepProductIds): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (['order_items' => 'products_id', 'reviews' => 'product_id', 'shopping_cart' => 'products_id', 'product_items' => 'products_id', 'product_images' => 'products_id'] as $table => $column) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->whereNotIn($column, $keepProductIds)->delete();
        }

        DB::table('products')->whereNotIn('id', $keepProductIds)->delete();

        Schema::enableForeignKeyConstraints();
    }

    private function resolveProductId(int $sourceId, int $targetId): ?int
    {
        if (DB::table('products')->where('id', $sourceId)->exists()) {
            return $sourceId;
        }

        if (DB::table('products')->where('id', $targetId)->exists()) {
            return $targetId;
        }

        return null;
    }

    private function remapCategories(array $orderedProductIds): void
    {
        if (! Schema::hasTable('categories')) {
            return;
        }

        $orderedCategoryIds = [];
        foreach ($orderedProductIds as $productId) {
            $categoryId = (int) DB::table('products')->where('id', $productId)->value('category_id');
            if ($categoryId > 0 && ! in_array($categoryId, $orderedCategoryIds, true)) {
                $orderedCategoryIds[] = $categoryId;
            }
        }

        if ($orderedCategoryIds === []) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        DB::table('categories')->whereNotIn('id', $orderedCategoryIds)->delete();

        $offset = 20000;
        $map = [];
        foreach ($orderedCategoryIds as $index => $oldId) {
            $map[$oldId] = $index + 1;
            $this->moveCategoryId($oldId, $oldId + $offset);
        }
        foreach ($orderedCategoryIds as $oldId) {
            $this->moveCategoryId($oldId + $offset, $map[$oldId]);
        }

        $this->resetAutoIncrement('categories');
        Schema::enableForeignKeyConstraints();

        $this->remapHomeCategoryIds($map);
    }

    private function moveCategoryId(int $from, int $to): void
    {
        if ($from === $to) {
            return;
        }

        DB::table('categories')->where('id', $from)->update(['id' => $to]);
        DB::table('products')->where('category_id', $from)->update(['category_id' => $to]);
    }

    private function collapseToTwoCategories(): void
    {
        if (! Schema::hasTable('categories') || ! Schema::hasTable('products')) {
            return;
        }

        $nightId = $this->categoryIdByName(['night shirts', 'nightshirts']);
        $pantsId = $this->categoryIdByName(['pants pajamas', 'pants pajama']);

        if ($nightId === null || $pantsId === null) {
            return;
        }

        $nightNames = ['night shirts', 'nightshirts', 'robes', 'robe', 'nightgown'];
        $pantsNames = ['pants pajamas', 'pants pajama', 'short pajamas', 'pajamas 3pieces', 'pajama three pieces'];

        foreach (DB::table('products')->get(['id', 'category_id']) as $product) {
            $categoryName = strtolower(trim((string) DB::table('categories')->where('id', $product->category_id)->value('name')));
            $targetId = $pantsId;
            if (in_array($categoryName, $nightNames, true) || (int) $product->category_id === $nightId) {
                $targetId = $nightId;
            } elseif (in_array($categoryName, $pantsNames, true) || (int) $product->category_id === $pantsId) {
                $targetId = $pantsId;
            }

            if ((int) $product->category_id !== $targetId) {
                DB::table('products')->where('id', $product->id)->update(['category_id' => $targetId]);
            }
        }

        Schema::disableForeignKeyConstraints();
        DB::table('categories')->whereNotIn('id', [$nightId, $pantsId])->delete();

        $this->moveCategoryId($nightId, 21001);
        $this->moveCategoryId($pantsId === $nightId ? 21001 : $pantsId, 21002);
        $this->moveCategoryId(21001, 1);
        $this->moveCategoryId(21002, 2);
        $nightId = 1;
        $pantsId = 2;

        $this->resetAutoIncrement('categories');
        Schema::enableForeignKeyConstraints();

        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $settings = SiteSetting::current();
        $changed = false;
        if (Schema::hasColumn('site_settings', 'home_category_1_id')) {
            $settings->home_category_1_id = $pantsId;
            $changed = true;
        }
        if (Schema::hasColumn('site_settings', 'home_category_2_id')) {
            $settings->home_category_2_id = $nightId;
            $changed = true;
        }
        if ($changed) {
            $settings->save();
            app(BrandingService::class)->clearCache();
        }
    }

    private function categoryIdByName(array $names): ?int
    {
        foreach (DB::table('categories')->get(['id', 'name']) as $category) {
            if (in_array(strtolower(trim((string) $category->name)), $names, true)) {
                return (int) $category->id;
            }
        }

        return null;
    }

    private function remapHomeCategoryIds(array $categoryMap): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $settings = SiteSetting::current();
        $changed = false;
        foreach (['home_category_1_id' => 4, 'home_category_2_id' => 5] as $field => $oldId) {
            if (! Schema::hasColumn('site_settings', $field) || ! isset($categoryMap[$oldId])) {
                continue;
            }

            $settings->{$field} = $categoryMap[$oldId];
            $changed = true;
        }

        if ($changed) {
            $settings->save();
            app(BrandingService::class)->clearCache();
        }
    }

    private function remapProducts(array $orderedProductIds): void
    {
        Schema::disableForeignKeyConstraints();

        $offset = 20000;
        foreach ($orderedProductIds as $currentId) {
            $this->moveProductId($currentId, $currentId + $offset);
        }
        foreach (array_values($orderedProductIds) as $index => $currentId) {
            $this->moveProductId($currentId + $offset, $index + 1);
        }

        $this->resetAutoIncrement('products');
        Schema::enableForeignKeyConstraints();
    }

    private function moveProductId(int $from, int $to): void
    {
        if ($from === $to) {
            return;
        }

        DB::table('products')->where('id', $from)->update(['id' => $to]);

        foreach (['product_images' => 'products_id', 'product_items' => 'products_id', 'order_items' => 'products_id', 'shopping_cart' => 'products_id', 'reviews' => 'product_id'] as $table => $column) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->where($column, $from)->update([$column => $to]);
        }
    }

    private function resetAutoIncrement(string $table): void
    {
        $max = (int) DB::table($table)->max('id');
        DB::statement('ALTER TABLE '.$table.' AUTO_INCREMENT = '.($max + 1));
    }

    private function publishSeedFile(string $relative): bool
    {
        $relative = str_replace('\\', '/', $relative);
        if ($relative === '' || str_contains($relative, '..')) {
            return false;
        }

        $source = database_path('seeders/files/'.$relative);
        if (! is_file($source)) {
            return false;
        }

        $destinations = [
            storage_path('app/public/'.$relative),
            public_path('storage/'.$relative),
        ];

        foreach ($destinations as $destination) {
            $directory = dirname($destination);
            if (! is_dir($directory) && ! @mkdir($directory, 0775, true) && ! is_dir($directory)) {
                return false;
            }

            if (! @copy($source, $destination) && ! is_file($destination)) {
                return false;
            }
        }

        return true;
    }
}
