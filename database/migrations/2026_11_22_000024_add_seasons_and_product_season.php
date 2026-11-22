<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seasons')) {
            Schema::create('seasons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        foreach (['Winter', 'Summer'] as $name) {
            $exists = DB::table('seasons')->where('name', $name)->exists();
            if (! $exists) {
                DB::table('seasons')->insert([
                    'name' => $name,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'season_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('season_id')->nullable()->after('type_id');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'type_id') && Schema::getConnection()->getDriverName() === 'mysql') {
            $column = collect(DB::select('SHOW COLUMNS FROM `categories` LIKE ?', ['type_id']))->first();
            if ($column && strtoupper((string) ($column->Null ?? 'NO')) !== 'YES') {
                $type = $column->Type ?: 'int';
                DB::statement("ALTER TABLE `categories` MODIFY `type_id` {$type} NULL");
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'season_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('season_id');
            });
        }

        Schema::dropIfExists('seasons');
    }
};
