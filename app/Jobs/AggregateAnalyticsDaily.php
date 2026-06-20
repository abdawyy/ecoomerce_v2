<?php

namespace App\Jobs;

use App\Services\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AggregateAnalyticsDaily implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ?string $date = null) {}

    public function handle(AnalyticsService $analytics): void
    {
        $date = $this->date
            ? Carbon::parse($this->date)
            : Carbon::yesterday();

        $analytics->aggregateDaily($date);
    }
}
