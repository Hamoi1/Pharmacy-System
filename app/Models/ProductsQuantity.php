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

  
}
