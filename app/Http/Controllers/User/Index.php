<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Livewire\Component;
use App\Models\UserDetails;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $username, $phone, $email, $role, $address, $password, $confirm_password,
        $UpdateUser, $UserId, $search, $roles, $status, $user, $statu, $Trashed = false;
    protected $paginationTheme = 'bootstrap',
        $queryString = [
            'search' => ['except' => ['id'], 'as' => 's'],
            'roles' => ['as' => 'r'],
            'status' => ['as' => 'st'],
            'Trashed' => ['except' => false],
            'page'
        ];
    private static function Resets()
    {
        return [
            'name',
            'username',
            'phone',
            'email',
            'role',
            'address',
            'password',
            'confirm_password',
            'UpdateUser',
            'UserId',
            'user',
            'statu',
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
    public function updateroles()
    {
        $this->roles = in_array($this->roles, [1, 2]) ? $this->roles : '';
    }
    public function updatstatus()
    {
        $this->status = in_array($this->status, [1, 0]) ? $this->status : '';
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
        // if not admin roles and status not working
        if (Gate::allows('admin')) {
            $this->status != null ? $users->Where('status', $this->status) : null;
            $this->roles  != null ? $users->Where('role', $this->roles) : null;
        }
        $users = $users->where('id', '!=', auth()->user()->id);
        $GetTrashDate = function ($date) {
            return $date->addMonth()->format('Y-m-d');
        };
        return view('user.index', [
            'users' => $users->latest()->UserDetails()->paginate(10),
            'GetTrashDate' => $GetTrashDate
        ]);
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
                'role' => 'required|in:1,2',
                'address' => 'required|alpha_dash|min:3|max:40',
                'statu' => 'in:1,0|nullable',
            ];
        } else {
            return [
                'name' => 'required|regex:/^[\p{N}\p{Arabic}a-zA-Z_ .-]*$/u|min:3|max:30',
                'username' => 'required|string|alpha_dash|min:3|max:20|unique:users,username',
                'phone' => 'required|numeric|digits:11|unique:users,phone',
                'email' => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u',
                'password' => 'required|min:8|max:40|same:confirm_password',
                'role' => 'required|in:1,2',
                'address' => 'required|alpha_dash|min:3|max:40',
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
            'role.required' => __('validation.required', ['attribute' => __('header.role')]),
            'role.in' => __('validation.in', ['attribute' => __('header.role')]),
            'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            'address.min' => __('validation.min.string', ['attribute' => __('header.address'), 'min' => 3]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.address'), 'max' => 40]),
            'address.alpha_dash' => __('validation.alpha_dash', ['attribute' => __('header.address')]),
            'statu.required' => __('validation.required', ['attribute' => __('header.status')]),
            'statu.in' => __('validation.in', ['attribute' => __('header.status')]),
        ];
    }
    public function submit()
    {
        if (!Gate::allows('admin')) {
            notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addError(__('header.not-allowed'));
            $this->done();
        }
        $this->validate($this->GetRulse(), $this->GetMessage());
        sleep(1);
        if ($this->UpdateUser) {
            $user = User::find($this->UserId);
            $user->update([
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'role' => $this->role,
                'status' => $this->statu,
            ]);
            UserDetails::where('user_id', $this->UserId)->update([
                'address' => $this->address,
            ]);
        } else {
            $users = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
            UserDetails::create([
                'user_id' => $users->id,
                'address' => $this->address,
            ]);
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess($this->UpdateUser ?  __('header.updated') :  __('header.add'));
        $this->done();
    }
    public function Update($id)
    {
        if (!Gate::allows('admin')) {
            notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.not-allowed'));
            $this->done();
        }
        $this->UpdateUser = true;
        $users = User::UserDetails()->findOrFail($id);
        $this->UserId = $users->id;
        $this->name = $users->name;
        $this->username = $users->username;
        $this->phone = $users->phone;
        $this->email = $users->email;
        $this->role = $users->role;
        $this->address = $users->address;
        $this->statu = $users->status;
    }
    public function delete($id)
    {
        if (!Gate::allows('admin')) {
            notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.not-allowed'));
            $this->done();
        }
        User::findOrFail($id)->delete();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.deleted_for_30_days'));
        $this->done();
    }
    public function show(User $user)
    {
        $user = User::UserDetails()->findOrFail($user->id);
        $this->user = $user;
    }

    public function toggleActive(User $user)
    {
        if (!Gate::allows('admin')) {
            notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.not-allowed'));
            $this->done();
        }
        $user->update([
            'status' => $user->status == 1 ? 0 : 1,
        ]);
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess($user->status == 1 ? __('header.actived') : __('header.deactived'));
        $this->done();
    }
    public function DeleteAll()
    {
        $users = $this->CheckTrashParameter()->get();
        if ($users->count() == 0)
            return;

        foreach ($users as $user) {
            $user->products()->update(['user_id' => null]);
            $user->sales()->update(['user_id' => null]);
            
            $user->forceDelete();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function RestoreAll()
    {
        $users = $this->CheckTrashParameter()->get();
        foreach ($users as $user) {
            $user->restore();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
}
