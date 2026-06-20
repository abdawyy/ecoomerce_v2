<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_views')) {
            Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id', 64)->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('referrer', 512)->nullable();
            $table->string('locale', 5)->nullable();
            $table->timestamp('viewed_at')->index();
            $table->index(['product_id', 'viewed_at']);
            });
        }

        if (! Schema::hasTable('page_views')) {
            Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 64)->index();
            $table->string('path', 255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id', 64)->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('locale', 5)->nullable();
            $table->timestamp('viewed_at')->index();
            $table->index(['page_key', 'viewed_at']);
            });
        }

        if (! Schema::hasTable('visitor_presence')) {
            Schema::create('visitor_presence', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('current_path', 255)->nullable();
            $table->string('current_page_key', 64)->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('locale', 5)->nullable();
            $table->timestamp('last_seen_at')->index();
            $table->timestamp('first_seen_at')->nullable();
            });
        }

        if (! Schema::hasTable('analytics_daily')) {
            Schema::create('analytics_daily', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->string('page_key', 64)->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->unsignedInteger('orders_count')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->unique(['date', 'product_id', 'page_key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_daily');
        Schema::dropIfExists('visitor_presence');
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('product_views');
    }
};
