<?php

use App\Enums\ViewPaths\Admin\Addon;
use App\Enums\ViewPaths\Admin\Category;
use App\Enums\ViewPaths\Admin\Attribute;
use App\Enums\ViewPaths\Admin\Unit;
use App\Http\Controllers\Admin\Item\AddonController;
use App\Http\Controllers\Admin\Item\AttributeController;
use App\Http\Controllers\Admin\Item\CategoryController;
use App\Http\Controllers\Admin\Item\UnitController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => ['admin', 'current-module']], function () {
        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get(Category::NAME_LIST[URI], [CategoryController::class, 'getNameList'])->name('get-all');
            Route::group(['middleware' => ['module:category']], function () {
                Route::get(Category::ADD[URI], [CategoryController::class, 'index'])->name('add');
                Route::get(Category::UPDATE[URI], [CategoryController::class, 'getUpdateView'])->name('edit');
                Route::post(Category::UPDATE[URI], [CategoryController::class, 'update'])->name('update');
                Route::get(Category::PRIORITY[URI], [CategoryController::class, 'updatePriority'])->name('priority');
                Route::post(Category::ADD[URI], [CategoryController::class, 'add'])->name('store');
                Route::get(Category::STATUS[URI], [CategoryController::class, 'updateStatus'])->name('status');
                Route::get(Category::FEATURED[URI], [CategoryController::class, 'updateFeatured'])->name('featured');
                Route::delete(Category::DELETE[URI], [CategoryController::class, 'delete'])->name('delete');
                Route::get(Category::EXPORT[URI], [CategoryController::class, 'exportList'])->name('export-categories');

                //Import and export
                Route::get(Category::BULK_IMPORT[URI], [CategoryController::class, 'getBulkImportView'])->name('bulk-import');
                Route::post(Category::BULK_IMPORT[URI], [CategoryController::class, 'importBulkData']);
                Route::post(Category::BULK_UPDATE[URI], [CategoryController::class, 'updateBulkData'])->name('bulk-update');
                Route::get(Category::BULK_EXPORT[URI], [CategoryController::class, 'getBulkExportView'])->name('bulk-export-index');
                Route::post(Category::BULK_EXPORT[URI], [CategoryController::class, 'exportBulkData'])->name('bulk-export');
            });
        });

        Route::group(['prefix' => 'attribute', 'as' => 'attribute.', 'middleware' => ['module:attribute']], function () {
            Route::get(Attribute::INDEX[URI], [AttributeController::class, 'index'])->name('add-new');
            Route::post(Attribute::ADD[URI], [AttributeController::class, 'add'])->name('store');
            Route::get(Attribute::UPDATE[URI], [AttributeController::class, 'getUpdateView'])->name('edit');
            Route::post(Attribute::UPDATE[URI], [AttributeController::class, 'update'])->name('update');
            Route::delete(Attribute::DELETE[URI], [AttributeController::class, 'delete'])->name('delete');
            Route::get(Attribute::EXPORT[URI], [AttributeController::class, 'exportList'])->name('export-attributes');
        });

        Route::group(['prefix' => 'unit', 'as' => 'unit.', 'middleware' => ['module:unit']], function () {
            Route::get(Unit::INDEX[URI], [UnitController::class, 'index'])->name('index');
            Route::post(Unit::ADD[URI], [UnitController::class, 'add'])->name('store');
            Route::get(Unit::UPDATE[URI], [UnitController::class, 'getUpdateView'])->name('edit');
            Route::put(Unit::UPDATE[URI], [UnitController::class, 'update'])->name('update');
            Route::post(Unit::SEARCH[URI], [UnitController::class, 'search'])->name('search');
            Route::delete(Unit::DELETE[URI], [UnitController::class, 'delete'])->name('destroy');
            Route::get(Unit::EXPORT[URI], [UnitController::class, 'exportList'])->name('export');
        });

        Route::group(['prefix' => 'addon', 'as' => 'addon.', 'middleware' => ['module:addon']], function () {
            Route::get(Addon::INDEX[URI], [AddonController::class, 'index'])->name('add-new');
            Route::post(Addon::ADD[URI], [AddonController::class, 'add'])->name('store');
            Route::get(Addon::UPDATE[URI], [AddonController::class, 'getUpdateView'])->name('edit');
            Route::post(Addon::UPDATE[URI], [AddonController::class, 'update'])->name('update');
            Route::delete(Addon::DELETE[URI], [AddonController::class, 'delete'])->name('delete');
            Route::get(Addon::EXPORT[URI], [AddonController::class, 'exportList'])->name('export');
            Route::get(Addon::UPDATE_STATUS[URI], [AddonController::class, 'updateStatus'])->name('status');

            Route::get(Addon::BULK_IMPORT[URI], [AddonController::class, 'getBulkImportView'])->name('bulk-import');
            Route::post(Addon::BULK_IMPORT[URI], [AddonController::class, 'importBulkData']);
            Route::post(Addon::BULK_UPDATE[URI], [AddonController::class, 'updateBulkData'])->name('bulk-update');
            Route::get(Addon::BULK_EXPORT[URI], [AddonController::class, 'getBulkExportView'])->name('bulk-export-index');
            Route::post(Addon::BULK_EXPORT[URI], [AddonController::class, 'exportBulkData'])->name('bulk-export');
        });
    });
});
