<?php

namespace App\Listeners\Relay;

use App\Events\FieldDataWasProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutFieldDataProcessing
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
     * @param  FieldDataWasProcessed  $event
     * @return void
     */
    public function handle(FieldDataWasProcessed $event)
    {
        //
    }
}
