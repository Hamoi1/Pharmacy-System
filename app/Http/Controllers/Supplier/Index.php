<?php

namespace App\Http\Controllers\Supplier;

use App\Events\SupplierPage;
use Livewire\Component;
use App\Models\Suppliers;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $name, $email, $phone, $address, $search, $updateSupplier, $supplier_id, $Trashed = false;
    protected $paginationTheme = 'bootstrap', $queryString = ['search', 'Trashed' => ['except' => false]],
        $listeners = ['RefreshSupplier' => '$refresh', 'supplier-page' => 'render'];
    public function mount()
    {
        if (!Gate::allows('View Supplier')) {
            abort(404);
        }
        Gate::allows('Supplier Trash') ? $this->Trashed  : $this->Trashed = false;
    }
    private function CheckTrashParameter()
    {
        if ($this->Trashed) {
            return Suppliers::onlyTrashed();
        } else {
            return Suppliers::query();
        }
    }
    public function render()
    {
        $suppliers = $this->CheckTrashParameter();
        $this->search != '' ? $suppliers->where('name', 'like', '%' . $this->search . '%')->orWhere('address', 'like', '%' . $this->search . '%') : '';
        $GetTrashDate = function ($date) {
            return $date->addMonth()->format('Y-m-d');
        };
        return view('supplier.index', [
            'suppliers' => $suppliers->latest()->paginate(10),
            'GetTrashDate' => $GetTrashDate
        ]);
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function Trash()
    {
        $this->Trashed = !$this->Trashed;
        $this->resetPage();
    }
    public function done()
    {
        event(new SupplierPage());
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function delete(Suppliers $supplier)
    {
        if (!Gate::allows('Delete Supplier')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $supplierName = [];
            foreach ($supplier->products as $product) {
                $product->update([
                    'supplier_id' => null,
                ]);
                $supplierName[] = '( ' . $product->name . ' )';
            }
            $supplier->delete();
            $data =  "Delete ( " . (implode(',', $supplierName)) . " )  from :" . now();
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Delete', $data, $data);
            flash()->addSuccess(__('header.deleted_for_30_days'));
        }

        $this->done();
    }
    public function DeleteAll()
    {
        $suppliers = $this->CheckTrashParameter()->get();
        if ($suppliers->count() == 0)
            return;
        $supplierName = [];
        foreach ($suppliers as $supplier) {
            $supplierName[] = '( ' . $product->name . ' )';
            $supplier->forceDelete();
        }
        $data =  "Delete ( " . (implode(',', $supplierName)) . " )  from :" . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Delete', $data, $data);
        flash()->addSuccess(__('header.deleted'));

        $this->done();
    }
    public function RestoreAll()
    {
        $suppliers = $this->CheckTrashParameter()->get();
        if ($suppliers->count() == 0)
            return;
        $supplierName = [];
        foreach ($suppliers as $supplier) {
            $supplierName[] = '( ' . $product->name . ' )';
            $supplier->restore();
        }
        $data =  "Restore ( " . (implode(',', $supplierName)) . " )  from :" . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Restore', $data,  'nothing to show');
        flash()->addSuccess(__('header.RestoreMessage'));

        $this->done();
    }
    public function restore($id)
    {
        $supplier = Suppliers::onlyTrashed()->findOrFail($id);
        $data =  "Restore ( " . $supplier->name . " )  from :" . now();
        $supplier->restore();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Supplier", 'Restore', $data,  'nothing to show');
        flash()->addSuccess(__('header.supplier') . ' ' . __('header.RestoreMessage'));

        $this->done();
    }
}
