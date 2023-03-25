<?php

namespace App\Models;

use App\Models\Sales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customers extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'guarantor_phone',
        'guarantor_address',
    ];

    public function sales()
    {
        return $this->hasMany(Sales::class, 'customer_id');
    }
}
