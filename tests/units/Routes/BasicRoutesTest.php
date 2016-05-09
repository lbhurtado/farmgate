<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BasicRoutesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function post_sms_poll()
    {
        $this->artisan('db:seed');

        Session::start(); // Start a session for the current test
        $params = [
            '_token' => csrf_token(), // Retrieve current csrf token
        ];

        $route = 'sms/09173011987/09189362340/alfonso 1 Lester';
        $response = $this->call('POST', $route, $params);

        $this->assertResponseOk();

        $route = 'sms/09173011987/09189362340/poll marcos 300 robredo 200 escudero 100 cayetano 50 honasan 25';
        $response = $this->call('POST', $route, $params);

        $this->assertResponseOk();

//        $this->assertEquals('{"from":"09173011987","to":"09189362340","message":"amadeo 1 Lester"}', $response->getContent());
    }
}
