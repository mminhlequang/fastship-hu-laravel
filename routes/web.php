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
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');


Route::auth();

Route::get('ajax/{action}', 'AjaxController@index');

Route::post('ajaxPost/{action}', 'AjaxPostController@index');


Route::group(['prefix' => 'admin','middleware' => ['auth', 'locale']], function () {
	Route::get('/', 'HomeController@index');

	Route::resource('roles', 'Admin\RolesController');
	Route::resource('permissions', 'Admin\PermissionsController');
	Route::resource('users', 'Admin\UsersController');
    Route::resource('notifications', 'Admin\NotificationController');
    Route::resource('customers', 'Admin\CustomerController');

	Route::resource('steps', 'Admin\StepController');
	Route::resource('cars', 'Admin\CustomerCarController');
	Route::resource('drivers', 'Admin\DriverController');

	Route::resource('partners', 'Admin\PartnerController');

    Route::resource('bookings', 'Admin\BookingController');
    Route::resource('discounts', 'Admin\DiscountController');
    Route::resource('address_delivery', 'Admin\AddressDeliveryController');
    Route::resource('approves', 'Admin\ApproveController');

    Route::resource('services', 'Admin\ServiceController');
    Route::resource('stores', 'Admin\StoreController');
    Route::resource('transactions', 'Admin\TransactionController')->except(['create']);
    Route::resource('withdrawals', 'Admin\WithdrawalController')->except(['create']);

    Route::resource('news', 'Admin\NewsController');
    Route::resource('products', 'Admin\ProductController');
    Route::resource('groups', 'Admin\GroupController');
    Route::resource('toppings', 'Admin\ToppingController');
    Route::resource('categories', 'Admin\CategoryController');
    Route::resource('banners', 'Admin\BannerController');
    Route::resource('provinces', 'Admin\ProvinceController');
    Route::resource('districts', 'Admin\DistrictController');
    Route::resource('wards', 'Admin\WardController');
    Route::resource('contacts', 'Admin\ContactController');
    Route::resource('payments', 'Admin\PaymentController');
    Route::resource('payments_accounts', 'Admin\PaymentAccountController')->except(['create', 'update']);

	Route::get('profile', 'Admin\ProfileController@getProfile');
	Route::post('profile', 'Admin\ProfileController@postProfile');

    Route::get('settings', 'SettingController@index');
	Route::patch('settings', 'SettingController@update');
	Route::post('change_locale', 'LocaleController@changeLocale');


});
