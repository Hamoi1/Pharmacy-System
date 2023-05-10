<?php

namespace App\Http\Controllers\Categorys;

use Livewire\Component;
use App\Models\Categorys;
use Illuminate\Support\Str;
use App\Events\CategoryPage;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    public $name, $search, $category_id, $Trashed = false, $updateCategory = false;
    protected  $paginationTheme = 'bootstrap', $queryString = ['search' => ['as' => 's', 'except' => ''], 'Trashed' => ['except' => false]],
        $listeners = ['category-page' => '$refresh'];
    public function mount()
    {
        if (!Gate::allows('View Category')) {
            abort(404);
        }
        Gate::allows('Category Trash') ? $this->Trashed  : $this->Trashed = false;
    }
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
        $GetTrashDate = function ($date) {
            return $date->addMonth()->format('Y-m-d');
        };
        return view('categorys.index', [
            'categorys' => $categorys->latest()->products_count()->paginate(10),
            'GetTrashDate' => $GetTrashDate,
        ]);
    }
    public function done($action = true)
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->reset([
            'name',
            'category_id',
        ]);
        $this->resetValidation();
        if ($action)
            event(new CategoryPage());
    }
    public function add(){
       return !$this->updateCategory;
    }
    public function submit()
    {
        $this->validate(
            ['name' => 'required|regex:/^[a-zA-Z0-9 _-]+$/|min:3|max:255|unique:categorys,name,' . $this->category_id ?? '',],
            [
                'name.required' => __('validation.required', ['attribute' => __('header.name')]),
                'name.min' => __('validation.min.string', ['attribute' => __('header.name'), 'min' => 3]),
                'name.max' => __('validation.max.string', ['attribute' => __('header.name'), 'max' => 255]),
                'name.unique' => __('validation.unique', ['attribute' => __('header.name')]),
                'name.regex' => __('validation.regex', ['attribute' => __('header.name')]),
                'name.exists' => __('validation.exists', ['attribute' => __('header.name')]),
            ]
        );
        if ($this->updateCategory && Gate::allows('Update Category')) {
            $category =  Categorys::findorFail($this->category_id);
            $oldData = [
                'name : ' . $category->name,
            ];
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]);
            $newData = [
                'name : ' . $category->name,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Category", 'Update', $oldData, $newData);
        } elseif ($this->updateCategory == false && Gate::allows('Insert Category')) {
            $category =  Categorys::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]);
            $newData = [
                'name : ' . $category->name,
            ];
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Category", 'Create', 'nothing to show', $newData);
        }
        flash()->addSuccess(__('header.Category') . ' ' . $this->updateCategory ?  __('header.updated') : __('header.add'));
        $this->done();
    }
    public function update(Categorys $category)
    {
        if (!Gate::allows('Update Category')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $this->updateCategory = true;
            $this->name = $category->name;
            $this->category_id = $category->id;
        }
    }
    public function destroy(Categorys $category)
    {
        if (!Gate::allows('Delete Category')) {
            flash()->addError(__('header.NotAllowToDo'));
        } else {
            $data = 'Delete ( ' . $category->name . ' ) form :' . now();
            auth()->user()->InsertToLogsTable(auth()->user()->id, "Category", 'Delete', $data, $data);
            $category->delete();
            flash()->addSuccess(__('header.deleted_for_30_days'));
        }
        $this->done();
    }
    public function restore($id, $status = true)
    {
        if ($id == null)
            return;
        $category = Categorys::onlyTrashed()->findorFail($id)->restore();
        $category = Categorys::findorFail($id);
        $categoryName  = '( ' . $category->name . ' )';
        $data = 'Restore ' . $categoryName . ' form :' . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Category", 'Restore', $data, 'nothing to show');
        if ($status) {
            flash()->addSuccess(__('header.Category') . ' ' . __('header.RestoreMessage'));
            $this->done();
        }
    }
    public function DeleteAll()
    {
        $categorys = $this->CheckTrashParameter()->get();
        if ($categorys->count() == 0)
            return;
        $categoryName = [];
        foreach ($categorys as $category) {
            $categoryName[] = '( ' . $category->name . ' )';
            $category->forceDelete();
        }
        $data = 'Delete ' . implode(',', $categoryName) . ' form :' . now();
        auth()->user()->InsertToLogsTable(auth()->user()->id, "Category", 'Delete', $data, $data);
        flash()->addSuccess(__('header.Category') . ' ' . __('header.deleted'));
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
        flash()->addSuccess(__('header.RestoreMessage'));
        $this->done();
    }
}
