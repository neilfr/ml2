<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Food;
use Faker\Generator as Faker;

$factory->define(Food::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween(0,7000),
        'description' => $faker->sentence,
        'kcal' => $faker->numberBetween(1,300),
        'fat' => $faker->numberBetween(1,300),
        'protein' => $faker->numberBetween(1,300),
        'carbohydrate' => $faker->numberBetween(1,300),
        'potassium' => $faker->numberBetween(1,300),
        'foodgroup_id' => function(){
            return factory(\App\Foodgroup::class)->create()->id;
        }
    ];
});
