<?php

namespace App\Models;

use App\Models\User;
use App\Models\DebtSale;
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
    public function scopeSaleDebtName($query)
    {
        return $query->addSelect([
            'name' => DebtSale::select('name')
                ->whereColumn('sale_id', 'sales.id')
        ]);
    }
    public function scopeSaleData($query)
    {
        return $query->with('sale_details', function ($query) {
            return $query->whereNotNull('product_id')->with('products');
        })->with('user', 'debt_sale');
    }
    public function scopeUser($query)
    {
        return $query->addSelect(['user_name' => User::select('name')->whereColumn('id', 'sales.user_id')]);
    }
}
