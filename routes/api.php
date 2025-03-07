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
    Route::post('/reset_password', 'Api\CustomerController@resetPassword');
    Route::post('/check_phone', 'Api\CustomerController@checkPhone');

    Route::post('/transaction/stripe_webhook', 'Api\TransactionController@stripeWebhook');

});


Route::prefix('v1')->middleware(['language', 'auth:api'])->group(function () {

    //** API-Auth */
    Route::post('/refresh_token', 'Api\CustomerController@refreshToken');
    Route::post('/update_password', 'Api\CustomerController@updatePassword');
    Route::get('/profile', 'Api\CustomerController@getProfile');
    Route::post('/update_profile', 'Api\CustomerController@updateProfile');
    Route::post('/update_device_token', 'Api\CustomerController@updateDeviceToken');
    Route::post('delete_account', 'Api\CustomerController@deleteAccount');

    //** API-Notification */
    Route::get('/notification', 'Api\NotificationController@getList');
    Route::get('/notification/detail', 'Api\NotificationController@detail');
    Route::post('/notification/delete', 'Api\NotificationController@delete');
    Route::post('/notification/read_all', 'Api\NotificationController@readAll');

    //** API-News */
    Route::get('/news', 'Api\NewsController@getList');
    Route::get('/news/detail', 'Api\NewsController@detail');

    //** API-Services */
    Route::get('/services', 'Api\ServiceController@getList');

    //** API-Categories */
    Route::get('/categories', 'Api\CategoryController@getList');
    Route::get('/categories/by_store', 'Api\CategoryController@getListByStore');
    Route::post('/categories/create', 'Api\CategoryController@create');
    Route::post('/categories/update', 'Api\CategoryController@update');
    Route::post('/categories/delete', 'Api\CategoryController@delete');

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
    Route::post('/product/rating/reply', 'Api\ProductController@replyRating');

    //** API-Topping */
    Route::get('/topping', 'Api\TopingController@getList');
    Route::post('/topping/create', 'Api\ToppingController@create');
    Route::post('/topping/update', 'Api\ToppingController@update');
    Route::post('/topping/delete', 'Api\ToppingController@delete');

    //** API-Group Topping */
    Route::get('/group', 'Api\ToppingGroupController@getList');
    Route::post('/group/create', 'Api\ToppingGroupController@create');
    Route::post('/group/update', 'Api\ToppingGroupController@update');
    Route::post('/group/delete', 'Api\ToppingGroupController@delete');

    //** API-Banners */
    Route::get('/banners', 'Api\BannerController@getListsBanner');

    //** API-Stores */
    Route::get('/store', 'Api\StoreController@getList');
    Route::get('/store/by_lat_lng', 'Api\StoreController@getListByLatLng');
    Route::get('/store/by_user', 'Api\StoreController@getListByUser');
    Route::get('/store/detail', 'Api\StoreController@detail');
    Route::get('/store/rating', 'Api\StoreController@getListRating');

    Route::post('/store/create', 'Api\StoreController@create');
    Route::post('/store/update', 'Api\StoreController@update');
    Route::post('/store/delete', 'Api\StoreController@delete');
    Route::post('/store/upload', 'Api\StoreController@uploadImage');
    Route::post('/store/rating/insert', 'Api\StoreController@insertRating');
    Route::post('/store/rating/reply', 'Api\StoreController@replyRating');


    //** API-Address Delivery */
    Route::get('/address_delivery', 'Api\StoreController@getList');
    Route::get('/address_delivery/detail', 'Api\AddressDeliveryController@detail');
    Route::post('/address_delivery/create', 'Api\AddressDeliveryController@create');
    Route::post('/address_delivery/update', 'Api\AddressDeliveryController@update');
    Route::post('/address_delivery/delete', 'Api\AddressDeliveryController@delete');

    //** API-Voucher */
    Route::get('/voucher', 'Api\VoucherController@getList');
    Route::get('/voucher/by_user', 'Api\VoucherController@getListByUser');
    Route::get('/voucher/detail', 'Api\VoucherController@detail');
    Route::post('/voucher/create', 'Api\VoucherController@create');
    Route::post('/voucher/update', 'Api\VoucherController@update');
    Route::post('/voucher/save', 'Api\VoucherController@save');
    Route::post('/voucher/delete', 'Api\VoucherController@delete');

    //** API-Driver */
    Route::get('/driver/rating', 'Api\DriverController@getListRating');
    Route::get('/driver/cars', 'Api\DriverController@getListCars');
    Route::get('/driver/payment_method', 'Api\PaymentController@getListPayment');
    Route::get('/driver/steps', 'Api\DriverController@getListSteps');
    Route::post('/driver/steps/confirm', 'Api\DriverController@confirmStep');
    Route::post('/driver/rating/insert', 'Api\DriverController@insertRating');
    Route::post('/driver/upload', 'Api\DriverController@uploadImage');
    Route::post('/driver/update_profile', 'Api\DriverController@updateProfile');

    //** API-WalletTransaction */
    Route::get('/transaction', 'Api\TransactionController@getList');
    Route::get('/transaction/detail', 'Api\TransactionController@detail');
    Route::get('/transaction/get_my_wallet', 'Api\TransactionController@getMyWallet');
    Route::post('/transaction/request_topup', 'Api\TransactionController@requestTopup');
    Route::post('/transaction/request_withdraw', 'Api\TransactionController@requestWithdraw');

    //** API-Cart */
    Route::get('/cart', 'Api\CartController@getList');
    Route::post('/cart/create', 'Api\CartController@create');
    Route::post('/cart/update', 'Api\CartController@update');
    Route::post('/cart/delete', 'Api\CartController@delete');

});
