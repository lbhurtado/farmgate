<?php

namespace App\Listeners\Relay;

use App\Events\PollResultsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutPollResultsProcessing
{
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
     * @param  PollResultsWereProcessed  $event
     * @return void
     */
    public function handle(PollResultsWereProcessed $event)
    {
        //
    }
}
