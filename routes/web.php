<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/get_user_orders', 'OrdersController@get_user_orders');
Route::post('/create_new_order', 'OrdersController@create_new_order');
Route::post('/delete_order', 'OrdersController@delete_order');
Route::post('/edit_order', 'OrdersController@edit_order');
Route::get('/get_all_items','ItemsController@get_all_items');

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');

