<?php

namespace App\Http\Controllers\Sales;

use App\Models\Customers;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\DebtSale as DebtSaleModel;
use Livewire\WithPagination;

class DebtSale extends Component
{
    public $Edit = false, $search, $status,
        $name, $phone, $currentPaid, $amount, $remain, $price, $__id, $customer_id;
    use WithPagination;
    protected $paginationTheme = 'bootstrap',
        $queryString = [
            // 'search' => ['as' => 's', 'except' => ''],
            'status' => ['as' => 'st', 'except' => ''],
            'customer_id' => ['as' => 'customer', 'except' => ''],
        ];
    public function mount()
    {
        $this->status = $this->status == 1 || $this->status == 0 ? $this->status : '';
    }
    public function render()
    {
        $customers = Customers::select(['id', 'name', 'email'])->get();
        if (!Gate::allows('View DebtSale')) {
            abort(404);
        }
        $debtSales = DebtSaleModel::query();
        $debtSales = $debtSales->with('sale', function ($query) {
            $query->select(['id', 'invoice'])->customersData();
        });
        // $this->search != null ? $debtSales->where('name', 'like', '%' . $this->search . '%') : null;
        $this->customer_id != null ?
            $debtSales->whereHas('sale', function ($query) {
                $query->where('customer_id', $this->customer_id);
            }) : null;
        $this->status != '' ? $debtSales->where('status', $this->status) : null;

        $debtSales = $debtSales->latest()->paginate(10);
        return view('sales.debt-sale', [
            'debtSales' =>  $debtSales,
            'customers' => $customers,
        ]);
    }
    public function done($action = true)
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->reset(['Edit', 'name', 'phone', 'currentPaid', 'amount', 'remain', 'price']);
        $this->resetValidation();
    }
    public function edit($id)
    {
        $debtSale =  DebtSaleModel::query();
        if (!Gate::allows('Update DebtSale')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
        } else {
            $debtSale = $debtSale->with('sale', function ($query) {
                $query->select(['id', 'invoice'])->customersData();
            })->find($id);
            $this->Edit = true;
            $this->__id = $debtSale->id;
            $this->name = $debtSale->sale->customer_name;
            $this->phone = $debtSale->sale->customer_phone;
            $this->amount = $debtSale->amount;
            $this->currentPaid = $debtSale->paid;
            $this->remain = $debtSale->remain;
        }
    }
    public function submit()
    {
        if (!Gate::allows('Update DebtSale')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
        } else {
            $this->validate([
                'price' => 'required|numeric|min:1|max:' . $this->remain,
            ], [
                'price.required' => __('validation.required', ['attribute' => __('header.Returned Money')]),
                'price.numeric' => __('validation.numeric', ['attribute' => __('header.Returned Money')]),
                'price.min' => __('validation.min.numeric', ['attribute' => __('header.Returned Money'), 'min' => 1]),
                'price.max' => __('validation.max.numeric', ['attribute' => __('header.Returned Money'), 'max' => $this->remain . ' ' . __('header.currency')]),
            ]);
            $debtSale = DebtSaleModel::query();

            $debtSale = $debtSale->find($this->__id);
            $oldData = [
                'name : ' . $debtSale->name,
                'phone : ' . $debtSale->phone,
                'amount : ' . (number_format($debtSale->amount, 0, null, '.')),
                'paid : ' . (number_format($debtSale->paid, 0, null, '.')),
                'remain : ' . (number_format($debtSale->remain, 0, null, '.')),
            ];
            $debtSale->paid = $this->currentPaid + $this->price;
            $debtSale->remain = $this->remain - $this->price;
            $debtSale->status = $debtSale->remain == 0 ? 1 : 0;
            $debtSale->delete_in = $debtSale->remain == 0 ? now()->addMonths(6) : null;
            $debtSale->sale()->update([
                'paid' => $debtSale->remain == 0 ? 1 : 0,
            ]);
            $debtSale->save();
            $newData = [
                'name : ' . $debtSale->name,
                'phone : ' . $debtSale->phone,
                'amount : ' . (number_format($debtSale->amount, 0, null, '.')),
                'paid : ' . (number_format($debtSale->paid, 0, null, '.')),
                'remain : ' . (number_format($debtSale->remain, 0, null, '.')),
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Debt sale", 'Update', $oldData, $newData);
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.updated')]);
        }
        $this->done();
    }
    public function destroy(DebtSaleModel $debtSale)
    {
        if (!Gate::allows('Delete DebtSale')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
        } else {
            $data = [
                'name : ' . $debtSale->name,
                'phone : ' . $debtSale->phone,
                'amount : ' . (number_format($debtSale->amount, 0, null, '.')),
                'paid : ' . (number_format($debtSale->paid, 0, null, '.')),
                'remain : ' . (number_format($debtSale->remain, 0, null, '.')),
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Debt sale", 'Delete', $data,  'nothing to show');
            $debtSale->delete();
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.deleted')]);
        }   
        $this->done();
    }
}
