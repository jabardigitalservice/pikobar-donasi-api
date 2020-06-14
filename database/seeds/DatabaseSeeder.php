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
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BanksSeeder::class);
        $this->call(StatisticSeeder::class);
        $this->call(MaterialSeeder::class);
    }
}
