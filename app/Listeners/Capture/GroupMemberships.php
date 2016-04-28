<?php

namespace App\Listeners\Capture;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\TokenRepository;
use App\Jobs\ClaimToken;

class GroupMemberships
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
