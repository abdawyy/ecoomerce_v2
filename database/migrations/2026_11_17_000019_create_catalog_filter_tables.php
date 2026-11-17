<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('catalog_filter_groups')) {
            Schema::create('catalog_filter_groups', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->boolean('is_visible')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('catalog_filter_options')) {
            Schema::create('catalog_filter_options', function (Blueprint $table) {
                $table->id();
                $table->string('group_key');
                $table->string('value')->nullable();
                $table->string('label_en')->nullable();
                $table->string('label_ar')->nullable();
                $table->decimal('min_price', 10, 2)->nullable();
                $table->decimal('max_price', 10, 2)->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
                $table->index('group_key');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_filter_options');
        Schema::dropIfExists('catalog_filter_groups');
    }
};
