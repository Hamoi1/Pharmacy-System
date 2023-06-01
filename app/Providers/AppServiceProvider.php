<?php

namespace App\Providers;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\Sales;
use App\Models\Products;
use App\Models\sale_details;
use App\Models\User;
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
        $ConvertDolarToDinar = function ($price) {
            if ($price != null) {
                $exchange_rate = \App\Models\Settings::first()->exchange_rate ?? 1450;
                $price = $price * $exchange_rate;
                $price = round($price / 250) * 250;
                return number_format($price, 0, ',', ',');
            } else {
                return 0;
            }
        };
        view()->share([
            'settings' => \App\Models\Settings::firstOrCreate(),
            'ConvertDolarToDinar' => $ConvertDolarToDinar,
        ]);
    }
}
