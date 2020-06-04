<?php

Route::post('/login', ['as' => 'api.login', 'uses' => 'ApiLoginController@login']);
Route::post('/login/refresh', 'ApiLoginController@refresh');
Route::delete('/logout', 'ApiLoginController@logout')->middleware('auth:api');

Route::group(['as' => 'api::', 'namespace' => 'Api', 'middleware' => 'auth:api', 'prefix' => 'v1'], function () {

    // Usercontroller
    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('/', 'UserController@index');
        Route::post('/create', 'UserController@store');
    });

    // MasterController
    Route::group(['as' => 'master.', 'prefix' => 'master'], function () {
        Route::get('/material', 'MasterController@getMaterial');
        Route::get('/uom', 'MasterController@getUom');
    });

    // Donasi
    Route::group(['as' => 'donate.', 'prefix' => 'donate'], function () {
        Route::get('/categories', 'DonateController@getCategory');
    });

    Route::group(['as' => 'sembako.', 'prefix' => 'sembako'], function () {
        Route::get('/', 'SembakoPackageController@index');
        Route::get('/items', 'SembakoPackageController@itemIndex');

        Route::post('/create', 'SembakoPackageController@store');
        Route::post('/update/{id}', 'SembakoPackageController@update');
        Route::post('/delete/{id}', 'SembakoPackageController@destroy');

        Route::post('/create-item', 'SembakoPackageController@itemStore');
        Route::post('/update-item/{id}', 'SembakoPackageController@itemUpdate');
        Route::post('/delete-item/{id}', 'SembakoPackageController@destroy');
    });


});
