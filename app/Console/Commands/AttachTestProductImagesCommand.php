<?php

namespace App\Console\Commands;

use App\Models\productImages;
use App\Models\products;
use App\Services\BrandingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AttachTestProductImagesCommand extends Command
{
    protected $signature = 'products:attach-test-images';

    protected $description = 'Cycle existing upload photos onto sellable products whose image file is missing';

    public function handle(BrandingService $branding): int
    {
        $sources = collect(File::files(storage_path('app/public/uploads')))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif'], true))
            ->map(fn ($file) => 'uploads/'.$file->getFilename())
            ->values();

        if ($sources->isEmpty()) {
            $this->error('No image files found in storage/app/public/uploads.');

            return self::FAILURE;
        }

        $assigned = 0;
        $skipped = 0;
        $index = 0;

        products::query()->sellable()->with('productImages')->orderBy('id')->chunkById(100, function ($products) use ($branding, $sources, &$assigned, &$skipped, &$index) {
            foreach ($products as $product) {
                $current = $product->productImages->first();
                if ($current && $branding->filesystemPath($current->images)) {
                    $skipped++;

                    continue;
                }

                $path = $sources[$index % $sources->count()];
                $index++;

                if ($current) {
                    $current->images = $path;
                    $current->save();
                } else {
                    productImages::query()->create([
                        'products_id' => $product->id,
                        'images' => $path,
                    ]);
                }

                $assigned++;
            }
        });

        $this->info("Assigned test images to {$assigned} products. Left {$skipped} unchanged.");

        return self::SUCCESS;
    }
}
