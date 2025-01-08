<div class="position-relative pie-chart">
    <div id="dognut-pie"></div>
    <!-- Total Orders -->
    <div class="total--orders">
        <h3 class="text-uppercase mb-xxl-2">
            {{ $totalCount }}</h3>
        <span class="text-capitalize">{{ translate('messages.total_trip') }}</span>
    </div>
    <!-- Total Orders -->
</div>
<div class="d-flex flex-wrap justify-content-center mt-4">
    <div class="chart--label">
        <span class="indicator chart-bg-1"></span>
        <span class="info">
            {{ translate('messages.Hourly_Trip') }} {{ $hourlyCount }}
        </span>
    </div>
    <div class="chart--label">
        <span class="indicator chart-bg-3"></span>
        <span class="info">
            {{ translate('messages.Distance_Wise_Trip') }} {{ $distanceWiseCount }}
        </span>
    </div>
</div>

<script>
    "use strict";

    options = {
        series: [{{ $hourlyCount }}, {{ $distanceWiseCount }}],
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

    // INITIALIZATION OF CHARTJS
    // =======================================================
    Chart.plugins.unregister(ChartDataLabels);

    $('.js-chart').each(function() {
        $.HSCore.components.HSChartJS.init($(this));
    });

    updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

</script>
