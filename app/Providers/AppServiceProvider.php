<?php

namespace App\Providers;

use App\Models\Sales;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (session('invoice') == null) {
            session()->put('invoice',  $this->getInvoice());
        }
        // check invoce has inside sale table
        if (session('invoice') != null) {
            $sale = Sales::where('invoice', session('invoice'))->first();
            if ($sale != null) {
                session()->put('invoice',  $this->getInvoice());
            }
        }
        view()->share('settings', \App\Models\Settings::firstOrCreate());
    }
    public function getInvoice()
    {
        $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
        $number = Str::limit($number, 9, '');
        return Str::start($number, 'inv-');
    }
}
