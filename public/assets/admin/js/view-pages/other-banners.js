"use strict";
$(document).ready(function() {

    $(".__upload-img, .upload-img-4, .upload-img-2, .upload-img-5, .upload-img-1, .upload-img").each(function(){
        var targetedImage = $(this).find('.img');
        var targetedImageSrc = $(this).find('.img img');
        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var uploadedFile = new FileReader();
                uploadedFile.onload = function (e) {
                    targetedImageSrc.attr('src', e.target.result);
                    targetedImage.addClass('image-loaded');
                    targetedImage.hide();
                    targetedImage.fadeIn(650);
                }
                uploadedFile.readAsDataURL(input.files[0]);
            }
        }
        $(this).find('input').on('change', function () {
            proPicURL(this);
        })
    })
});

$(".lang_link").click(function(e){
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');

    let form_id = this.id;
    let lang = form_id.substring(0, form_id.length - 5);

    $("#"+lang+"-form").removeClass('d-none');
    $("#"+lang+"-form1").removeClass('d-none');
    if(lang == 'default')
    {
        $(".default-form").removeClass('d-none');
    }
    else
    {
        $(".from_part_2").addClass('d-none');
    }
});

$(".form-check-input").click(function() {
    if ($(this).val() == 'image') {
        $("#image").removeClass('d-none');
        $("#video").addClass('d-none');
    } else {
        $("#video").removeClass('d-none');
        $("#image").addClass('d-none');
    }
});
