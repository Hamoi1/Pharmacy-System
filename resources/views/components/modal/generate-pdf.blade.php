<div wire:ignore.self class="modal fade" id="{{ $target }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $modalWidth }} ">
        <div class="modal-content rounded shadow-sm border-0">
            <div class="modal-body {{ auth()->user()->theme == 0 ? 'bg-white' : ''  }}">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
