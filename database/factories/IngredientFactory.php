<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Foodgroup;
use App\Foodsource;
use App\Ingredient;
use Faker\Generator as Faker;

$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence,
        'alias' => $faker->sentence,
        'kcal' => $faker->numberBetween(1, 300),
        'fat' => $faker->numberBetween(1, 300),
        'protein' => $faker->numberBetween(1, 300),
        'carbohydrate' => $faker->numberBetween(1, 300),
        'potassium' => $faker->numberBetween(1, 300),
        'base_quantity' => $faker->numberBetween(1, 300),
        'foodgroup_id' => factory(Foodgroup::class)->create()->id,
        'foodsource_id' => factory(Foodsource::class)->create()->id,
        'user_id' => auth()->user()->id,
    ];
});
