<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\WebsiteController;
use App\Http\Controllers\Api\V2\AddressController;

Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
    
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
    Route::post('/otp-login', [ApiAuthController::class, 'loginWithOTP'])->name('otp-login');
    Route::post('/register', [ApiAuthController::class, 'signup'])->name('register');
    Route::post('/forgot-password', [ApiAuthController::class, 'forgetRequest'])->name('forgot-password');
    Route::post('reset-password', [ApiAuthController::class, 'resetRequest']);


    Route::post('/verify-otp', [ApiAuthController::class, 'verifyOTP'])->name('verify-otp');
    Route::post('/resend-otp', [ApiAuthController::class, 'resendOTP'])->name('resend-otp');

    Route::get('home-categories', [ApiAuthController::class, 'categories'])->name('home-categories');
    Route::get('brands', [ApiAuthController::class, 'getAllBrands'])->name('brands');
    Route::get('home-products', [ApiAuthController::class, 'homeProducts'])->name('home-products');

    Route::get('deals-trend', [ApiAuthController::class, 'deal_trend_Listing'])->name('deals-trend');
    Route::get('menu-categories', [ApiAuthController::class, 'menuCategories'])->name('menu-categories');
    Route::get('category-products', [ApiAuthController::class, 'categoryProducts'])->name('category-products');
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', [ApiAuthController::class, 'logout']);
        Route::get('user-profile', [ApiAuthController::class, 'user']);
        Route::post('update-profile', [ApiAuthController::class, 'updateProfile'])->name('update-profile');
        Route::post('change-password', [ApiAuthController::class, 'changePassword'])->name('change-password');
        // Route::post('add-address', [ApiAuthController::class, 'addAddress'])->name('add-address');
        // Route::post('update-address', [ApiAuthController::class, 'updateAddress'])->name('update-address');
        // Route::post('set-default-address', [ApiAuthController::class, 'setDefaultAddress'])->name('set-default-address');
        // Route::post('delete-address', [ApiAuthController::class, 'deleteAddress'])->name('delete-address');
        Route::post('/update-profile-image', [ApiAuthController::class, 'updateProfileImage'])->name('update-profile-image');
        
        Route::apiResource('address', AddressController::class);
        Route::post('update-address', [AddressController::class, 'updateAddress'])->name('update-address');
        Route::post('set-default-address', [AddressController::class, 'setDefaultAddress'])->name('set-default-address');
        Route::post('delete-address', [AddressController::class, 'deleteAddress'])->name('delete-address');
    });

    Route::group(['prefix' => 'website'], function () {
        // Route::get('header', [WebsiteController::class, 'websiteHeader']);
        // Route::get('home', [WebsiteController::class, 'websiteHome']);
        // Route::get('categories', [WebsiteController::class, 'websiteCategories']);
        
        // Route::get('footer', [WebsiteController::class, 'websiteFooter']);
        Route::get('store-locator', [WebsiteController::class, 'storeLocations']);
        Route::get('page-contents', [WebsiteController::class, 'pageContents']);
        Route::post('contact-us', [WebsiteController::class, 'contactUs']);
        Route::get('news', [WebsiteController::class, 'news']);
        Route::get('news-details', [WebsiteController::class, 'newsDetails']);
    });
});

Route::fallback(function () {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
