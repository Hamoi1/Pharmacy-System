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
    protected $queryString = [
        'UserID' => ['as' => 'user', 'except' => ''],
        'date' => ['except' => ''],
        'invoice' => ['except' => '']
    ];
    public function mount()
    {
        if (!Gate::allows('View Sales')) {
            abort(404);
        }
    }
    public function updated($propertyName)
    {
        $this->resetPage();
    }
    public function Sales()
    {
        $sale =  Sales::query();
        if ($this->date) {
            $sale->whereDate('created_at', $this->date);
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
        $sales =  $sales->Where('status', '=', 1)->User()->latest()->paginate(10);
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
        $this->dispatchBrowserEvent('closeModal');
        $this->reset(['SaleID', 'saleView',]);
        $this->resetValidation();
    }
    public function View($id)
    {
        $this->saleView = Sales::SaleData()->findOrFail($id);
    }
    // public function convertToPdf($id)
    // {
    //     $sale  = Sales::with(['sale_details' => function ($query) {
    //         $query->with(['products' => function ($query) {
    //             $query->select(['id', 'name', 'sale_price']);
    //         }]);
    //     }])->findorFail($id);

    //     $pdf = Pdf::loadView('print.sale', ['sale' => $sale]);
    //     // dd($pdf);
    //     return response()->streamDownload(function () use ($pdf) {
    //         echo $pdf->output();
    //     }, 'invoice.pdf');
    // }
}
