<?php

use App\Enums\ViewPaths\Admin\Category;
use App\Http\Controllers\Admin\Item\CategoryController;
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
                Route::get(Category::EXPORT[URI], [CategoryController::class, 'exportData'])->name('export-categories');

                //Import and export
                Route::get(Category::BULK_IMPORT[URI], [CategoryController::class, 'getBulkImportView'])->name('bulk-import');
                Route::post(Category::BULK_IMPORT[URI], [CategoryController::class, 'importBulkData']);
                Route::post(Category::BULK_UPDATE[URI], [CategoryController::class, 'updateBulkData'])->name('bulk-update');
                Route::get(Category::BULK_EXPORT[URI], [CategoryController::class, 'getBulkExportView'])->name('bulk-export-index');
                Route::post(Category::BULK_EXPORT[URI], [CategoryController::class, 'exportBulkData'])->name('bulk-export');
            });
        });
    });
});
