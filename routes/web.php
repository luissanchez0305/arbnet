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

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::any('/', 'Controller@index');
Route::get('/get_processes', 'DashboardController@get_processes')->name('dashboard.get_processes');
Route::get('/get_orders', 'DashboardController@get_orders')->name('dashboard.get_orders');
Route::get('/get_balances', 'DashboardController@get_balances')->name('dashboard.get_balances');
Route::get('/convert_currency', 'DashboardController@convert_currency')->name('dashboard.convert_currency');
Route::get('/get_user_balance', 'DashboardController@get_user_balance')->name('dashboard.get_user_balance');
