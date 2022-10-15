@push('title') Dashboard @endpush
<div>
    <div class="px-2 px-lg-5 py-2 {{ app()->getLocale() == 'ckb' ? 'reverse' : '' }} ">
        <div class="mt-3">
            <div class="col-sm-12 my-3">
                <livewire:system.clean-up />
            </div>
            <div class="row row-deck row-cards">
                <livewire:dashboard.new-products />
                <livewire:dashboard.new-users />
                <livewire:dashboard.new-suppliers />
                <livewire:dashboard.new-categorys />
                <livewire:dashboard.expiry-product />
                <livewire:dashboard.stock-out-product />
                <livewire:dashboard.today-sale />
                <livewire:dashboard.today-sale-product />
                <livewire:dashboard.total-sales />
                <livewire:dashboard.total-product />
                <livewire:dashboard.total-users />
            </div>
        </div>
    </div>
</div>