<?php

namespace App\Http\Controllers;

use App\Jobs\RecordShortMessage;
use Illuminate\Http\Request;
use App\Http\Requests;
use League\Csv\Reader;
use SimpleSoftwareIO\SMS\Facades\SMS;


class SMSController extends Controller
{
    private $request;

    /**
     * SMSController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function post($from, $to, $message)
    {
        $job = new RecordShortMessage($from, $to, $message, INCOMING);
        $this->dispatch($job);

        return compact('from', 'to', 'message');
    }

    public function sun()
    {
        $to = "09229990758";
        $from = $this->request->get('from');
        $message = $this->request->get('msg');

        $this->post($from, $to, $message);
    }

    public function broadcast()
    {
        $reader = Reader::createFromPath(database_path('contacts.csv'));

        $message = $this->request->get('msg');

        $contacts = [];
        foreach ($reader as $index => $row)
        {
            $contacts [] =$row[0];
        }

        SMS::queue($message, [], function($sms) use ($contacts) {
            foreach($contacts as $contact)
            {
                $sms->to($contact);
            }

        });

        return $message;
    }
}
