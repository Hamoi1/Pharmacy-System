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
        $ConvertDolarToDinar = function ($value) {
            if ($value != null) {
                $convert = Currency::convert()->from('USD')->to('IQD')->amount($value)->get();
                return number_format(round($convert / 250) * 250, 0, ',', ',');
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
