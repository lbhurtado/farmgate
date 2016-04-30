<?php

namespace App\Listeners\Notify;

use App\Events\ClusterMembershipsWereProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactAboutClusterMembershipProcessing
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
