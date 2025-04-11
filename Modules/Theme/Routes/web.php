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
    Route::get('change_locale', 'FrontendController@changeLocale');
    Route::get('/', 'FrontendController@index');

    Route::get('/payment/success', function () {
        return view('theme::front-end.payment-success');
    })->name('payment.success');

    Route::get('/payment/cancel', function () {
        return view('theme::front-end.payment-cancel');
    })->name('payment.cancel');

    Route::post('/webhook/stripe', 'StripeController@handleStripeWebhook');
    Route::post('/createCheckoutSession', 'StripeController@createCheckoutSession');
    Route::post('/create-payment-qr', 'StripeController@createPaymentLinkWithQR');

    Route::get('ajaxFE/{action}', 'AjaxFrontendController@index');
    Route::post('ajaxFE/{action}', 'AjaxPostFrontEntController@index');

    //Auth Customer
    Route::get('/logout/customer', 'AuthController@logout');
    Route::post('/login/customer', 'AuthController@login');
    Route::post('/forgot_password/customer', 'AuthController@forgotPassword');
    Route::post('/reset_password/customer', 'AuthController@resetPassword');

    Route::middleware(['check.loyal_customer'])->group(function () {
        Route::get('/my-cart', 'AuthController@myCart');
        Route::get('/find-driver', 'AuthController@findDriver');
        Route::post('/check-out', 'AuthController@checkOut');
        Route::get('/my-account', 'AuthController@myAccount');
        Route::get('/my-order', 'AuthController@myOrder');
        Route::get('/my-wishlist', 'AuthController@myWishlist');
        Route::get('/my-wishlist-product', 'AuthController@myWishlistProduct');
        Route::get('/my-voucher', 'AuthController@myVoucher');
        Route::get('/customer/delete_favorite', 'AuthController@deleteFavorite');
        Route::post('/customer/update_profile', 'AuthController@updateProfile');
    });


    Route::get('/{slug}.html', 'FrontendController@getPage');


    Route::group(['prefix' => '{slugParent}'], function () {
        Route::get('/', 'FrontendController@getListParents');
        Route::get('/{slugDetail}.html', 'FrontendController@getDetail');
    });

});
