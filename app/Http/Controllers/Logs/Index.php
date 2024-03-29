<?php

namespace App\Http\Controllers\Logs;

use App\Models\Logs;
use App\Models\User;
use Livewire\Component;
use livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $user_id, $userSelect, $action, $searchByDate,
        $action_method = [
            'Sale',
            'Create',
            'Update',
            'Delete',
            'Restore',
            'Login',
            'Logout',
            'Profile',
            'Export'
        ];
    protected $queryString = [
            'user_id' => ['except' => '', 'as' => 'user'],
            'action' => ['except' => '', 'as' => 'action'],
            'searchByDate' => ['except' => '', 'as' => 'date'],
        ], $paginationTheme = 'bootstrap',
        $listeners = ['user-actions' => '$refresh'];

    public function updated($propertyName)
    {
        $this->resetPage();
    }
    public function render()
    {
        if (!Gate::allows('View Log')) {
            abort(404);
        }
        $user_select = $this->user_id;
        $users = DB::table('users')->whereNot('id', auth()->user()->id)->select('id', 'name');
        $user_logs = Logs::query();
        if ($this->user_id != null) {
            $user_logs = Logs::where('user_id', $this->user_id)->latest();
            $this->userSelect = User::whereNot('id', auth()->user()->id)->select('id', 'name')->where('id', $this->user_id)->first();
        } else {
            $user_logs = [];
            $this->action = null;
            $this->searchByDate = null;
        }
        if ($user_logs != null) {
            if ($this->action != null) {
                $user_logs = $user_logs->where('action', $this->action);
            }
            if ($this->searchByDate != null) {
                $user_logs = $user_logs->whereDate('created_at', $this->searchByDate);
            }
            $user_logs =   $user_logs->paginate(10);
        }
        return view('logs.index', ['user_logs' => $user_logs, 'users' => $users->get(), 'user_select' => $user_select]);
    }
    public function updatedUser_id()
    {
        $this->resetPage();
        $this->reset(['action', 'searchByDate']);
    }
    public function delete($index)
    {
        if (!Gate::allows('Delete Logs')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
        } else {
            if ($this->user_id) {
                auth()->user()->DeleteDataInLogs($this->user_id, $index);
                $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.data_clear')]);
            } else {
                $this->dispatchBrowserEvent('message', ['type' => 'warning', 'message' => __('header.user_data')]);
            }
        }
    }
    public function clear()
    {
        if (!Gate::allows('Clear Log')) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'message' => __('header.NotAllowToDo')]);
        } else {
            if ($this->user_id) {
                auth()->user()->DeleteLogs($this->user_id);
                $this->dispatchBrowserEvent('message', ['type' => 'success', 'message' => __('header.data_clear')]);
            } else {
                $this->dispatchBrowserEvent('message', ['type' => 'warning', 'message' => __('header.user_data')]);
            }
        }
    }
    public function pdf()
    {
        $logs = Logs::where('user_id', $this->user_id)->latest()->get();
        $pdf = Pdf::loadView('pdf.logs', ['logs' => $logs, 'user' => $this->userSelect]);
        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            $this->userSelect->name . '-logs.pdf',
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}
