<?php

namespace App\Http\Middleware;

use App\Models\Sales;
use Closure;
use Illuminate\Http\Request;

class CheckSale
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
        $date =   now()->subday();
        $sales = Sales::select(['id', 'total', 'invoice', 'created_at'])->whereDate('created_at', '<=', $date)->where('status', 0)->with('sale_details', function ($query) {
            $query->select(["id", "sale_id", "product_id", "quantity"])->with('products');
        })->get();

        foreach ($sales as $sale) {
            // foreach ($sale->sale_details as $sale_detail) {
            //     $sale_detail->products()->update([
            //         'quantity' => $sale_detail->products->quantity + $sale_detail->quantity
            //     ]);
            // }
            $sale->delete();
        }
        return $next($request);
    }
}
