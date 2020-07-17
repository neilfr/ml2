<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('tester'),
            'email' => 'admin@example.com',
        ]);
        factory(User::class)->create([
            'name' => 'Test User',
            'password' => Hash::make('tester'),
            'email' => 'tester@example.com',
        ]);
    }
}