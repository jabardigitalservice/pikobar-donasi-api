<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'id' => '2fba941d-8e41-40f0-829b-85087e1618d3',
            'role_name' => 'Owner',
            'slug' => 'owner',
            'description' => 'Owner',
            'is_active' => 1,
            'is_default' => 1
        ]);

        \App\Models\Role::create([
            'id' => 'b2bb972c-80a9-4f35-9b6a-7ca56727a248',
            'role_name' => 'Administrator',
            'slug' => 'administrator',
            'description' => 'Administrator/Owner',
            'is_active' => 1,
            'is_default' => 1
        ]);

        \App\Models\Role::create([
            'id' => '2893e8a4-8d7c-490d-871f-c98679f05509',
            'role_name' => 'Donatur',
            'slug' => 'donatur',
            'description' => 'donatur',
            'is_active' => 1,
            'is_default' => 1
        ]);
    }
}
