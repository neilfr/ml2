<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Admin User',
            'password' => 'tester',
            'email' => 'admin@example.com',
        ]);
        factory(User::class)->create([
            'name' => 'Test User',
            'password' => 'tester',
            'email' => 'tester@example.com',
        ]);
    }
}
