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
$factory->define(\App\Models\Investor::class, function (Faker $faker) {

    $donateCategoryId = ConstantParser::searchById('00000000-0003-43ad-a18c-000000000004',
        \App\Models\Constants::DONATION_CATEGORIES);

    $investorCategoryId = ConstantParser::searchById('10000000-0001-44bd-a26c-000000000001',
        Constants::INVESTOR_CATEGORIES);

    //not_verified,verified,reject
    $donateStatus = ConstantParser::searchBySlug('verified', Constants::INVESTOR_STATUS);

    $email = $faker->unique()->safeEmail;
    $phone = $faker->phoneNumber;
    $uuid = (string)\Webpatser\Uuid\Uuid::generate(4)->string;
    $name = $faker->name;

    return [
        'id' => $uuid,
        'investor_name' => $name,
        'category_id' => '10000000-0001-44bd-a26c-000000000001',
        'category_slug' => $investorCategoryId['slug'],
        'category_name' => $investorCategoryId['name'],
        'phone' => $phone,
        'email' => $email,
        'address' => $faker->streetAddress,
        'donate_id' => '00000000-0003-43ad-a18c-000000000004',
        'donate_category' => $donateCategoryId['slug'],
        'donate_category_name' => $donateCategoryId['name'],
        'show_name' => 1,
        'donate_status' => $donateStatus['slug'],
        'donate_status_name' => $donateStatus['name'],
        'donate_date' => now(),
        'invoice_number' => \App\Libraries\NumberLibrary::createInvoice(),
        'attachment_id' => null,
        'items' => function (array $product) use ($uuid, $name, $email, $phone) {
            return factory(\App\Models\InvestorItem::class)->create([
                'id' => (string)\Webpatser\Uuid\Uuid::generate(4)->string,
                'investor_id' => $uuid,
                'investor_name' => $name,
                'investor_phone' => $phone,
                'investor_email' => $email,
                'donate_category' => 'tunai',
                'bank_id' => '00004a8c-8101-4583-b05b-83a9bfa7cdba',
                'bank_account' => $name,
                'bank_number' => '010010101',
                'amount' => 1000000
            ])->investor_id;
        },
    ];
});
