<?php

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
        \App\User::create([
            'id' => '2fba941d-8e41-40f0-829b-85087e1618d3',
            'username' => 'pikobardonasi',
            'email' => 'pikobardonasi@gmail.com',
            'first_name' => 'pikobar',
            'last_name' => 'donasi',
            'password' => \Illuminate\Support\Facades\Hash::make('pass890'),
            'gender' => 'male',
            'active' => 1,
            'email_verified_at' => now()
        ])->roles()->attach('b2bb972c-80a9-4f35-9b6a-7ca56727a248',
            ['id' => '2fba941d-8e41-40f0-829b-85087e1618d4']
        );
    }
}
