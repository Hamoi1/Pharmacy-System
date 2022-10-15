<?php

use Illuminate\Support\Facades\Route;


Route::redirect('/', app()->getLocale() . '/login');
Route::group([
    'prefix' => '{lang}',
    'where' => ['lang' => 'en|ckb'],
], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard', app()->getLocale());
        });
        Route::get('/dashboard', App\Http\Controllers\Dashboard::class)->name('dashboard');
        Route::get('/users', App\Http\Controllers\User\Index::class)->name('users');
        Route::get('/products', App\Http\Controllers\Products\Index::class)->name('products');
        Route::get('/products/image/update/{id}', App\Http\Controllers\Products\UpdateImage::class)->name('products.image.update');
        Route::get('/ExpiryProducts', App\Http\Controllers\ExpiryProducts::class)->name('ExpiryProducts');
        Route::get('/stock-out-procuts', App\Http\Controllers\StockOutProducts::class)->name('StockOutProcuts');
        Route::get('/categorys', App\Http\Controllers\Categorys\Index::class)->name('categorys');
        Route::get('/suppliers', App\Http\Controllers\Supplier\Index::class)->name('suppliers');

        Route::get('/pont-of-sale', App\Http\Controllers\Pos\Index::class)->name('sales');
        Route::get('/sales', App\Http\Controllers\Sales\Index::class)->name('sales.index');
        Route::get('/sale/view/{id}/{invoice}', App\Http\Controllers\Sales\ViewSale::class)->name('sales.view');
        Route::get('/sales/debt', App\Http\Controllers\Sales\DebtSale::class)->name('sales.debt');

        Route::get('/profile', App\Http\Controllers\Profile\Update::class)->name('profile.update');
        Route::get('/settings', App\Http\Controllers\Setting::class)->name('settings');
        Route::get('/logout', function () {
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
