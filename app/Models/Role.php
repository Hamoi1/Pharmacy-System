<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $guarded = [];
    public function permissions()
    {
        return $this->hasMany(UserPermissions::class, 'role_id');
    }
}
