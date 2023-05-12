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
        $debtSale = DebtSaleModel::get();
        foreach ($debtSale as $debt) {
            if ($debt->delete_in <= now()  && $debt->remain == 0 && $debt->status == 1) { // if debt delete_in <= now() and remain == 0 and status == 1 then delete
                $debt->delete();
            }
        }
        return $next($request);
    }
}
