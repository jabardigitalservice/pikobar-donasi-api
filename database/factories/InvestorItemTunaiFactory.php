<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Libraries\ConstantParser;
use App\Models\Constants;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\InvestorItem::class, function (Faker $faker) use($factory) {
    return [
        'id' => (string)\Webpatser\Uuid\Uuid::generate(4)->string,
        //'investor_id' => factory(\App\Models\Investor::class)->create()->id,
        'investor_id' => $factory->create(\App\Models\Investor::class)->id,
        'investor_name' => (string)\Webpatser\Uuid\Uuid::generate(4)->string,
        'investor_phone' => 'ssss',
        'investor_email' => 'ssss',
        'donate_category' => 'tunai',
        'bank_id' => '00004a8c-8101-4583-b05b-83a9bfa7cdba',
        'bank_account' => 'Rahyan',
        'bank_number' => '010010101',
        'amount' => 1000000,
    ];
});
