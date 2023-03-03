<?php

namespace App\Http\Controllers\Generate;

use App\Models\Products;
use App\Models\Sales;
use App\Models\User;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rules\RequiredIf;

class UserReport extends Component
{
    protected $listeners = ['generateReport' => 'generateReport'];
    public $type, $UserId, $User_Name;
    public function reports()
    {
        return [
            __('header.Sales'),
            __('header.report.Add Product'),
        ];
    }
    public function render()
    {
        $ReportsType = $this->reports();
        return view('generate.user-report', compact('ReportsType'));
    }
    public function generateReport($id)
    {
        $this->UserId = $id;
        $this->User_Name = User::FindOrFail($id)->name;
    }
    public function done()
    {
        $this->reset(['type', 'UserId']);
        $this->dispatchBrowserEvent('closeModal');
    }
    public function generate()
    {
        $this->validate(
            [
                'type' => 'required',
            ],
            [
                'type.required' => __('validation.required', ['attribute' => __('header.report.index')]),
            ]
        );
        if ($this->type == 0) {
            $user = User::findOrFail($this->UserId);
            $sales = Sales::where('user_id', $this->UserId)->latest()->get();
            if ($sales->count() == 0) {
                return $this->addError('GlobalError', __('header.report.noData'));
            }
            $pdf = Pdf::loadView('pdf.sales', ['user' => $user, 'sales' => $sales]);
            $this->done();
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $this->User_Name . ' - sales' . '.pdf');
        } elseif ($this->type == 1) {
            $user = User::FindOrFail($this->UserId);
            $products = Products::where('user_id', $this->UserId)->latest()->get();
            if ($products->count() == 0) {
                return $this->addError('GlobalError', __('header.report.noData'));
            }
            $pdf = Pdf::loadView('pdf.add-products', ['user' => $user, 'products' => $products]);
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $this->User_Name . ' - addProduct' . '.pdf');
        } else {
            $this->addError('type', __('validation.required', ['attribute' => __('header.report.index')]));
        }
    }
}
