<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Csv\Reader;
use SimpleSoftwareIO\SMS\Facades\SMS;

class SendBroadcast extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $message;

    private $reader;

    public function __construct($message)
    {
        $this->message = $message;
        $this->reader = Reader::createFromPath(database_path('contacts.csv'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contacts = [];
        foreach ($this->reader as $index => $row)
        {
            $contacts [] =$row[0];
        }

        SMS::send($this->message, [], function($sms) use ($contacts) {
            foreach($contacts as $contact)
            {
                $sms->to($contact);
            }

        });
    }
}
