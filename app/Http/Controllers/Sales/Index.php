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
    public $SaleID, $start, $end, $price, $total, $total_paid, $total_debt, $sale_debt_id, $saleView, $UserID;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['start', 'end', 'UserID' => ['as' => 'user', 'except' => '']];
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
        if ($this->UserID) {
            $sales = $sales->where('user_id', $this->UserID);
        }
        $sales =  $sales->Where('status', '=', 1)->SaleDebtName()->latest()->paginate(10);
        $users = User::all();
        return view('sales.index', [
            'sales' => $sales,
            'users' => $users,
        ]);
    }
    public function done()
    {
        $this->resetExcept('start', 'end', 'UserID');
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
