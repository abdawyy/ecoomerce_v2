<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixMigrationsTableCommand extends Command
{
    protected $signature = 'project:fix-migrations-table';

    protected $description = 'Fix migrations.id missing AUTO_INCREMENT (common after SQL dump import)';

    public function handle(): int
    {
        if (! Schema::hasTable('migrations')) {
            $this->error('migrations table not found.');

            return self::FAILURE;
        }

        DB::statement('ALTER TABLE migrations MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');

        $next = (int) DB::table('migrations')->max('id') + 1;
        DB::statement("ALTER TABLE migrations AUTO_INCREMENT = {$next}");

        $this->info("migrations.id is now AUTO_INCREMENT (next id: {$next}).");

        return self::SUCCESS;
    }
}
