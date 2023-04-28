<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\UserActions;
use App\Models\Logs;
use App\Models\Sales;
use App\Models\Products;
use App\Models\UserDetails;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $table = 'users';
    protected $guarded  = [];
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'status',
        'role_id'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function user_details()
    {
        return $this->hasOne(UserDetails::class, 'user_id');
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
    public function create_at()
    {
        return $this->created_at->format('Y-m-d') == now()->format('Y-m-d') ? true : false;
    }
    public function products()
    {
        return $this->hasMany(Products::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'user_id');
    }

    public function GetPermissionName($User_id)
    {
        $roles = Role::get();
        $user  = User::find($User_id);
        $user_role = json_decode($user->role_id);
        $user_role = array_values($user_role);
        $role_name = [];
        // if user_role has role->id  get the role->name
        foreach ($roles as $role) {
            if (in_array($role->id, $user_role)) {
                $role_name[] = $role->name;
            }
        }
        return $role_name;
    }
    // insert data to file but dont delete privewe
    public function InsertToLogsTable($UserID, $where,  $action, $old, $new)
    {
        // dd($UserID, $where, $action, $old, $new);
        Logs::create([
            'user_id' => $UserID,
            'page' => $where,
            'action' => $action,
            'old_data' => json_encode($old),
            'new_data' => json_encode($new),
        ]);
        event(new  UserActions());
        return true;
    }

    public function GetDataLogs($UserID)
    {
        $logs = Logs::where('user_id', $UserID)->get();
        event(new  UserActions());
        return $logs;
    }

    public function DeleteLogs($UserID)
    {
        // delete data in logs table by id
        return Logs::where('user_id', $UserID)->delete();
        event(new  UserActions());
    }

    public function DeleteDataInLogs($UserID, $index)
    {
        // delete data in logs table by id
        return Logs::where('user_id', $UserID)->where('id', $index)->delete();
        event(new  UserActions());
    }
}
