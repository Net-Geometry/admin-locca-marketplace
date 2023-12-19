"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF DATATABLES
    // =======================================================



    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});

$('#reset_btn').click(function(){
    $('#exampleFormControlSelect1').val(null).trigger('change');
})
