

"use strict";

    document.addEventListener('DOMContentLoaded', function () {
        var checkboxes = document.querySelectorAll('.dynamic-checkbox');
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
                const type = checkbox.getAttribute('data-type');

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

                if (type == 'form_submit') {
                    $('#toggle-modal').modal('show');
                } else {
                    $('#toggle-status-modal').modal('show');
                }

            });
        });
    });


