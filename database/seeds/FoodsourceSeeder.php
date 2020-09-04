<?php

use App\Foodsource;
use Illuminate\Database\Seeder;

class FoodsourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Foodsource::class)->create([
            'name' => 'Health Canada',
            'sharable' => true,
        ]);
        factory(Foodsource::class)->create([
            'name' => 'User',
            'sharable' => false,
        ]);
    }
}
