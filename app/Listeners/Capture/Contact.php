<?php

namespace App\Listeners\Capture;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\CreateContact;
use Illuminate\Foundation\Bus\DispatchesJobs;

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
        $mobile = $event->shortMessage->getMobile();
        $handle = $event->shortMessage->getHandle();

        $job = new CreateContact($mobile, $handle);
        $this->dispatch($job);
    }
}
