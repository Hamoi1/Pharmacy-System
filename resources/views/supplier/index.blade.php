@push('title') Suppliers @endpush
<div>
    <div wire:loading wire:target="delete">
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
    <div class="{{ app()->getLocale() =='ckb' ? 'reverse' : '' }} px-lg-5 px-3">
        <div class="mt-4">
            <x-page-header title="{{ __('header.Suppliers') }}" target="#add-update" wire="wire:click=add" />
        </div>
        @canany(['Update Supplier','Insert Supplier'])
        @include('supplier.add-update')
        @endcanany
        @can('Delete Supplier')
        @include('supplier.delete')
        @endcan
        <x-not-access name="{{ __('header.Supplier') }}" />
        <div class="row g-3 align-items-center">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="input-icon">
                    <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
            @can('Supplier Trash')
            <div class="col-lg-1 col-md-6 col-5 mx-lg-2">
                <button class=" btn " wire:click="Trash">
                    <i class="fa fa-trash mx-2 mb-2"></i>
                    {{ __('header.Trash') }}
                </button>
            </div>
            @if ($Trashed)
            <div class="col-lg-1 col-md-6 col-5 mx-lg-2">
                <div class="dropdown">
                    <button class="btn btn-info " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('header.actions') }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li class="dropdwon-item mt-2">
                            <button class=" btn shadow-none text-danger" wire:click="DeleteAll">
                                <i class="fa-solid fa-trash-can mx-2 mb-2"></i>
                                {{ __('header.DeletedAll') }}
                            </button>
                        </li>
                        <li class="dropdwon-item mt-2">
                            <button class=" btn shadow-none text-success " wire:click="RestoreAll">
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
        <div class="row mt-3" wire:loading wire:target="search,previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3" wire:loading.remove wire:target="search,previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="fs-4">{{ __('header.name') }}</th>
                        <th class="fs-4">{{ __('header.email') }}</th>
                        <th class="fs-4">{{ __('header.phone') }}</th>
                        <th class="fs-4">{{ __('header.address') }}</th>
                        @if ($Trashed)
                        <th class="fs-4">{{ __('header.warning') }}</th>
                        @endif
                        @canany(['Update Supplier','Insert Supplier'])
                        <th class="col-1 fs-4 text-center">{{ __('header.actions') }}</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier )
                    <tr>
                        <td>
                            <a href="{{ route('products' , ['supplier'=>$supplier->id,'lang'=>app()->getLocale()]) }}">
                                {{ $supplier->name }}
                            </a>
                            @if ($supplier->create_at())
                            <span class="badge bg-warning mx-1">
                                {{ __('header.new') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            {{ $supplier->email }}
                        </td>
                        <td>
                            {{ $supplier->phone }}
                        </td>
                        <td>
                            {{ $supplier->address }}
                        </td>
                        @if ($Trashed)
                        <td>
                            <span class="badge bg-info px-2 py-2">
                                {{ __('header.DeletedAfter30Dayes' ,['date'=>  $GetTrashDate($supplier->deleted_at) ]) }}
                            </span>
                        </td>
                        @endif
                        @canany(['Update Supplier','Insert Supplier'])
                        <td class=" col-1 text-center">
                            @if (!$Trashed)
                            @can('Update Supplier')
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#add-update" wire:click.prevent="edit({{ $supplier->id }})">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            @endcan
                            @can('Delete Supplier')
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click.prevent="$set('supplier_id', {{ $supplier->id }})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            @endcan
                            @else
                            <button class="btn text-success" wire:click="restore({{ $supplier->id }})">
                                <i class="fa-solid fa-recycle"></i>
                            </button>
                            @endif
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <h4>
                                {{ __('header.NoData') }}
                            </h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3" wire:loading.remove wire:taget="search,previousPage,nextPage,gotoPage">
            {!! $suppliers->onEachSide(1)->links() !!}
        </div>
    </div>
</div>