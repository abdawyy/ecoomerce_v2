<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_settings')) {
            return;
        }

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('tagline_en')->nullable();
            $table->string('tagline_ar')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('logo_dark_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('og_image_path')->nullable();
            $table->string('footer_logo_path')->nullable();
            $table->string('placeholder_product_path')->nullable();
            $table->string('logo_alt_en')->nullable();
            $table->string('logo_alt_ar')->nullable();
            $table->string('primary_color', 20)->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
