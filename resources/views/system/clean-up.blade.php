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
    <button class="btn btn-dark rounded-3 " wire:click.prevent="CleanUp">
        <div class="d-flex align-itmes-center justify-content-center">
            <span class="mt-1">
                {{ __('header.cleanSystem') }}
            </span>
             <img src="{{ asset('assets/images/clean.png') }}" width="25" class="mx-1" alt="">
        </div>
    </button>
</div>