@push('title') Users @endpush
<div>
    <div wire:loading wire:target="delete,toggleActive">
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
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3">
        <div>
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="fw-bolder fs-1">
                            {{ __('header.Users') }}
                        </p>
                    </div>
                    @can('Insert User')
                    <div class="col-auto ms-auto">
                        <a href="" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#add-update" wire="wire:click=add">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        @canany(['Update User','Insert User'])
        @include('user.add-update')
        @endcanany
        @can('User GenerateReport')
        @livewire('generate.user-report')
        @endcan
        @can('Delete User')
        @include('user.delete')
        @endcan
        @include('user.view')
        @can('User Export')
        @include('user.export')
        @endcan
        <div class="row g-2">
            <div class="col-xl-2 col-lg-4 col-md-4 col-12">
                <div class="input-icon">
                    <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-4 col-6">
                <select class="form-select" wire:model="status">
                    <option value="">{{ __('header.status') }}</option>
                    <option value="1">Active</option>
                    <option value="0">Not Active</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-4 col-6">
                <select class="form-select" wire:model="role_id">
                    <option value="">{{ __('header.permission') }}</option>
                    @foreach ($roless as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-6 col-12">
                <div class="d-flex aling-items-center flex-wrap gap-2">
                    @can('User Export')
                    <div>
                        <button class="btn pt-2" data-bs-toggle="modal" data-bs-target="#export">
                            <i class="fa-solid fa-file-export mx-2 mb-1"></i>
                            {{ __('header.Export') }}
                        </button>
                    </div>
                    @endcan
                    @can('User Trash')
                    <div>
                        <button class="btn pt-2" wire:click="Trash">
                            <i class="fa fa-trash mx-2 mb-1"></i>
                            {{ __('header.Trash') }}
                        </button>
                    </div>
                    @if($Trashed)
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-info " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('header.actions') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li class="dropdwon-item mt-2">
                                    <button class=" btn shadow-none text-danger d-flex align-items-center justify-content-center" wire:click="DeleteAll">
                                        <i class="fa-solid fa-trash-can mx-2 mb-2"></i>
                                        {{ __('header.DeletedAll') }}
                                    </button>
                                </li>
                                <li class="dropdwon-item mt-2">
                                    <button class=" btn shadow-none text-success d-flex align-items-center justify-content-center " wire:click="RestoreAll">
                                        <i class="fa-solid fa-recycle mx-2 mb-2"></i>
                                        {{ __('header.RestoreAll') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
        <div class="row mt-3" wire:loading wire:target="role_id,search,status,previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3" wire:loading.remove wire:target="role_id,search,status,previousPage,nextPage,gotoPage">
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
                        @if($Trashed)
                        <th class="fs-4 text-center">
                            {{ __('header.warning') }}
                        </th>
                        @else
                        <th class="fs-4 text-center">
                            {{ __('header.status') }}
                        </th>
                        @endif
                        @canany(['Update User','Delete User','User GenerateReport'])
                        <th class="fs-4 text-center">
                            {{ __('header.actions') }}
                        </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $users as $user )
                    <tr class="">
                        <td>
                            <div class="d-flex py-1 align-items-center">
                                <img src="{{ $user->image($user->image) }}" class=" avatar  rounded-circle mx-2 object-cover d-none d-md-block">
                                <div class="flex-fill">
                                    <a class="mx-2 cursor-pointer" @if(!$Trashed) href="" wire:click.prevent="ViewUser({{ $user->id }})" data-bs-toggle="modal" data-bs-target="#view" @endif>
                                        <span class="font-weight-medium">
                                            {{ Str::limit($user->name,15) }}
                                        </span>
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
                        @if($Trashed)
                        @can('User Trash')
                        <td>
                            <span class="badge bg-info px-2 py-2">
                                {{ __('header.DeletedAfter30Dayes' ,['date'=>  $GetTrashDate($user->deleted_at) ]) }}
                            </span>
                        </td>
                        @endcan
                        @else
                        <td class="col-1 text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="badge bg-{{ $user->status == 1 ? 'success' : 'danger' }}">{{ $user->status == 1 ? __('header.active') : __('header.deactive') }}
                                </span>
                                @can('Update User')
                                <span>
                                    <label class="form-check form-switch mt-1 mx-2">
                                        <input class="form-check-input" type="checkbox" {{ $user->status == 1 ? 'checked' : '' }} wire:click="toggleActive({{ $user->id }})">
                                    </label>
                                </span>
                                @endcan
                            </div>
                        </td>
                        @endif
                        @canany(['Update User','User GenerateReport','Delete User'])
                        <td class=" col-1 text-center">
                            @if(!$Trashed)
                            @can('Update User')
                            <a class="btn " href="" data-bs-toggle="modal" data-bs-target="#add-update" wire:click.prevent="Update({{ $user->id }})">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            @endcan
                            @can('User GenerateReport')
                            <a class="btn " href="" data-bs-toggle="modal" data-bs-target="#generate" wire:click.prevent="$emit('generateReport',{{ $user->id }})">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                            @endcan
                            @can('Delete User')
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click="$set('UserId',{{ $user->id }})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            @endcan
                            @else
                            @can('User Trash')
                            <button class="btn" wire:click="restore({{ $user->id }})">
                                <i class="fa-solid fa-recycle text-success"></i>
                            </button>
                            @endcan
                            @endif
                        </td>
                        @endcanany
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

        <div class="my-5" wire:loading.remove wire:target="search,status,previousPage,nextPage,gotoPage">
            {!! $users->onEachSide(1)->links() !!}
        </div>
    </div>
</div>