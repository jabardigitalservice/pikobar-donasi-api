<?php

use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Statistic::create([
            'id' => \App\Models\Constants::DEFAULT_STATISTIC_ID,
            'personal_investor' => 0,
            'company_investor' => 0,
            'total_goods' => 0,
            'total_cash' => 0,
            'date_input' => now(),
            'is_last' => 1
        ]);
    }
}
