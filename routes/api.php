<?php

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

// 登陆相关
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::get('/user', 'AuthController@user');
Route::post('/profile', 'AuthController@profile');
Route::post('/refresh', 'AuthController@refresh');

Route::get('/settings', 'SettingController@index');
Route::post('/settings', 'SettingController@update');

Route::get('/app/config', 'AppController@config');

Route::post('/account/apply', 'AccountController@apply');
Route::post('/account/bind', 'AccountController@bind');
Route::post('/account/unbind', 'AccountController@unbind');
Route::get('/account/callback', 'AccountController@callback');
Route::get('/account/info', 'AccountController@info');
