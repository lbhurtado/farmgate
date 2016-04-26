<?php

namespace App\Listeners\Notify;

use App\Events\AlertsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutAlertsProcessing
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
