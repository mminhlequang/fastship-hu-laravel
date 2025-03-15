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


Route::get('/', 'FrontendController@index');

Route::get('ajaxFE/{action}', 'AjaxFrontEntController@index');
Route::post('ajaxFE/{action}', 'AjaxPostFrontEntController@index');

Route::get('/{slug}.html', 'FrontendController@getPage');

//Auth Customer
Route::get('/logout/customer', 'AuthController@logout');
Route::post('/login/customer', 'AuthController@login');
Route::post('/register/customer', 'AuthController@register');
Route::post('/forgot_password/customer', 'AuthController@forgotPassword');
Route::post('/reset_password/customer', 'AuthController@resetPassword');

Route::group(['prefix' => '{slugParent}'], function () {
    Route::get('/', 'FrontendController@getListParents')->name('slugParent.getListParents');
    Route::get('/{slugDetail}.html', 'FrontendController@getDetail')->name('slugDetail.getDetail');
});
