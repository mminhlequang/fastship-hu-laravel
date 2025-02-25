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
    //** API-Auth */
    Route::post('/login', 'Api\CustomerController@login');
    Route::post('/register', 'Api\CustomerController@register');
    Route::post('/update_password', 'Api\CustomerController@updatePassword');
    Route::post('/reset_password', 'Api\CustomerController@resetPassword');
    Route::get('/profile', 'Api\CustomerController@getProfile');
    Route::post('/update_profile', 'Api\CustomerController@updateProfile');
    Route::post('/check_phone', 'Api\CustomerController@checkPhone');
    Route::post('/update_device_token', 'Api\CustomerController@updateDeviceToken');
    Route::post('delete_account', 'Api\CustomerController@deleteAccount');


    //** API-News */
    Route::get('/news', 'Api\NewsController@getList');
    Route::get('/news/detail', 'Api\NewsController@detail');

    //** API-Services */
    Route::get('/services', 'Api\ServiceController@getList');

    //** API-Categories */
    Route::get('/categories', 'Api\CategoryController@getList');

    //** API-Product */
    Route::get('/product', 'Api\ProductController@getList');
    Route::get('/product/by_lat_lng', 'Api\StoreController@getListByLatLng');
    Route::get('/product/by_store', 'Api\ProductController@getListByStore');
    Route::get('/product/detail', 'Api\ProductController@detail');
    Route::get('/product/favorite', 'Api\ProductController@getListFavoriteByUser');
    Route::get('/product/rating', 'Api\ProductController@getListRating');

    Route::post('/product/create', 'Api\ProductController@create');
    Route::post('/product/update', 'Api\ProductController@update');
    Route::post('/product/delete', 'Api\ProductController@delete');
    Route::post('/product/favorite/insert', 'Api\ProductController@insertFavorite');
    Route::post('/product/rating/insert', 'Api\ProductController@insertRating');


    //** API-Banners */
    Route::get('/banners', 'Api\BannerController@getListsBanner');

    //** API-Stores */
    Route::get('/store', 'Api\StoreController@getList');
    Route::get('/store/by_lat_lng', 'Api\StoreController@getListByLatLng');
    Route::get('/store/by_user', 'Api\StoreController@getListByUser');
    Route::get('/store/detail', 'Api\StoreController@detail');
    Route::post('/store/create', 'Api\StoreController@create');
    Route::post('/store/update', 'Api\StoreController@update');
    Route::post('/store/delete', 'Api\StoreController@delete');


    //** API-Address Delivery */
    Route::get('/address_delivery', 'Api\StoreController@getList');
    Route::get('/address_delivery/detail', 'Api\AddressDeliveryController@detail');
    Route::post('/address_delivery/create', 'Api\AddressDeliveryController@create');
    Route::post('/address_delivery/update', 'Api\AddressDeliveryController@update');
    Route::post('/address_delivery/delete', 'Api\AddressDeliveryController@delete');

    //** API-Voucher */
    Route::get('/vouchers', 'Api\VoucherController@getList');


    //** API-Driver */
    Route::get('/driver/rating', 'Api\DriverController@getListRating');
    Route::post('/driver/rating/insert', 'Api\DriverController@insertRating');
    Route::post('/driver/upload', 'Api\DriverController@uploadImages');

});
