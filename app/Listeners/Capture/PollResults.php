<?php

namespace App\Listeners\Capture;

use App\Events\ShortMessageWasRecorded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PollResults
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
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        $input_lines = $event->shortMessage->message;

        if (preg_match_all("/(?P<candidate>\w+) (?P<votes>\d+)/", $input_lines, $output_array)){

//            var_dump($output_array);
        }

    }
}
