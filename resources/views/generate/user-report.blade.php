<div>
    <x-modal.generate-pdf target="generate" title="{{ __('header.GeneratePDF') }}" modalWidth="modal" wire="wire:click=done">
        <div wire:loading.remove wire:target="Update,add">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="modal-title me-auto" id="staticBackdropLabel">
                    {{ __('header.GeneratePDF') }}
                </h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
            </div>
            <form wire:submit.prevent="generate">
                <div class="row g-3">
                    <div class="col-lg-6 col-12">
                        <label for="">{{ __('header.report.type') }}</label>
                        <select class="form-select" wire:model="type">
                            <option selected>{{ __('header.report.Reports') }}</option>
                            @foreach ($ReportsType as $report )
                            <option value="{{ $loop->index }}">{{ $report }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-3">
                            {{__('header.GeneratePDF')}}
                        </button>
                        <div wire:loading wire:target="submit">
                            {{ __('header.waiting') }}
                            <span class="animated-dots fs-3">
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-modal.generate-pdf>
</div>