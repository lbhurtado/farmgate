<?php

return [
<<<<<<< Updated upstream
    'driver' => env('DEFAULT_SMS_DRIVER', 'log'),
=======
    'driver' => env('DEFAULT_SMS_DRIVER', 'log'), 
>>>>>>> Stashed changes
    'from' => 'Your Number or Email',
    'telerivet' => [
        'api_key'    => env('TELERIVET_API_KEY'),
        'project_id' => env('TELERIVET_PROJECT_ID'),
    ],
    'smart' => [
        'token'   => env('SMARTSUITE_TOKEN'),
        'wsdl'    => 'https://ws.smartmessaging.com.ph/soap/?wsdl',
        'service' => 'SENDSMS',
    ],
    'sun' => [
        'user' => env('SUN_USER'),
        'pass' => env('SUN_PASS'),
        'mask' => env('SUN_MASK'),
        'login_url' => 'http://mcpro.sun-solutions.ph/emcpro/login.aspx'
    ],
    'callfire' => [
        'app_login' => 'Your CallFire API Login',
        'app_password' => 'Your CallFire API Password'
    ],
    'eztexting' => [
        'username' => 'Your EZTexting Username',
        'password' => 'Your EZTexting Password'
    ],
    'labsmobile' => [
        'client' => 'Your client ID',
        'username' => 'Your Usernbame',
        'password' => 'Your Password',
        'test' => false
    ],
    'mozeo' => [
        'company_key' => 'Your Mozeo Company Key',
        'username' => 'Your Mozeo Username',
        'password' => 'Your Mozeo Password'
    ],
    'nexmo' => [
        'api_key' => 'Your Nexmo api key',
        'api_secret' => 'Your Nexmo api secret'
    ],
    'twilio' => [
        'account_sid' => 'Your SID',
        'auth_token' => 'Your Token',
        'verify' => true,
    ]
];
