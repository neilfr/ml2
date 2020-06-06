<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Foodsource;
use Faker\Generator as Faker;

$factory->define(Foodsource::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'deletable' => false,
        'updatable' => false,
        'sharable' => false,
    ];
});
