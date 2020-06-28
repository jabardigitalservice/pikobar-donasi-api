<?php

// Login, Logout, Forgot, Refresh Token
Route::post('/login', ['as' => 'api.login', 'uses' => 'ApiLoginController@login']);
Route::post('/refresh-token', 'ApiLoginController@refresh');
Route::delete('/logout', 'ApiLoginController@logout')->middleware('auth:api');
Route::post('/forgot', 'Api\AuthController@forgot');

Route::group(['as' => 'api::', 'namespace' => 'Api', 'prefix' => 'public/v1'], function () {

    // Investor/Donasi
    Route::group(['as' => 'donate.', 'prefix' => 'donate'], function () {
        Route::post('/create', 'InvestorController@store');
        Route::get('/show/{id}', 'InvestorController@showInvestor');
    });

    // MasterController
    Route::group(['as' => 'master.', 'prefix' => 'master'], function () {
        Route::get('/uom', 'MasterController@getUom');
        Route::get('/investor-category', 'MasterController@getInvestorCategory');
        Route::get('/investor-status', 'MasterController@getInvestorStatus');
        Route::get('/donation-type', 'MasterController@getDonationType');
        Route::get('/bank', 'MasterController@getBank');
    });

    Route::group(['as' => 'sembako.', 'prefix' => 'sembako'], function () {
        Route::get('/', 'SembakoPackageController@index');
        Route::get('/items', 'SembakoPackageController@itemIndex');
    });

    Route::group(['as' => 'statistic.', 'prefix' => 'statistic'], function () {
        Route::get('/', 'StatistikController@index');
    });

    //External Apps
    Route::group(['namespace' => 'External'], function () {
        Route::get('/external/logistic-material', 'LogisticController@getMaterial');
        Route::get('/external/logistic', 'LogisticController@getLogisticNeeds');
    });

});

Route::group(['as' => 'api::', 'namespace' => 'Api', 'middleware' => 'auth:api', 'prefix' => 'v1'], function () {

    // Usercontroller
    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('/', 'UserController@index');
        Route::post('/create', 'UserController@store');
    });

    Route::group(['as' => 'donate.', 'prefix' => 'donate'], function () {
        Route::get('/', 'InvestorController@index');
        Route::get('/show/{id}', 'InvestorController@showInvestor');
        Route::post('/verification/{id}', 'InvestorController@verification');
    });

    Route::group(['as' => 'statistic.', 'prefix' => 'statistic'], function () {
        Route::get('/', 'StatistikController@index');
        Route::get('/show/{id}', 'StatistikController@show');
        Route::get('/show-last', 'StatistikController@showLastStatistic');
        Route::post('/create', 'StatistikController@store');
        Route::post('/update/{id}', 'StatistikController@update');
        Route::get('/count', 'StatistikController@showCount');
    });

    Route::group(['as' => 'sembako.', 'prefix' => 'sembako'], function () {
        Route::get('/', 'SembakoPackageController@index');
        Route::get('/items', 'SembakoPackageController@itemIndex');

        Route::get('/show/{id}', 'SembakoPackageController@show');
        Route::get('/show-item/{id}', 'SembakoPackageController@showItem');

        Route::post('/create', 'SembakoPackageController@store');
        Route::post('/update/{id}', 'SembakoPackageController@update');
        Route::post('/delete/{id}', 'SembakoPackageController@destroy');

        Route::post('/create-item', 'SembakoPackageController@itemStore');
        Route::post('/update-item/{id}', 'SembakoPackageController@itemUpdate');
        Route::post('/delete-item/{id}', 'SembakoPackageController@destroyItem');
    });

});
