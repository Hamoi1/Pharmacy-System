<?php

namespace App\Providers;

use AmrShawky\LaravelCurrency\Facade\Currency;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $settings = \App\Models\Settings::firstOrCreate();
        $exchange_rate = $settings->exchange_rate ?? 1450;
        $ConvertDollarToDinar = function ($price) use ($exchange_rate) {
            if ($price != null) {
                $price = round(($price * $exchange_rate)/ 250) * 250;
                return number_format($price, 0, ',', ',');
            } else {
                return 0;
            }
        };
        view()->share([
            'settings' => $settings,
            'ConvertDollarToDinar' => $ConvertDollarToDinar,
        ]);
    }
}
