<?php

namespace App\Http\Controllers\Generate;

use App\Models\User;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class UserReport extends Component
{
    protected $listeners = ['generateReport' => 'generateReport'];
    public $type, $UserId;
    public function reports()
    {
        return [
            __('header.report.Sales'),
            __('header.report.Add Product'),
            __('header.report.Edit Product'),
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
    }
    public function done()
    {
        $this->dispatchBrowserEvent('closeModal');
    }
    public function generate()
    {
        $this->validate(
            ['type' => 'required'],
            [
                'type.required' => __('validation.required', ['attribute' => __('header.report.index')]),
            ]
        );
        if ($this->type == 0) {
            $user = User::with('sales')->FindOrFail($this->UserId);
            $pdf = Pdf::loadView('pdf.sales', ['user' => $user]);
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'sales.pdf');
        } else {
            $this->addError('type', __('validation.required', ['attribute' => __('header.report.index')]));
        }
        $this->done();
    }
}
