@push('title') Users @endpush
<div>
    <div wire:loading wire:target="delete,toggleActive">
        <div class="loading">
            <div class="loading-content">
                <div class="loading-icon">
                    <img src="{{ asset('assets/images/Spinner.gif') }}" width="250px" alt="">
                </div>
                <h1 class="loading-title ">
                    {!! __('header.waiting') !!}
                </h1>
            </div>
        </div>
    </div>

    <div class="{{ app()->getLocale() == 'ckb' ? 'reverse' : '' }} container-xl">
        <div class="mt-4">
            <x-page-header title="{{ __('header.Users') }}" target="#add-update" wire="wire:click=add" />
        </div>
        @can('admin')
        @include('user.add-update')
        @include('user.delete')
        @endcan
        <x-not-access name="{{ __('header.User') }}" />
        @include('user.view')
        <div class="row g-2 mt-3">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="input-icon">
                    <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
            @can('admin')
            <div class="col-lg-2 col-md-6 col-6">
                <select class="form-select" wire:model="status">
                    <option value="">{{ __('header.status') }}</option>
                    <option value="1">Active</option>
                    <option value="0">Not Active</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <select class="form-select" wire:model="roles">
                    <option value="">{{ __('header.role') }}</option>
                    <option value="1">Admin</option>
                    <option value="2">Cashier</option>
                </select>
            </div>
            @endcan
            @can('admin')
            <div class="col-lg-1 col-md-6 col-5 mx-lg-2">
                <button class=" btn" wire:click="Trash">
                    <i class="fa-solid fa-trash-can mx-2"></i>
                    {{ __('header.Trash') }}
                </button>
            </div>
            @if($Trashed)
            <div class="col-lg-1 col-md-6 col-5 mx-lg-2">
                <div class="dropdown">
                    <button class="btn btn-info " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('header.actions') }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li class="dropdwon-item mt-2">
                            <button class=" btn shadow-none text-danger" wire:click="DeleteAll">
                                <i class="fa-solid fa-trash-can mx-2"></i>
                                {{ __('header.DeletedAll') }}
                            </button>
                        </li>
                        <li class="dropdwon-item mt-2">
                            <button class=" btn shadow-none text-success " wire:click="RestoreAll">
                                <i class="fa-solid fa-recycle mx-1"></i>
                                {{ __('header.RestoreAll') }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
            @endcan
        </div>
        <div class="row mt-3" wire:loading wire:target="search,roles,status,previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3" wire:loading.remove wire:target="search,roles,status,previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr class="">
                        <th class="col-3 fs-4">
                            {{ __('header.name') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.username') }}
                        </th>
                        <th class="fs-4">
                            {{ __('header.email') }}
                        </th>
                        <th class="fs-4 col-1">
                            {{ __('header.role') }}
                        </th>
                        @if(!$Trashed)
                        <th class="fs-4 text-center">
                            {{ __('header.status') }}
                        </th>
                        @else
                        <th class="fs-4 text-center">
                            {{ __('header.warning') }}
                        </th>
                        @endif
                        @can('admin')
                        <th class="fs-4 text-center">
                            {{ __('header.actions') }}
                        </th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $users as $user )
                    <tr class="">
                        <td>
                            <div class="d-flex py-1 align-items-center">
                                <img src="{{ $user->image($user->image) }}" class=" avatar  rounded-circle mx-2 object-cover d-none d-md-block">
                                <div class="flex-fill">
                                    <a class="text-dark cursor-pointer" @if(!$Trashed) href="" wire:click.prevent="show({{ $user->id }})" data-bs-toggle="modal" data-bs-target="#view" @endif>
                                        <span class="font-weight-medium">{{ $user->name }}</span>
                                    </a>
                                    @if ($user->create_at())
                                    <span class="badge bg-warning">
                                        {{ __('header.new') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $user->username }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->role() }}
                        </td>
                        @if(!$Trashed)
                        <td class="col-1 text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="badge bg-{{ $user->status == 1 ? 'success' : 'danger' }}">{{ $user->status == 1 ? __('header.active') : __('header.deactive') }}
                                </span>
                                @can('admin')
                                <span>
                                    <label class="form-check form-switch mt-1 mx-2">
                                        <input class="form-check-input" type="checkbox" {{ $user->status == 1 ? 'checked' : '' }} wire:click="toggleActive({{ $user->id }})">
                                    </label>
                                </span>
                                @endcan
                            </div>
                        </td>
                        @else
                        <td>
                            <span class="badge bg-info px-2 py-2">
                                {{ __('header.DeletedAfter30Dayes' ,['date'=>  $GetTrashDate($user->deleted_at) ]) }}
                            </span>
                        </td>
                        @endif

                        @can('admin')
                        <td class=" col-1 text-center">
                            @if(!$Trashed)
                            <a class="btn " href="" data-bs-toggle="modal" data-bs-target="#add-update" wire:click.prevent="Update({{ $user->id }})">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click="$set('UserId',{{ $user->id }})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            @else
                            <button class="btn" wire:click="restore({{ $user->id }})">
                                <i class="fa-solid fa-recycle text-success"></i>
                            </button>
                            @endif
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <h4>
                                {{ __('header.NoData') }}
                            </h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="my-5" wire:loading.remove wire:target="search,roles,status,previousPage,nextPage,gotoPage">
            {!! $users->onEachSide(1)->links() !!}
        </div>
    </div>
</div>