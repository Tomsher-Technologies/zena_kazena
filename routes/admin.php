<?php

/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register admin routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

use App\Http\Controllers\Admin\AbandonedCartController;
use App\Http\Controllers\Admin\ShopsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessSettingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerBulkUploadController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return view('backend.test');
});

Route::get('/admin', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard')->middleware(['auth', 'admin']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    //Update Routes
    Route::resource('categories', CategoryController::class);
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    // Route::get('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/status', [CategoryController::class, 'updateStatus'])->name('categories.status');

    Route::get('/generate-slug', [CategoryController::class, 'generateSlug'])->name('generate-slug');

    Route::resource('brands', BrandController::class);
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('/brands/status', [BrandController::class, 'updateStatus'])->name('brands.status');
    // Route::get('/brands/destroy/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Route::get('/products/admin', [ProductController::class, 'admin_products'])->name('products.admin');
    // Route::get('/products/seller', [ProductController::class, 'seller_products'])->name('products.seller');
    Route::get('/products/all', [ProductController::class, 'all_products'])->name('products.all');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/admin/{id}/edit', [ProductController::class, 'admin_product_edit'])->name('products.admin.edit');
    Route::get('/products/seller/{id}/edit', [ProductController::class, 'seller_product_edit'])->name('products.seller.edit');
    Route::post('/products/todays_deal', [ProductController::class, 'updateTodaysDeal'])->name('products.todays_deal');
    Route::post('/products/featured', [ProductController::class, 'updateFeatured'])->name('products.featured');
    Route::post('/products/approved', [ProductController::class, 'updateProductApproval'])->name('products.approved');
    Route::post('/products/get_products_by_subcategory', [ProductController::class, 'get_products_by_subcategory'])->name('products.get_products_by_subcategory');
    // Route::post('/bulk-product-delete', [ProductController::class, 'bulk_product_delete'])->name('bulk-product-delete');


    Route::post('/bulk-shop-delete', [ShopsController::class, 'bulk_shop_delete'])->name('bulk-shop-delete');
    Route::get('/shops/destroy/{id}', [ShopsController::class, 'destroy'])->name('admin.shops.destroy');
    Route::resource('shops', ShopsController::class, [
        'as' => 'admin'
    ]);
    Route::post('/shops/delete/', [ShopsController::class, 'delete'])->name('admin.shops.delete');
    Route::get('/shops/edit/{id}', [ShopsController::class, 'edit'])->name('admin.shops.edit');
    Route::post('/shops/update/{id}', [ShopsController::class, 'update'])->name('admin.shops.update');

    Route::resource('customers', CustomerController::class);
    Route::get('customers_ban/{customer}', [CustomerController::class, 'ban'])->name('customers.ban');
    Route::get('/customers/login/{id}', [CustomerController::class, 'login'])->name('customers.login');
    Route::get('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/bulk-customer-delete', [CustomerController::class, 'bulk_customer_delete'])->name('bulk-customer-delete');
    Route::post('/bulk-customer-delete', [CustomerController::class, 'bulk_customer_delete'])->name('bulk-customer-delete');
    Route::get('/addresses/set_default/{customer}/{id}', [CustomerController::class, 'address_set_default'])->name('admin.addresses.set_default');

    // Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletters.index');
    // Route::post('/newsletter/send', [NewsletterController::class, 'send'])->name('newsletters.send');
    // Route::post('/newsletter/test/smtp', [NewsletterController::class, 'testEmail'])->name('test.smtp');

    Route::resource('profile', ProfileController::class);

    Route::post('/business-settings/update', [BusinessSettingsController::class, 'update'])->name('business_settings.update');
    Route::post('/business-settings/update/activation', [BusinessSettingsController::class, 'updateActivationSettings'])->name('business_settings.update.activation');
    Route::get('/general-setting', [BusinessSettingsController::class, 'general_setting'])->name('general_setting.index');
    // Route::get('/activation', [BusinessSettingsController::class,'activation'])->name('activation.index');
    // Route::get('/payment-method', [BusinessSettingsController::class,'payment_method'])->name('payment_method.index');
    // Route::get('/file_system', [BusinessSettingsController::class,'file_system'])->name('file_system.index');
    // Route::get('/social-login', [BusinessSettingsController::class,'social_login'])->name('social_login.index');
    Route::get('/smtp-settings', [BusinessSettingsController::class, 'smtp_settings'])->name('smtp_settings.index');
    Route::get('/google-analytics', [BusinessSettingsController::class, 'google_analytics'])->name('google_analytics.index');
    Route::get('/google-recaptcha', [BusinessSettingsController::class, 'google_recaptcha'])->name('google_recaptcha.index');
    Route::get('/google-map', [BusinessSettingsController::class, 'google_map'])->name('google-map.index');
    Route::get('/google-firebase', [BusinessSettingsController::class, 'google_firebase'])->name('google-firebase.index');

    //Facebook Settings
    Route::get('/facebook-chat', [BusinessSettingsController::class, 'facebook_chat'])->name('facebook_chat.index');
    Route::post('/facebook_chat', [BusinessSettingsController::class, 'facebook_chat_update'])->name('facebook_chat.update');
    Route::get('/facebook-comment', [BusinessSettingsController::class, 'facebook_comment'])->name('facebook-comment');
    Route::post('/facebook-comment', [BusinessSettingsController::class, 'facebook_comment_update'])->name('facebook-comment.update');
    Route::post('/facebook_pixel', [BusinessSettingsController::class, 'facebook_pixel_update'])->name('facebook_pixel.update');

    Route::post('/env_key_update', [BusinessSettingsController::class, 'env_key_update'])->name('env_key_update.update');
    Route::post('/payment_method_update', [BusinessSettingsController::class, 'payment_method_update'])->name('payment_method.update');
    Route::post('/google_analytics', [BusinessSettingsController::class, 'google_analytics_update'])->name('google_analytics.update');
    Route::post('/google_recaptcha', [BusinessSettingsController::class, 'google_recaptcha_update'])->name('google_recaptcha.update');
    Route::post('/google-map', [BusinessSettingsController::class, 'google_map_update'])->name('google-map.update');
    Route::post('/google-firebase', [BusinessSettingsController::class, 'google_firebase_update'])->name('google-firebase.update');
    //Currency
    // Route::get('/currency', [CurrencyController::class, 'currency'])->name('currency.index');
    // Route::post('/currency/update', [CurrencyController::class, 'updateCurrency'])->name('currency.update');
    // Route::post('/your-currency/update', [CurrencyController::class, 'updateYourCurrency'])->name('your_currency.update');
    // Route::get('/currency/create', [CurrencyController::class, 'create'])->name('currency.create');
    // Route::post('/currency/store', [CurrencyController::class, 'store'])->name('currency.store');
    // Route::post('/currency/currency_edit', [CurrencyController::class, 'edit'])->name('currency.edit');
    // Route::post('/currency/update_status', [CurrencyController::class, 'update_status'])->name('currency.update_status');

    //Tax
    // Route::resource('tax', TaxController::class);
    // Route::get('/tax/edit/{id}', [TaxController::class, 'edit'])->name('tax.edit');
    // Route::get('/tax/destroy/{id}', [TaxController::class, 'destroy'])->name('tax.destroy');
    // Route::post('tax-status', [TaxController::class, 'change_tax_status'])->name('taxes.tax-status');

    Route::get('/verification/form', [BusinessSettingsController::class, 'seller_verification_form'])->name('seller_verification_form.index');
    Route::post('/verification/form', [BusinessSettingsController::class, 'seller_verification_form_update'])->name('seller_verification_form.update');
    Route::get('/vendor_commission', [BusinessSettingsController::class, 'vendor_commission'])->name('business_settings.vendor_commission');
    Route::post('/vendor_commission_update', [BusinessSettingsController::class, 'vendor_commission_update'])->name('business_settings.vendor_commission.update');

    Route::resource('/languages', LanguageController::class);
    Route::post('/languages/{id}/update', [LanguageController::class, 'update'])->name('languages.update');
    Route::get('/languages/destroy/{id}', [LanguageController::class, 'destroy'])->name('languages.destroy');
    Route::post('/languages/update_rtl_status', [LanguageController::class, 'update_rtl_status'])->name('languages.update_rtl_status');
    Route::post('/languages/update-status', [LanguageController::class, 'update_status'])->name('languages.update-status');
    Route::post('/languages/key_value_store', [LanguageController::class, 'key_value_store'])->name('languages.key_value_store');

    //App Trasnlation
    Route::post('/languages/app-translations/import', [LanguageController::class, 'importEnglishFile'])->name('app-translations.import');
    Route::get('/languages/app-translations/show/{id}', [LanguageController::class, 'showAppTranlsationView'])->name('app-translations.show');
    Route::post('/languages/app-translations/key_value_store', [LanguageController::class, 'storeAppTranlsation'])->name('app-translations.store');
    Route::get('/languages/app-translations/export/{id}', [LanguageController::class, 'exportARBFile'])->name('app-translations.export');

    // website setting
    // Route::group(['prefix' => 'website'], function () {
    //     Route::get('/footer', [WebsiteController::class, 'footer'])->name('website.footer');
    //     Route::get('/header', [WebsiteController::class, 'header'])->name('website.header');
    //     Route::get('/appearance', [WebsiteController::class, 'appearance'])->name('website.appearance');
    //     Route::get('/pages', [WebsiteController::class, 'pages'])->name('website.pages');
    //     Route::resource('custom-pages', PageController::class);
    //     Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');
    //     Route::get('/custom-pages/destroy/{id}', [PageController::class, 'destroy'])->name('custom-pages.destroy');
    // });

    Route::resource('roles', RoleController::class);
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    //Subscribers
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/destroy/{id}', [SubscriberController::class, 'destroy'])->name('subscriber.destroy');

    // Route::get('/orders', [OrderController::class,'admin_orders'])->name('orders.index.admin');
    // Route::get('/orders/{id}/show', [OrderController::class,'show'])->name('orders.show');
    // Route::get('/sales/{id}/show', [OrderController::class,'sales_show'])->name('sales.show');
    // Route::get('/sales', [OrderController::class,'sales'])->name('sales.index');
    // All Orders
    Route::get('/all_orders', [OrderController::class, 'all_orders'])->name('all_orders.index');
    Route::get('/all_orders/{id}/show', [OrderController::class, 'all_orders_show'])->name('all_orders.show');
    Route::post('/transfer-product', [OrderController::class, 'transferProduct'])->name('transfer-product');

    Route::get('/transferred_orders', [OrderController::class, 'transferred_orders'])->name('transferred_orders.index');

    // Inhouse Orders
    Route::get('/inhouse-orders', [OrderController::class, 'admin_orders'])->name('inhouse_orders.index');
    Route::get('/inhouse-orders/{id}/show', [OrderController::class, 'show'])->name('inhouse_orders.show');

    // Seller Orders
    Route::get('/seller_orders', [OrderController::class, 'seller_orders'])->name('seller_orders.index');
    Route::get('/seller_orders/{id}/show', [OrderController::class, 'seller_orders_show'])->name('seller_orders.show');

    Route::post('/bulk-order-status', [OrderController::class, 'bulk_order_status'])->name('bulk-order-status');

    // Pickup point orders
    Route::get('orders_by_pickup_point', [OrderController::class, 'pickup_point_order_index'])->name('pick_up_point.order_index');
    Route::get('/orders_by_pickup_point/{id}/show', [OrderController::class, 'pickup_point_order_sales_show'])->name('pick_up_point.order_show');

    Route::get('/orders/destroy/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/bulk-order-delete', [OrderController::class, 'bulk_order_delete'])->name('bulk-order-delete');

    
    //Reports
    Route::get('/stock_report', [ReportController::class, 'stock_report'])->name('stock_report.index');
    Route::get('/in_house_sale_report', [ReportController::class, 'in_house_sale_report'])->name('in_house_sale_report.index');
    Route::get('/seller_sale_report', [ReportController::class, 'seller_sale_report'])->name('seller_sale_report.index');
    Route::get('/wish_report', [ReportController::class, 'wish_report'])->name('wish_report.index');
    Route::get('/user_search_report', [ReportController::class, 'user_search_report'])->name('user_search_report.index');
    Route::get('/wallet-history', [ReportController::class, 'wallet_transaction_history'])->name('wallet-history.index');

    Route::get('/abandoned-cart', [AbandonedCartController::class, 'index'])->name('abandoned-cart.index');
    Route::get('/{cart}/abandoned-cart', [AbandonedCartController::class, 'view'])->name('abandoned-cart.view');

    //Blog Section
    // Route::resource('blog-category', 'BlogCategoryController');
    // Route::get('/blog-category/destroy/{id}', [BlogCategoryController::class,'destroy'])->name('blog-category.destroy');
    Route::resource('news', BlogController::class);
    Route::get('/news/destroy/{id}', [BlogController::class,'destroy'])->name('news.destroy');
    Route::post('/news/change-status', [BlogController::class,'change_status'])->name('news.change-status');

    Route::resource('faq', FaqController::class);
    Route::get('/faq/destroy/{id}', [FaqController::class,'destroy'])->name('faq.destroy');
    Route::post('/faq/change-status', [FaqController::class,'change_status'])->name('faq.change-status');

    //Coupons
    Route::resource('coupon', CouponController::class);
    Route::get('/coupon/destroy/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');

    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/published', [ReviewController::class, 'updatePublished'])->name('reviews.published');

    Route::resource('attributes', AttributeController::class);
    Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])->name('attributes.edit');
    Route::get('/attributes/destroy/{id}', [AttributeController::class, 'destroy'])->name('attributes.destroy');

    //Attribute Value
    Route::post('/store-attribute-value', [AttributeController::class, 'store_attribute_value'])->name('store-attribute-value');
    Route::get('/edit-attribute-value/{id}', [AttributeController::class, 'edit_attribute_value'])->name('edit-attribute-value');
    Route::post('/update-attribute-value/{id}', [AttributeController::class, 'update_attribute_value'])->name('update-attribute-value');
    Route::get('/destroy-attribute-value/{id}', [AttributeController::class, 'destroy_attribute_value'])->name('destroy-attribute-value');

    //Colors
    // Route::get('/colors', [AttributeController::class,'colors'])->name('colors');
    // Route::post('/colors/store', [AttributeController::class,'store_color'])->name('colors.store');
    // Route::get('/colors/edit/{id}', [AttributeController::class,'edit_color'])->name('colors.edit');
    // Route::post('/colors/update/{id}', [AttributeController::class,'update_color'])->name('colors.update');
    // Route::get('/colors/destroy/{id}', [AttributeController::class,'destroy_color'])->name('colors.destroy');

    // Route::resource('addons', 'AddonController');
    // Route::post('/addons/activation', [AddonController::class,'activation'])->name('addons.activation');

    Route::get('/customer-bulk-upload/index', [CustomerBulkUploadController::class, 'index'])->name('customer_bulk_upload.index');
    Route::post('/bulk-user-upload', [CustomerBulkUploadController::class, 'user_bulk_upload'])->name('bulk_user_upload');
    Route::post('/bulk-customer-upload', [CustomerBulkUploadController::class, 'customer_bulk_file'])->name('bulk_customer_upload');
    Route::get('/user', [CustomerBulkUploadController::class, 'pdf_download_user'])->name('pdf.download_user');
 
    //Shipping Configuration
    Route::get('/shipping_configuration', [BusinessSettingsController::class, 'shipping_configuration'])->name('shipping_configuration.index');
    Route::post('/shipping_configuration/update', [BusinessSettingsController::class, 'shipping_configuration_update'])->name('shipping_configuration.update');

    // Route::resource('pages', 'PageController');
    // Route::get('/pages/destroy/{id}', [PageController::class,'destroy'])->name('pages.destroy');

    Route::resource('countries', CountryController::class);
    Route::post('/countries/status', [CountryController::class, 'updateStatus'])->name('countries.status');

    Route::resource('states', StateController::class);
    Route::post('/states/status', [StateController::class, 'updateStatus'])->name('states.status');

    Route::resource('cities', CityController::class);
    Route::get('/cities/edit/{id}', [CityController::class, 'edit'])->name('cities.edit');
    Route::get('/cities/destroy/{id}', [CityController::class, 'destroy'])->name('cities.destroy');
    Route::post('/cities/status', [CityController::class, 'updateStatus'])->name('cities.status');

    // Route::view('/system/update', 'backend.system.update')->name('system_update');
    // Route::view('/system/server-status', 'backend.system.server_status')->name('system_server');

    // uploaded files
    Route::any('/uploaded-files/file-info', [AizUploadController::class, 'file_info'])->name('uploaded-files.info');
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::get('/uploaded-files/destroy/{id}', [AizUploadController::class, 'destroy'])->name('uploaded-files.destroy');

    Route::post('/aiz-uploader', [AizUploadController::class, 'show_uploader']);
    Route::post('/aiz-uploader/upload', [AizUploadController::class, 'upload']);
    Route::get('/aiz-uploader/get_uploaded_files', [AizUploadController::class, 'get_uploaded_files']);
    Route::post('/aiz-uploader/get_file_by_ids', [AizUploadController::class, 'get_preview_files']);
    Route::get('/aiz-uploader/download/{id}', [AizUploadController::class, 'attachment_download'])->name('download_attachment');

    Route::get('/all-notification', [NotificationController::class, 'index'])->name('admin.all-notification');

    Route::get('/cache-cache', [AdminController::class, 'clearCache'])->name('cache.clear');

    Route::resource('custom-pages', PageController::class);
    Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');

    Route::get('/pages', [WebsiteController::class, 'pages'])->name('website.pages');

    Route::resource('roles', RoleController::class);
});
