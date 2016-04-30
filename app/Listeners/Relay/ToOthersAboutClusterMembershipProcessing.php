<?php

namespace App\Listeners\Relay;

use App\Events\ClusterMembershipsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToOthersAboutClusterMembershipProcessing
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
     * @param  ClusterMembershipsWereProcessed  $event
     * @return void
     */
    public function handle(ClusterMembershipsWereProcessed $event)
    {
        //
    }
}
