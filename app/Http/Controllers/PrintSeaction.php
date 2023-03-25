<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class PrintSeaction extends Controller
{
    public function sale(Request $request)
    {
        $sale  = Sales::with(['sale_details' => function ($query) {
            $query->with(['products' => function ($query) {
                $query->select(['id', 'name', 'sale_price']);
            }]);
        }])->findorFail($request->id);
        return view('Print.sale', ['sale' => $sale]);
    }
}
