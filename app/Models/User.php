<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Sales;
use App\Models\UserDetails;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;
    protected $table = 'users';
    protected $guarded  = [];
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'role',
        'status',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_details()
    {
        return $this->hasOne(UserDetails::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'user_id');
    }

    public function scopeUserDetails($query)
    {
        return $query->addSelect(
            ['image' =>
            UserDetails::select('image')->whereColumn('user_id', 'users.id')]
        )->addSelect(
            ['address' =>
            UserDetails::select('address')->whereColumn('user_id', 'users.id')]
        );
    }

    public function image($image)
    {
        return $image == null || $image == '' ? asset('assets/images/image_not_available.png') : asset('storage/users/' . $image);
    }
    public function role()
    {
        return $this->role == 1 ? 'Admin' : 'Cashier';
    }
    public function create_at()
    {
       return $this->created_at->format('Y-m-d') == now()->format('Y-m-d') ? true : false;
    }
}
