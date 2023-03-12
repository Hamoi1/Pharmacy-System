<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logs extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $fillable = [
        'user_id',
        'page',
        'action',
        'old_data',
        'new_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // format create_at to y-m-d
    public function create_at()
    {
        return $this->created_at->format('Y-m-d');
    }
}
