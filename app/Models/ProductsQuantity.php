<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsQuantity extends Model
{
    use HasFactory;
    protected $table = 'products_quantities';
    protected $fillable = [
        'product_id',
        'quantity',
        'purches_price',
        'sale_price',
        'expiry_date',
    ];

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    // public function scopeProductQuantity($query)
    // {
    //     $query->addSelect([
    //         'quantity_product' => ProductsQuantity::selectRaw('sum(quantity)')->whereColumn('product_id', 'products.id'),
    //         'sale_price_product' => ProductsQuantity::selectRaw('CAST(sum(sale_price * quantity) / sum(quantity) as UNSIGNED) as sale_price_total')->whereColumn('product_id', 'products.id'),
    //         // epiry_date
    //         'expiry_date' => ProductsQuantity::selectRaw('min(expiry_date)')->whereColumn('product_id', 'products.id'),
    //     ]);
    // }
}
