<div>
    <div wire:loading wire:target="CleanUp">
        <div class="loading">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}" width="150px" alt="">
                </div>
                <h1 class="loading-title ">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>
    <button class="btn btn-dark rounded-3" wire:click.prevent="CleanUp">
        <span class="mx-2 d-none d-md-block">
            {{ __('header.cleanSystem') }}
        </span>
        <img src="{{ asset('assets/images/clean.png') }}" width="25" class="rounded-circle mx-1" alt="">
    </button>
</div>