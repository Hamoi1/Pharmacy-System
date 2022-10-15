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
        'phone' => '07501842910',
        'email' => 'ihama9728@gmail.com',
        'address' => 'Ranya - Kewarash',
    ];
}
