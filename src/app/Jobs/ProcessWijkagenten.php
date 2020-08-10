<?php

namespace Politie\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Politie\Services\WijkagentenService;

class ProcessWijkagenten implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param WijkagentenService $service
     * @return void
     */
    public function handle(WijkagentenService $service)
    {
        // Process query...
        $service->queryAll();
        return true;
    }
}
