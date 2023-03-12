<x-modal.add target="add-update" modalWidth="modal-lg">
    <div wire:loading wire:target="Update,add">
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    <div wire:loading.remove wire:target="Update,add">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="modal-title me-auto" id="staticBackdropLabel">
                {{ $UpdateUser?__('header.update_' , ['name'=> __('header.User')])  : __('header.add_', ['name'=> __('header.User')])}}
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
        </div>
        <form wire:submit.prevent="submit">
            <div class="row g-3">
                @if (!$ShowPermission)
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.name') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('header.name') }}">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.username') }}</label>
                    <input type="text" class="form-control" wire:model.defer="username" placeholder="{{ __('header.username') }}">
                    @error('username')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.phone') }}</label>
                    <input type="tel" class="form-control" wire:model.defer="phone" placeholder="{{ __('header.phone') }}">
                    @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.email') }}</label>
                    <input type="email" class="form-control" wire:model.defer="email" placeholder="{{ __('header.email') }}">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="{{ $UpdateUser ? 'col-lg-6 col-12' : 'col-lg-6' }}">
                    @if($UpdateUser)
                    <label for="">{{ __('header.status') }}</label>
                    <select class="form-select" wire:model="statu">
                        <option value="1">Active</option>
                        <option value="0">Not Active</option>
                    </select>
                    @error('statu')<span class="text-danger">{{ $message }}</span>@enderror
                    @endif
                </div>
                <div class="{{ $UpdateUser ? 'col-lg-6 col-12' : 'col-12' }}">
                    <label for="">{{ __('header.address') }}</label>
                    <input type="text" class="form-control" wire:model.defer="address" placeholder="{{ __('header.address') }}">
                    @error('address')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-12">
                    <button type="button" class="btn btn-sm btn-dark rounded-2 pt-2 p-1 px-4 fs-4 text-center" wire:click="ShowPermission">
                        {{ __('header.permission') }}
                    </button>
                    @error('permission')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                @if(!$UpdateUser)
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.password') }}</label>
                    <input type="password" class="form-control" wire:model.defer="password" placeholder="{{ __('header.password') }}">
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-lg-6 col-12">
                    <label for="">{{ __('header.confirm_password') }} </label>
                    <input type="password" class="form-control" wire:model.defer="confirm_password" placeholder="{{ __('header.confirm_password') }}">
                    @error('confirm_password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                @endif
                @else
                <div class="col-12 my-3">
                    <button type="button" class="btn btn-sm btn-dark rounded-2 p-1 pt-2 px-4 fs-4 text-center" wire:click="ShowPermission">
                        {{ __('header.back') }}
                    </button>
                    <div class="d-flex align-items-center flex-wrap gap-3 p-2 mt-3">
                        @foreach($roless as $role)
                        <div class="mt-2 mx-1">
                            <div class="form-check form-switch d-flex align-items-center justify-content-center gap-1 not-reverse">
                                <input class="form-check-input permission" wire:loading.attr="disabled" type="checkbox" wire:click="role_permission({{ $role->id }})" value="{{ $role->id }}" @if ($UpdateUser) {{  in_array($role->id,$permission) ? 'checked' : '' }} @else {{  in_array($role->id,$permission) ? 'checked' : '' }} @endif>
                                <label class="form-check-label mt-2">{{ $role->name }}</label>
                            </div>
                        </div>
                        @endforeach
                        <div class="col-12">
                            <div wire:loading wire:target="role_permission">
                                {{ __('header.waiting') }}
                                <span class="animated-dots fs-3">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary  px-3 pt-2">
                        {{ $UpdateUser ? __('header.update') : __('header.add+') }}
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
</x-modal.add>