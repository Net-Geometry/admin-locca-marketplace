<?php
use Illuminate\Support\Facades\Route;
use Modules\TaxVat\Http\Controllers\TaxVatController;


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

Route::group(['prefix' => 'taxvat', 'as' => 'taxvat.','middleware' =>['admin','current-module']], function () {
        Route::get('get-taxvat-data', 'TaxVatController@index')->name('index');
        Route::post('add-taxvat-data', 'TaxVatController@store')->name('store');
        Route::put('update-taxvat-data/{taxVat} ', 'TaxVatController@update')->name('update');
        Route::get('update-taxvat-status/{taxVat} ', 'TaxVatController@status')->name('status');
});
