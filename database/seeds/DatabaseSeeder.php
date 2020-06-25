<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            FoodsourceSeeder::class,
            FoodgroupSeeder::class,
            FoodSeeder::class,
        ]);
        // $this->call(UserSeeder::class);
    }
}
