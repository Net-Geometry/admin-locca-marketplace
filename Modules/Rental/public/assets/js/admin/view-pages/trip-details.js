"use strict";
$('.js-select2-custom').each(function() {
    var select2 = $.HSCore.components.HSSelect2.init($(this));
});
$(document).ready(function () {
    $('.assign-vehicle-btn').on('click', function () {
        const detailsId = $(this).data('details_id');
        const vehicleId = $(this).data('vehicle_id');
        const tripId = $(this).data('trip_id');
        const quantity = $(this).data('quantity');
        const imgSrc = $(this).data('img');
        const name = $(this).data('name');
        const vendor = $(this).data('vendor');
        const category = $(this).data('category');
        const brand = $(this).data('brand');
        const list = $(this).data('list');
        const tripVehicleDetails = $(this).data('trip_vehicle_details');

        $('#vehicleImage').attr('src', imgSrc);
        $('#vehicleName').text(name);
        $('#vehicleQuantity').text(quantity);
        $('#vehicleVendor').text(vendor);
        $('#vehicleCategory').text(category);
        $('#vehicleBrand').text(brand);

        const tableBody = $('#assignVehicleModal tbody');
        tableBody.empty();

        let preCheckedIds = [];
        try {
            if (typeof tripVehicleDetails === 'string') {
                preCheckedIds = JSON.parse(tripVehicleDetails).map(item => item.vehicle_identity_id);
            } else {
                preCheckedIds = tripVehicleDetails.map(item => item.vehicle_identity_id);
            }
        } catch (error) {
            console.error('Error parsing trip_vehicle_details:', error);
        }

        if (list && Array.isArray(list)) {
            list.forEach((item, index) => {
                const isChecked = preCheckedIds.includes(item.id) ? 'checked' : '';
                const row = `
        <tr>
            <td>${index + 1}</td>
            <td>${item.vin_number || 'N/A'}</td>
            <td>${item.license_plate_number || 'N/A'}</td>
            <td>
                <div class="d-flex justify-content-center align-items-center">
                    <input class="form-check-input single-select m-auto position-relative" type="checkbox" name="vehicle_identity_ids[]" value="${item.id || ''}" ${isChecked}>
                    <input type="hidden" name="trip_id" value="${tripId}">
                    <input type="hidden" name="vehicle_id" value="${vehicleId}">
                    <input type="hidden" name="details_id" value="${detailsId}">
                </div>
            </td>
        </tr>
        `;
                tableBody.append(row);
            });
        } else {
            tableBody.append('<tr><td colspan="4" class="text-center">No data available</td></tr>');
        }

        let checkedCount = $('.single-select:checked').length;

        $('.single-select').on('change', function () {
            if ($(this).is(':checked')) {
                checkedCount++;
            } else {
                checkedCount--;
            }

            if (checkedCount > quantity) {
                $(this).prop('checked', false);
                checkedCount--;
                toastr.warning(`You can select up to ${quantity} vehicles only.`, '', {
                    closeButton: true,
                    progressBar: true
                });
            }
        });
    });

    let selectedDrivers = {};

    function initializeSelectedDrivers() {
        $('.driver-select').each(function() {
            let vehicleId = $(this).attr('name').match(/\[(.*?)\]/)[1];
            let selectedDriverId = $(this).val();

            if (selectedDriverId) {
                selectedDrivers[vehicleId] = selectedDriverId;
            }
        });

        disableUsedDrivers();
        updateUnassignedVehicleCount();
    }

    function disableUsedDrivers() {
        $('.driver-select option').prop('disabled', false).css('color', '');

        $('.driver-select').each(function() {
            let vehicleId = $(this).attr('name').match(/\[(.*?)\]/)[1];
            let selectedDriverId = selectedDrivers[vehicleId];

            if (selectedDriverId) {
                $('.driver-select').not(this).each(function() {
                    $(this).find(`option[value="${selectedDriverId}"]`).prop('disabled', true).css('color', 'gray');
                });
            }
        });
    }

    function updateUnassignedVehicleCount() {
        let unassignedCount = 0;

        $('.driver-select').each(function() {
            if (!$(this).val()) {
                unassignedCount++;
            }
        });

        $('#vehicle-assign-count').text(unassignedCount);
    }

    $('.assign-driver-modal').on('click', function() {
        $('#assignDriverModal').modal('show');
    });

    $('.driver-select').on('change', function() {
        let selectedDriverId = $(this).val();
        let vehicleId = $(this).attr('name').match(/\[(.*?)\]/)[1];

        if (selectedDriverId) {
            selectedDrivers[vehicleId] = selectedDriverId;
        }

        updateUnassignedVehicleCount();
        disableUsedDrivers();
    });

    initializeSelectedDrivers();

});
