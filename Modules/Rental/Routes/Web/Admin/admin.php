<?php

use Illuminate\Support\Facades\Route;
use Modules\Rental\Http\Controllers\Web\Admin\BrandController;
use Modules\Rental\Http\Controllers\Web\Admin\Promotions\BannerController;
use Modules\Rental\Http\Controllers\Web\Admin\Promotions\CouponController;
use Modules\Rental\Http\Controllers\Web\Admin\Promotions\CashBackController;
use Modules\Rental\Http\Controllers\Web\Admin\Promotions\NotificationController;
use Modules\Rental\Http\Controllers\Web\Admin\CategoryController;
use Modules\Rental\Http\Controllers\Web\Admin\DriverController;
use Modules\Rental\Http\Controllers\Web\Admin\ProviderController;
use Modules\Rental\Http\Controllers\Web\Admin\DashboardController;
use Modules\Rental\Http\Controllers\Web\Admin\VehicleController;
use Modules\Rental\Http\Controllers\Web\Admin\SettingsController;

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
            Route::get('export-categories', [CategoryController::class, 'export'])->name('export-categories');
        });

        Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
            Route::get('list', [BrandController::class, 'list'])->name('list');
            Route::post('list', [BrandController::class, 'store']);
            Route::get('edit/{id}', [BrandController::class, 'edit'])->name('edit');
            Route::post('edit/{id}', [BrandController::class, 'update']);
            Route::delete('delete/{id}', [BrandController::class, 'destroy'])->name('delete');
            Route::get('status/{id}', [BrandController::class, 'status'])->name('status');
            Route::get('export', [BrandController::class, 'export'])->name('export-brands');

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
            Route::get('details/{id}/{tab?}/{sub_tab?}', [ProviderController::class, 'details'])->name('details');
            Route::get('export-categories', [ProviderController::class, 'export'])->name('export-brands');
            Route::get('new-requests', [ProviderController::class, 'newRequests'])->name('new-requests');
            Route::get('new-requests-details/{id}', [ProviderController::class, 'newRequestsDetails'])->name('new-requests-details');
            Route::get('approve-or-deny/{id}', [ProviderController::class, 'approveOrDeny'])->name('approve-or-deny');

            Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
                Route::get('create/{provider_id}', [DriverController::class, 'create'])->name('create');
                Route::post('create/{provider_id}', [DriverController::class, 'store']);
                Route::get('update/{id}', [DriverController::class, 'edit'])->name('edit');
                Route::post('update/{id}', [DriverController::class, 'update']);
                Route::get('details/{id}', [DriverController::class, 'details'])->name('details');
                Route::get('status/{id}', [DriverController::class, 'status'])->name('status');
                Route::delete('delete/{id}', [DriverController::class, 'destroy'])->name('delete');
                Route::get('export', [DriverController::class, 'export'])->name('export');
            });

            Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
                Route::get('list', [VehicleController::class, 'index'])->name('list');
                Route::get('create', [VehicleController::class, 'create'])->name('create');
                Route::post('create', [VehicleController::class, 'store']);
                Route::get('update/{id}', [VehicleController::class, 'edit'])->name('edit');
                Route::post('update/{id}', [VehicleController::class, 'update']);
                Route::get('status/{id}', [VehicleController::class, 'status'])->name('status');
                Route::get('new-tag/{id}', [VehicleController::class, 'newTag'])->name('new-tag');
                Route::delete('delete/{id}', [VehicleController::class, 'destroy'])->name('delete');
                Route::get('export', [VehicleController::class, 'export'])->name('export');
            });

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

        Route::group(['prefix' => 'cashback', 'as' => 'cashback.', 'middleware' => ['module:cashback']], function () {
            Route::get('/', [CashBackController::class,'list'])->name('add-new');
            Route::post('store', [CashBackController::class,'store'])->name('store');
            Route::get('edit/{cashback}', [CashBackController::class,'edit'])->name('edit');
            Route::post('edit/{cashback}', [CashBackController::class,'update'])->name('update');
            Route::delete('delete/{cashback}', [CashBackController::class,'destroy'])->name('delete');
            Route::get('status/{cashback}', [CashBackController::class,'status'])->name('status');
            // Route::get('export', [CashBackController::class, 'export'])->name('export');

        });

        Route::group(['prefix' => 'notification', 'as' => 'notification.', 'middleware' => ['module:notification']], function () {
            Route::get('/', [NotificationController::class,'list'])->name('list');
            Route::post('store', [NotificationController::class,'store'])->name('store');
            Route::get('edit/{notification}', [NotificationController::class,'edit'])->name('edit');
            Route::post('update/{notification}', [NotificationController::class,'update'])->name('update');
            Route::get('status/{notification}', [NotificationController::class,'status'])->name('status');
            Route::delete('delete/{notification}', [NotificationController::class,'destroy'])->name('delete');
            Route::get('export', [NotificationController::class,'export'])->name('export');
        });
        Route::group(['prefix' => 'settings', 'as' => 'settings.', 'middleware' => ['module:settings']], function () {
            Route::get('/', [SettingsController::class,'homePageDownApp'])->name('down_app');
            Route::post('/down_app_update', [SettingsController::class,'homePageDownAppUpdate'])->name('down_app_update');
            Route::get('vendors-registration/', [SettingsController::class,'vendorsRegistration'])->name('vendors_registration');
            Route::post('/vendors-registration-update', [SettingsController::class,'vendorsRegistrationUpdate'])->name('vendors_registration_update');
        });

    });
});


