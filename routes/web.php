<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'Auth', 'prefix' => 'admin'], function () {

    // # LOGIN
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');

    // # LOGOUT
    Route::post('/logout', 'LoginController@logout')->name('logout');
});

Route::group(['as' => 'backend::', 'namespace' => 'Backend', 'middleware' => 'auth:web', 'prefix' => 'admin'], function () {
    // do not remove this line
    Route::get('/', ['uses' => 'DashboardController@index']);

    // do not remove this line
    Route::get('/home', ['as' => 'home', 'uses' => 'DashboardController@index']);

    // # DASHBOARD
    Route::group(['as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'DashboardController@index']);
    });

    // # SIDEBAR SETTING
    Route::group(['as' => 'sidebars.', 'prefix' => 'sidebars'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'SidebarController@index']);
        Route::post('menu/{id}/tree', 'SidebarController@tree');
        Route::get('show/{id}', 'SidebarController@show');
        Route::get('showChildForm/{id}', 'SidebarController@showChildForm');
        Route::post('updateChildNode', ['as' => 'updateChildNode', 'uses' => 'SidebarController@updateChildNode']);
        Route::post('updateNode', ['as' => 'updateNode', 'uses' => 'SidebarController@updateNode']);
        Route::post('delete/{id}', ['as' => 'delete', 'uses' => 'SidebarController@delete']);
        //
        Route::get('showCreateRoot/{id}', ['as' => 'showCreateRoot', 'uses' => 'SidebarController@showCreateRoot']);
        Route::post('store', ['as' => 'store', 'uses' => 'SidebarController@store']);
    });
});