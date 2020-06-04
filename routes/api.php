<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', ['as' => 'api.login', 'uses' => 'AppLoginController@login']);
Route::post('/login/refresh', 'AppLoginController@refresh');
Route::delete('/logout', 'AppLoginController@logout')->middleware('auth:api');

Route::group(['as' => 'api::', 'namespace' => 'Api', 'middleware' => 'auth:api', 'prefix' => 'v1'], function () {
//Route::group(['as' => 'api::', 'namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('/', 'UserController@index');
        Route::post('/create', 'UserController@store');
    });
    Route::group(['as' => 'master.', 'prefix' => 'master'], function () {
        Route::get('/material', 'MasterController@getMaterial');
    });
    Route::group(['as' => 'donate.', 'prefix' => 'donate'], function () {
        Route::get('/categories', 'DonateController@getCategory');
    });
});

Route::get('/check-oauth-passwd', ['as' => 'get', 'uses' => 'AppController@showPasswordCredentials'])->middleware(['auth:api']);
Route::get('/check-oauth-cred', ['as' => 'get', 'uses' => 'AppController@showClientCredentials'])->middleware(['oauth-client']);