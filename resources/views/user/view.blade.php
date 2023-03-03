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
            <img src="{{ $user->image($user->user_details->image) }}" width="250" height="250" class=" image-profile">
        </div>
        <div class=" col-12 {{ app()->getLocale() == 'ckb'   || app()->getLocale() == 'ar' ? 'reverse' : '' }}">
            <p class="fw-bolder fs-3">
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
            <div class="col-12 my-3">
                <div class="accordion p-0  mx-0 w-100" id="accordion-example">
                    <div class="accordion-item border-0 shadow-none p-0">
                        <h2 class="accordion-header" id="heading-1">
                            <button class="accordion-button p-2 fw-bolder " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">
                                {{ __('header.permission') }}
                            </button>
                        </h2>
                        <div id="collapse-1" class="accordion-collapse collapse mt-3" data-bs-parent="#accordion-example" style="">
                            <div class="accordion-body pt-0">
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse ($user->GetPermissionName($user->id) as $permission)
                                    <span class="bg-blue rounded-2 p-2">{{ $permission }}</span>
                                    @empty

                                    <p class="fs-3 fw-bold">{{ __('header.no_permission') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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