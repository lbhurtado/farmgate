<?php

namespace App\Listeners\Relay;

use App\Events\AlertsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutAlertsProcessing
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
     * @param  AlertsWereProcessed  $event
     * @return void
     */
    public function handle(AlertsWereProcessed $event)
    {
        //
    }
}
