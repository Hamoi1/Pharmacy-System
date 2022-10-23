<?php

namespace App\Models;

use App\Models\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebtSale extends Model
{
    use HasFactory;
    protected $table = 'debt_sales';
    protected $fillable = [
        'sale_id',
        'name',
        'phone',
        'amount',
        'paid',
        'remain',
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }
    public function scopesale($query)
    {
        return $query->addSelect(['invoice' => Sales::select('invoice')->whereColumn('id', 'sale_id')]);
    }
}
