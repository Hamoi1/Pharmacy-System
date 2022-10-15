<?php

namespace App\Http\Controllers\Sales;

use App\Models\User;
use App\Models\Sales;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $SaleID, $price, $total, $total_paid, $total_debt, $sale_debt_id, $saleView, $UserID,
        $date;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['UserID' => ['as' => 'user', 'except' => ''], 'date' => ['as' => 'date', 'except' => '']];
    public function mount()
    {
        if (!Gate::allows('admin')) {
            abort(404);
        }
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
    public function done()
    {
        $this->resetExcept('UserID', 'date');
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
