<?php

namespace App\Http\Controllers\Logs;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    public $user, $page, $date, $file, $action, $old, $new, $oldData, $newData, $searchByDate, $action_method_select,
        $action_method = [
            'Sale',
            'Create',
            'Update',
            'Delete',
            'Restore',
            'Login',
            'Logout',
            'Profile'
        ];
    protected $queryString = [
        'user' => ['except' => ''],
        'action_method_select' => ['except' => '', 'as' => 'action'],
        'searchByDate' => ['except' => '', 'as' => 'date'],
    ];
    public function render()
    {
        $this->getData();
        $users = User::whereNot('id', auth()->user()->id)->get();
        $user_select = $this->user;
        return view('logs.index', ['users' => $users, 'user_select' => $user_select]);
    }
    public function getData()
    {
        $this->user == auth()->user()->id ? $this->user = '' : $this->user;
        $this->date = $this->page = $this->action = $this->old = $this->new = [];
        if ($this->user  && ($this->user != auth()->user()->id)) {
            $this->file = User::find($this->user)->GetFile($this->user);
        }
        
        if ($this->file == []) {
            $this->file = [];
        } else {
            foreach ($this->file as $item) {
                // get date time inside file
                $date = explode(' / ', $item);
                $date = $date[0];
                $this->date[] = $date;

                // get Page inside file
                $Page = explode(' / ', $item);
                $Page = $Page[1];
                $this->page[] = $Page;

                // get action inside file
                $action = explode(' / ', $item);
                $action = $action[2];
                $this->action[] = $action;

                // get old data
                $old = explode(' / ', $item);
                $old = $old[3];
                $this->old[] = $old;

                // get new data
                $new = explode(' / ', $item);
                $new = $new[4];
                $this->new[] = $new;
            }

            // all date merge 
            $this->file = array_merge(
                array_map(null, $this->date, $this->page, $this->action, $this->old, $this->new)
            );
        }
        $this->file = array_reverse($this->file);
        $this->action_method_select ?
            $this->file = $this->GetDataByMethod($this->file) :
            $this->file = $this->file;
        $this->searchByDate ?
            $this->file = $this->searchByDate($this->file) :
            $this->file = $this->file;

        return;
    }
    public function GetDataByMethod($file)
    {
        $data = [];
        foreach ($file as $item) {
            if ($item[2] == $this->action_method_select) {
                $data[] = $item;
            }
        }
        return $data;
    }

    public function searchByDate($file)
    {
        $data = [];
        foreach ($file as $item) {
            // format a date to date
            $item[0] = date('Y-m-d', strtotime($item[0]));
            if ($item[0] == $this->searchByDate) {
                $data[] = $item;
            }
        }
        return $data;
    }
    public function delete($action, $index)
    {
        if (!Gate::allows('Delete Logs')) {
            flash()->addSuccess(__('header.NotAllowToDo'));
        } else {
            $userID = $this->user ?? null;
            if ($userID) {
                auth()->user()->DeleteDataInFile($userID, $index, $action);
                $this->file = User::find($userID)->GetFile($userID);
                flash()->addSuccess('Data has been deleted successfully !');
            } else {
                flash()->addWarning('Can\'t delete a data because you don\'t select a user !');
            }
        }
    }
    public function clear()
    {
        if (!Gate::allows('Clear Log')) {
            flash()->addSuccess(__('header.NotAllowToDo'));
        } else {
            $userID = $this->user ?? null;
            if ($userID) {
                auth()->user()->ClearFile($userID);
                $this->file = User::find($userID)->GetFile($userID);
                flash()->addSuccess('File has been cleared successfully !');
            } else {
                flash()->addWarning('Can\'t clear a file because you don\'t select a user !');
            }
        }
    }
}
