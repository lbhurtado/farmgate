<?php

namespace App\Listeners\Relay;

use App\Events\TaskAccomplishmentsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutTaskAccomplishmentsProcessing
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
     * @param  TaskAccomplishmentsWereProcessed  $event
     * @return void
     */
    public function handle(TaskAccomplishmentsWereProcessed $event)
    {
        //
    }
}
