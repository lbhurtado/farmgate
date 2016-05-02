<?php

namespace App\Listeners\Capture;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShortMessageWasRecorded;
use App\Jobs\TallyVotes;
use App\Instruction;

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
        $instruction = $event->shortMessage->getInstruction();

        if ($instruction->isValid())
            switch ($instruction->getKeyword())
            {
                case strtoupper(Instruction::$keywords['POLL']):
                    $job = new TallyVotes($instruction);
                    $this->dispatch($job);
                    break;
            }
    }
}
