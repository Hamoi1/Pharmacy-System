<?php

namespace App\Http\Controllers\Sales;

use App\Models\User;
use App\Models\Sales;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $SaleID, $saleView, $UserID, $date, $invoice;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['UserID' => ['as' => 'user', 'except' => ''], 'date' => ['as' => 'date', 'except' => ''], 'invoice' => ['except' => '']];
    public function mount()
    {
        if (!Gate::allows('admin')) {
            abort(404);
        }
    }
    public function updatedInvoice()
    {
        $this->resetPage();
    }
    public function Sales()
    {
        $sale =  Sales::query();
        if ($this->date == 'today') {
            $sale->whereDate('created_at', today());
        } elseif ($this->date == 'yesterday') {
            $sale->whereDate('created_at', today()->subDays(1));
        } elseif ($this->date == 'this_week') {
            $sale->whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()]);
        } elseif ($this->date == 'this_month') {
            $sale->whereMonth('created_at', today()->month);
        }
        if ($this->invoice) {
            $invoice = Str::start($this->invoice, 'inv-');
            $sale->where('invoice', 'like', '%' . $invoice . '%');
        }
        return $sale;
    }
    public function render()
    {
        $sales = $this->Sales();
        if ($this->UserID) {
            $sales = $sales->where('user_id', $this->UserID);
        }
        $sales =  $sales->Where('status', '=', 1)->SaleDebtName()->User()->latest()->paginate(10);
        $users = User::all();
        return view('sales.index', [
            'sales' => $sales,
            'users' => $users,
        ]);
    }
    public function updatedDate()
    {
        $this->resetPage();
    }
    public function updatedUserID()
    {
        $this->resetPage();
    }
    public function done()
    {
        $this->reset(['SaleID', 'saleView',]);
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function destroy(Sales $sale)
    {
        $sale->delete();
        flash()->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function View($id)
    {
        $sale = Sales::SaleData()->findOrFail($id);
        $this->saleView = $sale;
    }
}
