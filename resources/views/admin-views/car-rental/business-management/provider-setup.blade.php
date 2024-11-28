@extends('layouts.admin.app')

@section('title', translate('messages.Provider_Setup'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-20">
            <h1 class="page-header-title text-break">
                {{ translate('messages.Provider_Setup') }}
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div>
                            To view a list of all active zones on your <a href="#"
                                class="text--info text-underline">Admin Landing</a> Page, Enable the <span
                                class="font-semibold">'Available Zones'</span> feature
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch dark h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                    <span class="line--limit-1">
                                        {{ translate('messages.store_temporarily_closed') }}
                                    </span>
                                </span>
                                <input type="checkbox" data-id="store_temporarily_closed_status" data-type="toggle"
                                    data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                    data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                    data-title-on="<strong>{{ translate('messages.Want_to_enable_store_temporarily_closed?') }}</strong>"
                                    data-title-off="<strong>{{ translate('messages.Want_to_disable_store_temporarily_closed?') }}</strong>"
                                    data-text-on="<p>{{ translate('messages.If_you_enable_this,_store_will_be_temporarily_closed.') }}</p>"
                                    data-text-off="<p>{{ translate('messages.If_you_disable_this,_store_will__not_be_temporarily_closed.') }}</p>"
                                    class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                    name="store_temporarily_closed_status" id="store_temporarily_closed_status" checked>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Basic_Settings') }}
                    </h5>
                    <p class="fs-12 mb-0">
                        {{ translate('messages.Provider Logo & Covers') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch dark h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                        <span class="line--limit-1">
                                            {{ translate('messages.manage_vehicle_setup') }}
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="manage_vehicle_setup_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_manage_vehicle_setup?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_manage_vehicle_setup?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_manage_vehicle_setup_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_manage_vehicle_setup_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="manage_vehicle_setup_status" id="manage_vehicle_setup_status" checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch dark h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                        <span class="line--limit-1">
                                            {{ translate('messages.scheduled_trip') }}
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="scheduled_trip_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_scheduled_trip?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_scheduled_trip?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_scheduled_trip_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_scheduled_trip_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="scheduled_trip_status" id="scheduled_trip_status" checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label class="input-label font-medium"
                                    for="">{{ translate('messages.Minimum Trip Amount (hr)') }}
                                </label>
                                <input type="number" name="" class="form-control" placeholder="Ex: 5 "
                                    value="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch dark h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                        <span class="line--limit-1">
                                            {{ translate('messages.extra_service_charge') }}
                                            <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.extra_service_charge') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="extra_service_charge_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_extra_service_charge?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_extra_service_charge?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_extra_service_charge_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_extra_service_charge_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="extra_service_charge_status" id="extra_service_charge_status" checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label class="input-label font-medium"
                                    for="">{{ translate('messages.Extra Service Charge Amount') }}
                                </label>
                                <input type="text" name="" class="form-control" placeholder="Ex: $100"
                                    value="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch dark h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                        <span class="line--limit-1">
                                            {{ translate('messages.When ON guest user can make trip') }}
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="gst_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_gst?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_gst?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_gst_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_gst_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="gst_status" id="gst_status" checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                        <span class="line--limit-1">
                                            {{ translate('messages.provider_cancelation_rate') }}
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="provider_cancelation_rate_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_provider_cancelation_rate?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_provider_cancelation_rate?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_provider_cancelation_rate_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_provider_cancelation_rate_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="provider_cancelation_rate_status" id="provider_cancelation_rate_status"
                                        checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label class="input-label font-medium" for="">
                                    {{ translate('messages.Cancelation Rate Limit') }} (%)
                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Cancelation Rate Limit') }}">
                                        <i class="tio-info-outined text--title"></i>
                                    </span>
                                </label>
                                <input type="number" name="" class="form-control" placeholder="Ex: 25"
                                    value="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label class="input-label font-medium" for="">
                                    {{ translate('messages.Cancelation Rate Warning') }} (%)
                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Cancelation Rate Warning') }}">
                                        <i class="tio-info-outined text--title"></i>
                                    </span>
                                </label>
                                <input type="number" name="" class="form-control" placeholder="Ex: 20"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative">
                                <label class="input-label font-medium"
                                    for="tax">{{ translate('Approx. Pickup Time') }}</label>
                                <div class="custom-group-btn border">
                                    <div class="item flex-sm-grow-1">
                                        <input id="min" type="number" name="min" value=""
                                            class="form-control h--45px border-0 pl-unset"
                                            placeholder="{{ translate('Min') }}: 20" required value="">
                                    </div>
                                    <div class="item flex-sm-grow-1">
                                        <input id="max" type="number" name="max" value=""
                                            class="form-control h--45px border-0 pl-unset"
                                            placeholder="{{ translate('Max') }}: 30" required value="">
                                    </div>
                                    <div class="item flex-shrink-0">
                                        <select name="delivery_time_type" id="delivery_time_type"
                                            class="custom-select border-0">
                                            <option value="min" selected>
                                                {{ translate('messages.minutes') }}
                                            </option>
                                            <option value="hours">
                                                {{ translate('messages.hours') }}
                                            </option>
                                            <option value="days">
                                                {{ translate('messages.days') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 pickup-zone-tag height-custom">
                                <label class="input-label font-medium"
                                    for="pickup_zones">{{ translate('messages.pickup_zone') }}<span
                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.select_pickup_zone_for_map') }}">
                                        <i class="tio-info-outined text--title"></i>
                                    </span></label>
                                <select name="pickup_zones[]" id="pickup_zones" class="form-control  multiple-select2"
                                    multiple="multiple">
                                    <option value="1" selected>{{ translate('messages.New_York_State') }}
                                    </option>
                                    <option value="2">{{ translate('messages.Washington') }}
                                        State</option>
                                    <option value="3">{{ translate('messages.Chicago_Municipal') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" id="reset_btn"
                                    class="btn btn--reset min-w-120px">{{ translate('messages.reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary min-w-120px">{{ translate('messages.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Provider_Active_Time') }}
                    </h5>
                    <p class="fs-12 mb-0">
                        {{ translate('messages.Provider Logo & Covers') }}
                    </p>
                </div>
            </div>
            <div class="card-body pt-0" id="schedule">
                <div class="schedule-item border-bottom">
                    <span class="btn">Saturday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="d-inline-flex align-items-center gap-4">
                            <div class="d-inline-flex align-items-center">
                                <span class="start--time">
                                    <span class="clock--icon">
                                        <i class="tio-time"></i>
                                    </span>
                                    <span class="info">
                                        <span>Opening time</span>
                                        11:22:am
                                    </span>
                                </span>
                                <span class="end--time">
                                    <span class="clock--icon">
                                        <i class="tio-time"></i>
                                    </span>
                                    <span class="info">
                                        <span>Closing time</span>
                                        12:22:pm
                                    </span>
                                </span>
                                <span class="dismiss--date delete-schedule"
                                    data-url="{{ route('admin.store.remove-schedule', [1]) }}">
                                    <i class="tio-clear-circle-outlined"></i>
                                </span>
                            </div>
                            <div class="d-inline-flex align-items-center">
                                <span class="start--time">
                                    <span class="clock--icon">
                                        <i class="tio-time"></i>
                                    </span>
                                    <span class="info">
                                        <span>Opening time</span>
                                        01:22:pm
                                    </span>
                                </span>
                                <span class="end--time">
                                    <span class="clock--icon">
                                        <i class="tio-time"></i>
                                    </span>
                                    <span class="info">
                                        <span>Closing time</span>
                                        03:22:pm
                                    </span>
                                </span>
                                <span class="dismiss--date delete-schedule"
                                    data-url="{{ route('admin.store.remove-schedule', [1]) }}">
                                    <i class="tio-clear-circle-outlined"></i>
                                </span>
                            </div>
                        </span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="0" data-day="Saturday"><i class="tio-add"></i></span>
                    </div>
                </div>

                <div class="schedule-item border-bottom">
                    <span class="btn">Sunday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="d-inline-flex align-items-center gap-4">
                            <div class="d-inline-flex align-items-center">
                                <span class="start--time">
                                    <span class="clock--icon">
                                        <i class="tio-time"></i>
                                    </span>
                                    <span class="info">
                                        <span>Opening time</span>
                                        09:22:am
                                    </span>
                                </span>
                                <span class="end--time">
                                    <span class="clock--icon">
                                        <i class="tio-time"></i>
                                    </span>
                                    <span class="info">
                                        <span>Closing time</span>
                                        03:22:pm
                                    </span>
                                </span>
                                <span class="dismiss--date delete-schedule"
                                    data-url="{{ route('admin.store.remove-schedule', [0]) }}">
                                    <i class="tio-clear-circle-outlined"></i>
                                </span>
                            </div>
                        </span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="1" data-day="Sunday"><i class="tio-add"></i></span>
                    </div>
                </div>

                <div class="schedule-item border-bottom">
                    <span class="btn">Monday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="btn btn-sm btn-outline-danger m-1 disabled">Offday</span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="1" data-day="Monday"><i class="tio-add"></i></span>
                    </div>
                </div>

                <div class="schedule-item border-bottom">
                    <span class="btn">Tuesday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="btn btn-sm btn-outline-danger m-1 disabled">Offday</span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="2" data-day="Tuesday"><i class="tio-add"></i></span>
                    </div>
                </div>
                <div class="schedule-item border-bottom">
                    <span class="btn">Wednesday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="btn btn-sm btn-outline-danger m-1 disabled">Offday</span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="3" data-day="Wednesday"><i class="tio-add"></i></span>
                    </div>
                </div>

                <div class="schedule-item border-bottom">
                    <span class="btn">Thursday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="btn btn-sm btn-outline-danger m-1 disabled">Offday</span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="4" data-day="Thursday"><i class="tio-add"></i></span>
                    </div>
                </div>

                <div class="schedule-item border-bottom">
                    <span class="btn">Friday</span>
                    <div class="schedult-date-content border-0 py-20">
                        <span class="btn btn-sm btn-outline-danger m-1 disabled">Offday</span>
                        <span class="btn add--primary ml-3" data-toggle="modal" data-target="#exampleModal"
                            data-dayid="5" data-day="Friday"><i class="tio-add"></i></span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Create schedule modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.Create Schedule') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="add-schedule">
                        @csrf
                        <input type="hidden" name="day" id="day_id_input" value="1">
                        <input type="hidden" name="store_id" value="1">
                        <div class="form-group">
                            <label for="recipient-name"
                                class="col-form-label">{{ translate('messages.Start time') }}:</label>
                            <input type="time" class="form-control" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="message-text"
                                class="col-form-label">{{ translate('messages.End time') }}:</label>
                            <input type="time" class="form-control" name="end_time" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ translate('messages.Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Create schedule modal ends -->

@endsection

@push('script_2')
    <script>
        "use strict";

        // ---- time schedule starts
        $(document).ready(function() {
            $('#exampleModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let day_name = button.data('day');
                let day_id = button.data('dayid');
                let modal = $(this);
                modal.find('.modal-title').text('{{ translate('messages.Create Schedule For ') }} ' +
                    day_name);
                modal.find('.modal-body input[name=day]').val(day_id);
            });


        });
        $(document).on('click', '.delete-schedule', function() {
            let route = $(this).data('url');
            Swal.fire({
                title: '<?php echo e(translate('Want_to_delete_this_schedule?')); ?>',
                text: '<?php echo e(translate('If_you_select_Yes,_the_time_schedule_will_be_deleted')); ?>',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '<?php echo e(translate('messages.no')); ?>',
                confirmButtonText: '<?php echo e(translate('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function(data) {
                            if (data.errors) {
                                for (let i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#schedule').empty().html(data.view);
                                toastr.success('<?php echo e(translate('messages.Schedule removed successfully')); ?>', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error('<?php echo e(translate('messages.Schedule not found')); ?>', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                    });
                }
            })
        });

        $('#add-schedule').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.store.add-schedule') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#schedule').empty().html(data.view);
                        $('#exampleModal').modal('hide');
                        toastr.success('{{ translate('messages.Schedule added successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(XMLHttpRequest.responseText, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
        // ---- time schedule ends
    </script>
@endpush
