<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                    $image = $product->image;
                    if ($image != null) {
                        $image = json_decode($image);
                        foreach ($image as $img) {
                            Storage::delete('public/products/' . $img);
                        }
                    }
                    $product->forceDelete();
                }
            }
        }

        // users
        $users = \App\Models\User::onlyTrashed()->get();
        foreach ($users as $user) {
            if ($user->deleted_at != null) {
                if ($user->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                   $image = $user->user_details->image;
                    if ($image != null) {
                        Storage::delete('public/users/' . $image);
                    }
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
