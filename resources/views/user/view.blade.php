<x-modal.view target="view" title="{{ __('header.title_view' , ['name'=>__('header.User')]) }}" modalWidth="modal-fullscreen" wire="wire:click=done">
    <div wire:loading>
        <div class="d-flex justify-content-center">
            <h3>
                {{ __('header.waiting') }}
                <span class="animated-dots "></span>
            </h3>
        </div>
    </div>
    @if($user)
    <div wire:loading.remove class="viewUser-Grid px-md-4 py-md-2  {{ app()->getLocale() == 'ckb'   || app()->getLocale() == 'ar' ? 'reverse' : '' }}"">
                    <div class=" text-center">
        <img src=" {{ $user->image($user->user_details->image) }}" width="300" height="300" class="img-fluid rounded-2 shadow-sm">
    </div>
    <div class="">
        <p class=" fw-bolder fs-3">
            {{ __('header.UserDetails') }}
        </p>
        <div class="align-center">
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
    </div>
    <div class="col-12" wire:loading.remove>
        <ul class="nav nav-pills mb-3 border-bottom" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-sales-tab" data-bs-toggle="pill" data-bs-target="#pills-sales" type="button" role="tab" aria-controls="pills-sales" aria-selected="true">
                    {{ __('header.Sales') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-products-tab" data-bs-toggle="pill" data-bs-target="#pills-products" type="button" role="tab" aria-controls="pills-products" aria-selected="false">
                    {{ __('header.Products') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-permission-tab" data-bs-toggle="pill" data-bs-target="#pills-permission" type="button" role="tab" aria-controls="pills-permission" aria-selected="false">
                    {{ __('header.permission') }}
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-sales" role="tabpanel" aria-labelledby="pills-sales-tab" tabindex="0">
                @if($user->sales != null)
                <div class="table-responsive mt-3" wire:loading.remove wire:target="prevPageSales,nextPageSales">
                    <table class="table table-vcenter table-nowrap">
                        <thead>
                            <tr>
                                <th class="col-1 fs-4">
                                    Invoice-ID
                                </th>
                                <th class="col-1 fs-4">
                                    {{ __('header.price') }}
                                </th>
                                <th class="col-1 fs-4">
                                    {{ __('header.discount') }}
                                </th>
                                <th class="col-1 fs-4">
                                    {{ __('header.status') }}
                                </th>
                                <th class="col-1 fs-4">
                                    {{ __('header.date') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->sales as $sale )
                            <tr>
                                <td>
                                    <a href="{{ $sale->paid ?'#' : route('sales.debt' ,['lang'=>app()->getLocale(),'s'=>$sale->name]) }}" class="{{ $sale->paid ? '' : 'text-blue' }}">
                                        {{ $sale->invoice }}
                                    </a>
                                </td>
                                <td>
                                    {{ number_format($sale->total,0)  }} {{ __('header.currency') }}
                                </td>
                                <td>
                                    {{ $sale->discount != null ?  number_format($sale->discount,0) . __('header.currency') : __('header.Not-discount') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sale->paid ? 'success' : 'info' }}">
                                        {{ $sale->paid ? __('header.Cash') : __('header.debt') }}
                                    </span>
                                </td>
                                <td>
                                    {{ $sale->created_at->format('Y-m-d') }}
                                </td>
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
                    @if ($user->sales)
                    <div class="my-2 mt-4 px-4 not-reverse " wire:loading.remove wire:target="prevPageSales,nextPageSales">
                        <button class="btn p-2 cursor-pointer" wire:click="prevPageSales" @if ($salesPage==1) disabled @endif>
                            <i class="fa fa-angle-left"></i>
                        </button>
                        <button class="btn p-2 cursor-pointer " wire:click="nextPageSales" @if ($salesPage==$lastPageSale) disabled @endif>
                            <i class="fa fa-angle-right"></i>
                        </button>
                    </div>
                    @endif

                </div>
                @else
                <div class="text-center">
                    <h4>
                        {{ __('header.NoData') }}
                    </h4>
                </div>
                @endif
            </div>
            @if($user->products)
            <div class="tab-pane fade" id="pills-products" role="tabpanel" aria-labelledby="pills-products-tab" tabindex="0">
                <div class="table-responsive mt-3" wire:loading.remove wire:target="prevPageProduct,nextPageProduct">
                    <table class="table table-vcenter table-nowrap">
                        <thead>
                            <tr>
                                <th class="col-1 fs-4">{{ __('header.name') }}</th>
                                <th class="col-1 fs-4">{{ __('header.barcode') }}</th>
                                <th class="fs-4">{{ __('header.Category') }}</th>
                                <th class="fs-4">{{ __('header.supplier') }}</th>
                                <th class="fs-4">{{ __('header.purches_price') }}</th>
                                <th class="fs-4">{{ __('header.sale_price') }}</th>
                                <th class="fs-4">{{ __('header.quantity') }}</th>
                                <th class="fs-4 text-center">{{ __('header.expire_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->products as $product )
                            <tr>
                                <td>
                                    <a href="{{ route('products',['lang'=>app()->getLocale(),'s'=>$product->name]) }}">
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
                                    {{ $product->category_name ?? __('header.not have',['name'=>__('header.Category')]) }}
                                </td>
                                <td>
                                    {{ $product->supplier_name ?? __('header.not have',['name'=>__('header.supplier')]) }}
                                </td>
                                <td>
                                    {{ number_format($product->purches_price,0,',',',') }} {{ __('header.currency') }}
                                </td>
                                <td>
                                    {{ number_format($product->sale_price,0,',',',') }} {{ __('header.currency') }}
                                </td>
                                <td>
                                    {{ number_format($product->quantity,0,',',',')  }}
                                    @if ($product->quantity == 0) <span class="badge bg-danger mx-2">
                                        {{ __('header.StockOut') }}
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center p-0 m-0">
                                    {{ $product->expiry_date }}
                                    @if ($product->expiry_date <= now()) <span class="badge bg-danger mx-2">
                                        {{ __('header.expired') }}
                                        </span>
                                        @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <h4>
                                        {{ __('header.NoData') }}
                                    </h4>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($user->products)
                <div class="my-2 mt-4 not-reverse " wire:loading.remove wire:target="prevPageProduct,nextPageProduct">
                    <button class="btn p-2 cursor-pointer" wire:click="prevPageProduct" @if ($productsPage==1) disabled @endif>
                        <i class="fa fa-angle-left"></i>
                    </button>
                    <button class="btn p-2  cursor-pointer" wire:click="nextPageProduct" @if ($productsPage==$lastPageproducts) disabled @endif>
                        <i class="fa fa-angle-right"></i>
                    </button>
                </div>
                @endif
                @endif
            </div>
            <div class="tab-pane fade" id="pills-permission" role="tabpanel" aria-labelledby="pills-permission-tab" tabindex="0">
                <div class="d-flex align-items-center flex-wrap">
                    @forelse ($user->GetPermissionName($user->id) as $permission)
                    <span class="bg-blue rounded-2 p-1 m-1 ">{{ $permission }}</span>
                    @empty
                    <p class="fw-bold">{{ __('header.no_permission') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif
</x-modal.view>