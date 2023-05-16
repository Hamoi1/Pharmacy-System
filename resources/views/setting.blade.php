@push('title') Setting @endpush
<div>
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} container-lg pt-4">
        <div class="row g-4 shadow-sm pb-5 ">

            <div class="col-lg-4 col-12">
                <div class="col-12 my-2 fs-3 px-2 py-2">
                    <span class="fw-bold">{{ __('header.settings.name') }} :</span>
                    <span class="text-break">
                        {{ $setting->name ?? '' }}
                    </span>
                </div>
                <div class="col-12 my-2 fs-3 px-2 py-2">
                    <span class="fw-bold">{{ __('header.settings.phone') }} :</span>
                    <span class="text-break">
                        {{ $setting->phone ?? '' }}
                    </span>
                </div>
                <div class="col-12 my-2 fs-3 px-2 py-2">
                    <span class="fw-bold">{{ __('header.settings.email') }} :</span>
                    <span class="text-break">
                        {{ $setting->email ?? '' }}
                    </span>
                </div>
                <div class="col-12 my-2 fs-3 px-2 py-2">
                    <span class="fw-bold">{{ __('header.settings.address') }} :</span>
                    <span class="text-break">
                        {{ $setting->address ?? '' }}
                    </span>
                </div>
                <div class="col-12 my-2 fs-3 px-2 py-2 d-flex align-items-center gap-2">
                    <span class="fw-bold">{{ __('header.settings.logo') }} :</span>
                    <span class="">
                        <img src="{{  $setting->logo != null ? asset('storage/logo/'.$setting->logo) : asset('assets/images/capsules.png') }}" class="rounded-circle avatar avatar-md object-cover" alt="">
                    </span>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                <form wire:submit.prevent="submit">
                    <div class="row gy-2">
                        <div class="col-12 mb-2">
                            <label class="form-label">
                                {{ __('header.exchangeRate') }}
                            </label>
                            <input type="text" class="form-control exchangeRate" wire:model.defer="exchangeRate" placeholder="{{ __('header.enter_' , ['name' => __('header.exchangeRate')]) }}">
                            @error('exchangeRate') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-6 col-12">
                            <label class="form-label">{{ __('header.settings.name') }}</label>
                            <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('header.enter_' , ['name' => __('header.settings.name')]) }}">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-6 col-12">
                            <label class="form-label">{{ __('header.settings.phone') }}</label>
                            <input type="text" class="form-control" wire:model.defer="phone" placeholder="{{ __('header.enter_' , ['name' => __('header.settings.phone')]) }}">
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-6 col-12">
                            <label class="form-label">{{ __('header.settings.email') }}</label>
                            <input type="text" class="form-control" wire:model.defer="email" placeholder="{{ __('header.enter_' , ['name' => __('header.settings.email')]) }}">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-6 col-12">
                            <label class="form-label">{{ __('header.settings.address') }}</label>
                            <input type="text" class="form-control" wire:model.defer="address" placeholder="{{ __('header.enter_' , ['name' => __('header.settings.address')]) }}">
                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-6 col-12">
                            <label class="form-label">{{ __('header.settings.logo') }}</label>
                            <input type="file" class="form-control" wire:model="logo" accept="image/*">
                            @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-lg-6 col-12 mt-4">
                            @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" class="rounded-circle avatar object-cover" alt="">
                            @endif
                        </div>
                        <div class="col-lg-6 col-12 mt-3">
                            <button type="submit" class="btn btn-blue">{{ __('header.update') }}</button>
                            <div wire:loading wire:target="submit">
                                {{ __('header.waiting') }}
                                <span class="animated-dots fs-3 mx-2">
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div class="col-12 mt-0 mb-4">
                <h4>
                    {{ __('header.changetheme') }}
                </h4>
                <form>
                    <button class="changeThemeBtn shadow-sm " wire:click.prevent="ChangeTheme({{ auth()->user()->theme == 0 ? 0 : 1  }})">
                        <span class="fs-3 {{ auth()->user()->theme == 0 ? 'mb-2' : 'mt-2'  }} text-light"> {{ auth()->user()->theme == 0 ? "🌙" : "☀️"  }}</span>
                    </button>
                    <span class=" fs-3 mx-2  {{ auth()->user()->theme == 0 ? 'txet-dark' : 'text-light'  }}">
                        {{ auth()->user()->theme == 0 ? "dark" : "light"  }}
                    </span>
                </form>
            </div>

            <div class="col-12">
                <div class="d-flex align-items-center">
                    <livewire:system.clean-up />
                    <livewire:system.backup />
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script defer>
    $(document).ready(function() {
        ChangePrice = (price) => {
            var change = price.toLocaleString(
                undefined, {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2,
                }
            );
            return change;
        }
        var change = ChangePrice(Number($('.exchangeRate').val()));
        $('.exchangeRate').val(change);
    });
</script>
@endpush