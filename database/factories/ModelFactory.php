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

use App\Mobile;

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
        'from'    => Mobile::number($faker->numberBetween(917,919) . $faker->numberBetween(8362340,9362340)),
        'to'      => Mobile::number($faker->numberBetween(916,918) . $faker->numberBetween(2011987,3011987)),
        'message' => $faker->sentence
    ];
});

$factory->define(App\Entities\Contact::class, function (Faker\Generator $faker) {
    return [
        'mobile' => Mobile::number($faker->numberBetween(900,999) . $faker->numberBetween(1000000,9999999)),
        'handle' => $faker->userName,
    ];
});

$factory->define(App\Entities\Group::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\Entities\Cluster::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company . $faker->numberBetween(1,9999),
        'precincts' => str_pad($faker->numberBetween(1,99), 3, STR_PAD_LEFT) . $faker->randomElement(["A", "B", "C", "D", "E"]),
        'registered_voters' =>  rand(100, 1000),
        'town_id' => function () {
            return factory(App\Entities\Town::class)->create()->id;
        },
        'polling_place_id' => function () {
            return factory(App\Entities\PollingPlace::class)->create()->id;
        }
    ];
});

$factory->define(App\Entities\Candidate::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name . $faker->numberBetween(1,9999),
        'alias' => $faker->lastName . $faker->numberBetween(1,9999)
    ];
});

$factory->define(App\Entities\ElectionResult::class, function (Faker\Generator $faker) {
    return [
        'votes' => $faker->numberBetween(500,800),
        'candidate_id' => function () {
            return factory(App\Entities\Candidate::class)->create()->id;
        },
        'cluster_id' => function () {
            return factory(App\Entities\Cluster::class)->create()->id;
        },
    ];
});

$factory->define(App\Entities\Town::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->city,
    ];
});

$factory->define(App\Entities\Barangay::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'town_id' => function () {
            return factory(App\Entities\Town::class)->create()->id;
        },
    ];
});

$factory->define(App\Entities\PollingPlace::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'barangay_id' => function () {
            return factory(App\Entities\Barangay::class)->create()->id;
        },
    ];
});

$factory->define(App\Entities\ElectivePosition::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'tag'  => $faker->numberBetween(1,7)
    ];
});

$factory->define(App\Entities\District::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->city,
    ];
});