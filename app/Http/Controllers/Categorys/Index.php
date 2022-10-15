<?php

namespace App\Http\Controllers\Categorys;

use Livewire\Component;
use App\Models\Categorys;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $name, $search, $category_id, $Trashed = false;
    public $updateCategory = false;
    protected  $paginationTheme = 'bootstrap', $queryString = ['search' => ['as' => 's', 'except' => ''], 'Trashed' => ['except' => false]];
    private function CheckTrashParameter()
    {
        if ($this->Trashed) {
            return Categorys::onlyTrashed();
        } else {
            return Categorys::query();
        }
    }
    public function Trash()
    {
        $this->Trashed = !$this->Trashed;
        $this->resetPage();
    }
    public function render()
    {
        $categorys = $this->CheckTrashParameter();
        $this->search != null || $this->search != '' ? $categorys->where('name', 'like', '%' . $this->search . '%') : '';
        if (!Gate::allows('admin')) {
            abort(404);
        }
        $GetTrashDate = function ($date) {
            return $date->addMonth()->format('Y-m-d');
        };
        // dd($categorys->withTrashed()->get());
        return view('categorys.index', [
            'categorys' => $categorys->latest()->products_count()->paginate(10),
            'GetTrashDate' => $GetTrashDate,
        ]);
    }
    public function done()
    {
        $this->reset($this->ResetData());
        $this->resetValidation();
        $this->dispatchBrowserEvent('closeModal');
    }
    public function ResetData()
    {
        return [
            'name',
            'updateCategory',
            'category_id',
        ];
    }
    public function add()
    {
        $this->updateCategory = false;
    }
    public function GetRuls()
    {
        return [
            'name' => 'required|regex:/^[a-zA-Z0-9 _-]+$/|min:3|max:255|unique:categorys,name,' . $this->category_id ?? '',
        ];
    }
    public function GetMessages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('header.name')]),
            'name.min' => __('validation.min.string', ['attribute' => __('header.name'), 'min' => 3]),
            'name.max' => __('validation.max.string', ['attribute' => __('header.name'), 'max' => 255]),
            'name.unique' => __('validation.unique', ['attribute' => __('header.name')]),
            'name.regex' => __('validation.regex', ['attribute' => __('header.name')]),
            'name.exists' => __('validation.exists', ['attribute' => __('header.name')]),
        ];
    }
    public function submit()
    {
        $this->validate($this->GetRuls(), $this->GetMessages());
        $this->updateCategory ?
            Categorys::findorFail($this->category_id)->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]) :
            Categorys::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]);
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess($this->updateCategory ?  __('header.updated') : __('header.add'));
        $this->done();
    }
    public function destroy(Categorys $category)
    {
        // delete category soft delete
        $category->delete();
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.deleted_for_30_days'));
        $this->done();
    }
    public function update(Categorys $category)
    {
        $this->updateCategory = true;
        $this->name = $category->name;
        $this->category_id = $category->id;
    }
    public function restore($id, $status = true)
    {
        if ($id == null)
            return;
        Categorys::onlyTrashed()->findorFail($id)->restore();
        if ($status) {
            notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.RestoreMessage'));
            $this->done();
        }
    }
    public function DeleteAll()
    {
        $categorys = $this->CheckTrashParameter()->get();
        if ($categorys->count() == 0)
            return;
        foreach ($categorys as $category) {
            $category->forceDelete();
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function RestoreAll()
    {
        $categorys = $this->CheckTrashParameter()->get();
        if ($categorys->count() == 0)
            return;
        foreach ($categorys as $category) {
            $this->restore($category->id, false);
        }
        notyf()->position('y', 'top')->position('x', 'center')->duration(2500)->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
}
