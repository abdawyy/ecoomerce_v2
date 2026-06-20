<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('guides')) {
            Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('content_type', 16)->default('html');
            $table->string('file_path')->nullable();
            $table->longText('html_content_en')->nullable();
            $table->longText('html_content_ar')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_auth')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
            });
        }

        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'guide_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('guide_id')->nullable()->after('type_id');
            });
        }

        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('site_settings', 'accent_color')) {
                    $table->string('accent_color', 20)->nullable()->after('primary_color');
                }
                if (! Schema::hasColumn('site_settings', 'pdf_footer_en')) {
                    $table->string('pdf_footer_en')->nullable();
                    $table->string('pdf_footer_ar')->nullable();
                    $table->string('pdf_thank_you_en')->nullable();
                    $table->string('pdf_thank_you_ar')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'guide_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('guide_id');
            });
        }
        Schema::dropIfExists('guides');
    }
};
