"use strict";
function getRequest(route, id) {
    $.get({
        url: route,
        dataType: 'json',
        success: function (data) {
            $('#' + id).empty().append(data.options);
        },
    });
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#viewer').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$('#banner_type').on('change', function () {
    let order_type = $(this).val();
    banner_type_change(order_type);
})
function banner_type_change(order_type) {
    if(order_type=='item_wise')
    {
        $('#store_wise').hide();
        $('#item_wise').show();
        $('#default').hide();
    }
    else if(order_type=='store_wise')
    {
        $('#store_wise').show();
        $('#item_wise').hide();
        $('#default').hide();
    }
    else if(order_type=='default')
    {
        $('#default').show();
        $('#store_wise').hide();
        $('#item_wise').hide();
    }
    else{
        $('#item_wise').hide();
        $('#store_wise').hide();
        $('#default').hide();
    }
}

$("#customFileEg1").change(function () {
    readURL(this);
});

$(".lang_link").click(function(e){
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.substring(0, form_id.length - 5);
    console.log(lang);
    $("#"+lang+"-form").removeClass('d-none');
})

$('#reset_btn').click(function(){
    location.reload(true);
})
