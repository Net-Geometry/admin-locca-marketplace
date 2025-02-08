<div id="grow-sale-chart"></div>
<script>
    "use strict";
    options = {
        series: [ {
            name: 'Commission Earning',
            data: [{{ implode(",", array_map(fn($val) => number_format($val, 2, '.', ''), $commission)) }}]
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

</script>
