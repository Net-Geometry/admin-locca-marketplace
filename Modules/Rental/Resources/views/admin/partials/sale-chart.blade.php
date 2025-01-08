<div id="grow-sale-chart"></div>
<script>
    "use strict";
    options = {
        series: [{
            name: 'Gross Earning',
            data: [{{ implode(",",$total_sell) }}]
        }, {
            name: 'Commission Earning',
            data: [{{ implode(",",$commission) }}]
        }, {
            name: 'Subscription Earning',
            data: [{{ implode(",",$total_subs) }}]
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            },
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        dataLabels: {
            enabled: false,
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        stroke: {
            curve: 'smooth',
            width: 2,
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        fill: {
            type: 'gradient',
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        xaxis: {
            categories: [{!! implode(",",$label) !!}]
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
    };

    ApexChart = new ApexCharts(document.querySelector("#grow-sale-chart"), options);
    ApexChart.render();

    // INITIALIZATION OF CHARTJS
    // =======================================================
    Chart.plugins.unregister(ChartDataLabels);

    $('.js-chart').each(function() {
        $.HSCore.components.HSChartJS.init($(this));
    });

    updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
</script>
