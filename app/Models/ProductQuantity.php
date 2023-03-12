<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuantity extends Model
{
    use HasFactory;
    protected $table = 'product_quantity';
    protected $fillable = [
        'product_id', 'quantity',
        'purches_price', 'expiry_date',
        'sale_price',
    ];
    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
