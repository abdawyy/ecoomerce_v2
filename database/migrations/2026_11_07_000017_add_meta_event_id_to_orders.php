<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'meta_event_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('meta_event_id', 64)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'meta_event_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('meta_event_id');
            });
        }
    }
};
