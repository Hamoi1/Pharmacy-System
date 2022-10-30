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
                <div class="row mt-4 gy-3 not-reverse">
                    <div class="col-12">
                        <h3 class="">{{ __('Top 10 User Sales') }}</h3>
                        <div class="card">
                            <div class="card-body">
                                <div id="chart" class="chart-lg chart"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <h3 class="">{{ __('Top 10 Product Sales') }}</h3>
                        <div class="card">
                            <div class="card-body">
                                <div id="chart-product" class="chart-lg"></div>
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
            enabled: true,
            offsetX: 0,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
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


    var Products = @js($products);
    var options = {
        series: [{
            name: 'Quantity Product Sale',
            data: Products.map(function(product) {
                return product.total_quantity
            })
        }],
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: true,
                columnWidth: '50%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: true,
            offsetX: 0,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        stroke: {
            show: true,
            width: 4,
            colors: ['transparent']
        },
        xaxis: {
            categories: Products.map(function(product) {
                return product.products.name
            }),
        },
        yaxis: {},
        fill: {
            opacity: .7,
            colors: ['#1C2833']
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val
                }
            }
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart-product"), options);
    chart.render();
</script>

@endpush