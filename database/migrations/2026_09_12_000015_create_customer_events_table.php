<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_events')) {
            Schema::create('customer_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_name', 64)->index();
                $table->string('session_id', 64)->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('guest_id')->nullable()->index();
                $table->unsignedBigInteger('product_id')->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->string('device', 16)->nullable()->index();
                $table->string('traffic_source', 32)->nullable()->index();
                $table->string('locale', 5)->nullable();
                $table->json('properties')->nullable();
                $table->timestamp('occurred_at')->index();
                $table->index(['event_name', 'occurred_at']);
            });
        }

        if (Schema::hasTable('page_views')) {
            Schema::table('page_views', function (Blueprint $table) {
                if (! Schema::hasColumn('page_views', 'referrer')) {
                    $table->string('referrer', 512)->nullable()->after('locale');
                }
                if (! Schema::hasColumn('page_views', 'device')) {
                    $table->string('device', 16)->nullable()->index()->after('referrer');
                }
                if (! Schema::hasColumn('page_views', 'traffic_source')) {
                    $table->string('traffic_source', 32)->nullable()->index()->after('device');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('page_views')) {
            Schema::table('page_views', function (Blueprint $table) {
                foreach (['referrer', 'device', 'traffic_source'] as $column) {
                    if (Schema::hasColumn('page_views', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('customer_events');
    }
};
