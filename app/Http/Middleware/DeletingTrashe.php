<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeletingTrashe
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

        // product 
        $products = \App\Models\Products::onlyTrashed()->get();
        foreach ($products as $product) {
            if ($product->deleted_at != null) {
                if ($product->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                    $product->forceDelete();
                }
            }
        }

        // users
        $users = \App\Models\User::onlyTrashed()->get();
        foreach ($users as $user) {
            if ($user->deleted_at != null) {
                if ($user->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                    $user->forceDelete();
                }
            }
        }

        // categorys
        $categorys = \App\Models\Categorys::onlyTrashed()->get();
        foreach ($categorys as $category) {
            if ($category->deleted_at != null) {
                if ($category->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                    $category->forceDelete();
                }
            }
        }

        // supplier
        $suppliers = \App\Models\Suppliers::onlyTrashed()->get();
        foreach ($suppliers as $supplier) {
            if ($supplier->deleted_at != null) {
                if ($supplier->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                    $supplier->forceDelete();
                }
            }
        }


        return $next($request);
    }
}
