"use strict";
$(document).ready(function() {
    let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
});

$('#select-all').on('change', function(){
    if(this.checked === true) {
        $('.check--item-wrapper .check-item .form-check-input').attr('checked', true)
    } else {
        $('.check--item-wrapper .check-item .form-check-input').attr('checked', false)
    }
})
$('.check--item-wrapper .check-item .form-check-input').on('change', function(){
    if(this.checked === true) {
        $(this).attr('checked', true)
    } else {
        $(this).attr('checked', false)
    }
})

$(".lang_link").click(function(e){
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.substring(0, form_id.length - 5);
    $("#"+lang+"-form").removeClass('d-none');
});
