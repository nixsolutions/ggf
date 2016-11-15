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

$factory->define(App\League::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'logoPath' => ''
    ];
});

$factory->define(App\Match::class, function () {
    return [
        'homeScore' => 0,
        'awayScore' => 0,
        'homePenaltyScore' => 0,
        'awayPenaltyScore' => 0,
        'round' => 1,
        'status' => \App\Match::STATUS_NOT_STARTED,
        'gameType' => \App\Match::GAME_TYPE_GROUP_STAGE,
        'resultType' => \App\Match::RESULT_TYPE_UNKNOWN
    ];
});

$factory->define(App\Member::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'facebookId' => $faker->randomNumber(6)
    ];
});

$factory->define(App\Team::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'logoPath' => ''
    ];
});

$factory->define(App\Tournament::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text(50)
    ];
});

$factory->define(App\TournamentTeam::class, function () {
    return [
    ];
});

$factory->define(App\TeamMember::class, function () {
    return [
    ];
});