<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReverbStartCommand extends Command
{
    protected $signature = 'reverb:start
                            {--host=0.0.0.0 : The host address to bind}
                            {--port=6001 : The port to listen on}';

    protected $description = 'Start the local WebSocket server (Laravel 10 stand-in for Reverb)';

    public function handle(): int
    {
        $this->info('Laravel Reverb is not available on Laravel 10. Starting the free local WebSockets server instead.');

        return $this->call('websockets:serve', [
            '--host' => $this->option('host'),
            '--port' => $this->option('port'),
        ]);
    }
}
