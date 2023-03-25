<div wire:ignore.self>
    <div class="offcanvas offcanvas-seaction {{ app()->getLocale() == 'ckb' || app()->getLocale() == 'ar' ? 'reverse offcanvas-start' : 'offcanvas-end' }}" tabindex="-1" id="Products" aria-labelledby="offcanvasStartLabel" aria-modal="true" role="dialog">
        <div class="offcanvas-header   ">
            <h2 class="offcanvas-title" id="offcanvasStartLabel">
                {{ __('header.Products') }}
            </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-12">
                    <h4>
                        {{ __('header.ExpiryProducts') }} : {{  $countExpiry }}
                    </h4>
                    <div class="table-responsive mt-3 expiry">
                        <table class="table table-vcenter table-nowrap ">
                            <thead>
                                <tr class="">
                                    <th class="col-3 fs-4">
                                        {{ __('header.name') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.expire_date') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.Days left to expire') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expiry as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('products',['lang'=>app()->getLocale(),'s'=>$item->name]) }}">
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $item->expiry_date }}
                                        @if ($item->expiry_date <= now()) <span class="badge bg-danger mx-2">
                                            {{ __('header.expired') }}
                                            </span>
                                            @endif
                                    </td>
                                    <td>
                                        @if ($calculatedate($item->expiry_date) == 0)
                                        {{ '0 '.__('header.days') }}
                                        @else
                                        {{ $calculatedate($item->expiry_date) . ' ' .__('header.days')   }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        {{ __('header.No Data') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <h4>
                        {{ __('header.StockedOutProducts') }} : {{  $countStockout }}
                    </h4>
                    <div class="table-responsive mt-3 stockout">
                        <table class="table table-vcenter table-nowrap ">
                            <thead>
                                <tr class="">
                                    <th class="col-3 fs-4">
                                        {{ __('header.name') }}
                                    </th>
                                    <th class="col-1 fs-4">
                                        {{ __('header.quantity') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockout as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('products',['lang'=>app()->getLocale(),'s'=>$item->name]) }}">
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $item->min_quantity }}
                                        @if ($item->min_quantity == 0) <span class="badge bg-danger mx-2">
                                            {{ __('header.StockOut') }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        {{ __('header.No Data') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>