@extends('layouts.saleLayout')
@section('content')

<div class="">
    <div class="row {{ app()->getLocale() == 'ckb' || app()->getLocale() == 'ar' ? 'reverse' : ''  }}">
        <div class="row gy-1 py-2">
            <span>
                {{ $settings->name }}
            </span>
            <span>
                {{ __('header.phone') }} : {{ $settings->phone }}
            </span>
            <span>
                {{ __('header.address') }} : {{ $settings->address }}
            </span>
            <span >
                {{ __('header.date') }} : <span class="not-reverse"> {{ $sale->created_at->format('Y-m-d') }}</span>
            </span>
        </div>
        <div class="col-12 mt-1">
            <div class="text-center not-reverse mt-2 border py-1">
                <span class="pt-1">{{ $sale->invoice }}</span>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('header.name') }}</th>
                        <th>{{ __('header.quantity') }}</th>
                        <th>{{ __('header.price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->sale_details as $sale_detail )
                    <tr>
                        <th>{{ $sale_detail->products->name }}</th>
                        <th>{{ $sale_detail->quantity }}</th>
                        <th>{{ number_format($sale_detail->products->sale_price,2,',',',') }} {{ __('header.currency') }}</th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p>
            {{ __('header.TotalPrice') }} : {{ number_format($sale->total,0,',',',') }} {{ __('header.currency') }}
        </p>
    </div>
</div>
@endsection