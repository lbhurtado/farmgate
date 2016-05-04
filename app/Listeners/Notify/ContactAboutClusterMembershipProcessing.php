<?php

namespace App\Listeners\Notify;

use App\Events\ClusterMembershipsWereProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SimpleSoftwareIO\SMS\Facades\SMS;

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
        $cluster = $event->cluster;

        $mobile = $cluster->contacts->mobile;

        $message = "Please proceed to " . $cluster->polling_place->name;

        SMS::queue($message, [], function($sms) use ($mobile) {
            $sms->to($mobile);
        });
    }
}
