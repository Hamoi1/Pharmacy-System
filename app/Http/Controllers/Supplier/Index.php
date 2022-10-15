<?php

namespace App\Http\Controllers\Supplier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Suppliers;

class Index extends Component
{
    use WithPagination;
    public $name, $email, $phone, $address, $search, $updateSupplier, $supplier_id, $Trashed = false;
    protected $paginationTheme = 'bootstrap', $queryString = ['search', 'Trashed' => ['except' => false]];
    private function CheckTrashParameter()
    {
        if ($this->Trashed) {
            return Suppliers::onlyTrashed();
        } else {
            return Suppliers::query();
        }
    }
    public function render()
    {
        $suppliers = $this->CheckTrashParameter();
        $this->search != '' ? $suppliers->where('name', 'like', '%' . $this->search . '%')->orWhere('address', 'like', '%' . $this->search . '%') : '';
        $GetTrashDate = function ($date) {
            return $date->addMonth()->format('Y-m-d');
        };
        return view('supplier.index', [
            'suppliers' => $suppliers->latest()->paginate(10),
            'GetTrashDate' => $GetTrashDate
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function Trash()
    {
        $this->Trashed = !$this->Trashed;
        $this->resetPage();
    }
    public function done()
    {
        $this->reset(['name', 'email', 'phone', 'address']);
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function GetRuls()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $this->supplier_id ?? '',
            'phone' => 'required|digits:11|unique:suppliers,phone,' . $this->supplier_id ?? '',
            'address' => 'required|min:3|max:255',
        ];
    }
    public function GetMessage()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('header.name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.name')]),
            'name.min' => __('validation.min.string', ['attribute' => __('header.name'), 'min' => 2]),
            'name.max' => __('validation.max.string', ['attribute' => __('header.name'), 'max' => 255]),
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
            'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
            'phone.digits' => __('validation.digits', ['attribute' => __('header.phone'), 'digits' => 11]),
            'phone.unique' => __('validation.unique', ['attribute' => __('header.phone')]),
            'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            'address.min' => __('validation.min.string', ['attribute' => __('header.address'), 'min' => 3]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.address'), 'max' => 255]),
        ];
    }
    public function add()
    {
        $this->updateSupplier = false;
    }
    public function update()
    {
        $this->updateSupplier = true;
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        if ($this->updateSupplier) {
            $supplier = Suppliers::find($this->supplier_id);
            $supplier->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
        } else {
            Suppliers::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess($this->updateSupplier ? __('header.updated') : __('header.add'));
        $this->done();
    }
    public function edit(Suppliers  $supplier)
    {
        $this->updateSupplier = true;
        $this->supplier_id = $supplier->id;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
    }
    public function delete(Suppliers $supplier)
    {
        foreach ($supplier->products as $product) {
            $product->update([
                'supplier_id' => null,
            ]);
        }
        $supplier->delete();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.deleted_for_30_days'));
        $this->done();
    }
    public function DeleteAll()
    {
        $suppliers = $this->CheckTrashParameter()->get();
        if ($suppliers->count() == 0)
            return;

        foreach ($suppliers as $supplier) {
            $supplier->forceDelete();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function RestoreAll()
    {
        $suppliers = $this->CheckTrashParameter()->get();
        foreach ($suppliers as $supplier) {
            $supplier->restore();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
    public function restore($id)
    {
        $user = Suppliers::onlyTrashed()->findOrFail($id);
        $user->restore();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
}
