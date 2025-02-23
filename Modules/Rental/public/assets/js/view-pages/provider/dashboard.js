"use strict";
$(document).ready(function () {
    $('.trip_stats_update').on('change', function () {
        let statistics_type = $('.trip_stats_update').val();
        let route = $(this).data('route');
        trip_stats_update(statistics_type, route);
    });

    function trip_stats_update(statistics_type, route) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.get({
            url: route,
            data: {
                statistics_type: statistics_type
            },
            beforeSend: function () {
                $('#loading').show()
            },
            success: function (data) {
                $('#deliveryStatistics').html(data.delivery_statistics);
            },
            complete: function () {
                $('#loading').hide()
            }
        });
    }

    $('.commission_overview_stats_update').on('change', function () {
        let type = $(this).val();
        let route = $(this).data('route');

        commission_overview_stats_update(type, route);
    });

    function commission_overview_stats_update(type, route) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.get({
            url: route,
            data: {
                commission_overview: type,
            },
            beforeSend: function () {
                $('#loading').show()
            },
            success: function (data) {
                let grossEarningTotal = (data.grossEarning).toFixed(2)
                insert_param('commission_overview', type);
                $('#commission-overview-board').html(data.view);
                $('.gross-earning').text(formatCurrency(grossEarningTotal));
            },
            complete: function () {
                $('#loading').hide()
            }
        });
    }

    function formatCurrency(value) {
        var currency = $('#currency').data('currency');
        return currency + value;
    }

    function insert_param(key, value) {
        var currentUrl = $('#currency').data('current-url');
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
        window.history.pushState('page2', 'Title', +currentUrl + params);
    }
});
