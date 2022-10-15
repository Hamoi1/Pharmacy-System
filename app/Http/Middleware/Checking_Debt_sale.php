<?php

namespace App\Http\Middleware;

use App\Models\DebtSale as DebtSaleModel;
use Closure;
use Illuminate\Http\Request;

class Checking_Debt_sale
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
        $debtSale = DebtSaleModel::all();
        foreach ($debtSale as $debt) {
            if ($debt->updated_at == now() && $debt->remain != 0) {
                $debt->delete();
            }
        }
        return $next($request);
    }
}
