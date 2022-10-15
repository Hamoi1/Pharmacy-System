<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $SaleID, $start, $end, $price, $total, $total_paid, $total_debt, $sale_debt_id, $saleView;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['start', 'end'];
    public function mount()
    {
        if (!Gate::allows('admin')) {
            abort(404);
        }
    }
    public function render()
    {
        $sales = Sales::query();
        if ($this->start && $this->end) {
            $sales = $sales->whereBetween('created_at', [$this->start, $this->end]);
        }
        $sales =  $sales->Where('status', '=', 1)->SaleDebtName()->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }
    public function done()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function destroy(Sales $sale)
    {
        $sale->delete();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function View($id)
    {
        $sale = Sales::SaleData()->findOrFail($id);
        $this->saleView = $sale;
        // dd($this->saleView);
    }
}
