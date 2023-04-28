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
        $products = \App\Models\Products::onlyTrashed()->get();
        $users = \App\Models\User::onlyTrashed()->get();
        $categorys = \App\Models\Categorys::onlyTrashed()->get();
        $suppliers = \App\Models\Suppliers::onlyTrashed()->get();
        
        $this->Deleteing($products);
        $this->Deleteing($users);
        $this->Deleteing($categorys);
        $this->Deleteing($suppliers);
        
        return $next($request);
    }

    private function Deleteing($models)
    {
        foreach ($models as $model) {
            if ($model->deleted_at != null) {
                if ($model->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                    $model->forceDelete();
                }
            }
        }
    }
}
