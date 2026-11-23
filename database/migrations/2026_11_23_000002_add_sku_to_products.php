<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('products', 'sku')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('sku', 32)->nullable()->unique()->after('id');
            });
        }

        DB::table('products')
            ->where(function ($query) {
                $query->whereNull('sku')->orWhere('sku', '');
            })
            ->orderBy('id')
            ->pluck('id')
            ->each(function ($id) {
                DB::table('products')->where('id', $id)->update([
                    'sku' => sprintf('TL-%05d', (int) $id),
                ]);
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasColumn('products', 'sku')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn('sku');
        });
    }
};
