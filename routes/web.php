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

Route::get('/', 'OrderController@create');

Route::group(['prefix' => 'order'], function(){
	Route::get('index', 'OrderController@index');
	Route::post('store', 'OrderController@store');
	Route::get('show/{order_id}', 'OrderController@show')->where(['order_id' => '[0-9]+']);
	Route::get('status/{order_id}', 'OrderController@status')->where(['order_id' => '[0-9]+']);
});
