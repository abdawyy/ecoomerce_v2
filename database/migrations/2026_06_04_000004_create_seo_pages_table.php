<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seo_pages')) {
            Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('slug')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_ar')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->string('meta_keywords_en')->nullable();
            $table->string('meta_keywords_ar')->nullable();
            $table->string('og_image_path')->nullable();
            $table->boolean('is_indexable')->default(true);
            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
