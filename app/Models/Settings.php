<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $guarded = [];
    protected $attributes = [
        'name' => 'Pharmacy',
        'phone' => '07500000000',
        'email' => 'gmail@gmail.com',
        'address' => 'location',
        'exchange_rate' => 1450,
    ];
}
