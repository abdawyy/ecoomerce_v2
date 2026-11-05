<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_settings') && ! Schema::hasColumn('site_settings', 'free_shipping_min_amount')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->decimal('free_shipping_min_amount', 10, 2)->nullable();
            });
        }

        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'delivery_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('delivery_fee', 10, 2)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('site_settings') && Schema::hasColumn('site_settings', 'free_shipping_min_amount')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropColumn('free_shipping_min_amount');
            });
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'delivery_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('delivery_fee');
            });
        }
    }
};
