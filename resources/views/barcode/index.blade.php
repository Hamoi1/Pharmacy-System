@push('title') Barcode @endpush
<div>
    <div class="container-lg {{ app()->getLocale() == 'ckb'  || app()->getLocale() == 'ar' ? 'reverse' : '' }} pt-4">
        <div class="col-12 mt-2">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    {{ __('header.barcodes.barcodes') }}
                </h2>
                @include('barcode.pages.generate')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generate">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        @include('barcode.pages.delete')
        <div class="row mt-3" wire:loading wire:target="previousPage,nextPage,gotoPage,download">
            <div class="d-flex  gap-2">
                <h3>
                    {{ __('header.waiting') }}
                </h3>
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        <div class="table-responsive mt-3" wire:loading.remove wire:target="previousPage,nextPage,gotoPage,download">
            <table class="table table-vcenter table-nowrap">
                <thead>
                    <tr>
                        <th class="fs-4">{{ __('header.name') }}</th>
                        <th class="fs-4">{{ __('header.barcode') }}</th>
                        <th class="fs-4">{{ __('header.barcodes.quantity') }}</th>
                        <th class="fs-4 col-3">{{ __('header.barcodes.shape of barcode') }}</th>
                        <th class="fs-4 text-center">{{ __('header.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barcodes as $barcode )
                    <tr>
                        <td>
                            {{ $barcode->name ?? __('header.barcodes.no name') }}
                        </td>
                        <td>
                            {{ $barcode->barcode }}
                        </td>
                        <td>
                            {{ number_format($barcode->quantity,0) }}
                        </td>
                        <td class="col-3">
                            <div class="">
                                {!! DNS1D::getBarcodeHTML($barcode->barcode, 'I25') !!}
                            </div>
                        </td>
                        <td class=" col-1 text-center">
                            <button class="btn" wire:click="download({{ $barcode->id }})">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click.prevent="$set('barcode_id',{{$barcode->id}})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
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
        <div class="mt-4" wire:loading.remove wire:target="search,Category,Supplier,previousPage,nextPage,gotoPage">
            {{ $barcodes->onEachSide(1)->links() }}
        </div>
    </div>
</div>
<!-- 
@push('js')

<script>
    var Barcode_section = '';
    const printBarcode = (BarcodeQuantity, BarcodeNumber) => {
        console.log(BarcodeQuantity);
        // foreach barcodeQuantity create barcode  
        if (BarcodeNumber != null) {

            for ($i = 0; $i < BarcodeQuantity; $i++) {
                Barcode_section += `<div class="col-3">
                <div class="d-flex justify-content-center">
                <div class="barcode">
                <div class="barcode__text">
                </div>
                <div class="barcode__code">
                        <span>${BarcodeNumber}</span>
                    </div>
                    </div>
                    </div>`;
            }
        }
    }
</script>

@endpush -->
<!-- 
    <div class="container-lg">
        <div class="row border p-3 mt-5">
            @php
            $number = rand(1000000000, 9999999999);
            @endphp
            <div class="w-auto border ">
                {!! DNS1D::getBarcodeHTML($number, 'I25') !!}
                <p class="text-center">{{ $number }}</p>
            </div>
        </div>

    </div> -->


<!-- <div class="p-4 mt-5 row gy-5">
        <div class="mb-3">{!! DNS2D::getBarcodeSVG('4445645656', 'QRCODE') !!}</div>
    <div class=" mt-5">{!! DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T') !!}</div>

    <div class="mb-3">{!! DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T') !!}</div>
    <div class="mb-3">{!! DNS1D::getBarcodeSVG(rand(1,10000), 'CODABAR') !!}</div>
        <div class="mb-3">{!! DNS1D::getBarcodeHTML(rand(1,10000), 'CODABAR') !!}</div>
        <div class="mb-3">{!! DNS1D::getBarcodeSVG('4445645656', 'KIX') !!}</div> -->

<!-- <div class="mb-3">{!! DNS1D::getBarcodeSVG('4445645656', 'RMS4CC') !!}</div> -->
<!-- <div class="mb-3">{!! DNS1D::getBarcodeSVG('4445645656', 'UPCA') !!}</div> -->
</div>
</div>