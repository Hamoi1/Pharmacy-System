<?php

use Illuminate\Support\Facades\Route;

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

Route::redirect('/', app()->getLocale() . '/login');
Route::group([
    'prefix' => '{lang}',
    'where' => ['lang' => 'en|ckb|ar'],
], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard', app()->getLocale());
        });
        Route::get('/dashboard', App\Http\Controllers\Dashboard::class)->name('dashboard');
        Route::get('/users', App\Http\Controllers\User\Index::class)->name('users');
        Route::get('/products', App\Http\Controllers\Products\Index::class)->name('products');
        Route::get('/product/return', App\Http\Controllers\Products\ReturnProduct::class)->name('returnproduct');
        Route::get('/product/update/{id}', App\Http\Controllers\Products\UpdateQuantity::class)->name('UpdateQuantity');
        Route::get('/categorys', App\Http\Controllers\Categorys\Index::class)->name('categorys');
        Route::get('/suppliers', App\Http\Controllers\Supplier\Index::class)->name('suppliers');
        Route::get('/pont-of-sale', App\Http\Controllers\Pos\Index::class)->name('sales');
        Route::get('/sales', App\Http\Controllers\Sales\Index::class)->name('sales.index');
        Route::get('/sale/print/{id}', [App\Http\Controllers\PrintSeaction::class, 'sale'])->name('sales.print');
        Route::get('/sale/view/{id}/{invoice}', App\Http\Controllers\Sales\PrintSales::class)->name('sales.PrintSales');

        Route::get('/sales/debt', App\Http\Controllers\Sales\DebtSale::class)->name('sales.debt');
        Route::get('/customers', App\Http\Controllers\Customer\Index::class)->name('customers');

        Route::get('/profile', App\Http\Controllers\Profile\Update::class)->name('profile.update');
        Route::get('/settings', App\Http\Controllers\Setting::class)->name('settings');
        Route::get('/barcode', App\Http\Controllers\Barcode\Index::class)->name('barcode');
        Route::get('/logs', App\Http\Controllers\Logs\Index::class)->name('logs');
        Route::get('/logout', function () {
            $data = auth()->user()->name . ' logogut form : ' . now();
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Logout", 'Logout', $data, $data);
            auth()->logout();
            return redirect()->route('login', app()->getLocale());
        })->name('logout');
    });

    Route::middleware(['guest'])->group(function () {
        Route::get('/login', App\Http\Controllers\Auth\Login::class)->name('login');
        Route::get('/forget-password', App\Http\Controllers\Auth\ForgetPassowrd::class)->name('forget-password');
        Route::get('/cahnge-password/{token}/{email}', App\Http\Controllers\Auth\ChangePassowrd::class)->name('ChangePassowrd');
    });
});
