@push('title') Customers @endpush
<div>
    <div wire:loading wire:target="delete">
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
                            {{ __('header.Customers') }}
                        </p>
                    </div>
                    @can('Insert Customer')
                    <div class="col-auto ms-auto">
                        <a href="" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#add-update" wire:click.prevent="$emit('addustomer')">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        @canany(['Update Customer','Insert Customer'])
        <livewire:customer.add-update />
        @endcanany
        @can('Delete Customer')
        @include('customer.delete')
        @endcan
        @include('customer.view')
        <div class="row g-2 ">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="input-icon">
                    <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
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
                    <tr class="">
                        <th class="col-2 fs-4">
                            {{ __('header.name') }}
                        </th>
                        <th class="col-2 fs-4">
                            {{ __('header.phone') }}
                        </th>
                        <th class="fs-4 col-2">
                            {{ __('header.email') }}
                        </th>
                        <th class="fs-4">
                            {{ __('header.address') }}
                        </th>
                        <th class="fs-4">
                            {{ __('header.guarantoraddress') }}
                        </th>
                        <th class="fs-4">
                            {{ __('header.guarantorphone') }}
                        </th>
                        @canany(['Update Customer','Delete Customer'])
                        <th class="fs-4 text-center">
                            {{ __('header.actions') }}
                        </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ( $customers as $customer )
                    <tr class="">
                        <td>
                            <a class="mx-2 cursor-pointer" data-bs-toggle="modal" data-bs-target="#view" wire:click.prevent="show({{ $customer->id }})">
                                <span class="font-weight-medium">{{ Str::limit($customer->name,30) }}</span>
                            </a>
                        </td>
                        <td>
                            {{ $customer->phone }}
                        </td>
                        <td>
                            {{ $customer->email }}
                        </td>
                        <td>
                            {{ $customer->address }}
                        </td>
                        <td>
                            {{ $customer->guarantoraddress  ?? __('header.not have' ,['name'=> __('header.guarantoraddress')]) }}
                        </td>
                        <td>
                            {{ $customer->guarantorphone  ?? __('header.not have' ,['name'=> __('header.guarantorphone')]) }}
                        </td>
                        @canany(['Update Customer','Delete Customer'])
                        <td class=" col-1 text-center">
                            @can('Update Customer')
                            <a class="btn " href="" data-bs-toggle="modal" data-bs-target="#add-update" wire:click.prevent="$emit('Update','{{ $customer->id }}')">
                                <i class="fa-solid fa-edit text-primary"></i>
                            </a>
                            @endcan
                            @can('Delete Customer')
                            <a class="btn" href="" data-bs-toggle="modal" data-bs-target="#delete" wire:click="$set('CustomerId',{{ $customer->id }})">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            @endcan
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <h4>
                                {{ __('header.NoData') }}
                            </h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="my-2" wire:loading.remove wire:target="search,previousPage,nextPage,gotoPage">
            {!! $customers->onEachSide(1)->links() !!}
        </div>
    </div>
</div>
