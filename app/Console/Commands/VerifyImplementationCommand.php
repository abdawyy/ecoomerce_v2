<?php

namespace App\Console\Commands;

use App\Support\ImplementationManifest;
use Illuminate\Console\Command;

class VerifyImplementationCommand extends Command
{
    protected $signature = 'project:verify';

    protected $description = 'Verify Tasks 1–5 implementation files and key routes exist (no database required)';

    public function handle(): int
    {
        $missing = [];

        foreach (ImplementationManifest::requiredFiles() as $path => $label) {
            if (! is_file(base_path($path))) {
                $missing[] = "{$path} ({$label})";
            }
        }

        if ($missing !== []) {
            $this->error('Missing '.count($missing).' file(s):');
            foreach ($missing as $line) {
                $this->line('  - '.$line);
            }

            return self::FAILURE;
        }

        $this->info('All '.count(ImplementationManifest::requiredFiles()).' implementation files present.');

        $routeErrors = [];
        $routes = ImplementationManifest::requiredRoutes();

        foreach ($routes as $name) {
            if (! \Illuminate\Support\Facades\Route::has($name)) {
                $routeErrors[] = $name;
            }
        }

        if ($routeErrors !== []) {
            $this->error('Missing routes: '.implode(', ', $routeErrors));

            return self::FAILURE;
        }

        $this->info('All '.count($routes).' key routes registered.');
        $this->newLine();
        $this->comment('Database steps (run when MySQL is available):');
        $this->line('  php artisan migrate --force');
        $this->line('  php artisan db:seed --class=SeoPagesSeeder');

        return self::SUCCESS;
    }
}
