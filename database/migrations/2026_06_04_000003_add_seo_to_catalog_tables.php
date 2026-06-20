<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['products', 'categories', 'type'] as $tableName) {
            if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'slug')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->string('slug')->nullable()->unique();
                $table->string('meta_title_en')->nullable();
                $table->string('meta_title_ar')->nullable();
                $table->text('meta_description_en')->nullable();
                $table->text('meta_description_ar')->nullable();
                $table->string('meta_keywords_en')->nullable();
                $table->string('meta_keywords_ar')->nullable();
                $table->string('og_image_path')->nullable();
                $table->boolean('is_indexable')->default(true);
            });
        }
    }

    public function down(): void
    {
        foreach (['products', 'categories', 'type'] as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'slug')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'slug', 'meta_title_en', 'meta_title_ar', 'meta_description_en', 'meta_description_ar',
                    'meta_keywords_en', 'meta_keywords_ar', 'og_image_path', 'is_indexable',
                ]);
            });
        }
    }
};
