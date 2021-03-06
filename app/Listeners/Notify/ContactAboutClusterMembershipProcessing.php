<?php

namespace App\Listeners\Notify;

use App\Events\ClusterMembershipsWereProcessed;
use App\Repositories\ShortMessageRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SimpleSoftwareIO\SMS\Facades\SMS;
use App\Mobile;

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
        $mobile = Mobile::national($cluster->contacts->mobile);

        $handle = $cluster->contacts->handle;

        $message  = ($handle != $mobile) ? "$handle:" : "";
        $message .= "\n" . "Go to " . strtok($cluster->polling_place->name, ",");
        $message .= "\n" . "Precinct: " . $cluster->precincts;
        $message .= "\n" . "Send: POLL <CANDIDATE> <VOTES> <CANDIDATE> <VOTES>...";
        $message .= "\n" . "To: (0922) 999-0758";
//        $message .= "\n" . "To: (0922) 999-0758, (0947) 524-3435, (0939) 418-2957, (0917) 825-1991";

        SMS::queue($message, [], function($sms) use ($mobile, $message) {
            $sms->to($mobile);
            \App::make(ShortMessageRepository::class)->skipPresenter()->create([
                'from'      => '09178251991',
                'to'        => $mobile,
                'message'   => $message,
                'direction' => OUTGOING
            ]);
        });
    }
}
