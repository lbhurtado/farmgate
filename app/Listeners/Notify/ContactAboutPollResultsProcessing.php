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
        $poll_result = http_build_query($event->reesults, nullOrEmptyString(), "\n");
        $message = "Processed:\n" . $poll_result;
        $mobile = $event->instruction->getShortMessage()->mobile;

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
