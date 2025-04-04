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

Route::middleware(['locale'])->group(function () {
    Route::get('/', 'FrontendController@index');

    //OTP
    Route::post('/send-otp', 'FireBaseController@sendOtp');
    Route::post('/verify-otp', 'FireBaseController@verifyOtp');
    Route::get('change_locale', 'FrontendController@changeLocale');

    //Auth Customer
    Route::get('/logout/customer', 'AuthController@logout');
    Route::post('/login/customer', 'AuthController@login');
    Route::post('/register/customer', 'AuthController@register');
    Route::post('/forgot_password/customer', 'AuthController@forgotPassword');
    Route::post('/reset_password/customer', 'AuthController@resetPassword');

    Route::middleware(['check.loyal_customer'])->group(function () {
        Route::get('/my-account', 'AuthController@myAccount');
        Route::get('/my-order', 'AuthController@myOrder');
        Route::get('/my-wishlist', 'AuthController@myWishlist');
        Route::get('/my-voucher', 'AuthController@myVoucher');
    });

    Route::get('ajaxFE/{action}', 'AjaxFrontendController@index');
    Route::post('ajaxFE/{action}', 'AjaxPostFrontEntController@index');

    Route::get('/{slug}.html', 'FrontendController@getPage');


    Route::group(['prefix' => '{slugParent}'], function () {
        Route::get('/', 'FrontendController@getListParents');
        Route::get('/{slugDetail}.html', 'FrontendController@getDetail');
    });

});
