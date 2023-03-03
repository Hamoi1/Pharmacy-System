<x-modal.add target="add-update" static="" modalWidth="modal-lg">
    <div wire:loading wire:target="add">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="add">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ __('header.AddNewImage') }}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>
        <form wire:submit.prevent="submit">
            <div class="row g-3">
                <div class="col-12">
                    <label>{{ __('header.image') }}</label>
                    <input type="file" class="form-control" multiple wire:model.defer="images" accept="image/*" />
                    <div class="mt-3" wire:loading wire:target="images">
                        <span>{{ __('header.uploading') }}</span>
                        <span class="spinner-border mx-2 text-primary fs-5" role="status"></span>
                    </div>
                    @error('images.*')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                @if($images)
                <div class="col-12">
                    <div class="row text-center g-lg-2 gy-3">
                        @foreach ($images as $image)
                        @if (in_array($image->extension(), ['jpg', 'png', 'jpeg','svg']))
                        <div class="col-lg-3 col-md-6 col-12 position-relative">
                            <img src="{{ $image->temporaryUrl() }}" width="300px" height="300px" class="img-fluid rounded object-cover">
                            <span class="position-absolute top-0 left-0 delete-img" wire:click.prevent="removeImage({{ $loop->index }})">
                                <i class="fa fa-times"></i>
                            </span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary  px-3">
                        {{ __('header.add+')  }}
                    </button>
                    <div wire:loading wire:target="submit">
                        {{ __('header.waiting') }}
                        <span class="animated-dots mx-1 fs-3">
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-modal.add>