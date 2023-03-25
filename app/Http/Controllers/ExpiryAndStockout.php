<?php

namespace App\Http\Controllers;

use DateTime;
use Livewire\Component;
use App\Models\Products;
use Livewire\WithPagination;

class ExpiryAndStockout extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        return view('expiry-and-stockout');
    }
}
