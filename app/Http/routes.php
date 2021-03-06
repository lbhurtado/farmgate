<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Request;
use SimpleSoftwareIO\SMS\Facades\SMS;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('groups', 'GroupsController');

Route::post('sms/{from}/{to}/{message}', 'SMSController@post');

Route::post('sun', 'SMSController@sun');

Route::post('send', function(){
    $mobile = Request::get('to');
//    $message = Request::get('msg');

    $message = urlencode("Sa Mayo 9, 2016 'wag kalimutan \n\"TEMY SIMUNDAC\" #5\n sa balota para Mayor ng Muntinlupa City");

    SMS::queue($message, [], function($sms) use ($mobile, $message) {
        $sms->to($mobile);
    });

    return Request::all();
});

Route::post('broadcast', 'SMSController@broadcast');