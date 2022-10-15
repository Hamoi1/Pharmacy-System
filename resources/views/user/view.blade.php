<x-modal.view target="view" title="{{ __('header.title_view' , ['name'=>__('header.User')]) }}" modalWidth="modal" wire="wire:click=done">
    <div wire:loading wire:target="show">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    @if($user)
    <div class="row g-4 mt-4 not-reverse">
        <div class="col-12 text-center">
            <img src="{{ $user->image($user->user_details->image) }}" width="250" height="250" class=" img-fulid rounded-circle object-cover">
        </div>
        <div class=" col-12 {{ app()->getLocale() == 'ckb' ? 'reverse' : '' }}">
            <p>
                {{ __('header.UserDetails') }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.name') }} :</span>
                {{ $user->name }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.username') }} :</span>
                {{ $user->username }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.email') }} :</span>
                {{ $user->email }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.phone') }} :</span>
                {{ $user->phone }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.address') }} :</span>
                {{ $user->address }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.role') }} :</span>
                {{ $user->role() }}
            </p>
            <p>
                <span class="fw-bold">{{ __('header.status') }} :</span>
                <span class="badge bg-{{ $user->status == 1 ? 'success' : 'danger' }}">{{ $user->status == 1 ? 'Active' : 'Not Active' }}</span>
            </p>
            <p>
                <span class="fw-bold">{{ __('header.created_at') }} :</span>
                {{ $user->created_at->diffForHumans() }}

            <p>
                <span class="fw-bold">{{ __('header.barwar') }} :</span>
                {{ $user->created_at->format('Y-m-d') }}
            </p>
        </div>
    </div>


    @endif
</x-modal.view>