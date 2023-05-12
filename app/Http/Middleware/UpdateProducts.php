<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
            if ($product->expiry_date == $product->min_expiry_date)
                continue;
            $product->expiry_date = $product->min_expiry_date;
            $product->quantity = $product->total_quantity;
            $product->save();
        }
        return $next($request);
    }
}
