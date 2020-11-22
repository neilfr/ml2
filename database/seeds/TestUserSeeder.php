<?php

use App\Food;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUser = factory(User::class)->create([
            'id' => 2,
            'name' => 'Test User',
            'password' => Hash::make('tester'),
            'email' => 'tester@example.com',
        ]);

        DB::insert("
            INSERT INTO `foods` (`alias`, `description`, `potassium`, `kcal`, `protein`, `carbohydrate`, `fat`, `foodgroup_id`, `foodsource_id`, `user_id`, `base_quantity`)
            VALUES
                ('tf1','Test food one',119,204,'9.54','5.91','15.7',22,1,1,100)");

        $food = Food::where('alias','tf1')->first();

        $food->ingredients()->attach(2,['quantity' => 200]);
        $food->ingredients()->attach(4, ['quantity' => 250]);

    }
}
