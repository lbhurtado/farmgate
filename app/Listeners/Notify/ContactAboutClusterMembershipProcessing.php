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

        $message  = "Go to: " . strtok($cluster->polling_place->name, ",");
        $message .= "\n" . "Precinct: " . $cluster->precincts;
        $message .= "\n" . "Send: TXTCMDR POLL <CANDIDATE> <VOTES> <CANDIDATE> <VOTES>...";
        $message .= "\n" . "To: (0917) 301-1987";

        dd($message);

        SMS::queue($message, [], function($sms) use ($mobile) {
            $sms->to($mobile);
        });
    }
}
