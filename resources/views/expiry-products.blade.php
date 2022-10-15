@push('title') Expiry Products @endpush
<div>
    <div class="{{ app()->getLocale() =='ckb' ? 'reverse' : '' }} px-lg-5 px-3">
        <div class="my-4">
            <h1>
                {!! __('header.ExpiryProducts') !!}
            </h1>
        </div>
        <div class="row mt-3" wire:loading wire:target="previousPage,nextPage,gotoPage">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3" wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="fs-4">{{ __('header.name') }}</th>
                        <th class="fs-4">{{ __('header.barcode') }}</th>
                        <th class="fs-4">{{ __('header.sale_price') }}</th>
                        <th class="fs-4 text-center">{{ __('header.expire_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product )
                    <tr>
                        <td>
                            <a href="{{ route('products', ['s' => $product->name , 'lang'=>app()->getLocale()]) }}">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>
                            {{ $product->barcode }}
                        </td>
                        <td>
                            {{ number_format($product->sale_price , 0) }} {{ __('header.currency') }}
                        </td>
                        <td class="text-center">
                            {{ $product->expiry_date }}
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
        </div>
        <div class="mt-4" wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
            {{ $products->onEachSide(1)->links() }}
        </div>
    </div>
</div>