"use strict";
$(document).ready(function() {

    $('#cashback_type').on('change', function() {
        if($('#cashback_type').val() == 'amount')
        {
            $('#max_discount').attr("readonly","true");
            $('#max_discount').val(null);
            $('#percentage').addClass('d-none');
            $('#cuttency_symbol').removeClass('d-none');
        }
        else
        {
            $('#max_discount').removeAttr("readonly");
            $('#percentage').removeClass('d-none');
            $('#cuttency_symbol').addClass('d-none');
        }
    });

    $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
    $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);

    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});

$("#date_from").on("change", function () {
    $('#date_to').attr('min',$(this).val());
});

$("#date_to").on("change", function () {
    $('#date_from').attr('max',$(this).val());
});
$('#reset_btn').click(function(){
    $('#select_customer').val(null).trigger('change');

})
