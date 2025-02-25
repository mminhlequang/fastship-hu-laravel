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

Route::prefix('v1')->middleware(['language'])->group(function () {
    Route::post('/login', 'Api\CustomerController@login');
    Route::post('/register', 'Api\CustomerController@register');
    Route::post('/update_password', 'Api\CustomerController@updatePassword');
    Route::post('/reset_password', 'Api\CustomerController@resetPassword');
    Route::get('/profile', 'Api\CustomerController@getProfile');
    Route::post('/update_profile', 'Api\CustomerController@updateProfile');
    Route::post('/check_phone', 'Api\CustomerController@checkPhone');

    //** API-Login-Social */
    Route::post('/update_device_token', 'Api\CustomerController@updateDeviceToken');
    Route::post('delete_account', 'Api\CustomerController@deleteAccount');

});
