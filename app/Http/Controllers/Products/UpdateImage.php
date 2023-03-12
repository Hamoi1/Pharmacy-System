<?php

namespace App\Http\Controllers\Products;

use Livewire\Component;
use App\Models\Products;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UpdateImage extends Component
{
    use WithFileUploads;
    public $product, $images = [];
    public function mount($id)
    {
        if (!Gate::allows('Update Product')) {
            abort(404);
        }
        $this->product =  Products::suppliers()->categorys()->findorFail($id);
    }
    public function render()
    {
        return view('products.Update-image.update-image', ['product' => $this->product]);
    }
    public function updatingImages()
    {
        $this->validate(
            [
                'images.*' => 'required|image|max:20000|mimes:jpg,jpeg,png,svg',
            ],
            [
                'images.*.required' => __('validation.required', ['attribute' => __('header.image')]),
                'images.*.image' => __('validation.image', ['attribute' => __('header.image')]),
                'images.*.max' => __('validation.max', ['attribute' => __('header.image'), 'max' => 20000]),
                'images.*.mimes' => __('validation.mimes', ['attribute' => __('header.image'), 'values' => 'jpg,jpeg,png,svg']),
            ]
        );
    }
    public function removeImage($index)
    {
        unset($this->images[$index]);
        if (count($this->images) == 0) {
            $this->images = [];
        }
    }
    public function submit()
    {
        if ($this->images != [] || $this->images != null) {
            $this->product->image = $this->product->image != null ? json_decode($this->product->image) : [];
            $images = [];
            foreach ($this->images as $image) {
                if (in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'svg'])) {
                    $imageName = time() . '-' . uniqid() . '-' . uniqid() . '.' . $image->GetClientOriginalExtension();
                    $image->storeAs('public/products', $imageName);
                    $images[] = $imageName;
                }
            }
            $images = array_merge($this->product->image, $images);
            $this->product->update([
                'image' => $images,
            ]);
            flash()->addSuccess(__('header.add'));
            $this->done();
            return;
        }
        flash()->addWarning(__('header.NotAdd'));
        $this->done();
    }
    public function done()
    {
        $this->resetValidation();
        $this->reset(['images']);
        $this->dispatchBrowserEvent('closeModal');
        $this->mount($this->product->id);
    }
    public function remove($index)
    {
        $images = json_decode($this->product->image);
        if ($images === null) {
            return;
        }
        Storage::delete('public/products/' . $images[$index]);
        unset($images[$index]);
        $images = array_values($images);
        $images == [] ? $images = null : $images;
        $this->product->update([
            'image' => $images,
        ]);
        flash()->addSuccess(__('header.deleted'));
        $this->done();
    }
    public function deleteAll()
    {
        $images = json_decode($this->product->image);
        foreach ($images as $image) {
            Storage::delete('public/products/' . $image);
        }
        $this->product->update([
            'image' => null,
        ]);
        notyf()->position('y', 'top')->position('x', 'center')->duration(2000)->addSuccess(__('header.deleted'));
        $this->done();
    }
}
