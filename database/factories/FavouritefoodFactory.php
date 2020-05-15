<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Favouritefood;
use App\Foodgroup;
use Faker\Generator as Faker;

$factory->define(Favouritefood::class, function (Faker $faker) {
    return [
        'user_id' => auth()->user()->id,
        'foodgroup_id' => factory(Foodgroup::class)->create()->id,
        'code' => null,
        'alias' => $faker->word,
        'description' => $faker->sentence,
        'kcal' => $faker->numberBetween(0,300),
        'potassium' => $faker->numberBetween(0,300),
    ];
});
