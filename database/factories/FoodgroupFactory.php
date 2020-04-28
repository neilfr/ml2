<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Foodgroup;
use Faker\Generator as Faker;

$factory->define(Foodgroup::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence,
    ];
});
