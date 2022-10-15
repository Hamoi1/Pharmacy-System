<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sale_details extends Model
{
    use HasFactory;
    protected $table = 'sale_details';
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
    public function scopeproduct($query)
    {
        return
            $query
            ->addSelect(['product_id' => Products::select('id')->whereColumn('id', 'sale_details.product_id')])
            ->addSelect(['product_name' => Products::select('name')->whereColumn('id', 'sale_details.product_id')])->addSelect(['product_price' => Products::select('sale_price')->whereColumn('id', 'sale_details.product_id')])
            ->addSelect(['product_barcode' => Products::select('barcode')->whereColumn('id', 'sale_details.product_id')]);
    }
}
