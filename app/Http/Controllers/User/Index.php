<?php

namespace App\Http\Controllers\User;

use App\Events\UserActions;
use App\Events\UserPage;
use App\Events\UserStatus;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\exitPoint;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $username, $phone, $email, $address, $password, $confirm_password,
        $UpdateUser, $UserId, $search, $status, $user, $statu, $Trashed = false,
        $permission = [], $ShowPermission = false, $role_id,
        $salesPerPage = 10,
        $salesPage = 1,
        $lastPageSale,
        $productsPerPage = 10,
        $productsPage = 1,
        $lastPageproducts;
    protected $paginationTheme = 'bootstrap',
        $queryString = [
            'search' => ['except' => ['id'], 'as' => 's'],
            'status' => ['as' => 'st'],
            'Trashed' => ['except' => false],
            'page',
            'role_id' => ['as' => 'permission']
        ],
        $listeners = ['user-page' => '$refresh'];

    public $ExportData = [
            'name' => 'Name',
            'username' => 'Username',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
        $ExportDataSelected = [], $quantity, $export = false;
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
        $users = $users->whereNot('id', auth()->user()->id);
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
        // search by role_id in table users and role_id column data is array is like [1,2,3,4,5,6,7,8,9]
        $this->role_id != null ? $users->whereRaw(
            'JSON_CONTAINS(role_id, ' . $this->role_id . ')'
        ) : null;
        $GetTrashDate = function ($date) {
            return $date;
        };
        $roless = DB::table('role')->get();
        return view('user.index', [
            'users' => $users->orderByDesc('id')->UserDetails()->paginate(10),
            'GetTrashDate' => $GetTrashDate,
            'roless' => $roless
        ]);
    }
    public function updateRole_id()
    {
        $this->role_id = $this->role_id;
    }
    public function ViewUser($id)
    {
        $this->user = User::with([
            'sales',
            'products',
        ])->find($id);
        $this->Sales($this->salesPage);
        $this->products($this->productsPage);
    }

    public function Sales($numerPage)
    {
        $this->user->sales = $this->user->sales()->orderByDesc('id')->paginate(
            $this->salesPerPage,
            ['*'],
            'sales',
            $numerPage
        );
        $this->lastPageSale = $this->user->sales->lastPage();
    }
    public function products($numberPage)
    {
        $this->user->products = $this->user->products()->suppliers()->categorys()->orderByDesc('id')->paginate(
            $this->productsPerPage,
            ['*'],
            'products',
            $numberPage
        );
        $this->lastPageproducts = $this->user->products->lastPage();
    }
    public function prevPageSales()
    {
        if ($this->salesPage > 1) {
            $this->salesPage--;
            $this->Sales($this->salesPage);
        }
    }
    public function nextPageSales()
    {
        if ($this->salesPage < $this->lastPageSale) {
            $this->salesPage++;
            $this->Sales($this->salesPage);
        }
    }
    public function prevPageProduct()
    {
        if ($this->productsPage > 1) {
            $this->productsPage--;
            $this->products($this->productsPage);
        }
    }
    public function nextPageProduct()
    {
        if ($this->productsPage < $this->lastPageproducts) {
            $this->productsPage++;
            $this->products($this->productsPage);
        }
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
            'ShowPermission',
            'ExportDataSelected',
            'quantity',
            'export',
            'role_id',
            'status',
        ];
    }
    public function done($action = true)
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->reset(self::Resets());
        $this->resetValidation();
        if ($action) {
            event(new UserActions());
            event(new UserPage());
        }
    }

    public function updatstatus()
    {
        $this->status = in_array($this->status, [1, 0]) ? $this->status : '';
    }

    public function Trash()
    {
        $this->Trashed = !$this->Trashed;
        $this->resetPage();
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
                'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|unique:users,email',
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
        // if $role_id = all 
        //    add all role_id to role_permission array
        if ($role_id == 'all') {
            $this->permission = Role::pluck('id')->toArray();
        }
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
    public function All_permission()
    {
        // check   if role_permission array is empty or not
        // if role_permission array is empty
        // add all role_id to role_permission array
        if (empty($this->permission))
            $this->permission = Role::pluck('id')->toArray();
        // if role_permission array is not empty
        // remove all role_id from role_permission array
        else
            $this->permission = [];
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
                'role_id' => json_encode(array_values($this->permission))
            ]);
            $user->save();
            $user->user_details->update([
                'address' => $this->address,
            ]);
            #new data
            $new_data = [
                'name : ' . $this->name,
                'username : ' .  $this->username,
                'phone number : ' .  $this->phone,
                'email : ' .  $this->email,
                $this->statu ? 'status : Active' : 'status : Not Active',
                'address : ' .  $this->address,
            ];
            $user->InsertToLogsTable(auth()->user()->id, "User", 'Update', $old_data, $new_data);
            event(new UserStatus($user));
        } elseif (!$this->UpdateUser && Gate::allows('Insert User')) {
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => json_encode(array_values($this->permission))
            ]);
            $user->save();
            $user->user_details()->create([
                'address' => $this->address,
            ]);
            $new_data = [
                'name : ' . $this->name,
                'username : ' .  $this->username,
                'phone number : ' .  $this->phone,
                'email : ' .  $this->email,
                'status : ' .  $this->statu ? 'Active' : 'Inactive',
                'address : ' .  $this->address,
            ];
            $user->InsertToLogsTable(auth()->user()->id, "User", 'Update', '', $new_data);
        }
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.User') . ' ' . $this->UpdateUser ?  __('header.updated') :  __('header.add')]);
        $this->done();
    }
    public function Update($id)
    {
        if (!Gate::allows('Update User')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $this->UpdateUser = true;
            $user = User::UserDetails()->findOrFail($id);
            $this->UserId = $user->id;
            $this->name = $user->name;
            $this->username = $user->username;
            $this->phone = $user->phone;
            $this->email = $user->email;
            $this->address = $user->address;
            $this->statu = $user->status;
            $this->permission = json_decode($user->role_id, true) ?? [];
        }
    }
    public function delete($id)
    {
        if (!Gate::allows('Delete User')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $user = User::findOrFail($id);
            if ($user->id == auth()->user()->id) {
                $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.CanNotDeleteUser')]);
            } else {
                $data = ['Delete ( ' . $user->name . ' ) form : ' . now()];
                $user->delete();
                $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.deleted_for_30_days')]);
                auth()->user()->InsertToLogsTable(auth()->user()->id, "User", 'Delete',  $data,  $data);
            }
        }
        $this->done();
    }

    public function toggleActive(User $user)
    {
        if (!Gate::allows('Update User')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
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
            $user->InsertToLogsTable(auth()->user()->id, "User", 'Update', $old_data, $new_data);
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => $user->status == 1 ? __('header.User') . ' ' . __('header.actived') : __('header.User') . ' ' . __('header.deactived')]);
            event(new UserStatus($user));
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
            $user->DeleteLogs($user->id);
            $user->products()->update(['user_id' => null]);
            $user->sales()->update(['user_id' => null]);
            $userName[] = '( ' . $user->name . ' )';
            $user->forceDelete();
        }
        $data = ['Delete  ' . implode(' , ', $userName) . '  form : ' . now()];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "User", 'Delete',  $data,  $data);
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.deleted')]);
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
            $user->restore();
        }
        $data = ['Restore   ' . implode(' , ', $userName) . '  form : ' . now()];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "User", 'Restore',  $data,  '["nothing to show"]');
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.RestoreMessage')]);
        $this->done();
    }
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $data = ['Restore ( ' . $user->name . ' ) form : ' . now()];
        $user->restore();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "User", 'Restore',  $data,  '["nothing to show"]');
        $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.User') . ' ' . __('header.RestoreMessage')]);
        $this->done();
    }

    public function ShowPermission()
    {
        $this->ShowPermission = !$this->ShowPermission;
    }
    public function Upload($data)
    {
        if (!in_array($data, $this->ExportDataSelected)) {
            $this->ExportDataSelected[] = $data;
        } else {
            $this->ExportDataSelected = array_diff($this->ExportDataSelected, [$data]);
        }
    }

    public function ExportData()
    {
        $this->validate(
            [
                'quantity' => 'nullable|numeric',
            ]
        );

        if (count($this->ExportDataSelected) == 0) {
            flash()->addError(__('header.SelectData'));
            return;
        }
        $data = '';
        foreach ($this->ExportDataSelected as $key => $value) {
            $data .= $value . ' , ';
        }
        $data = ['Export  ' . $data . '  form : ' . now()];
        auth()->user()->InsertToLogsTable(auth()->user()->id, "User", 'Export',  $data,  $data);
        $this->ExportDataSelected = array_unique($this->ExportDataSelected);
        return ExportController::export($this->ExportDataSelected, 'users', $this->quantity);
    }
}
