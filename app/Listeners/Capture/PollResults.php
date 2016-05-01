<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\TallyVotes;

class PollResults
{
    use DispatchesJobs;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $contact = $event->shortMessage->contact;
        $cluster = $contact->cluster;

        $message = $event->shortMessage->message;

        $instruction = $event->shortMessage->getInstruction();

//        var_dump($instruction);

        if ($instruction->isValid())
        {
//            $job = new TallyVotes($instruction);
//
//            $this->dispatch($job);
        }
    }
}
