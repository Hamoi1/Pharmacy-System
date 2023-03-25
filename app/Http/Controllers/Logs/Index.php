<?php

namespace App\Http\Controllers\Logs;

use App\Models\Logs;
use App\Models\User;
use Livewire\Component;
use App\Events\UserStatus;
use livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $user_id, $userSelect, $date, $action, $searchByDate,
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
    ], $paginationTheme = 'bootstrap';

    public function updated($propertyName)
    {
        $this->resetPage();
    }
    public function render()
    {
        if (!Gate::allows('View Log')) {
            $this->resetExcept();
            abort(404);
        }
        $user_select = $this->user_id;
        $users = User::whereNot('id', auth()->user()->id)->select('id', 'name');
        $user_logs = Logs::query();
        if ($this->user_id != null) {
            $user_logs = Logs::where('user_id', $this->user_id)->latest();
            $this->userSelect = User::whereNot('id', auth()->user()->id)->select('id', 'name')->where('id', $this->user_id)->first();
        } else {
            $user_logs = [];
        }

        if ($this->action != null) {
            $user_logs = $user_logs->where('action', $this->action);
        }
        if ($this->date != null) {
            $user_logs = $user_logs->whereDate('created_at', $this->date->format('Y-m-d'));
        }
        if ($this->user_id != null) {
            $user_logs =   $user_logs->paginate(10);
        } else {
            $user_logs = [];
        }
        return view('logs.index', ['user_logs' => $user_logs, 'users' => $users->get(), 'user_select' => $user_select]);
    }
    public function delete($index)
    {
        if (!Gate::allows('Delete Logs')) {
            flash()->addSuccess(__('header.NotAllowToDo'));
        } else {
            if ($this->user_id) {
                auth()->user()->DeleteDataInLogs($this->user_id, $index);
                flash()->addSuccess(__('header.data').' '.__('header.deleted'));
            } else {
                flash()->addWarning(__('header.user_data'));
            }
        }
    }
    public function clear()
    {
        if (!Gate::allows('Clear Log')) {
            flash()->addSuccess(__('header.NotAllowToDo'));
        } else {
            if ($this->user_id) {
                auth()->user()->DeleteLogs($this->user_id);
                flash()->addSuccess(__('header.data_clear'));
            } else {
                flash()->addWarning(__('header.user_data'));
            }
        }
    }
    public function pdf()
    {
        $logs = Logs::where('user_id', $this->user_id)->latest()->get();
        $pdf = Pdf::loadView('pdf.logs', ['logs' => $logs, 'user' => $this->userSelect]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'logs.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
