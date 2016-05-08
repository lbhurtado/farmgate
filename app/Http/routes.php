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

Route::get('sun', function(){
    $mobile = Request::get('from');
    $message = "fwd: " . Request::get('msg');

    SMS::queue($message, [], function($sms) use ($mobile, $message) {
        $sms->to($mobile);
    });

    return Request::all();
});
