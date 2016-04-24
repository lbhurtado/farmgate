<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use libphonenumber\PhoneNumberFormat;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Entities\ShortMessage::class, function (Faker\Generator $faker) {
    return [
        'from'    => phone_format($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999), 'PH', PhoneNumberFormat::E164),
        'to'      => phone_format($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999), 'PH', PhoneNumberFormat::E164),
        'message' => $faker->sentence
    ];
});
