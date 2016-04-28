<?php

namespace App\Listeners\Capture;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\CreateContactFromShortMessage;

class Contact
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
        $job = new CreateContactFromShortMessage($event->shortMessage);

        $this->dispatch($job);
    }
}
