<?php

namespace App\Console\Commands;

use App\Jobs\AggregateAnalyticsDaily;
use Illuminate\Console\Command;

class AggregateAnalyticsCommand extends Command
{
    protected $signature = 'analytics:aggregate {date?}';

    protected $description = 'Roll up raw analytics into analytics_daily';

    public function handle(): int
    {
        AggregateAnalyticsDaily::dispatch($this->argument('date'));
        $this->info('Analytics aggregation dispatched.');

        return self::SUCCESS;
    }
}
