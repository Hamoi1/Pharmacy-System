<?php

namespace App\Http\Middleware;

use App\Models\Products;
use Closure;
use Illuminate\Http\Request;

class CheckProduct
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $date = now()->subMonths(3)->format('Y-m-d');
        // $products = Products::with('product_quantity')->SalePrice()->get();
        // foreach ($products as $product) {
        //     // update sale price
        //     if ($product->final_sale_price != $product->sale_price && $product->final_sale_price != 0) {
        //         $product->sale_price = $product->final_sale_price;
        //         $product->saveQuietly();
        //     }
        // }
        // foreach ($products as $product) {
        //     $product->expiry_date = $product->product_quantity->min('expiry_date');
        //     $product->quantity = $product->product_quantity->min('quantity');
        //     $product->save();
        //     foreach ($product->product_quantity as $product_quantity) {
        //         if ($product_quantity->created_at <= $date && $product_quantity->quantity == 0) {
        //             $product_quantity->delete();
        //             $final_sale_price = $product->sale_price;
        //             $product->sale_price = $final_sale_price;
        //             $product->save();
        //         }
        //     }
        // }
        return $next($request);
    }
}
