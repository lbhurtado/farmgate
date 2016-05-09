<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use SimpleSoftwareIO\SMS\Facades\SMS;

class SendShortMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $mobile;
    private $message;

    /**
     * SendShortMessage constructor.
     * @param $mobile
     * @param $message
     */
    public function __construct($mobile, $message)
    {
        $this->mobile = $mobile;
        $this->message = $message;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SMS::send($this->message, [], function($sms) {
            $sms->to($this->mobile);
        });
    }
}
