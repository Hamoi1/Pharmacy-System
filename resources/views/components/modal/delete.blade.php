<div wire:ignore.self class="modal fade" id="{{ $target }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $modalWidth }} ">
        <div class="modal-content rounded shadow-sm border-0">
            <div class="modal-body {{ auth()->user()->theme == 0 ? 'bg-white' : ''  }} ">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="modal-title me-auto" id="staticBackdropLabel">{{ $title }}</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" {{ $wire }}></button>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>