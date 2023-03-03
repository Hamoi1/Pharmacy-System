@push('title') Profile @endpush
<div class="mt-md-4 mt-2">
    <div wire:loading wire:target="image">
        <div class="loading profile">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}" width="250px" alt="">
                </div>
                <h1 class="loading-title fw-bolder">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' :'' }} container-lg mt-4">
        @include('profile.update.add-update')
        @include('profile.update.ChangePassword')
        <div class="d-flex justify-content-center  flex-wrap gap-3">
            <div class="col-lg-5 text-center">
                @if (auth()->user()->user_details->image)
                <img src="{{ auth()->user()->image(auth()->user()->user_details->image) }}" class="image-profile  rounded " alt="">
                @else
                <img src="{{ asset('assets/images/image_not_available.png') }}"  width="300" alt="">
                @endif
                <div>
                    <div class="d-flex align-items-center  justify-content-center m-3 flex-wrap">
                        <input type="file" hidden class="image-change" wire:model="image">
                        <button class="btn" id="imageBtn">
                            <i class=" fas fa-camera"></i>
                        </button>
                        <button class="btn text-danger" wire:click.prevent="deleteImage('{{ auth()->user()->user_details->image }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <a herf="" class="btn text-primary" data-bs-toggle="modal" data-bs-target="#update" wire:click.prevent="edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a herf="" class="btn text-primary" data-bs-toggle="modal" data-bs-target="#ChangePassword">
                            <i class="fas fa-lock"></i>
                        </a>
                        @error('image')
                        <div class="col-12 mt-2">
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 mt-md-0 mt-5 ">
                <p class="fs-3 fw-bold">
                    {{ __('header.UserDetails') }}
                </p>
                <ul class="list-group-custom">
                    <li class="list-group-custom-item">
                        <span class="fw-bold">{{ __('header.name') }} :</span>
                        {{ auth()->user()->name }}
                    </li>
                    <li class="list-group-custom-item">
                        <span class="fw-bold">{{ __('header.username') }} :</span>
                        {{ auth()->user()->username }}
                    </li>
                    <li class="list-group-custom-item">
                        <span class="fw-bold">{{ __('header.email') }} :</span>
                        {{ auth()->user()->email }}
                    </li>
                    <li class="list-group-custom-item">
                        <span class="fw-bold">{{ __('header.phone') }} :</span>
                        {{ auth()->user()->phone }}
                    </li>
                    <li class="list-group-custom-item">
                        <span class="fw-bold">{{ __('header.address') }} :</span>
                        {{ auth()->user()->user_details->address }}
                    </li>
                    <li class="list-group-custom-item">
                        <span class="fw-bold">{{ __('header.status') }} :</span>
                        <span class="badge bg-{{ auth()->user()->status == 1 ? 'success' : 'danger' }}">{{ auth()->user()->status == 1 ? 'Active' : 'Not Active' }}</span>
                    </li>
                    <li class="list-group-custom-item text-center text-break">
                        <span class="fw-bold">{{ __('header.acount_create') }} :</span>
                        {{ auth()->user()->created_at->diffForHumans() }}
                        /
                        <span class="fw-bold">{{ __('header.barwar') }} :</span>
                        {{ auth()->user()->created_at->format('Y-m-d') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
    $(document).ready(function() {
        $('#imageBtn').click(function(e) {
            e.preventDefault();
            $('.image-change').click();
        });
    });
</script>

@endpush