@extends('layouts.vendor.app')

@section('title',translate('messages.Dashboard - Taxi Module'))

@push('css_or_js')

@endpush


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img class="onerror-image" data-onerror-image="{{ asset('/public/assets/admin/img/grocery.svg') }}"
                            src="{{ asset('/public/assets/admin/img/100x100/2.jpg') }}" width="38" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title text-title mb-0">
                                {{ translate('messages.Car_Rental_Module_Dashboard') }}</h1>
                            <p class="page-header-text text-title fs-12 m-0">{{ translate('messages.Monitor_your') }}
                                <strong class="font-bold"> {{ translate('messages.car_rental_business') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-auto min--280">
                    <select name="zone_id" class="form-control js-select2-custom fetch_data_zone_wise">
                        <option value="all">{{ translate('messages.All_Zones') }}</option>
                        <option value="test">
                            {{ translate('messages.Test') }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Stats -->
        <div class="card mb-3">
            <div class="card-body pt-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between statistics--title-area">
                    <div class="statistics--title pr-sm-3" id="stat_zone">
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="page-header-title text-title fs-18 mb-0">
                                {{ translate('messages.Delivery_Statistics') }}</h3>
                            <label class="badge badge-soft-primary m-0">
                                {{ translate('messages.zone') }} : {{ translate('messages.all') }}
                            </label>
                        </div>
                    </div>
                    <div class="statistics--select">
                        <select class="custom-select border-0 order_stats_update" name="statistics_type">
                            <option value="all" selected="">
                                {{ translate('messages.All_Time') }}
                            </option>
                            <option value="test">
                                {{ translate('messages.test') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-1" href="javascript:">
                            <h4 class="title">66</h4>
                            <span class="subtitle font-regular">{{ translate('messages.Confirmed') }}</span>
                            <img src="{{ asset('/public/assets/admin/img/dashboard/1.png') }}" alt="img"
                                class="resturant-icon top-50px">
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-2" href="javascript:">
                            <h4 class="title">100</h4>
                            <span class="subtitle font-regular"> {{ translate('messages.Ongoing_Trip') }}
                            </span>
                            <img src="{{ asset('/public/assets/admin/img/dashboard/4.png') }}" alt="img"
                                class="resturant-icon top-50px">
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card bg-F1E8FA" href="javascript:">
                            <h4 class="title text-success">200</h4>
                            <span class="subtitle font-regular"> 
                                {{ translate('messages.Completed') }}
                            </span>
                            <img src="{{ asset('/public/assets/admin/img/dashboard/2.png') }}" alt="img"
                                class="resturant-icon top-50px">
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-4" href="javascript:">
                            <h4 class="title">60</h4>
                            <span class="subtitle font-regular"> {{ translate('messages.Canceled_Trip') }}
                            </span>
                            <span class="subtitle font-regular">
                                Cancellation Rate <span class="text--danger font-bold">12%</span>
                            </span>
                            <img src="{{ asset('/public/assets/admin/img/dashboard/5.png') }}" alt="img"
                                class="resturant-icon top-50px">
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="order--card h-100 badge--accepted" href="#">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    Pending
                                </h6>
                                <span class="card-title text--info">
                                    200
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="order--card h-100 badge--accepted" href="#">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    Scheduled
                                </h6>
                                <span class="card-title text--success">
                                    200
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="order--card h-100 badge--accepted" href="#">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    Regular
                                </h6>
                                <span class="card-title text--danger">
                                    200
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <!-- Card -->
                        <a class="order--card h-100 badge--accepted" href="#">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    All
                                </h6>
                                <span class="card-title text--title">
                                    200
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats -->
        <div class="row g-2">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center __gap-12px">
                            <div class="__gross-amount" id="gross_earning">
                                <h6>$855.8K</h6>
                                <span>{{ translate('messages.Gross_Earnings') }}</span>
                            </div>
                            <div class="chart--label __chart-label p-0 move-left-100 ml-auto">
                                <span class="indicator chart-bg-2"></span>
                                <span class="info">
                                    {{ translate('Earnings') }} ({{ date('Y') }})
                                </span>
                            </div>
                            <select
                                class="custom-select border-0 text-center w-auto ml-auto commission_overview_stats_update"
                                name="commission_overview">
                                <option value="this_year" selected>
                                    {{ translate('this_year') }}
                                </option>
                                <option value="this_month">
                                    {{ translate('this_month') }}
                                </option>
                                <option value="this_week">
                                    {{ translate('this_week') }}
                                </option>
                            </select>
                        </div>
                        <div id="commission-overview-board">

                            <div id="grow-sale-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Card -->
                <div class="card h-100" id="top-customer-view">
                    <div class="card-header border-0 order-header-shadow">
                        <h5 class="card-header-title font-bold d-flex justify-content-between">
                            <span>{{ translate('Top_Zones') }}</span>
                        </h5>
                        <a href="javascript:" class="fz-12px font-semibold text-006AE5">{{ translate('view_all') }}</a>
                    </div>
                    <div class="card-body">

                        <div class="top--selling">

                            <a class="grid--card align-items-center" href="javascript:">
                                <h5 class="mb-0 font-bold line--limit-1">{{ translate('Dhaka_Zone') }} <span class="font-semibold opacity-70"> {{ translate('(Business_Zone)') }} </span></h5>
                                <div class="ml-auto">
                                    <span class="badge badge-soft">{{ translate('Trips') }} : 300</span>
                                </div>
                            </a>
                            <a class="grid--card align-items-center" href="javascript:">
                                <h5 class="mb-0 font-bold line--limit-1">{{ translate('Mirpur_Zone') }}</h5>
                                <div class="ml-auto">
                                    <span class="badge badge-soft">{{ translate('Trips') }} : 300</span>
                                </div>
                            </a>

                        </div>

                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-6">
                <!-- Card -->
                <div class="card h-100" id="top-provider-view">
                    <div class="card-header border-0 order-header-shadow">
                        <h5 class="card-header-title font-bold d-flex justify-content-between">
                            <span>{{ translate('messages.Top_Vehicles') }}</span>
                        </h5>
                        <a href="javascript:" class="fz-12px font-semibold text-006AE5">{{ translate('view_all') }}</a>
                    </div>
                    <div class="card-body">

                        <div class="top--selling">

                            <a class="grid--card" href="javascript:">
                                <img class="onerror-image aspect-2-1 w--100px"
                                    data-onerror-image="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                    src="{{ asset('public/assets/admin/img/car-demo.png') }}">
                                <div class="cont pt-2">
                                    <h6 class="mb-1 line--limit-1">{{ translate('Toyota - F Premio 2006') }}</h6>
                                    <span class="line--limit-1">+Nator Kha 21-3214</span>
                                </div>
                                <div class="ml-auto">
                                    <span class="badge badge-soft">{{ translate('Trips') }} : 300</span>
                                </div>
                            </a>
                            <a class="grid--card" href="javascript:">
                                <img class="onerror-image aspect-2-1 w--100px"
                                    data-onerror-image="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                    src="{{ asset('public/assets/admin/img/car-demo.png') }}">
                                <div class="cont pt-2">
                                    <h6 class="mb-1 line--limit-1">{{ translate('Toyota - F Premio 2006') }}</h6>
                                    <span class="line--limit-1">+Nator Kha 21-3214</span>
                                </div>
                                <div class="ml-auto">
                                    <span class="badge badge-soft">{{ translate('Trips') }} : 300</span>
                                </div>
                            </a>

                        </div>

                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{ asset('public/assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>

    <!-- Apex Charts -->
    <script src="{{ asset('/public/assets/admin/js/apex-charts/apexcharts.js') }}"></script>
    <!-- Apex Charts -->
@endpush

@push('script_2')
    <!-- Dognut Pie Chart -->
    <script>
        "use strict";
        let options;
        let chart;

        // Static data for demonstration
        const hourlyCount = 450;
        const distancWiseCount = 50;
        const totalSell = [200, 300, 400, 500, 600,20, 30, 40, 50, 60, 100, 300];
        const commission = [20, 30, 40, 50, 60,20, 30, 40, 50, 60, 100, 20];
        const deliveryCommission = [10, 15, 20, 25, 30, 20, 30, 40, 50, 60, 100, 30];
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        options = {
            series: [hourlyCount, distancWiseCount],
            chart: {
                width: 320,
                type: 'donut',
            },
            labels: ['Hourly Trip', 'Distance Wise Trip'],
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#005555', '#b9e0e0']
                }
            },
            responsive: [{
                breakpoint: 1650,
                options: {
                    chart: {
                        width: 250
                    },
                }
            }],
            colors: ['#005555', '#111'],
            fill: {
                colors: ['#005555', '#b9e0e0']
            },
            legend: {
                show: false
            },
        };

        chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
        chart.render();

        options = {
            series: [{
                name: 'Gross Earning',
                data: totalSell
            }, {
                name: 'Commission Earning',
                data: commission
            }, {
                name: 'Subscription Earning',
                data: deliveryCommission
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            dataLabels: {
                enabled: false,
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            fill: {
                type: 'gradient',
                colors: ['#76ffcd', '#ff6d6d', '#005555'],
            },
            xaxis: {
                categories: labels
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        chart = new ApexCharts(document.querySelector("#grow-sale-chart"), options);
        chart.render();

        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        let updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

        $('.order_stats_update').on('change', function() {
            let type = $(this).val();
            order_stats_update(type);
        });

        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.dashboard-stats.order') }}',
                data: {
                    statistics_type: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('statistics_type', type);
                    $('#order_stats').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $('.fetch_data_zone_wise').on('change', function() {
            let zone_id = $(this).val();
            fetch_data_zone_wise(zone_id);
        });

        function fetch_data_zone_wise(zone_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.dashboard-stats.zone') }}',
                data: {
                    zone_id: zone_id,
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('zone_id', zone_id);
                    $('#order_stats').html(data.order_stats);
                    $('#user-overview-board').html(data.user_overview);
                    $('#popular-restaurants-view').html(data.popular_restaurants);
                    $('#top-deliveryman-view').html(data.top_deliveryman);
                    $('#top-rated-foods-view').html(data.top_rated_foods);
                    $('#top-restaurants-view').html(data.top_restaurants);
                    $('#top-selling-foods-view').html(data.top_selling_foods);
                    $('#top-customer-view').html(data.top_customers);
                    $('#stat_zone').html(data.stat_zone);
                    commission_overview_stats_update($('.commission_overview_stats_update').val());
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $('.user_overview_stats_update').on('change', function() {
            let type = $(this).val();
            user_overview_stats_update(type);
        });

        function user_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.dashboard-stats.user-overview') }}',
                data: {
                    user_overview: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('user_overview', type);
                    $('#user-overview-board').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        $('.commission_overview_stats_update').on('change', function() {
            let type = $(this).val();
            commission_overview_stats_update(type);
        });

        function commission_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.dashboard-stats.commission-overview') }}',
                data: {
                    commission_overview: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    insert_param('commission_overview', type);
                    $('#commission-overview-board').html(data.view);
                    $('#gross_earning').html(data.gross_earning);
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        function insert_param(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);
            let kvp = document.location.search.substr(1).split('&');
            let i = 0;

            for (; i < kvp.length; i++) {
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }
            if (i >= kvp.length) {
                kvp[kvp.length] = [key, value].join('=');
            }
            let params = kvp.join('&');
            window.history.pushState('page2', 'Title', '{{ url()->current() }}?' + params);
        }
    </script>
@endpush


