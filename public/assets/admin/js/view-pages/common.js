

"use strict";

    document.addEventListener('DOMContentLoaded', function () {
        let checkboxes = document.querySelectorAll('.dynamic-checkbox');
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function (event) {
                event.preventDefault();
                const checkboxId = checkbox.getAttribute('data-id');
                const imageOn = checkbox.getAttribute('data-image-on');
                const imageOff = checkbox.getAttribute('data-image-off');
                const titleOn = checkbox.getAttribute('data-title-on');
                const titleOff = checkbox.getAttribute('data-title-off');
                const textOn = checkbox.getAttribute('data-text-on');
                const textOff = checkbox.getAttribute('data-text-off');

                const isChecked = checkbox.checked;

                if (isChecked) {
                    $('#toggle-status-title').empty().append(titleOn);
                    $('#toggle-status-message').empty().append(textOn);
                    $('#toggle-status-image').attr('src',imageOn);
                    $('#toggle-status-ok-button').attr('toggle-ok-button', checkboxId);
                    $('#toggle-ok-button').attr('toggle-ok-button', checkboxId);

                    console.log('Checkbox ' + checkboxId + ' is checked');
                } else {
                    $('#toggle-status-title').empty().append(titleOff);
                    $('#toggle-status-message').empty().append(textOff);
                    $('#toggle-status-image').attr('src',imageOff);
                    $('#toggle-status-ok-button').attr('toggle-ok-button', checkboxId);
                    $('#toggle-ok-button').attr('toggle-ok-button', checkboxId);
                    console.log('Checkbox ' + checkboxId + ' is unchecked');
                }


                $('#toggle-status-modal').modal('show');

            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        let checkboxes = document.querySelectorAll('.dynamic-checkbox-toggle');
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function (event) {
                event.preventDefault();
                const checkboxId = checkbox.getAttribute('data-id');
                const imageOn = checkbox.getAttribute('data-image-on');
                const imageOff = checkbox.getAttribute('data-image-off');
                const titleOn = checkbox.getAttribute('data-title-on');
                const titleOff = checkbox.getAttribute('data-title-off');
                const textOn = checkbox.getAttribute('data-text-on');
                const textOff = checkbox.getAttribute('data-text-off');


                const isChecked = checkbox.checked;

                if (isChecked) {
                    $('#toggle-title').empty().append(titleOn);
                    $('#toggle-message').empty().append(textOn);
                    $('#toggle-image').attr('src',imageOn);
                    $('#toggle-ok-button').attr('toggle-ok-button', checkboxId);

                } else {
                    $('#toggle-title').empty().append(titleOff);
                    $('#toggle-message').empty().append(textOff);
                    $('#toggle-image').attr('src',imageOff);
                    $('#toggle-ok-button').attr('toggle-ok-button', checkboxId);
                }

                    $('#toggle-modal').modal('show');
            });
        });
    });


      document.addEventListener('DOMContentLoaded', function () {
        let imageData = document.querySelectorAll('.remove-image');
        imageData.forEach(function (image) {
            image.addEventListener('click', function (event) {
                event.preventDefault();
                const imageId = image.getAttribute('data-id');
                const title = image.getAttribute('data-title');
                const text = image.getAttribute('data-text');

                    $('#toggle-status-title').empty().append(title);
                    $('#toggle-status-message').empty().append(text);
                    $('#toggle-status-ok-button').attr('toggle-ok-button', imageId);
                    $('#toggle-ok-button').attr('toggle-ok-button', imageId);

                $('#toggle-status-modal').modal('show');

            });
        });
    });


    $(".lang_link").click(function(e) {
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');
        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);
        $("#" + lang + "-form").removeClass('d-none');
        $("#" + lang + "-form1").removeClass('d-none');
        $("#" + lang + "-form2").removeClass('d-none');
        if (lang === 'default') {
            $(".default-form").removeClass('d-none');
        }
    });
