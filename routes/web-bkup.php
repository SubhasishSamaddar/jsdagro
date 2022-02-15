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


Route::get('/', function () {
    return view('welcome');
})->name('sitelink');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');



//Route::get('/', 'PageController@comingsoon')->name('sitelink');


Route::get('/home', 'PageController@homepage');

Route::get('/signup/activate/{token}', 'AuthController@signupActivate');

Route::get('/help', 'PageController@contactpage')->name('help');   
Route::get('/contact-us', 'PageController@contactus')->name('contact-us');    
Route::get('/legal-notice', 'PageController@legalnoticepage')->name('legal-notice');
Route::get('/terms-and-conditions', 'PageController@termsandconditionspage')->name('terms-and-conditions');
Route::get('/about-us', 'PageController@aboutuspage')->name('about-us');  
Route::get('/sitemap', 'PageController@sitemappage')->name('sitemap');  
Route::get('/faq', 'PageController@faqpage')->name('faq');  
Route::get('/ckeditor', 'CkeditorController@index');  
Route::post('ckeditor/image_upload', 'CkeditorController@upload')->name('upload');
Route::get('/get-swadesh-hut', 'ProductsController@getSwadeshHut')->name('get-swadesh-hut');
Route::get('/product-listing', 'ProductsController@listing')->name('product-listing');
Route::get('/category-wise-product-listing/{id}', 'ProductsController@categoryWiseListing')->name('category-wise-product-listing');
Route::get('/category-wise-product-listing-pagination', 'ProductsController@categoryWiseListingPagination')->name('category-wise-product-listing-pagination');
Route::get('/products/{id}', 'ProductsController@details')->name('products');
Route::get('/product-add-to-cart', 'ProductsController@productAddToCart')->name('product-add-to-cart');
Route::get('/product-remove-from-cart', 'ProductsController@productRemoveFromCart')->name('product-remove-from-cart');
Route::get('/product-update-cart', 'ProductsController@productUpdateCart')->name('product-update-cart');
Route::get('/cart', 'ProductsController@cart')->name('cart');
Route::get('/category-listing', 'CategoriesController@categoryListing')->name('category-listing');
Route::get('/search-result', 'ProductsController@productSearchListing')->name('search-result');   
Route::get('/stock-alert', 'ProductsController@stockAlert')->name('stock-alert');
Route::get('/product-category/{cat_id}', 'ProductsController@productCategoryListing')->name('product-category');
Route::get('/my-order-details/{order_id}', 'WebViewController@order_details_webview');

Route::get('/brands/{brand_id}', 'ProductsController@brand');


Route::get('/autocomplete', 'AutocompleteController@autocomplete');
Route::get('/featured-products', 'ProductsController@featuredProduct')->name('featured-products');
Route::post('/set-cookie', 'PageController@setCookie')->name('set-cookie');
Route::post('/save-help-data', 'PageController@saveHelpData')->name('save-help-data');
Route::post('/save-contactus-data', 'PageController@saveContactUsData')->name('save-contactus-data');
Route::post('/check_pin', 'CheckoutController@checkPin')->name('check_pin');
Route::post('/cancel_order', 'OrderController@cancelOrder')->name('cancel_order');
Route::post('/return_order', 'OrderController@returnOrder')->name('return_order');
Route::post('/update_profile', 'AuthController@updateProfile')->name('update_profile');
Route::post('/send-otp', 'AuthController@sendOtp')->name('send-otp');
Route::get('payment/status', ['as' => 'payment.status', 'uses' => 'CheckoutController@status']);


Route::post('/get-product-price-by-weight', 'ProductsController@get_product_price_by_weight')->name('get-product-price-by-weight');
Route::post('/get-product-price-by-id', 'ProductsController@get_product_price_by_id')->name('get-product-price-by-id');

