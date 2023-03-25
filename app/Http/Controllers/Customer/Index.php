<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customers;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $customer, $CustomerId, $search, $debt = false, $totalDebt;

    public $salesPerPage = 6,
        $salesPage = 1,
        $lastPageSale;
    protected $paginationTheme = 'bootstrap',
        $queryString = [
            'search' => ['except' => ['id'], 'as' => 's'],
            'debt' => ['except' => false],
        ],
        $listeners = ['RefreshCustomer' => '$refresh'];
    public function mount()
    {
        if (!Gate::allows('View Customer')) {
            abort(404);
        }
        $this->debt = false;
    }
    public function render()
    {
        $customers = Customers::query();

        $this->search != null ? $customers->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        }) : null;
        $customers = $customers->orderByDesc('id')->paginate(10);
        return view('customer.index', ['customers' => $customers]);
    }
    public function show($id)
    {
        $this->customer = Customers::with([
            'sales' => function ($query) {
                $query->with(['debt_sale' => function ($query) {
                    $query->where('status', 0);
                }]);
            },
        ])->find($id);
        // get sales  where have a debt_sale
        $sales = $this->customer->sales()->whereHas('debt_sale')->get();
        $this->totalDebt = 0;
        foreach ($sales as $sale) {
            $this->totalDebt += $sale->debt_sale->remain;
        }
        $this->Sales($this->salesPage);
    }
    public function Sales($numerPage, $action = null)
    {
        $query = $this->customer->sales();

        if ($action === 'whereHas') {
            $query->whereHas('debt_sale');
        }
        $this->customer->sales = $query->User()->orderByDesc('id')->paginate(
            $this->salesPerPage,
            ['*'],
            'sales',
            $numerPage
        );
        $this->lastPageSale = $this->customer->sales->lastPage();
    }
    public function debt_sale($debt = false)
    {
        $this->salesPage = 1;
        if ($debt) {
            $this->Sales($this->salesPage, 'whereHas');
            $this->debt = true;
        } else {
            $this->Sales($this->salesPage);
            $this->debt = false;
        }
    }
    public function prevPageSales($debt)
    {
        if ($this->salesPage > 1) {
            $this->salesPage--;
            if ($debt) {
                $this->Sales($this->salesPage, 'whereHas');
            } else {
                $this->Sales($this->salesPage);
            }
        }
    }
    public function nextPageSales($debt)
    {
        if ($this->salesPage < $this->lastPageSale) {
            $this->salesPage++;
            if ($debt) {
                $this->Sales($this->salesPage, 'whereHas');
            } else {
                $this->Sales($this->salesPage);
            }
        }
    }
    public function done()
    {
        $this->reset([
            'debt',
            'totalDebt'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }


    public function delete($id)
    {
        if (!Gate::allows('Delete Customer')) {
            abort(404);
        }
        Customers::find($id)->delete();
        flash()->addSuccess(__('header.Customer') . ' ' . __('header.deleted'));
        $this->done();
    }
}
