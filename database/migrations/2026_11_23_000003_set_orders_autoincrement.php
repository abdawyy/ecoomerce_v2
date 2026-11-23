<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->setAutoIncrement(4332);
    }

    public function down(): void
    {
        $this->setAutoIncrement(1);
    }

    protected function setAutoIncrement(int $minimum): void
    {
        if (! Schema::hasTable('orders') || Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $max = (int) DB::table('orders')->max('id');
        $next = max($max + 1, $minimum);
        $table = Schema::getConnection()->getTablePrefix().'orders';

        DB::statement('ALTER TABLE `'.$table.'` AUTO_INCREMENT = '.$next);
    }
};
