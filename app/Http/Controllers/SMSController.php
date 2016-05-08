<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\SMS\Facades\SMS;
use App\Jobs\RecordShortMessage;
use Illuminate\Http\Request;
use App\Http\Requests;


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
        $mobile = $this->request->get('from');
        $message = "fwd: " . $this->request->get('msg');

        SMS::queue($message, [], function($sms) use ($mobile, $message) {
            $sms->to($mobile);
        });

        return Request::all();
    }
}
