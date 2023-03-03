<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Sales;
use App\Models\Products;
use App\Models\UserDetails;
use App\Models\UserPermissions;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
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
        'created_at',
        'updated_at',
        'email_verified_at',
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
    public function Permissions()
    {
        return  $this->hasMany(UserPermissions::class);
    }
    public static function  GetUserData()
    {
        return Auth::user();
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

    public function GetPermissionName($User_id)
    {
        $permissions = $this->Permissions()->where('user_id', $User_id)->with('role')->get();
        $permission_name = [];
        if ($permissions) {

            foreach ($permissions as $permission) {
                $permission_name[] = $permission->role->name;
            }
        } else {
            $permission_name = [];
        }
        return $permission_name;
    }
    public function files()
    {
        $files = scandir('logs');
        // remove . and .. from array
        array_shift($files);
        array_shift($files);
        return $files;
    }
    public function CreateFile($UserID)
    {
        $file = fopen('logs/user-' . $UserID . '-' . now()->format('Y.m.d') . '.log', 'w'); // use w to create a file if a file doesn't exist
        fclose($file);
        return true;
    }

    // insert data to file but dont delete privewe
    public function InsertDataToFile($UserID, $where,  $action, $old, $new)
    {
        // get all file inside folder logs
        $files = $this->files();
        foreach ($files as $file) {
            $file_name = explode('-', $file);
            // check if file name is equal to user id
            if ($file_name[1] == $UserID) {
                $file = fopen('logs/user-' . $UserID . '-' . $file_name[2], 'a'); // use a  to eneter data in the end of file
                // old data can be array 
                if (is_array($old)) {
                    $old = implode(',', $old);
                }
                // new data can be array
                if (is_array($new)) {
                    $new = implode(',', $new);
                }
                $data = date('Y-m-d h:i:s') . ' / ' . $where . ' / ' . $action . ' / ' . $old . ' / ' . $new . PHP_EOL;
                fwrite($file, $data);
                fclose($file);
                break;
            }
        }
        return true;
    }

    public function GetFile($UserID)
    {
        $files = $this->files();
        foreach ($files as $file) {
            $file_name = explode('-', $file);
            // check if file name is equal to user id
            if ($file_name[1] == $UserID) {
                $file = fopen('logs/user-' . $UserID . '-' . $file_name[2], 'r'); // use r to get data in the satrt of file
                // check file size
                if (filesize('logs/user-' . $UserID  . '-' . $file_name[2]) == 0) {
                    return [];
                }
                $data = fread($file, filesize('logs/user-' . $UserID  . '-' . $file_name[2]));
                // change data to array
                $data = explode(PHP_EOL, $data);
                // remove last element
                array_pop($data);
                return $data;
            }
        }

        return [];
    }

    public function DeleteFile($UserID)
    {
        $files = $this->files();
        foreach ($files as $file) {
            $file_name = explode('-', $file);
            // check if file name is equal to user id
            if ($file_name[1] == $UserID) {
                unlink('logs/user-' . $UserID . '-' . $file_name[2]);
                break;
            }
        }
    }

    public function DeleteDataInFile($UserID, $index, $action)
    {
        $files = $this->files();
        foreach ($files as $file) {
            $file_name = explode('-', $file);
            // check if file name is equal to user id
            if ($file_name[1] == $UserID) {
                // delete a record by index an action 
                $data = file_get_contents('logs/user-' . $UserID . '-' . $file_name[2]);
                $data = explode(PHP_EOL, $data);
                array_pop($data);
                $data = array_reverse($data);
                $new_data = [];
                foreach ($data as $key => $value) {
                    $value = explode(' / ', $value);
                    if ($key == $index && $value[2] == $action) {
                        continue;
                    }
                    $new_data[] = implode(' / ', $value); // convert array to string            
                }
                $new_data = array_reverse($new_data);
                $new_data = implode(PHP_EOL, $new_data);
                $file = fopen('logs/user-' . $UserID . '-' . $file_name[2], 'w');
                fwrite($file, $new_data . PHP_EOL);
                fclose($file);
            }
        }
    }

    public function ClearFile($id)
    {
        $this->CreateFile($id);
    }
}
