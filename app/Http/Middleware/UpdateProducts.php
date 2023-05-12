<?php

namespace App\Http\Middleware;

use App\Models\ProductsQuantity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateProducts
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
        $products = \App\Models\Products::ExpiryDate()->TotalQuantity()->get();
        foreach ($products as $product) {
            $product->expiry_date = $product->min_expiry_date;
            $product->quantity = $product->total_quantity;
            $product->save();
        }
        return $next($request);
    }
}
