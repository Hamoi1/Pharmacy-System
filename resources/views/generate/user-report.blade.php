<div>
    <x-modal.generate-pdf target="generate" title="{{ __('header.GeneratePDF') }}" modalWidth="modal" wire="wire:click=done">
        <div>
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="modal-title me-auto" id="staticBackdropLabel">
                    {{ __('header.GeneratePDF') }}
                </h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
            </div>
            <div wire:loading wire:target="type">
                <div class="d-flex justify-content-center">
                    <h3>
                        {{ __('header.waiting') }}
                        <span class="animated-dots "></span>
                    </h3>
                </div>
            </div>
            <form wire:submit.prevent="generate" wire:loading.remove wire:target="type">
                <div class="">
                    <p class="fw-bolder">
                        {{ __('header.report.description'  , ['name'=> $User_Name]) }}
                    </p>

                </div>
                <div class="row g-3 ">
                    <div class="col-12">
                        <label class="form-label">{{ __('header.report.type') }}</label>
                        <select class="form-select" wire:model="type">
                            <option selected>{{ __('header.report.Reports') }}</option>
                            @foreach ($ReportsType as $report )
                            <option value="{{ $loop->index }}">{{ $report }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        @error('GlobalError') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary  btn-primary   px-3">
                            {{__('header.GeneratePDF')}}
                        </button>
                        <div wire:loading wire:target="generate,type">
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