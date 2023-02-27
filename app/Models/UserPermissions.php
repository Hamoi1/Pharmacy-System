<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPermissions extends Model
{
    use HasFactory;

    protected $table = 'user_permissions';

    protected $fillable = [
        'role_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
