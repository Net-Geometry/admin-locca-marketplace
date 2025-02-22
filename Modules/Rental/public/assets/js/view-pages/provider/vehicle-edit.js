"use strict";
$(document).ready(function () {
    const MAX_FILE_SIZE_MB = 1;
    const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

    $('.single_file_input').on('change', function (event) {
        var file = event.target.files[0];
        var type = $(this).data('type');
        var size = $(this).data('size');

        if (!ALLOWED_FILE_TYPES.includes(file.type)) {
            toastr.error(type, {
                CloseButton: true,
                ProgressBar: true
            });
            $(this).val('');
            return;
        }

        if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
            toastr.error(size, {
                CloseButton: true,
                ProgressBar: true
            });
            $(this).val('');
            return;
        }

        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var $card = $(event.target).closest('.upload-file');
                $card.find('.upload-file-textbox').hide();
                $card.find('.upload-file-img').attr('src', e.target.result).show();
                $card.find('.remove-btn').css('opacity', 1);
            };
            reader.readAsDataURL(file);
        }
    });

    $('.upload-file').each(function () {
        var $card = $(this);
        var $textbox = $card.find('.upload-file-textbox');
        var $imgElement = $card.find('.upload-file-img');
        var $removeBtn = $card.find('.remove-btn');

        if ($imgElement.attr('src') && $imgElement.attr('src') !== window.location.href) {
            $textbox.hide();
            $imgElement.show();
        }
    });

    $('.thumbnail-remove-btn').click(function () {
        var $card = $(this).closest('.upload-file');
        var img = $(this).data('img');
        $card.find('.single_file_input').val('');
        $card.find('.upload-file-img').attr('src', img);
        $(this).css('opacity', 0);
    });

    $('#reset_btn').click(function () {
        var $cards = $('.upload-file');
        var img = $('.thumbnail-remove-btn').data('img');

        $cards.each(function () {
            $(this).find('.single_file_input').val('');
            $(this).find('.upload-file-img').attr('src', img);
            $(this).find('.remove-btn').css('opacity', 0);
        });
    });

    function getApplicablePrice() {
        let hourlyChecked = $('input[name="trip_hourly"]').is(':checked');
        let distanceChecked = $('input[name="trip_distance"]').is(':checked');
        let hourlyPrice = parseFloat($('input[name="hourly_price"]').val()) || 0;
        let distancePrice = parseFloat($('input[name="distance_price"]').val()) || 0;

        if (hourlyChecked && distanceChecked) {
            return Math.min(hourlyPrice, distancePrice);
        } else if (hourlyChecked) {
            return hourlyPrice;
        } else if (distanceChecked) {
            return distancePrice;
        }
        return 0;
    }

    $('#discount_input').on('input', function () {
        let discountType = $('#discount_type').val();
        let inputValue = parseFloat($(this).val());
        let applicablePrice = getApplicablePrice();

        if (discountType === 'percent' && inputValue >= 100) {
            $(this).val(99);
        } else if (discountType === 'amount' && inputValue > applicablePrice) {
            $(this).val(applicablePrice);
        }
    });

    $('input[name="trip_hourly"], input[name="trip_distance"], input[name="hourly_price"], input[name="distance_price"]').on('change input', function () {
        $('#discount_input').trigger('input');
    });
});
