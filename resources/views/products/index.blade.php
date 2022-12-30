@push('title') Products @endpush
<div>
    <div wire:loading wire:target="delete,restore">
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
    <div class="{{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3">
        <div class="mt-4">
            <x-page-header title="{{ __('header.Products') }}" target="#add-update" wire="wire:click=add" />
        </div>
        @can('admin')
        @include('products.add-update')
        @include('products.delete')
        @endcan
        <x-not-access name="{{ __('header.Product') }}" />
        @include('products.view')
        <div class="row g-2 my-3">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="input-icon">
                    <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-lg-1 col-md-6 col-6">
                <select class="form-select" wire:model="Category">
                    <option value="">{{ __('header.Categorys') }}</option>
                    @foreach ($categorys as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <select class="form-select" wire:model="Supplier">
                    <option value="">{{ __('header.Suppliers') }}</option>
                    @forelse ($suppliers as $supplier )
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @empty
                    <option value="">{{ __('header.NoData') }}</option>
                    @endforelse
                </select>
            </div>
            <div class="col-lg-2 col-md-12">
                <select class="form-select" wire:model="ExpiryOrStockedOut">
                    <option value="">{{ __('header.ExpiryOrStockedOut') }}</option>
                    <option value="e">{{ __('header.Expiry') }}</option>
                    <option value="s">{{ __('header.StockedOut') }}</option>
                </select>
            </div>
            @can('admin')
            <div class="col-lg-4 col-12">
                <div class="d-flex align-items-center ">
                    <button class="btn" wire:click="Trash">
                        <i class="fa fa-trash mx-1"></i>
                        {{ __('header.Trash') }}
                    </button>
                    @if($Trashed)
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
                    @endif
                </div>
            </div>
            @endcan
        </div>
        <div class="row mt-3" wire:loading wire:target="search,Category,Supplier,previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>  
        <div class="table-responsive mt-3" wire:loading.remove wire:target="search,Category,Supplier,previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="col-1 fs-4">{{ __('header.name') }}</th>
                        <th class="col-1 fs-4">{{ __('header.barcode') }}</th>
                        <th class="fs-4">{{ __('header.purches_price') }}</th>
                        <th class="fs-4">{{ __('header.sale_price') }}</th>
                        <th class="fs-4">{{ __('header.Category') }}</th>
                        <th class="fs-4">{{ __('header.supplier') }}</th>
                        <th class="fs-4">{{ __('header.quantity') }}</th>
                        <th class="fs-4 text-center">{{ __('header.expire_date') }}</th>
                        @if($Trashed)
                        <th class="fs-4 text-center">{{ __('header.warning') }}</th>
                        @endif
                        @can('admin')
                        <th class="col-1 fs-4 text-center">{{ __('header.actions') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product )
                    <tr>
                        <td>
                            <a href="" class="" data-bs-toggle="modal" data-bs-target="#view" wire:click.prevent="show({{ $product->id }})">
                                {{ $product->name }}
                            </a>
                            @if ($product->create_at())
                            <span class="badge bg-warning mx-1">
                                {{ __('header.new') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            {{ $product->barcode }}
                        </td>
                        <td>
                            {{ $product->purches_price }} {{ __('header.currency') }}
                        </td>
                        <td>
                            {{ $product->sale_price }} {{ __('header.currency') }}
                        </td>
                        <td>
                            {{ $product->category_name ?? __('header.not have',['name'=>__('header.Category')]) }}
                        </td>
                        <td>
                            {{ $product->supplier_name ?? __('header.not have',['name'=>__('header.supplier')]) }}
                        </td>
                        <td>
                            {{ $product->quantity }}
                        </td>
                        <td class="text-center">
                            {{ $product->expiry_date }}
                            @if ($product->expiry_date <= now()) <span class="badge bg-danger mx-2">
                                Expired
                                </span>
                                @endif
                        </td>
                        @if($Trashed)
                        <td>
                            <span class="badge bg-info px-2 py-2">
                                {{ __('header.DeletedAfter30Dayes' ,['date'=>  $GetTrashDate($product->deleted_at) ]) }}
                            </span>
                        </td>
                        @endif
                        @can('admin')
                        <td class=" col-1 text-center">
                            @if(!$Trashed)
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#add-update" wire:click="updateProduct({{ $product->id }})">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click="$set('productID' , {{ $product->id }})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            <a class="btn" href="{{ route('products.image.update',['lang'=>app()->getLocale() ,'id'=>$product->id]) }}">
                                <i class="fa-solid fa-image text-info"></i>
                            </a>
                            @else
                            <button class="btn" wire:click="restore({{ $product->id }})">
                                <i class="fa-solid fa-recycle text-success"></i>
                            </button>
                            @endif
                        </td>
                        @endcan
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
        <div class="mt-4" wire:loading.remove wire:target="search,Category,Supplier,previousPage,nextPage,gotoPage">
            {{ $products->onEachSide(1)->links() }}
        </div>
    </div>
</div>