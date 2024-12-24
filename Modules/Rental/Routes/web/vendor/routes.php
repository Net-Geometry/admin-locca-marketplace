<?php

use Illuminate\Support\Facades\Route;
use Modules\Rental\Http\Controllers\Web\Provider\DriverController;
use Modules\Rental\Http\Controllers\Web\Provider\Promotions\BannerController;
use Modules\Rental\Http\Controllers\Web\Provider\Promotions\CouponController;
use Modules\Rental\Http\Controllers\Web\Provider\ProviderController;
use Modules\Rental\Http\Controllers\Web\Provider\VehicleController;

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

Route::group([ 'middleware' => ['vendor']], function () {
    Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
        Route::get('list', [VehicleController::class, 'index'])->name('list');
        Route::get('create', [VehicleController::class, 'create'])->name('create');
        Route::post('create', [VehicleController::class, 'store']);
        Route::get('update/{id}', [VehicleController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [VehicleController::class, 'update']);
        Route::get('details/{id}', [VehicleController::class, 'details'])->name('details');
        Route::get('status/{id}', [VehicleController::class, 'status'])->name('status');
        Route::get('new-tag/{id}', [VehicleController::class, 'newTag'])->name('new-tag');
        Route::delete('delete/{id}', [VehicleController::class, 'destroy'])->name('delete');
        Route::get('export', [VehicleController::class, 'export'])->name('export');
    });

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('list', [ProviderController::class, 'categoryList'])->name('list');
        Route::get('export', [ProviderController::class, 'categoryExport'])->name('export');
    });

    Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
        Route::get('list', [ProviderController::class, 'brandList'])->name('list');
        Route::get('export', [ProviderController::class, 'brandExport'])->name('export');
    });

    Route::group(['prefix' => 'rental-banner', 'as' => 'rental_banner.', 'middleware' => ['module:banner']], function () {
        Route::get('/', [BannerController::class,'list'])->name('list');
        Route::post('/', [BannerController::class,'store']);
        Route::get('edit/{banner}', [BannerController::class,'edit'])->name('edit');
        Route::post('edit/{banner}', [BannerController::class,'update']);
        Route::delete('delete/{banner}', [BannerController::class,'destroy'])->name('delete');
        Route::get('status/{banner}/{status}', [BannerController::class,'status'])->name('status');
        Route::get('featured/{banner}/{status}', [BannerController::class,'updateFeatured'])->name('featured');
        Route::get('export', [BannerController::class, 'export'])->name('export');
    });

    Route::group(['prefix' => 'rental-coupon', 'as' => 'rental_coupon.', 'middleware' => ['module:coupon']], function () {
        Route::get('/', [CouponController::class,'list'])->name('list');
        Route::post('/', [CouponController::class,'store']);
        Route::get('edit/{id}', [CouponController::class,'edit'])->name('edit');
        Route::post('edit/{id}', [CouponController::class,'update']);
        Route::delete('delete/{coupon}', [CouponController::class,'destroy'])->name('delete');
        Route::get('status/{coupon}', [CouponController::class,'status'])->name('status');
        Route::get('export', [CouponController::class, 'export'])->name('export');
    });

    Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
        Route::get('list', [DriverController::class, 'list'])->name('list');
        Route::get('/create', [DriverController::class, 'create'])->name('create');
        Route::post('/create', [DriverController::class, 'store']);
        Route::get('update/{id}', [DriverController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [DriverController::class, 'update']);
        Route::get('details/{id}', [DriverController::class, 'details'])->name('details');
        Route::get('status/{id}', [DriverController::class, 'status'])->name('status');
        Route::delete('delete/{id}', [DriverController::class, 'destroy'])->name('delete');
        Route::get('export', [DriverController::class, 'export'])->name('export');
    });

});


