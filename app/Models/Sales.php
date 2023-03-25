<?php

namespace App\Models;

use App\Models\User;
use App\Models\DebtSale;
use App\Models\Customers;
use App\Models\sale_details;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $guarded = [];

    public function sale_details()
    {
        return $this->hasMany(sale_details::class, 'sale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function debt_sale()
    {
        return $this->hasOne(DebtSale::class, 'sale_id');
    }

    public function customers()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function scopecustomersData($query)
    {
        return $query->addSelect(['customer_name' => Customers::select('name')->whereColumn('id', 'sales.customer_id')])
            ->addSelect(['customer_phone' => Customers::select('phone')->whereColumn('id', 'sales.customer_id')]);
    }

    public function scopeSaleData($query)
    {
        return $query->with('sale_details', function ($query) {
            return $query->whereNotNull('product_id')->with('products');
        })->with('user', 'debt_sale', 'customers');
    }
    public function scopeUser($query)
    {
        return $query->addSelect(['user_name' => User::select('name')->whereColumn('id', 'sales.user_id')]);
    }
}
