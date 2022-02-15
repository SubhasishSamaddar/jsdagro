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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
        //Route::get('signup/activate/{token}', 'AuthController@signupActivate');
        Route::group([
        'middleware' => 'auth:api'
        ], function() {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });
});

Route::group([
        'namespace' => 'Auth',
        'middleware' => 'api',
        'prefix' => 'password'
    ], function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
        Route::post('reset', 'PasswordResetController@reset');

});
Route::middleware('auth:api')->group( function () {
    /*
	Route::resource('countries', 'API\CountryController');
    Route::resource('states', 'API\StateController');
    Route::get('categories', 'API\CategoryController@index');
    Route::get('products', 'API\ProductController@index');
	*/
	Route::get('banner', 'API\StateController@banner');
	Route::get('states/{country_id}', 'API\StateController@statesByCountry');
	Route::get('order/{id}', 'API\OrderMasterController@orderDetails');
	Route::get('myorder', 'API\OrderMasterController@listing');
	Route::post('update-profile', 'API\UserController@updateProfile');

    //cancel or return order
    Route::post('cancel-order', 'API\OrderMasterController@cancelOrder');
    Route::post('return-order', 'API\OrderMasterController@returnOrder');
    

	Route::post('change-password', 'API\UserController@change_password');
	Route::get('swadesh-hut-by-pincode/{pincode}', 'API\OrderMasterController@checkPicode');
	Route::get('categories', 'API\CategoryController@listing');
	Route::get('categories-by-parent-id/{parent_id}', 'API\CategoryController@listing');
	Route::get('products', 'API\ProductController@listing');
    Route::get('product-details', 'API\ProductController@productDetails');
    Route::get('featured-products', 'API\ProductController@featuredProductListing');
	Route::get('product-details', 'API\ProductController@productDetails');
	//Route::post('place-order', 'API\OrderMasterController@placeOrder');
	Route::get('order-details/{id}', 'API\OrderMasterController@orderInduvisualDetails');
	Route::post('place-order-item', 'API\OrderMasterController@placeOrderDetails');
	Route::post('place-order', 'API\OrderMasterController@placeOrderDetails1');
});




//Testing For App Created By Subhasish
Route::get('testcategories', 'API\CategoryController@testlisting');
Route::get('testproducts', 'API\ProductController@testlisting');
Route::get('testcategoryproducts', 'API\ProductController@productCategoryListing');
Route::get('testbanners', 'API\CategoryController@testbannerslisting');