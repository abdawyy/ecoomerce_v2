<?php

namespace Database\Seeders;

use App\Models\Guide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AdminWebsiteGuideSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('guides')) {
            $this->command?->warn('guides table missing — run migrations first.');

            return;
        }

        Guide::updateOrCreate(
            ['slug' => 'admin-website-manual'],
            [
                'title_en' => __('admin_guide.title', [], 'en'),
                'title_ar' => __('admin_guide.title', [], 'ar'),
                'description_en' => __('admin_guide.description', [], 'en'),
                'description_ar' => __('admin_guide.description', [], 'ar'),
                'content_type' => 'html',
                'html_content_en' => __('admin_guide.html', [], 'en'),
                'html_content_ar' => __('admin_guide.html', [], 'ar'),
                'sort_order' => 0,
                'is_active' => false,
                'requires_auth' => false,
                'published_at' => now(),
            ]
        );

        Guide::updateOrCreate(
            ['slug' => 'sample-editable-guide'],
            [
                'title_en' => __('pdf_sample.title', [], 'en'),
                'title_ar' => __('pdf_sample.title', [], 'ar'),
                'description_en' => __('pdf_sample.description', [], 'en'),
                'description_ar' => __('pdf_sample.description', [], 'ar'),
                'content_type' => 'html',
                'html_content_en' => __('pdf_sample.html', [], 'en'),
                'html_content_ar' => __('pdf_sample.html', [], 'ar'),
                'sort_order' => 10,
                'is_active' => true,
                'requires_auth' => false,
                'published_at' => now(),
            ]
        );

        $this->command?->info('Seeded admin manual and editable sample guide.');
    }
}
