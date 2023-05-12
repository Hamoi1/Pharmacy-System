<?php

namespace App\Models;

use App\Models\User;
use App\Models\Categorys;
use App\Models\Suppliers;
use App\Models\sale_details;
use App\Models\ProductsQuantity;
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
    public function product_quantity()
    {
        return $this->hasMany(ProductsQuantity::class, 'product_id');
    }

    public function scopeTotalQuantity($query)
    {
        return  $query->addSelect([
            'total_quantity' => ProductsQuantity::selectRaw('sum(quantity)')->whereColumn('product_id', 'products.id'),
        ]);
    }
    public function scopeMinQuantity($query)
    {
        return  $query->addSelect([
            'min_quantity' => ProductsQuantity::selectRaw('min(quantity)')->whereColumn('product_id', 'products.id'),
        ]);
    }
    public function scopeExpiryDate($query)
    {
        return  $query->addSelect([
            'min_expiry_date' => ProductsQuantity::selectRaw('min(expiry_date)')->whereColumn('product_id', 'products.id')
                ->where('expiry_date', '>', now()->format('Y-m-d')),
        ]);
    }

    public function scopeSalePrice($query)
    {
        return  $query->addSelect([
            // 'final_sale_price' => ProductsQuantity::selectRaw('CAST(sum(sale_price * quantity) / sum(quantity) as UNSIGNED) as sale_price_total')->whereColumn('product_id', 'products.id'),
            // remove cast
            'final_sale_price' => ProductsQuantity::selectRaw('sum(sale_price * quantity) / sum(quantity) as sale_price_total')->whereColumn('product_id', 'products.id'),
        ]);
    }
}
