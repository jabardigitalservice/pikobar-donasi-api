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

    // # USER
    Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::get('create', ['as' => 'showCreate', 'uses' => 'UserController@showCreate']);
        Route::get('datatables', ['as' => 'datatables', 'uses' => 'UserController@getDatatable']);
        // Transaction
        Route::post('/store', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'UserController@showEdit']);
        Route::post('/remove-media/{id}', ['as' => 'remove.media', 'uses' => 'UserController@removeMedia']);
        Route::post('/password-update', ['as' => 'password', 'uses' => 'UserController@updatePassword']);
    });

    // #STATISTIK
    Route::group(['as' => 'statistics.', 'prefix' => 'statistics'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'StatistikController@index']);
        Route::get('create', ['as' => 'showCreate', 'uses' => 'StatistikController@showCreate']);
        Route::get('/show/{id}', ['as' => 'showUpdate', 'uses' => 'StatistikController@showUpdate']);
        Route::get('datatables', ['as' => 'datatables', 'uses' => 'StatistikController@getDatatable']);

        // Transaction
        Route::post('/store', ['as' => 'store', 'uses' => 'StatistikController@store']);
        Route::post('/update/{id}', ['as' => 'update', 'uses' => 'StatistikController@update']);
    });

    // #INVESTORS
    Route::group(['as' => 'investors.', 'prefix' => 'investors'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'InvestorController@index']);
        Route::get('datatables', ['as' => 'datatables', 'uses' => 'InvestorController@getDatatable']);
        Route::get('/show/{id}', ['as' => 'showDetail', 'uses' => 'InvestorController@showDetail']);
    });

    // #SEMBAKO PACKAGE
    Route::group(['as' => 'sembako-packages.', 'prefix' => 'sembako-packages'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'SembakoPackageController@index']);
        Route::get('datatables', ['as' => 'datatables', 'uses' => 'SembakoPackageController@getDatatable']);
        Route::get('create', ['as' => 'showCreate', 'uses' => 'SembakoPackageController@showCreate']);
        Route::get('/show/{id}', ['as' => 'showDetail', 'uses' => 'SembakoPackageController@showDetail']);
        Route::get('/edit/{id}', ['as' => 'showDetail', 'uses' => 'SembakoPackageController@showEdit']);
        Route::post('/update/{id}', ['as' => 'update', 'uses' => 'SembakoPackageController@update']);
        Route::post('/store', ['as' => 'store', 'uses' => 'SembakoPackageController@store']);
        Route::post('delete/{id}', ['as' => 'delete', 'uses' => 'SembakoPackageController@delete']);

    });

    // #SEMBAKO PACKAGE ITEMS
    Route::group(['as' => 'sembako-items.', 'prefix' => 'sembako-items'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'SembakoItemController@index']);
        Route::get('datatables', ['as' => 'datatables', 'uses' => 'SembakoItemController@getDatatable']);
        Route::get('create', ['as' => 'showCreate', 'uses' => 'SembakoItemController@showCreate']);
        Route::get('/show/{id}', ['as' => 'showDetail', 'uses' => 'SembakoItemController@showDetail']);
        Route::get('/edit/{id}', ['as' => 'showDetail', 'uses' => 'SembakoItemController@showEdit']);
        Route::post('/update/{id}', ['as' => 'update', 'uses' => 'SembakoItemController@update']);
        Route::post('/store', ['as' => 'store', 'uses' => 'SembakoItemController@store']);
        Route::post('delete/{id}', ['as' => 'delete', 'uses' => 'SembakoItemController@delete']);
    });
});