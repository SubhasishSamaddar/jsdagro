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



Route::post('/registerUser', '\App\Http\Controllers\Auth\RegisterController@registerUser')->name('registerUser');



//Route::get('/', 'PageController@comingsoon')->name('sitelink');



Route::get('/poslogin', 'PosController@login')->name('poslogin');

Route::get('/poslogout', 'PosController@logout')->name('poslogout');

Route::post('/posloginsubmit', 'PosController@posloginsubmit')->name('posloginsubmit');

Route::get('/posdashboard', 'PosController@posdashboard')->name('posdashboard');





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

Route::get('/category/{cat_id}', 'ProductsController@productCategoryListing')->name('category');

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

Route::post('/send-otp-by-login', 'AuthController@sendOtpByLogin')->name('send-otp-by-login');

Route::get('payment/status', ['as' => 'payment.status', 'uses' => 'CheckoutController@status']);



Route::post('/payment-success', 'CheckoutController@success')->name('payment-success');

Route::post('/payment-failure', 'CheckoutController@failure')->name('payment-failure');



Route::post('/get-product-price-by-weight', 'ProductsController@get_product_price_by_weight')->name('get-product-price-by-weight');

Route::post('/get-product-price-by-id', 'ProductsController@get_product_price_by_id')->name('get-product-price-by-id');





Route::post('/filter-products-by-barcode', 'ProductsController@filterProductsByBarcode')->name('filter-products-by-barcode');

Route::post('/filter-products-by-keyword', 'PosController@filterProductsByBarcode')->name('filter-products-by-keyword');

Route::post('/show-all-product', 'PosController@showAllProduct')->name('show-all-product');

Route::post('/filter-products-by-pageno', 'PosController@filterProductsByPageno')->name('filter-products-by-pageno');

Route::post('/set-session-id-array', 'ProductsController@setSessionIdArray')->name('set-session-id-array');

Route::post('/generate_cart_data', 'ProductsController@generateCartData')->name('generate_cart_data');

Route::post('/get-exact-product-by-barcode', 'ProductsController@getExactProductByBarcode')->name('get-exact-product-by-barcode');

Route::post('/show_registerd_user', 'ProductsController@showRegisterdUser')->name('show_registerd_user');

Route::post('/set_user_address', 'ProductsController@setUserAddress')->name('set_user_address');

Route::post('/pos-place-order', 'PosController@place_order')->name('pos-place-order');

Route::get('/pos-order-details/{order_id}', 'WebViewController@pos_order_details_webview');

Route::post('/check-availability', 'ProductsController@checkAvailability')->name('check-availability');

Route::post('/lazy-load', 'PosController@lazyLoad');

Route::post('/lazy-load2', 'PosController@lazyLoad2');



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

    Route::get('cpanel/package_inventory/voucher-details/{voucher_no}', 'Admin\PackageInventoryController@printVoucherDetails');

    Route::get('cpanel/order/datewiseOrderExcelDownload', 'Admin\OrderController@datewiseOrderExcelDownload');

    Route::get('cpanel/products/assigninventoryid', 'Admin\ProductController@assigninventoryid');

    Route::get('cpanel/products/generatestockreport', 'Admin\ProductController@generatestockreport');

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

        Route::get('categories/changenameurl', 'CategoryController@changeNameUrl');

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

        Route::get('products/expiryproductlog', 'ProductController@expiryproductlog')->name('expiryproductlog');

        Route::get('generatecriticalproductreport', 'ProductController@generateCriticalProductReport')->name('generatecriticalproductreport');

        Route::get('products/changenameurl', 'ProductController@changeNameUrl');

        Route::resource('products','ProductController');

        



		Route::get('swadesh_hut/changestatus', 'Swadesh_hutsController@changeStatus');

        Route::resource('stores','Swadesh_hutsController');

        Route::resource('swadesh_hut_pos','swadesh_hut_posController');



		Route::get('package_location/changestatus', 'Package_locationsController@changeStatus');

        Route::resource('package_location','Package_locationsController');



		//Route::get('package_location/changestatus', 'Package_locationsController@changeStatus');

        Route::resource('order','OrderController');

        Route::post('order/datewiseOrderreport', 'OrderController@datewiseOrderreport')->name('datewiseOrderreport');

        



        Route::get('audits', 'AuditController@index')->middleware('auth', \App\Http\Middleware\AllowOnlyAdmin::class)->name('audits');



        Route::get('user-contacts', 'UserContactController@userContacts')->name('user-contacts');



        Route::get('contact-us-info', 'UserContactController@contactUsInfo')->name('contact-us-info');



        Route::get('/online-payments', 'OnlinePaymentController@index')->name('online-payments');



        ///// For Package Inventroy Management

		Route::get('package_inventory/import', 'PackageInventoryController@packageInventoryImport')->name('piimport');

		Route::post('package_inventory/import', 'PackageInventoryController@packageInventoryImport')->name('piimport');

        Route::get('package_inventory/export', 'PackageInventoryController@exportCsv')->name('piexport');

        Route::get('package_inventory/stockinlogexport', 'PackageInventoryController@stockInLog')->name('stockinlogexport');

        Route::get('package_inventory/datewisereport', 'PackageInventoryController@datewiseReport')->name('datewisereport');

        Route::post('package_inventory/pidatewiseexport', 'PackageInventoryController@datewiseExportCsv')->name('pidatewiseexport');

        Route::get('package_inventory/changestatus', 'PackageInventoryController@changeStatus')->middleware('can:package_inventory-create');

        Route::get('package_inventory/downloadsamplecsv', 'PackageInventoryController@downloadSampleCsv');

        Route::get('package_inventory/inventory-in-log', 'PackageInventoryController@inventoryInLog');

        Route::get('package_inventory/inventory-out-log', 'PackageInventoryController@inventoryOutLog');

        

        Route::resource('package_inventory','PackageInventoryController');

        Route::resource('company-payouts','CompanyPayoutController');


		// For Package Inventory out process

        Route::post('package_inventory/inventory_out_process', 'PackageInventoryController@inventoryOutProcess');

        Route::post('package_inventory/inventory_stock_check', 'PackageInventoryController@inventoryStockCheck');



        Route::post('package_inventory/stock_in_process', 'PackageInventoryController@stockInProcess');

        Route::post('package_inventory/bulk_stock_out_process', 'PackageInventoryController@bulkStockOutProcess');
        Route::get('company-payout/get-log', 'CompanyPayoutController@getLog');

        Route::post('voucher/changestatus', 'PackageInventoryController@voucherChangeStatus');
        Route::post('voucher/showdetails', 'PackageInventoryController@voucherShowDetails');
        Route::post('products/returnproduct', 'ProductController@returnProduct');


        Route::get('image-crop', 'ImageController@imageCrop');
        Route::post('image-crop', 'ImageController@imageCropPost');

        Route::post('package_inventory/inventory_search', 'PackageInventoryController@inventorySearch');

        Route::post('package_inventory/show_search_data', 'PackageInventoryController@showSearchData');

        Route::post('product/product_search', 'ProductController@productSearch');
        Route::post('product/show_search_data', 'ProductController@showSearchData');


        Route::get('add-package-inventory-id-in-product', 'ProductController@addPIDinProdcuct');

        Route::get('returned-product', 'ReturnedProductController@index');

    });

});







//Route::get('/{url}', 'PageController@index');

