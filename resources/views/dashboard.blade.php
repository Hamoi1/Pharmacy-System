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
                <livewire:dashboard.today-sale />
                <livewire:dashboard.today-sale-product />
                <livewire:dashboard.expiry-product />
                <livewire:dashboard.stock-out-product />
                <livewire:dashboard.total-sales />
                <livewire:dashboard.total-product />
                <livewire:dashboard.total-users />
                <div class="row mt-4 not-reverse">
                    <h3 class="">{{ __('Top 10 User Sales') }}</h3>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="chart" class="chart-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    var Users = @js($users);
    console.log(Users);
    var options = {
        series: [{
            name: 'Sales',
            data: Users.map(function(user) {
                return user.sales_count
            })
        }],
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: Users.map(function(user) {
                return user.name
            }),
        },
        yaxis: {
            title: {
                text: 'Sales'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val
                }
            }
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

@endpush