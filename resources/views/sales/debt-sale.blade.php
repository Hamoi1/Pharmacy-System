@push('title') Debt Sales @endpush
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
    <div class="{{ app()->getLocale() == 'ckb'   || app()->getLocale() == 'ar' ? 'reverse' : '' }} px-lg-5 px-3">
        @can('UpdateDebtSale')
        <x-modal.add target="update" modalWidth="modal-lg">
            <div wire:loading wire:target="edit">
                <div class="d-flex justify-content-center">
                    <h3>
                        {{ __('header.waiting') }}
                        <span class="animated-dots "></span>
                    </h3>
                </div>
            </div>
            <div wire:loading.remove wire:target="edit">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="modal-title me-auto" id="staticBackdropLabel">
                        {{ __('header.update') }}
                    </h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" wire:click=done></button>
                </div>
                <form wire:submit.prevent="submit({{ $__id }})">
                    <div class="row g-3">
                        <div class="row mt-2">
                            <p class="fw-bold">
                                {{ __('header.name') }} :
                                <span> {{ $name }} </span>
                            </p>
                            <p class="fw-bold">
                                {{ __('header.phone') }} :
                                <span> {{ $phone }} </span>
                            </p>
                            <p class="fw-bold">
                                {{ __('header.debtPaid') }} :
                                <span class="fw-bolder mx-1 fs-3">
                                    {{ number_format($amount , 0) }}
                                </span>
                                {{ __('header.currency') }}
                            </p>
                            <p class="fw-bold">
                                {{ __('header.currentPaid') }} :
                                <span class="fw-bolder mx-1 fs-3">
                                    {{ number_format($currentPaid , 0) }}
                                </span>
                                {{ __('header.currency') }}
                            </p>
                            <p class="fw-bold">
                                {{ __('header.debtPrice') }} :
                                <span class="fw-bolder mx-1 fs-3">
                                    {{ number_format($remain , 0) }}
                                </span>
                                {{ __('header.currency') }}
                            </p>
                        </div>
                        <div class="col-12">
                            <label for="">{{ __('header.Returned Money') }}</label>
                            <input type="number" class="form-control" wire:model.defer="price">
                            @error('price')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-3">
                                {{ __('header.update') }}
                            </button>
                            <div wire:loading wire:target="submit">
                                {{ __('header.waiting') }}
                                <span class="animated-dots fs-3">
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </x-modal.add>
        @endcan
        @can('DeleteDebtSale')
        <x-modal.delete target="delete" title="{{ __('header.delete') }}" modalWidth="modal" wire="wire:click=done">
            <div wire:loading>
                <div class="d-flex justify-content-center">
                    <h3>
                        {{ __('header.waiting') }}
                        <span class="animated-dots "></span>
                    </h3>
                </div>
            </div>
            <div wire:loading.remove>
                <span>
                    {{__('header.AreYouSure' , ['name'=>__('header.sale')]) }}
                </span>
                <form>
                    <div class="row g-3">
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger px-3 py-1 mx-2" wire:click.prevent="destroy({{ $__id }})">
                                {{ __('header.delete') }}
                            </button>
                            <button class="btn btn-primary px-3 py-1 mx-2" wire:click.prevent="done">
                                {{ __('header.cancel') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </x-modal.delete>
        @endcan

        <div class="d-flex align-items-center justify-content-between mt-3">
            <p class="fw-bolder fs-1">
                {{ __('header.Debts') }}
            </p>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4 col-12">
                <div class="input-icon">
                    <input type="text" class="form-control" placeholder="{{ __('header.search') }}" wire:model="search">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
            <div class="col-lg-2 col-12">
                <select class="form-select" wire:model="status">
                    <option value=""> {{ __('header.status') }}</option>
                    <option value="1">{{ __('header.debtsFinish') }}</option>
                    <option value="0">{{ __('header.debtsNotFinish') }}</option>
                </select>
            </div>
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
                        <th class="col-1 fs-4">
                            Invoice-ID
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.name') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.phone') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.debtPaid') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.currentPaid') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.debtPrice') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.status') }}
                        </th>
                        <th class="col-1 fs-4">
                            {{ __('header.date') }}
                        </th>
                        <th class="col-1 fs-4 text-center">{{ __('header.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($debtSales as $debt )
                    <tr>
                        <td>
                            {{ $debt->invoice }}
                        </td>

                        <td>
                            {{ $debt->name }}
                        </td>

                        <td>
                            {{ $debt->phone }}
                        </td>

                        <td>
                            {{ number_format($debt->amount , 0) }} {{ __('header.currency') }}
                        </td>

                        <td>
                            {{ number_format($debt->paid , 0) }} {{ __('header.currency') }}
                        </td>

                        <td>
                            {{ number_format($debt->remain , 0) }} {{ __('header.currency') }}
                        </td>

                        <td>
                            @if ($debt->status == 0)
                            <span class="badge bg-info">{{ __('header.debtNotFinish') }}</span>
                            @else
                            <span class="badge bg-success">{{ __('header.debtFinish') }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $debt->created_at->format('Y-m-d') }} <br>
                            {{ $debt->created_at->format('h:i:s') }}
                        </td>
                        <td class="text-center">
                            @if ($debt->status == 0)
                            @can('UpdateDebtSale')
                            <a href="" class="btn text-blue" data-bs-toggle="modal" data-bs-target="#update" wire:click="edit({{$debt->id }})">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @else
                            @can('DeleteDebtSale')
                            <a href="" class="btn text-danger" data-bs-toggle="modal" data-bs-target="#delete" wire:click="$set('__id',{{ $debt->id }})">
                                <i class="fas fa-trash"></i>
                            </a>
                            @endcan
                            @endif
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

            <div class="mt-4" wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
                {{ $debtSales->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>