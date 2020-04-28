<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Favouritefood;
use Faker\Generator as Faker;

$factory->define(Favouritefood::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence,
    ];
});
