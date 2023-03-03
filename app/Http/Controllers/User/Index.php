<?php

namespace App\Http\Controllers\User;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\UserDetails;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\UserPermissions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $username, $phone, $email, $address, $password, $confirm_password,
        $UpdateUser, $UserId, $search, $status, $user, $statu, $Trashed = false,
        $permission = [], $ShowPermission = false, $permission_id;
    protected $paginationTheme = 'bootstrap',
        $queryString = [
            'search' => ['except' => ['id'], 'as' => 's'],
            'status' => ['as' => 'st'],
            'Trashed' => ['except' => false],
            'page',
            'permission_id' => ['as' => 'permission']
        ];
    public function mount()
    {
        if (!Gate::allows('View User')) {
            abort(404);
        }
        Gate::allows('User Trash') ? $this->Trashed  : $this->Trashed = false;
    }

    private function CheckTrashParameter()
    {
        if ($this->Trashed) {
            return User::onlyTrashed();
        } else {
            return User::query();
        }
    }

    public function render()
    {
        $users = $this->CheckTrashParameter();
        $this->search != '' ? $users->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('username', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere(function ($query) {
                    $query->whereHas('user_details', function ($query) {
                        $query->where('address', 'like', '%' . $this->search . '%');
                    });
                });
        }) : '';
        $this->status != null ? $users->Where('status', $this->status) : null;
        // serach by permission_id
        $this->permission_id != null ? $users->WhereHas('permissions', function ($query) {
            $query->where('role_id', $this->permission_id);
        }) : null;
        $users = $users->where('id', '!=', auth()->user()->id);
        $GetTrashDate = function ($date) {
            return $date;
        };
        $roless = Role::get();
        return view('user.index', [
            'users' => $users->latest()->UserDetails()->paginate(10),
            'GetTrashDate' => $GetTrashDate,
            'roless' => $roless
        ]);
    }

    private static function Resets()
    {
        return [
            'name',
            'username',
            'phone',
            'email',
            'address',
            'password',
            'confirm_password',
            'UpdateUser',
            'UserId',
            'user',
            'statu',
            'permission',
            'ShowPermission'
        ];
    }
    public function done()
    {
        $this->add();
        $this->reset(self::Resets());
        $this->dispatchBrowserEvent('closeModal');
        $this->resetValidation();
    }
    public function add()
    {
        $this->UpdateUser == false;
    }

    public function updatstatus()
    {
        $this->status = in_array($this->status, [1, 0]) ? $this->status : '';
    }

    public function Trash()
    {
        $this->Trashed = !$this->Trashed;
        $this->resetPage();
        $this->done();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function GetRulse()
    {
        if ($this->UpdateUser) {
            return [
                'name' => 'required|regex:/^[\p{N}\p{Arabic}a-zA-Z_ .-]*$/u|min:3|max:30',
                'username' => 'required|string|alpha_dash|min:3|max:20|unique:users,username,' . $this->UserId,
                'phone' => 'required|numeric|digits:11|unique:users,phone,' . $this->UserId,
                'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|unique:users,email,' . $this->UserId,
                'address' => 'required|alpha_dash|min:3|max:40',
                'statu' => 'in:1,0|nullable',
                'permission' => 'array|exists:role,id',
            ];
        } else {
            return [
                'name' => 'required|regex:/^[\p{N}\p{Arabic}a-zA-Z_ .-]*$/u|min:3|max:30',
                'username' => 'required|string|alpha_dash|min:3|max:20|unique:users,username',
                'phone' => 'required|numeric|digits:11|unique:users,phone',
                'email' => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u',
                'password' => 'required|min:8|max:40|same:confirm_password',
                'address' => 'required|alpha_dash|min:3|max:40',
                'permission' => 'array|exists:role,id',
            ];
        }
    }
    public function GetMessage()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('header.name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.name')]),
            'name.min' => __('validation.min.string', ['attribute' => __('header.name'), 'min' => 3]),
            'name.max' => __('validation.max.string', ['attribute' => __('header.name'), 'max' => 30]),
            'name.regex' => __('validation.regex', ['attribute' => __('header.name')]),
            'username.required' => __('validation.required', ['attribute' => __('header.username')]),
            'username.string' => __('validation.string', ['attribute' => __('header.username')]),
            'username.min' => __('validation.min.string', ['attribute' => __('header.username'), 'min' => 3]),
            'username.max' => __('validation.max.string', ['attribute' => __('header.username'), 'max' => 20]),
            'username.unique' => __('validation.unique', ['attribute' => __('header.username')]),
            'username.alpha_dash' => __('validation.alpha_dash', ['attribute' => __('header.username')]),
            'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
            'phone.digits' => __('validation.digits', ['attribute' => __('header.phone'), 'digits' => 11]),
            'phone.unique' => __('validation.unique', ['attribute' => __('header.phone')]),
            'phone.numeric' => __('validation.numeric', ['attribute' => __('header.phone')]),
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
            'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
            'password.required' => __('validation.required', ['attribute' => __('header.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('header.password'), 'min' => 8]),
            'password.max' => __('validation.max.string', ['attribute' => __('header.password'), 'max' => 40]),
            'password.same' => __('validation.same', ['attribute' => __('header.password'), 'other' => __('header.confirm_password')]),
            'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            'address.min' => __('validation.min.string', ['attribute' => __('header.address'), 'min' => 3]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.address'), 'max' => 40]),
            'address.alpha_dash' => __('validation.alpha_dash', ['attribute' => __('header.address')]),
            'statu.required' => __('validation.required', ['attribute' => __('header.status')]),
            'statu.in' => __('validation.in', ['attribute' => __('header.status')]),
            'permission.required' => __('validation.required', ['attribute' => __('header.permission')]),
            'permission.array' => __('validation.array', ['attribute' => __('header.permission')]),
            'permission.min' => __('validation.min.array', ['attribute' => __('header.permission'), 'min' => 1]),
            'permission.exists' => __('validation.exists', ['attribute' => __('header.permission')]),
        ];
    }
    public function role_permission($role_id)
    {
        //    before add data to role_permission array check 
        //    if this role_id is exist in role_permission array or not
        if (!in_array($role_id, $this->permission)) {
            array_push($this->permission, $role_id);
        } else {
            //    if this role_id is exist in role_permission array
            //    remove it from role_permission array
            $this->permission = array_diff($this->permission, [$role_id]);
        }
    }

    public function submit()
    {
        $this->validate($this->GetRulse(), $this->GetMessage());
        if ($this->UpdateUser && Gate::allows('Update User')) {
            $user = User::find($this->UserId);
            #old data
            $old_data = [
                'name : ' . $user->name,
                'username : ' .  $user->username,
                'phone number : ' .  $user->phone,
                'email : ' .  $user->email,
                $user->status ? 'status : Active' : 'status : Not Active',
                'address : ' .  $user->user_details->address,
            ];
            $user->update([
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'status' => $this->statu,
            ]);
            UserDetails::where('user_id', $this->UserId)->update([
                'address' => $this->address,
            ]);
            // update permission
            UserPermissions::where('user_id', $this->UserId)->delete();
            foreach ($this->permission as $permission) {
                // get role id add to UserPermission table
                $id = Role::find($permission)->id;
                UserPermissions::create([
                    'role_id' => $id,
                    'user_id' => $this->UserId,
                ]);
            }
            #new data
            $new_data = [
                'name : ' . $this->name,
                'username : ' .  $this->username,
                'phone number : ' .  $this->phone,
                'email : ' .  $this->email,
                $this->statu ? 'status : Active' : 'status : Not Active',
                'address : ' .  $this->address,
            ];
            $user->InsertDataToFile(auth()->user()->id, "User", 'Update', $old_data, $new_data);
        } elseif (!$this->UpdateUser && Gate::allows('Insert User')) {
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            UserDetails::create([
                'user_id' => $user->id,
                'address' => $this->address,
            ]);
            foreach ($this->permission as $permission) {
                $permission_name = Role::find($permission)->name;
                UserPermissions::create([
                    'role_id' => $permission_name,
                    'user_id' => $user->id,
                ]);
            }
            $new_data = [
                'name : ' . $this->name,
                'username : ' .  $this->username,
                'phone number : ' .  $this->phone,
                'email : ' .  $this->email,
                'status : ' .  $this->statu ? 'Active' : 'Inactive',
                'address : ' .  $this->address,
            ];
            $user->CreateFile($user->id);
            $user->InsertDataToFile(auth()->user()->id, "User", 'Update', '', $new_data);
        }
        flash()->addSuccess($this->UpdateUser ?  __('header.updated') :  __('header.add'));
        $this->done();
    }
    public function Update($id)
    {
        if (!Gate::allows('Update User')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $this->UpdateUser = true;
            $users = User::UserDetails()->with('Permissions')->findOrFail($id);
            $this->UserId = $users->id;
            $this->name = $users->name;
            $this->username = $users->username;
            $this->phone = $users->phone;
            $this->email = $users->email;
            $this->address = $users->address;
            $this->statu = $users->status;
            $this->permission = $users->Permissions->pluck('role_id')->toArray();
        }
    }
    public function delete($id)
    {
        if (!Gate::allows('Delete User')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $user = User::findOrFail($id);
            if ($user->id == auth()->user()->id) {
                flash()->addError(__('header.CanNotDeleteUser'));
            } else {
                $user->DeleteFile($user->id);
                $data = 'Delete ( ' . $user->name . ' ) form : ' . now();
                $user->delete();
                flash()->addSuccess(__('header.deleted_for_30_days'));
                auth()->user()->InsertDataToFile(auth()->user()->id, "User", 'Delete',  $data,  $data);
            }
        }
        $this->done();
    }
    public function show(User $user)
    {
        $user = User::UserDetails()->findOrFail($user->id);
        $this->user = $user;
    }

    public function toggleActive(User $user)
    {
        if (!Gate::allows('Update User')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $old_data = [
                'Name : ' . $user->name,
                $user->status ? 'status : Active' : 'status : Not Active'
            ];
            $user->update([
                'status' => $user->status == 1 ? 0 : 1,
            ]);
            $new_data = [
                'Name : ' . $user->name,
                $user->status ? 'status : Active' : 'status : Not Active'
            ];
            $user->InsertDataToFile(auth()->user()->id, "User", 'Update', $old_data, $new_data);
            flash()->addSuccess($user->status == 1 ? __('header.actived') : __('header.deactived'));
        }
        $this->done();
    }
    public function DeleteAll()
    {
        $users = $this->CheckTrashParameter()->get();
        if ($users->count() == 0)
            return;

        $userName = [];
        foreach ($users as $user) {
            $user->products()->update(['user_id' => null]);
            $user->sales()->update(['user_id' => null]);
            $userName[] = '( ' . $user->name . ' )';
            $user->forceDelete();
        }
        $data = 'Delete  ' . implode(' , ', $userName) . '  form : ' . now();
        auth()->user()->InsertDataToFile(auth()->user()->id, "User", 'Delete',  $data,  '');
        flash()->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function RestoreAll()
    {
        $users = $this->CheckTrashParameter()->get();
        if ($users->count() == 0)
            return;

        $userName = [];
        foreach ($users as $user) {
            $userName[] = '( ' . $user->name . ' )';
            $user->CreateFile($user->id);
            $user->restore();
        }
        $data = 'Restore   ' . implode(' , ', $userName) . '  form : ' . now();
        auth()->user()->InsertDataToFile(auth()->user()->id, "User", 'Restore',  $data, '');
        flash()->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->CreateFile($user->id);
        $data = 'Restore ( ' . $user->name . ' ) form : ' . now();
        $user->restore();
        auth()->user()->InsertDataToFile(auth()->user()->id, "User", 'Restore',  $data, '');
        flash()->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }

    public function ShowPermission()
    {
        $this->ShowPermission = !$this->ShowPermission;
    }
}
