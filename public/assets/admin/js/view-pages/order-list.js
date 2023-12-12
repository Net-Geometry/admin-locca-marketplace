"use strict";
$(document).ready(function() {

    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    let zone_id = [];
    $('#zone_ids').on('change', function(){
        if($(this).val())
        {
            zone_id = $(this).val();
        }
        else
        {
            zone_id = [];
        }
    });

    $('.refund-filter').on('change', function(){
        window.location.href = $(this).val();
    });

    $('.filter-button-show').on('click', function(){
        $('#datatableFilterSidebar,.hs-unfold-overlay').show(500)
    });

    $('.filter-button-hide').on('click', function(){
        $('#datatableFilterSidebar,.hs-unfold-overlay').hide(500)
    });

    $('#export-copy').click(function () {
        datatable.button('.buttons-copy').trigger()
    });

    $('#export-excel').click(function () {
        datatable.button('.buttons-excel').trigger()
    });

    $('#export-csv').click(function () {
        datatable.button('.buttons-csv').trigger()
    });

    $('#export-print').click(function () {
        datatable.button('.buttons-print').trigger()
    });

    $('#datatableSearch').on('mouseup', function (e) {
        let $input = $(this),
            oldValue = $input.val();

        if (oldValue == "") return;

        setTimeout(function () {
            let newValue = $input.val();

            if (newValue == "") {
                // Gotcha
                datatable.search('').draw();
            }
        }, 1);
    });

    $('#toggleColumn_order').change(function (e) {
        datatable.columns(1).visible(e.target.checked)
    })

    $('#toggleColumn_date').change(function (e) {
        datatable.columns(2).visible(e.target.checked)
    })

    $('#toggleColumn_customer').change(function (e) {
        datatable.columns(3).visible(e.target.checked)
    })
    $('#toggleColumn_store').change(function (e) {
        datatable.columns(4).visible(e.target.checked)
    })
    $('#toggleColumn_total').change(function (e) {
        datatable.columns(5).visible(e.target.checked)
    })
    $('#toggleColumn_order_status').change(function (e) {
        datatable.columns(6).visible(e.target.checked)
    })

    $('#toggleColumn_actions').change(function (e) {
        datatable.columns(7).visible(e.target.checked)
    })
    // INITIALIZATION OF TAGIFY
    // =======================================================
    $('.js-tagify').each(function () {
        let tagify = $.HSCore.components.HSTagify.init($(this));
    });

    $("#date_from").on("change", function () {
        $('#date_to').attr('min',$(this).val());
    });

    $("#date_to").on("change", function () {
        $('#date_from').attr('max',$(this).val());
    });
});
