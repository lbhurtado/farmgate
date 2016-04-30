<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\ClaimToken;

class ClusterMemberships
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $job = new ClaimToken($event->shortMessage);

        $this->dispatch($job);
    }
}
