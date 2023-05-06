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
        // $date = now()->addMonths(2)->format('Y-m-d');
        // $numberOfStockout = 20;
        // $product =  Products::with('product_quantity')->ExpiryDate()->MinQuantity()->TotalQuantity();
        // $checkProduct = $product->get();
        // $countExpiry = 0;
        // foreach ($checkProduct as $key => $value) {
        //     $min = $value->product_quantity->min('expiry_date');
        //     $value->expiry_date = $min;
        //     $value->save();
        //     if ($min == now()) {
        //         $countExpiry++;
        //     }
        // }
        // $expiry = $product->whereDate('expiry_date', '<=', $date)->orderByDesc('expiry_date')->get();
        // $stockout = $product->where('quantity', '<=', $numberOfStockout)->orderByDesc('quantity')->get();
        // $countStockout = 0;
        // foreach ($stockout as $key => $value) {
        //     if ($value->quantity == 0) {
        //         $countStockout++;
        //     }
        // }
        // // count stocked out
        // $calculatedate = function ($expiryDate) {
        //     if ($expiryDate > now()) {
        //         return \Carbon\Carbon::parse(now()->format('Y-m-d'))->diffInDays($expiryDate);
        //     } else {
        //         return 0;
        //     }
        // };
        view()->share([
            'settings' => \App\Models\Settings::firstOrCreate(),

            // 'expiry' => $expiry, 'stockout' => $stockout, 'calculatedate' => $calculatedate, 'countExpiry' => $countExpiry, 'countStockout' => $countStockout,
        ]);
    }
    public function getInvoice()
    {
        $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
        $number = Str::limit($number, 9, '');
        return Str::start($number, 'inv-');
    }
}
