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
    Route::post('/login_phone_otp', 'Api\CustomerController@loginPhoneOtp');
    Route::post('/register', 'Api\CustomerController@register');
    Route::post('/reset_password', 'Api\CustomerController@resetPassword');
    Route::post('/check_phone', 'Api\CustomerController@checkPhone');
    Route::post('/transaction/stripe_webhook', 'Api\TransactionController@stripeWebhook');

    //** API-Config */
    Route::get('/config', 'Api\ConfigController@getConfig');
    Route::get('/support_channels', 'Api\ConfigController@getSupportChannel');

    //** API-Categories */
    Route::get('/categories/get_categories', 'Api\CategoryController@getCategories');
    Route::get('/categories/detail', 'Api\CategoryController@detail');

    //** API-News */
    Route::get('/news/get_news', 'Api\NewsController@getList');
    Route::get('/news/detail', 'Api\NewsController@detail');

    //** API-Services */
    Route::get('/get_services', 'Api\ServiceController@getList');

    //** API-Product */
    Route::get('/product/get_products', 'Api\ProductController@getProducts');
    Route::get('/product/detail', 'Api\ProductController@detail');

    //** API-Banners */
    Route::get('/banners', 'Api\BannerController@getListsBanner');

    //** API-Stores */
    Route::get('/store/get_stores', 'Api\StoreController@getStores');
    Route::get('/store/by_lat_lng', 'Api\StoreController@getListByLatLng');
    Route::get('/store/get_my_stores', 'Api\StoreController@getListByUser');
    Route::get('/store/get_menus', 'Api\StoreController@getMenus');
    Route::get('/store/detail', 'Api\StoreController@detail');


    //** API-Wallet */
    Route::get('/transaction/get_payment_wallet_provider', 'Api\TransactionController@getPaymentWallet');

    //** API-Rate */
    Route::get('/rating/get_rating_product', 'Api\RatingController@getRatingProduct');
    Route::get('/rating/get_rating_store', 'Api\RatingController@getRatingStore');
    Route::get('/rating/get_rating_driver', 'Api\RatingController@getRatingDriver');

    //**API-Order */
    Route::get('/order/detail', 'Api\OrderController@detail');
    Route::post('/order/update', 'Api\OrderController@update');

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
    Route::get('/notification/get_notifications', 'Api\NotificationController@getList');
    Route::get('/notification/detail', 'Api\NotificationController@detail');
    Route::post('/notification/delete', 'Api\NotificationController@delete');
    Route::post('/notification/read_all', 'Api\NotificationController@readAll');
    Route::post('/notification/sent_customize_notification', 'Api\NotificationController@sendNotification');


    //** API-Categories */
    Route::post('/categories/create', 'Api\CategoryController@create');
    Route::post('/categories/delete', 'Api\CategoryController@delete');

    //** API-Product */
    Route::get('/product/get_favorites', 'Api\ProductController@getListFavoriteByUser');

    Route::post('/product/create', 'Api\ProductController@create');
    Route::post('/product/update', 'Api\ProductController@update');
    Route::post('/product/delete', 'Api\ProductController@delete');
    Route::post('/product/upload', 'Api\ProductController@uploadImage');
    Route::post('/product/favorite/insert', 'Api\ProductController@insertFavorite');

    //** API-Topping */
    Route::get('/topping/get_my_stores', 'Api\ToppingController@getMyStores');
    Route::post('/topping/create', 'Api\ToppingController@create');
    Route::post('/topping/update', 'Api\ToppingController@update');
    Route::post('/topping/delete', 'Api\ToppingController@delete');

    //** API-Group Topping */
    Route::get('/group/get_my_stores', 'Api\ToppingGroupController@getMyStores');
    Route::post('/group/create', 'Api\ToppingGroupController@create');
    Route::post('/group/update', 'Api\ToppingGroupController@update');
    Route::post('/group/delete', 'Api\ToppingGroupController@delete');

    //** API-Variation */
    Route::post('/variation/create', 'Api\VariationController@create');
    Route::post('/variation/update', 'Api\VariationController@update');
    Route::post('/variation/delete', 'Api\VariationController@delete');

    //** API-Stores */
    Route::post('/store/create', 'Api\StoreController@create');
    Route::post('/store/update', 'Api\StoreController@update');
    Route::post('/store/delete', 'Api\StoreController@delete');
    Route::post('/store/upload', 'Api\StoreController@uploadImage');
    Route::post('/store/favorite/insert', 'Api\StoreController@insertFavorite');
    Route::post('/store/sort_categories', 'Api\StoreController@sortCategories');
    Route::post('/store/sort_toppings', 'Api\StoreController@sortToppings');
    Route::post('/store/sort_products', 'Api\StoreController@sortProducts');


    //** API-Rate */
    Route::post('/rating/insert_rating_product', 'Api\RatingController@insertRatingProduct');
    Route::post('/rating/reply_rating_product', 'Api\RatingController@replyRatingProduct');
    Route::post('/rating/insert_rating_store', 'Api\RatingController@insertRatingStore');
    Route::post('/rating/reply_rating_store', 'Api\RatingController@replyRatingStore');
    Route::post('/rating/insert_rating_driver', 'Api\RatingController@insertRatingDriver');
    Route::post('/rating/upload', 'Api\RatingController@uploadFile');


    //** API-Address Delivery */
    Route::get('/address_delivery/get_my_address', 'Api\AddressDeliveryController@getList');
    Route::get('/address_delivery/detail', 'Api\AddressDeliveryController@detail');
    Route::post('/address_delivery/create', 'Api\AddressDeliveryController@create');
    Route::post('/address_delivery/update', 'Api\AddressDeliveryController@update');
    Route::post('/address_delivery/delete', 'Api\AddressDeliveryController@delete');

    //** API-Voucher */
    Route::get('/voucher/get_vouchers', 'Api\VoucherController@getVouchers');
    Route::get('/voucher/get_vouchers_user_saved', 'Api\VoucherController@getListByUser');
    Route::get('/voucher/detail', 'Api\VoucherController@detail');
    Route::post('/voucher/create', 'Api\VoucherController@create');
    Route::post('/voucher/update', 'Api\VoucherController@update');
    Route::post('/voucher/save', 'Api\VoucherController@save');
    Route::post('/voucher/delete', 'Api\VoucherController@delete');
    Route::post('/voucher/check_voucher', 'Api\VoucherController@checkVoucher');

    //** API-Driver */
    Route::get('/driver/cars', 'Api\DriverController@getListCars');
    Route::get('/driver/get_my_team', 'Api\DriverController@getMyTeam');
    Route::get('/driver/payment_method', 'Api\DriverController@getListPayment');
    Route::get('/driver/steps', 'Api\DriverController@getListSteps');
    Route::post('/driver/steps/confirm', 'Api\DriverController@confirmStep');
    Route::post('/driver/upload', 'Api\DriverController@uploadImage');
    Route::post('/driver/update_profile', 'Api\DriverController@updateProfile');
    Route::post('/driver/update_status_online', 'Api\DriverController@updateStatusOnline');

    Route::get('/driver/statistics/overview', 'Api\DriverStaticController@getStaticOverView');
    Route::get('/driver/statistics/income-chart', 'Api\DriverStaticController@getStaticIncomeChart');
    Route::get('/driver/statistics/trips-chart', 'Api\DriverStaticController@getStaticStripsChart');
    Route::get('/driver/statistics/income-breakdown', 'Api\DriverStaticController@getStaticIncomeBreakDown');
    Route::get('/driver/statistics/time-chart', 'Api\DriverStaticController@getStaticTimeChart');
    Route::get('/driver/statistics/details', 'Api\DriverStaticController@getStaticDetail');


    //** API-Wallet */
    Route::get('/transaction', 'Api\TransactionController@getList');
    Route::get('/transaction/get_static_driver', 'Api\TransactionController@getStaticDriver');
    Route::get('/transaction/get_report_driver', 'Api\TransactionController@getReportDriver');
    Route::get('/transaction/get_payment_accounts', 'Api\TransactionController@getPaymentAccount');
    Route::post('/transaction/create_payment_accounts', 'Api\TransactionController@createPaymentAccount');
    Route::post('/transaction/update_payment_accounts', 'Api\TransactionController@updatePaymentAccount');
    Route::post('/transaction/delete_payment_accounts', 'Api\TransactionController@deletePaymentAccount');

    Route::get('/transaction/detail', 'Api\TransactionController@detail');
    Route::get('/transaction/get_my_wallet', 'Api\TransactionController@getMyWallet');
    Route::post('/transaction/request_topup', 'Api\TransactionController@requestTopup');
    Route::post('/transaction/request_withdraw', 'Api\TransactionController@requestWithdraw');

    //** API-Cart */
    Route::get('/cart/get_carts_by_user', 'Api\CartController@getList');
    Route::post('/cart/create', 'Api\CartController@create');
    Route::post('/cart/update', 'Api\CartController@update');
    Route::post('/cart/delete', 'Api\CartController@delete');


    //** API-Order */
    Route::get('/order/get_orders_by_user', 'Api\OrderController@getOrdersByUser');
    Route::get('/order/get_orders_by_store', 'Api\OrderController@getOrdersByStore');
    Route::get('/order/preview_calculate_order', 'Api\OrderController@previewCalculate');
    Route::post('/order/create', 'Api\OrderController@create');
    Route::post('/order/assigned_driver', 'Api\OrderController@assigned');
    Route::post('/order/complete', 'Api\OrderController@complete');
    Route::post('/order/cancel', 'Api\OrderController@cancel');


    //**API-Report */
    Route::get('/reports/overview', 'Api\ReportController@getOverview');
    Route::get('/reports/revenue-chart', 'Api\ReportController@getRevenueChart');
    Route::get('/reports/top-selling-items', 'Api\ReportController@getTopSellingItem');
    Route::get('/reports/recent-reviews', 'Api\ReportController@getRecentReviews');
    Route::get('/reports/recent-orders', 'Api\ReportController@getRecentOrders');
    Route::get('/reports/cancelled-orders', 'Api\ReportController@getCancelledOrders');
    Route::get('/reports/performance-metrics', 'Api\ReportController@getPerformanceMetrics');

});
