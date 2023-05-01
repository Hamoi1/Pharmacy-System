<?php

namespace App\Http\Controllers\Customer;

use App\Events\CustomerPage;
use Livewire\Component;
use App\Models\Customers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class AddUpdate extends Component
{
    public $customer, $name, $phone, $email, $address, $guarantorphone, $guarantoraddress,
        $CustomerUpadate = false, $CustomerId;
    protected $listeners = ['Update', 'addustomer'];
    public function mount()
    {
        if (!Gate::allows(['Update Customer', 'Insert Customer']) && Route::currentRouteName() != 'slaes') {
            abort(404);
        }
    }
    public function render()
    {
        return view('customer.add-update');
    }
    public function done($action = true)
    {
        $this->reset([
            'name',
            'phone',
            'email',
            'address',
            'guarantorphone',
            'guarantoraddress',
            'CustomerId',
            'customer',
            'CustomerUpadate'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
        if ($action) {
            event(new CustomerPage());
            $this->emit('RefreshCustomer');
        }
    }
    public function addustomer()
    {
        $this->CustomerUpadate == false;
    }
    private function Rule()
    {
        return [
            'name' => 'required|string|unique:customers,name,' . $this->CustomerId ?? '',
            'phone' => 'required|numeric|digits:11|unique:customers,phone,' . $this->CustomerId ?? '',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z]+\.[a-zA-Z]+$/u|unique:customers,email,' . $this->CustomerId ?? '',
            'address' => 'required|string',
            'guarantorphone' => 'nullable|numeric',
            'guarantoraddress' => 'nullable|string',
        ];
    }
    private function messages()
    {
        return  [
            'name.required' => __('validation.required', ['attribute' => __('header.name')]),
            'name.string' => __('validation.string', ['attribute' => __('header.name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('header.name')]),
            'email.required' => __('validation.required', ['attribute' => __('header.email')]),
            'email.email' => __('validation.email', ['attribute' => __('header.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('header.email')]),
            'email.regex' => __('validation.regex', ['attribute' => __('header.email')]),
            'phone.required' => __('validation.required', ['attribute' => __('header.phone')]),
            'phone.numeric' => __('validation.numeric', ['attribute' => __('header.phone')]),
            'phone.digits' => __('validation.digits', ['attribute' => __('header.phone')]),
            'phone.unique' => __('validation.unique', ['attribute' => __('header.phone')]),
            'address.required' => __('validation.required', ['attribute' => __('header.address')]),
            'guarantorphone.numeric' => __('validation.numeric', ['attribute' => __('header.guarantorphone')]),
            'guarantorphone.digits' => __('validation.digits', ['attribute' => __('header.guarantorphone')]),
            'guarantoraddress.string' => __('validation.string', ['attribute' => __('header.guarantoraddress')]),
        ];
    }

    public function submit()
    {
        $this->validate(
            $this->Rule(),
            $this->messages()
        );
        if ($this->CustomerUpadate) {
            Customers::find($this->CustomerId)->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'guarantorphone' => $this->guarantorphone,
                'guarantoraddress' => $this->guarantoraddress,
            ]);
        } else {
            Customers::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'guarantorphone' => $this->guarantorphone,
                'guarantoraddress' => $this->guarantoraddress,
            ]);
        }
        $this->CustomerUpadate ?
            flash()->addSuccess(__('header.Customer') . ' ' . __('header.updated')) :
            flash()->addSuccess(__('header.Customer') . ' ' . __('header.add'));
        $this->done();
    }

    public function Update($id)
    {
        $this->CustomerUpadate = true;
        $customer = Customers::find($id);
        $this->CustomerId = $customer->id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->address = $customer->address;
        $this->guarantorphone = $customer->guarantorphone;
        $this->guarantoraddress = $customer->guarantoraddress;
    }
}
