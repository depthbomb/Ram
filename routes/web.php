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

Route::group(['namespace' => 'Core'], function() {

	Route::get('/', ['uses' => 'PageController@index', 'as' => 'index']);
});

Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax', 'as' => 'ajax'], function() {
	
	Route::group(['prefix' => 'map', 'as' => '.map', 'middleware' => 'logged_in'], function() {
		Route::get('test', ['uses' => 'MapController@test']);
		Route::post('setnextmap', ['uses' => 'MapController@setNextMap']);
	});
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'as' => 'auth'], function() {
	
	Route::get('logout', ['uses' => 'AuthController@logout', 'as' => '.logout']);
	Route::get('login', ['uses' => 'AuthController@login', 'as' => '.login']);

	Route::get('process', ['uses' => 'AuthController@process', 'as' => '.process']);
});


Route::group(['prefix' => 'users', 'namespace' => 'Users', 'as' => 'users', 'middleware' => 'is_super_admin'], function() {
	
	Route::get('/', ['uses' => 'UsersController@index', 'as' => '.index']);
	Route::get('{steamid}', ['uses' => 'UsersController@view', 'as' => '.view']);

	Route::get('edit/{steamid}', ['uses' => 'UsersController@edit', 'as' => '.edit']);
});


Route::group(['prefix' => 'map', 'namespace' => 'Map', 'as' => 'map', 'middleware' => 'logged_in'], function() {
	
	Route::get('/', ['uses' => 'MapController@index', 'as' => '.index']);
});