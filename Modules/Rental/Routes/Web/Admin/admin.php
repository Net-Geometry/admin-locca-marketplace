<?php

use Illuminate\Support\Facades\Route;
use Modules\Rental\Http\Controllers\Web\Admin\BrandController;
use Modules\Rental\Http\Controllers\Web\Admin\Promotions\BannerController;
use Modules\Rental\Http\Controllers\Web\Admin\Promotions\CouponController;
use Modules\Rental\Http\Controllers\Web\Admin\CategoryController;
use Modules\Rental\Http\Controllers\Web\Admin\ProviderController;
use Modules\Rental\Http\Controllers\Web\Admin\DashboardController;
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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['admin', 'current-module']], function () {
    Route::group(['prefix' => 'rental', 'as' => 'rental.'], function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('list', [CategoryController::class, 'list'])->name('list');
            Route::post('list', [CategoryController::class, 'store']);
            Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
            Route::post('edit/{id}', [CategoryController::class, 'update']);
            Route::delete('delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
            Route::get('status/{id}', [CategoryController::class, 'status'])->name('status');
            Route::get('status/{id}', [CategoryController::class, 'status'])->name('status');
            Route::get('export-categories', [CategoryController::class, 'export'])->name('export-categories');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
            Route::get('list', [BrandController::class, 'list'])->name('list');
            Route::post('list', [BrandController::class, 'store']);
            Route::get('edit/{id}', [BrandController::class, 'edit'])->name('edit');
            Route::post('edit/{id}', [BrandController::class, 'update']);
            Route::delete('delete/{id}', [BrandController::class, 'destroy'])->name('delete');
            Route::get('status/{id}', [BrandController::class, 'status'])->name('status');
            Route::get('status/{id}', [BrandController::class, 'status'])->name('status');
            Route::get('export-categories', [BrandController::class, 'export'])->name('export-brands');

        });

        Route::group(['prefix' => 'provider', 'as' => 'provider.'], function () {
            Route::get('list', [ProviderController::class, 'list'])->name('list');
            Route::get('create', [ProviderController::class, 'create'])->name('create');
            Route::post('create', [ProviderController::class, 'store']);
            Route::get('edit-basic-setup/{id}', [ProviderController::class, 'editBasicSetup'])->name('edit-basic-setup');
            Route::post('edit-basic-setup/{id}', [ProviderController::class, 'updateBasicSetup']);
            Route::get('edit-business-setup/{id}', [ProviderController::class, 'editBusinessSetup'])->name('edit-business-setup');
            Route::post('edit-business-setup/{id}', [ProviderController::class, 'updateBusinessSetup']);
            Route::delete('delete/{id}', [ProviderController::class, 'destroy'])->name('delete');
            Route::get('status/{id}', [ProviderController::class, 'status'])->name('status');
            Route::get('details/{id}', [ProviderController::class, 'details'])->name('details');
            Route::get('export-categories', [ProviderController::class, 'export'])->name('export-brands');
            Route::get('new-requests', [ProviderController::class, 'newRequests'])->name('new-requests');
            Route::get('new-requests-details/{id}', [ProviderController::class, 'newRequestsDetails'])->name('new-requests-details');
            Route::get('new-requests-details/{id}', [ProviderController::class, 'newRequestsDetails'])->name('new-requests-details');
            Route::get('approve-or-deny/{id}', [ProviderController::class, 'approveOrDeny'])->name('approve-or-deny');

        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:banner']], function () {
            Route::get('/', [BannerController::class,'list'])->name('add-new');
            Route::post('store', [BannerController::class,'store'])->name('store');
            Route::get('edit/{banner}', [BannerController::class,'edit'])->name('edit');
            Route::post('edit/{banner}', [BannerController::class,'update'])->name('update');
            Route::delete('delete/{banner}', [BannerController::class,'destroy'])->name('delete');
            Route::get('status/{banner}/{status}', [BannerController::class,'status'])->name('status');
            Route::get('featured/{banner}/{status}', [BannerController::class,'updateFeatured'])->name('featured');
            Route::get('export', [BannerController::class, 'export'])->name('export');
        });

        Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => ['module:coupon']], function () {
            Route::get('/', [CouponController::class,'list'])->name('add-new');
            Route::post('store', [CouponController::class,'store'])->name('store');
            Route::get('edit/{coupon}', [CouponController::class,'edit'])->name('edit');
            Route::post('edit/{coupon}', [CouponController::class,'update'])->name('update');
            Route::delete('delete/{coupon}', [CouponController::class,'destroy'])->name('delete');
            Route::get('status/{coupon}', [CouponController::class,'status'])->name('status');
            Route::get('export', [CouponController::class, 'export'])->name('export');

        });

    });
});


