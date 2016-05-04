<?php

namespace App\Listeners\Notify;

use App\Repositories\ShortMessageRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PollResultsWereProcessed;
use SimpleSoftwareIO\SMS\Facades\SMS;

class ContactAboutPollResultsProcessing
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
     * @param  PollResultsWereProcessed  $event
     * @return void
     */
    public function handle(PollResultsWereProcessed $event)
    {
        $poll_result = http_build_query($event->reesults, null, "\n");

        $mobile = $event->instruction->getShortMessage()->from;
        $handle = $event->instruction->getShortMessage()->contact->handle;

        $message  = ($handle != $mobile) ? "$handle:" : "";
        $message .= "\n" . "Processed:";
        $message .= "\n" .  $poll_result;
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