//Auth::routes();  
Auth::routes(['verify' => true]);
Route::group(['middleware' => ['auth']], function() {
    Route::get('/shipping', 'CheckoutController@shipping')->name('shipping');
    Route::post('/shipping', 'CheckoutController@shipping')->name('shipping');
    Route::post('/payment', 'CheckoutController@payment')->name('payment');
    Route::post('/place-order', 'CheckoutController@place_order')->name('place-order');
	Route::get('/my-order', 'OrderController@myOrder')->name('my-order');
	Route::get('/my-profile', 'OrderController@myProfile')->name('my-profile');
    Route::get('/order-details/{id}', 'OrderController@myOrderDetails')->name('order-details');

});
Route::group([
    'prefix' => 'cpanel',
    'namespace' => 'Admin',
], function () {
    Route::group(['middleware' => ['auth']], function() {

		Route::get('/home', 'HomeController@index')->name('home');

        Route::resource('users','UserController');

        Route::get('profile_update', 'UserController@profile_update');
        Route::post('profile_update', 'UserController@store_profile')->name('change.profile');
        Route::get('/profile_update/getStates/{id}', 'UserController@getStates');

        Route::get('change-password', 'ChangePasswordController@index');
        Route::post('change-password', 'ChangePasswordController@store')->name('change.password');

        Route::resource('roles','RoleController');

        Route::get('email_templates/changestatus', 'EmailTemplateController@changeStatus');
        Route::resource('email_templates','EmailTemplateController');

        Route::get('navigations/changestatus', 'NavigationController@changeStatus');
        Route::resource('navigations','NavigationController');

        Route::get('countries/changestatus', 'CountriesController@changeStatus');
        Route::resource('countries','CountriesController');

        Route::resource('states','StatesController');

        Route::resource('gridview','DemoController');

        Route::get('categories/changestatus', 'CategoryController@changeStatus');
        Route::get('categories/changeshownstatus', 'CategoryController@changeshownstatus');
        Route::resource('categories','CategoryController');

        Route::get('banner/updatetext', 'BannerController@updateText');
        Route::get('banner/updateurl', 'BannerController@updateUrl');
        Route::get('banner/deleteimage', 'BannerController@deleteImage');
        Route::resource('banner','BannerController');

        Route::resource('brands','BrandController');



        Route::get('products/changediscount', 'ProductController@changeDiscount');
        Route::get('products/changestockalert', 'ProductController@changeStockAlert');
        Route::get('products/changestatus', 'ProductController@changeStatus');
        Route::get('products/import', 'ProductController@productImport')->name('import');
        Route::get('products/export', 'ProductController@exportCsv')->name('export');
        Route::post('products/import', 'ProductController@productImport')->name('import');
        Route::get('generatecriticalproductreport', 'ProductController@generateCriticalProductReport')->name('generatecriticalproductreport');
        Route::resource('products','ProductController');

		Route::get('swadesh_hut/changestatus', 'Swadesh_hutsController@changeStatus');
        Route::resource('swadesh_hut','Swadesh_hutsController');

		Route::get('package_location/changestatus', 'Package_locationsController@changeStatus');
        Route::resource('package_location','Package_locationsController');

		//Route::get('package_location/changestatus', 'Package_locationsController@changeStatus');
        Route::resource('order','OrderController');

        Route::get('audits', 'AuditController@index')->middleware('auth', \App\Http\Middleware\AllowOnlyAdmin::class)->name('audits');

        Route::get('user-contacts', 'UserContactController@userContacts')->name('user-contacts');

        Route::get('contact-us-info', 'UserContactController@contactUsInfo')->name('contact-us-info');

        Route::get('/online-payments', 'OnlinePaymentController@index')->name('online-payments');

        ///// For Package Inventroy Management
		Route::get('package_inventory/import', 'PackageInventoryController@packageInventoryImport')->name('piimport');
		Route::post('package_inventory/import', 'PackageInventoryController@packageInventoryImport')->name('piimport');
        Route::get('package_inventory/export', 'PackageInventoryController@exportCsv')->name('piexport');
        Route::get('package_inventory/datewisereport', 'PackageInventoryController@datewiseReport')->name('datewisereport');
        Route::post('package_inventory/pidatewiseexport', 'PackageInventoryController@datewiseExportCsv')->name('pidatewiseexport');
        Route::get('package_inventory/changestatus', 'PackageInventoryController@changeStatus')->middleware('can:package_inventory-create');
        Route::get('package_inventory/downloadsamplecsv', 'PackageInventoryController@downloadSampleCsv');
        Route::resource('package_inventory','PackageInventoryController');
		// For Package Inventory out process
        Route::post('package_inventory/inventory_out_process', 'PackageInventoryController@inventoryOutProcess');
        Route::post('package_inventory/inventory_stock_check', 'PackageInventoryController@inventoryStockCheck');


    });
});



//Route::get('/{url}', 'PageController@index');
