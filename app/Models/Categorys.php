<?php

namespace App\Models;

use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorys extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categorys';
    protected $guarded  = [];


    public function scopeproducts_count($query)
    {
        return $query->addSelect(
            ['products_count' => Products::selectRaw('count(*)')->whereColumn('category_id', 'categorys.id')]
        );
    }
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
    public function create_at()
    {
        return $this->created_at->format('Y-m-d') == now()->format('Y-m-d') ? true : false;
    }
}
