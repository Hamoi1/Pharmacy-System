<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
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
        $Models = [
            \App\Models\Products::onlyTrashed()->get(),
            \App\Models\User::onlyTrashed()->get(),
            \App\Models\Categorys::onlyTrashed()->get(),
            \App\Models\Suppliers::onlyTrashed()->get(),
        ];
        foreach ($Models as $Model) {
            $this->Deleteing($Model);
        }
        return $next($request);
    }

    private function Deleteing($Model): void
    {
        foreach ($Model as $data) {
            if ($data->deleted_at != null) {
                if ($data->deleted_at->format('Y-m-d') <= now()->subMonth()->format('Y-m-d')) { // if deleted_at date is less than 1 month after that data will be delete forever
                    $data->forceDelete();
                }
            }
        }
    }
}
