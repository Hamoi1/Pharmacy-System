<?php

namespace App\Http\Controllers\Barcode;

use App\Models\Barcode;
use Livewire\Component;
use App\Models\Products;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public  $barcode, $quantity, $barcode_name, $barcode_id, $Barcode;
    public function mount()
    {
        if (!Gate::allows('View Barcode')) abort(404);
    }
    public function render()
    {
        $barcodes = Barcode::paginate(10);
        return view('barcode.index', ['barcodes' => $barcodes]);
    }
    public function done()
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->reset(['barcode', 'quantity', 'barcode_name', 'barcode_id', 'Barcode']);
        $this->resetErrorBag();
    }
    private function  generate()
    {
        return rand(100000000, 999999999) . rand(100000000, 999999999) . rand(100000000, 999999999);
    }

    public function GenerateBarcode()
    {
        $barcode = $this->generate();
        $checkBarcode = Barcode::where('barcode',  $barcode)->first();
        if ($checkBarcode) {
            $barcode = $this->generate();
        } else {
            $checkBarcodeInProduct = Products::where('barcode', $barcode)->first();
            if ($checkBarcodeInProduct) {
                $barcode = $this->generate();
            } else {
                $barcode = $barcode;
            }
        }
        $this->barcode =  Str::substr($barcode, 0, 12);
        $this->resetValidation();
    }

    public function rules()
    {
        return [
            'barcode_name' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/u',
            'barcode' => 'required|numeric|digits:12|unique:barcodes,barcode|unique:products,barcode',
            'quantity' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'barcode_name.string' => __('validation.string', ['attribute' => __('header.name')]),
            'barcode_name.regex' => __('validation.regex', ['attribute' => __('header.name')]),
            'barcode.required' => __('validation.required', ['attribute' => __('header.barcode')]),
            'barcode.numeric' => __('validation.numeric', ['attribute' => __('header.barcode')]),
            'barcode.digits' => __('validation.digits', ['attribute' => __('header.barcode'), 'digits' => 12]),
            'barcode.unique' => __('validation.unique', ['attribute' => __('header.barcode')]),
            'quantity.required' => __('validation.required', ['attribute' => __('header.quantity')]),
            'quantity.numeric' => __('validation.numeric', ['attribute' => __('header.quantity')]),
            'quantity.min' => __('validation.min', ['attribute' => __('header.quantity'), 'min' => 1]),
        ];
    }

    public function updatedBarcode()
    {
        $this->validate($this->rules(), $this->messages());
        $this->barcode = $this->barcode;
    }
    public function submit()
    {
        if (!Gate::allows('Insert Barcode')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $this->validate($this->rules(), $this->messages());
            $barcode = Barcode::create([
                'name' => $this->barcode_name,
                'barcode' => $this->barcode,
                'quantity' => $this->quantity,
            ]);
            $data = [
                'Name : ' . ($this->barcode_name ?? 'No name'),
                'Barcode : ' . $this->barcode,
                'Quantity : ' . $this->quantity,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Barcode", 'Create', 'nothing to show', $data);
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.barcodes.SuccessfullyGenerated')]);
        }
        $this->done();
    }
    public function destroy(Barcode $barcode)
    {
        if (!Gate::allows('Delete Barcode')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
        } else {
            $data = [
                'Name : ' . ($barcode->name ?? 'No name'),
                'Barcode : ' . $barcode->barcode,
                'Quantity : ' . $barcode->quantity,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Barcode", 'Delete', $data, $data);
            $barcode->delete();
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.barcodes.index') . ' ' . __('header.deleted')]);
        }
        $this->done();
    }
    public function show(Barcode $barcode)
    {
        $this->Barcode = $barcode;
    }
    public function download(Barcode $barcode)
    {
        $pdf = Pdf::loadView('pdf.barcode', ['barcode' => $barcode]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'barcode.pdf');
    }
}
