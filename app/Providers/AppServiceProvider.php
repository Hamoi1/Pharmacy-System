<?php

namespace App\Providers;

use App\Models\Sales;
use App\Models\Products;
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
        view()->share([
            'settings' => \App\Models\Settings::firstOrCreate()
        ]);
    }
}
