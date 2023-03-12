<?php

namespace App\Models;

use App\Models\User;
use App\Models\Sales;
use App\Models\Categorys;
use App\Models\Suppliers;
use App\Models\sale_details;
use App\Models\ProductQuantity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';
    protected $guarded  = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Categorys::class, 'category_id', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id', 'id');
    }

    public function sale_details()
    {
        return  $this->hasMany(sale_details::class, 'product_id');
    }
    public function ProductsQuantity()
    {
        return $this->hasMany(ProductQuantity::class, 'product_id');
    }
    public function scopesuppliers($query)
    {
        return $query->addSelect(['supplier_name' => Suppliers::select('name')->whereColumn('id', 'products.supplier_id')]);
    }
    public function scopecategorys($query)
    {
        return $query->addSelect(['category_name' => Categorys::select('name')->whereColumn('id', 'products.category_id')]);
    }
    public function create_at()
    {
        return $this->created_at->format('Y-m-d') == now()->format('Y-m-d') ? true : false;
    }
    // public function scopeProductQuantity($query)
    // {
    //     $query->addSelect([
    //         'quantity_product' => ProductQuantity::selectRaw('sum(quantity)')->whereColumn('product_id', 'products.id'),
    //         'sale_price_product' => ProductQuantity::selectRaw('CAST(sum(sale_price * quantity) / sum(quantity) as UNSIGNED) as sale_price_total')->whereColumn('product_id', 'products.id'),
    //         // epiry_date
    //         'expiry_date' => ProductQuantity::selectRaw('min(expiry_date)')->whereColumn('product_id', 'products.id'),
    //     ]);
    //     return $query;
    // }

    // //  sale_price_product insert into a sale_price in 
    // public function getSalePriceAttribute($value)
    // {
    //     return $this->sale_price_product;
    // }
}
