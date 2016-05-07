<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use GuzzleHttp\Client;

class AdhocTest extends TestCase
{
    /** test */
    public function testSendingToSun()
    {
        $client = new Client();
        $res = $client->request('GET', 'http://mcpro.sun-solutions.ph/emcpro/login.aspx?user=LHurtado&pass=ue2ywt31', []);
        if ($res->getStatusCode() == 200)
        {
            $body = $res->getBody()->getContents();
            $result = explode(',', $body);

            if ((int)$result[0] == 20100)
            {

                $session = $result[2];
                $from = "TXTCMDR";
                $to = "09189362340,09173011987";
                $msg = "Multiple recipients";

                $query = http_build_query(compact('session', 'from', 'to', 'msg'));
                $res = $client->request('GET', "http://mcpro.sun-solutions.ph/emcpro/send.aspx?$query", []);


            }
        }
    }

    /** test */
    public function testSMSSun()
    {
//        $mobile1 = "09189362340";
//        $mobile2 = "09173305210";
//        $message  = "We're online, yeah!";
//
//        SMS::queue($message, [], function($sms) use ($mobile1, $mobile2, $message) {
//            $sms->to($mobile1)->to($mobile2);
//        });
    }
}
