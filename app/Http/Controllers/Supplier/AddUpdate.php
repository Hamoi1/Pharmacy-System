<?php

namespace App\Http\Controllers\Supplier;

use Livewire\Component;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Gate;

class AddUpdate extends Component
{
    public $name, $email, $phone, $address, $updateSupplier = false, $supplier_id;
    protected $listeners = ['AddSupplier', 'UpdateSupplier'];
    public function render()
    {
        return view('supplier.add-update');
    }
    public function done()
    {
        $this->emit('RefreshSupplier');
        $this->reset(['name', 'email', 'phone', 'address', 'updateSupplier']);
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function GetRuls()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|unique:suppliers,email,' . $this->supplier_id ?? '',
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
            'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
            'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
            'phone.digits' => __('validation.digits', ['attribute' => __('header.phone'), 'digits' => 11]),
            'phone.unique' => __('validation.unique', ['attribute' => __('header.phone')]),
            'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            'address.min' => __('validation.min.string', ['attribute' => __('header.address'), 'min' => 3]),
            'address.max' => __('validation.max.string', ['attribute' => __('header.address'), 'max' => 255]),
        ];
    }
    public function AddSupplier()
    {
        $this->updateSupplier = false;
        return;
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessage());
        if ($this->updateSupplier &&  Gate::allows('Update Supplier')) {
            $supplier = Suppliers::find($this->supplier_id);
            $oldData = [
                'name : ' . $supplier->name,
                'email : ' . $supplier->email,
                'phone : ' . $supplier->phone,
                'address : ' . $supplier->address,
            ];
            $supplier->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            $newData = [
                'name : ' . $this->name,
                'email : ' . $this->email,
                'phone : ' . $this->phone,
                'address : ' . $this->address,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Update', $oldData, $newData);
        } elseif ($this->updateSupplier == false && Gate::allows('Insert Supplier')) {
            $supplier = Suppliers::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            $newData = [
                'name : ' . $this->name,
                'email : ' . $this->email,
                'phone : ' . $this->phone,
                'address : ' . $this->address,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Create',  'nothing to show', $newData);
        }
        flash()->addSuccess($this->updateSupplier ? __('header.supplier') . ' ' . __('header.updated') : __('header.supplier') . ' ' . __('header.add'));
        $this->done();
    }
    public function UpdateSupplier(Suppliers  $supplier)
    {
        if (!Gate::allows('Update Supplier')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $this->updateSupplier = true;
            $this->supplier_id = $supplier->id;
            $this->name = $supplier->name;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->address = $supplier->address;
        }
    }
}
