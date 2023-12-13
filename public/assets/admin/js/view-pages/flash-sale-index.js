"use strict";
$("#from").on("change", function () {
    $('#to').attr('min',$(this).val());
});

$("#to").on("change", function () {
    $('#from').attr('max',$(this).val());
});
$(document).on('ready', function () {
    $('#from').attr('min',(new Date()).toISOString().split('T')[0]);
    $('#to').attr('min',(new Date()).toISOString().split('T')[0]);
    // INITIALIZATION OF DATATABLES
    // =======================================================
    let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

    $('#column1_search').on('keyup', function () {
        datatable
            .columns(1)
            .search(this.value)
            .draw();
    });


    $('#column3_search').on('change', function () {
        datatable
            .columns(2)
            .search(this.value)
            .draw();
    });


    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});

$(".lang_link").click(function(e){
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.substring(0, form_id.length - 5);
    $("#"+lang+"-form").removeClass('d-none');
})
