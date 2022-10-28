<?php

namespace App\Http\Controllers\Sales;

use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\DebtSale as DebtSaleModel;
use Livewire\WithPagination;

class DebtSale extends Component
{
    public $Edit = false, $search, $status,
        $name, $phone, $currentPaid, $amount, $remain, $price, $__id;
    use WithPagination;
    protected $paginationTheme = 'bootstrap',
        $queryString = [
            'search' => ['as' => 's', 'except' => ''],
            'status' => ['as' => 'st', 'except' => ''],
        ];
    public function mount()
    {
        $this->status = $this->status == 1 || $this->status == 0 ? $this->status : '';
    }
    public function render()
    {
        if (!Gate::allows('admin')) {
            abort(404);
        }
        $debtSales = DebtSaleModel::query();
        $this->search != null ? $debtSales->where('name', 'like', '%' . $this->search . '%') : null;
        $this->status != '' ? $debtSales->where('status', $this->status) : null;
        $debtSales = $debtSales->sale()->latest()->paginate(10);
        return view('sales.debt-sale', [
            'debtSales' =>  $debtSales,
        ]);
    }
    public function done()
    {
        $this->reset(['Edit', 'name', 'phone', 'currentPaid', 'amount', 'remain', 'price']);
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function edit(DebtSaleModel $debtSale)
    {
        $this->Edit = true;
        $this->__id = $debtSale->id;
        $this->name = $debtSale->name;
        $this->phone = $debtSale->phone;
        $this->amount = $debtSale->amount;
        $this->currentPaid = $debtSale->paid;
        $this->remain = $debtSale->remain;
    }
    public function submit()
    {
        $this->validate([
            'price' => 'required|numeric|min:1|max:' . $this->remain,
        ], [
            'price.required' => __('validation.required', ['attribute' => __('header.Returned Money')]),
            'price.numeric' => __('validation.numeric', ['attribute' => __('header.Returned Money')]),
            'price.min' => __('validation.min.numeric', ['attribute' => __('header.Returned Money'), 'min' => 1]),
            'price.max' => __('validation.max.numeric', ['attribute' => __('header.Returned Money'), 'max' => $this->remain . ' ' . __('header.currency')]),
        ]);
        $debtSale = DebtSaleModel::find($this->__id);
        $debtSale->paid = $this->currentPaid + $this->price;
        $debtSale->remain = $this->remain - $this->price;
        $debtSale->status = $debtSale->remain == 0 ? 1 : 0;
        $debtSale->updated_at = $debtSale->remain == 0 ? now()->addMonth() : now();
        $debtSale->sale()->update([
            'paid' => $debtSale->remain == 0 ? 1 : 0,
        ]);
        $debtSale->save();
        flash()->addSuccess('header.updated');
        $this->done();
    }
    public function destroy(DebtSaleModel $debtSale)
    {
        $debtSale->delete();
        flash()->addSuccess('header.deleted');
        $this->done();
    }
}
