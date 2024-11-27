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
        @php
            $delivery_time_start = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode('-', $store->delivery_time)[0]
                : 10;
            $delivery_time_end = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode(' ', explode('-', $store->delivery_time)[1])[0]
                : 30;
            $delivery_time_type = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode(' ', explode('-', $store->delivery_time)[1])[1]
                : 'min';
        @endphp
        @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
        @php($language = $language->value ?? null)
        @php($defaultLang = 'en')
        <!-- End Page Header -->

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div>
                            To view a list of all active zones on your <a href="#" class="text--info text-underline">Admin Landing</a> Page, Enable the <span class="font-semibold">'Available Zones'</span> feature
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-0">
                            <label
                                class="toggle-switch dark h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label text--title opacity-lg">
                                    <span class="line--limit-1">
                                        {{translate('messages.store_temporarily_closed') }}
                                    </span>
                                </span>
                                <input type="checkbox"
                                       data-id="store_temporarily_closed_status"
                                       data-type="toggle"
                                       data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                       data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                       data-title-on="<strong>{{ translate('messages.Want_to_enable_store_temporarily_closed?') }}</strong>"
                                       data-title-off="<strong>{{ translate('messages.Want_to_disable_store_temporarily_closed?') }}</strong>"
                                       data-text-on="<p>{{ translate('messages.If_you_enable_this,_store_will_be_temporarily_closed.') }}</p>"
                                       data-text-off="<p>{{ translate('messages.If_you_disable_this,_store_will__not_be_temporarily_closed.') }}</p>"
                                       class="status toggle-switch-input dynamic-checkbox-toggle"
                                       value="1"
                                    name="store_temporarily_closed_status" id="store_temporarily_closed_status"
                                    checked>
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
                                            {{translate('messages.manage_vehicle_setup') }}
                                        </span>
                                    </span>
                                    <input type="checkbox"
                                           data-id="manage_vehicle_setup_status"
                                           data-type="toggle"
                                           data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                           data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                           data-title-on="<strong>{{ translate('messages.Want_to_enable_manage_vehicle_setup?') }}</strong>"
                                           data-title-off="<strong>{{ translate('messages.Want_to_disable_manage_vehicle_setup?') }}</strong>"
                                           data-text-on="<p>{{ translate('messages.If_you_enable_this,_manage_vehicle_setup_will_be_enabled.') }}</p>"
                                           data-text-off="<p>{{ translate('messages.If_you_disable_this,_manage_vehicle_setup_will_be_disabled.') }}</p>"
                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                           value="1"
                                        name="manage_vehicle_setup_status" id="manage_vehicle_setup_status"
                                        checked>
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
                                            {{translate('messages.scheduled_trip') }}
                                        </span>
                                    </span>
                                    <input type="checkbox"
                                           data-id="scheduled_trip_status"
                                           data-type="toggle"
                                           data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                           data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                           data-title-on="<strong>{{ translate('messages.Want_to_enable_scheduled_trip?') }}</strong>"
                                           data-title-off="<strong>{{ translate('messages.Want_to_disable_scheduled_trip?') }}</strong>"
                                           data-text-on="<p>{{ translate('messages.If_you_enable_this,_scheduled_trip_will_be_enabled.') }}</p>"
                                           data-text-off="<p>{{ translate('messages.If_you_disable_this,_scheduled_trip_will_be_disabled.') }}</p>"
                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                           value="1"
                                        name="scheduled_trip_status" id="scheduled_trip_status"
                                        checked>
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
                                            {{translate('messages.extra_service_charge') }}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.extra_service_charge') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox"
                                           data-id="extra_service_charge_status"
                                           data-type="toggle"
                                           data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                           data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                           data-title-on="<strong>{{ translate('messages.Want_to_enable_extra_service_charge?') }}</strong>"
                                           data-title-off="<strong>{{ translate('messages.Want_to_disable_extra_service_charge?') }}</strong>"
                                           data-text-on="<p>{{ translate('messages.If_you_enable_this,_extra_service_charge_will_be_enabled.') }}</p>"
                                           data-text-off="<p>{{ translate('messages.If_you_disable_this,_extra_service_charge_will_be_disabled.') }}</p>"
                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                           value="1"
                                        name="extra_service_charge_status" id="extra_service_charge_status"
                                        checked>
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
                                            {{translate('messages.When ON guest user can make trip') }}
                                        </span>
                                    </span>
                                    <input type="checkbox"
                                           data-id="gst_status"
                                           data-type="toggle"
                                           data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                           data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                           data-title-on="<strong>{{ translate('messages.Want_to_enable_gst?') }}</strong>"
                                           data-title-off="<strong>{{ translate('messages.Want_to_disable_gst?') }}</strong>"
                                           data-text-on="<p>{{ translate('messages.If_you_enable_this,_gst_will_be_enabled.') }}</p>"
                                           data-text-off="<p>{{ translate('messages.If_you_disable_this,_gst_will_be_disabled.') }}</p>"
                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                           value="1"
                                        name="gst_status" id="gst_status"
                                        checked>
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
                                            {{translate('messages.provider_cancelation_rate') }}
                                        </span>
                                    </span>
                                    <input type="checkbox"
                                           data-id="provider_cancelation_rate_status"
                                           data-type="toggle"
                                           data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                           data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                           data-title-on="<strong>{{ translate('messages.Want_to_enable_provider_cancelation_rate?') }}</strong>"
                                           data-title-off="<strong>{{ translate('messages.Want_to_disable_provider_cancelation_rate?') }}</strong>"
                                           data-text-on="<p>{{ translate('messages.If_you_enable_this,_provider_cancelation_rate_will_be_enabled.') }}</p>"
                                           data-text-off="<p>{{ translate('messages.If_you_disable_this,_provider_cancelation_rate_will_be_disabled.') }}</p>"
                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                           value="1"
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
                                <label class="input-label font-medium"
                                    for="">
                                    {{ translate('messages.Cancelation Rate Limit') }} (%)
                                    <span
                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
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
                                <label class="input-label font-medium"
                                    for="">
                                    {{ translate('messages.Cancelation Rate Warning') }} (%)
                                    <span
                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
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
                                        <input id="min" type="number" name="min"
                                            value=""
                                            class="form-control h--45px border-0 pl-unset"
                                            placeholder="{{ translate('Min') }}: 20"
                                            required
                                            value="">
                                    </div>
                                    <div class="item flex-sm-grow-1">
                                        <input id="max" type="number" name="max"
                                            value=""
                                            class="form-control h--45px border-0 pl-unset"
                                            placeholder="{{ translate('Max') }}: 30" 
                                            required value="">
                                    </div>
                                    <div class="item flex-shrink-0">
                                        <select name="delivery_time_type" id="delivery_time_type"
                                            class="custom-select border-0">
                                            <option value="min"
                                            selected>
                                                {{ translate('messages.minutes') }}
                                            </option>
                                            <option value="hours"
                                                >
                                                {{ translate('messages.hours') }}
                                            </option>
                                            <option value="days"
                                                >
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
                                <select name="pickup_zones[]" id="pickup_zones"
                                    class="form-control  multiple-select2" multiple="multiple">
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
            <div class="card-body" id="schedule">
                {{-- @include('admin-views.vendor.view.partials._schedule', $store) --}}
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <script>
        // Get all upload-file input elements
        document.querySelectorAll('.single_file_input').forEach(function(input) {
            input.addEventListener('change', function(event) {
                var file = event.target.files[0];
                var card = event.target.closest('.upload-file');
                var textbox = card.querySelector('.upload-file__textbox');
                var imgElement = card.querySelector('.upload-file__img__img');

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        textbox.style.display = 'none';
                        imgElement.src = e.target.result;
                        imgElement.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <script>
        // ----- mutiple image upload 
        document.addEventListener("DOMContentLoaded", function() {
            const MAX_FILES = 5;
            const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png"];
            const MAX_FILE_SIZE_MB = 1; // Set maximum file size in MB
            const imageContainer = document.getElementById("image_container");
            const uploadWrapper = document.getElementById("image_upload_wrapper");

            document.querySelector('.multiple_image_input').addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                const currentFiles = imageContainer.querySelectorAll(".image-single").length;

                if (currentFiles + files.length > MAX_FILES) {
                    toastr.error('{{ translate('You can upload a maximum of') }} ' + MAX_FILES +
                        ' {{ translate('files.') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    return;
                }

                files.forEach(file => {
                    // Validate file type
                    if (!ALLOWED_FILE_TYPES.includes(file.type)) {
                        toastr.error(
                            '{{ translate('please_only_input_png_or_jpg_type_file') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        return;
                    }

                    // Validate file size
                    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                        toastr.error('{{ translate('file_size_too_big') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        return;
                    }

                    // Create file preview
                    const fileURL = URL.createObjectURL(file);

                    const imageSingle = document.createElement("div");
                    imageSingle.className = "image-single h-100 max-w-200px p-0";
                    imageSingle.innerHTML = `
                <a href="javascript:void(0);" class="remove-btn" onclick="removeImage(event, this)">
                    <i class="tio-clear"></i>
                </a>
                <img class="img--vertical-2 rounded-10" width="200" height="100" loading="lazy" src="${fileURL}" alt="">
            `;

                    imageContainer.appendChild(imageSingle);

                    // Success notification
                    toastr.success('{{ translate('image_added') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                });

                toggleUploadWrapper();

                // Clear file input after upload
                event.target.value = "";
            });

            window.removeImage = function(event, element) {
                event.stopPropagation();
                const imageSingle = element.closest(".image-single");
                imageSingle.remove();
                toggleUploadWrapper();
            };

            function toggleUploadWrapper() {
                const currentFiles = imageContainer.querySelectorAll(".image-single").length;
                uploadWrapper.style.display = currentFiles >= MAX_FILES ? "none" : "block";
            }
        });
        // ----- mutiple image upload ends

        // ----- mutiple document upload 
        document.addEventListener("DOMContentLoaded", function() {
            const MAX_FILES = 5;
            const pdfContainer = document.getElementById("pdf-container");
            const uploadWrapper = document.getElementById("upload-wrapper");

            document.querySelector('.multiple_document_input').addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                const currentFiles = pdfContainer.querySelectorAll(".pdf-single").length;

                if (currentFiles + files.length > MAX_FILES) {
                    toastr.error(`You can upload a maximum of ${MAX_FILES} files.`, {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                    return;
                }

                files.forEach((file) => {
                    const fileURL = URL.createObjectURL(file);
                    const fileName = file.name;
                    const fileType = file.type;

                    const pdfSingle = document.createElement("div");
                    pdfSingle.className = "pdf-single";
                    pdfSingle.setAttribute("data-pdf-url", fileURL);
                    pdfSingle.setAttribute("onclick", `window.open('${fileURL}', '_blank')`);

                    const iconSrc = fileType.startsWith("image/") ?
                        "{{ asset('public/assets/admin/img/picture.svg') }}" :
                        "{{ asset('public/assets/admin/img/document.svg') }}";

                    pdfSingle.innerHTML = `
                        <div class="pdf-frame">
                            <canvas class="pdf-preview" style="display: none;"></canvas>
                            <img class="pdf-thumbnail" src="{{ asset('public/assets/admin/img/blank2.png') }}" alt="File Thumbnail">
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="remove-btn" onclick="removeDocument(event, this)">
                                <i class="tio-clear"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="${iconSrc}" width="34" alt="File Type Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span class="file-name">${fileName}</span>
                                    <span class="opacity-50">Click to view the file</span>
                                </div>
                            </div>
                        </div>
                    `;

                    pdfContainer.appendChild(pdfSingle);

                    // Call thumbnail renderer
                    renderFileThumbnail(pdfSingle, fileType);

                    // Show success notification
                    toastr.success("File added successfully.", {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                });

                toggleUploadWrapper();

                // Clear file input after upload
                event.target.value = "";
            });

            window.removeDocument = function(event, element) {
                event.stopPropagation();
                const pdfSingle = element.closest(".pdf-single");
                pdfSingle.remove();
                toggleUploadWrapper();
            };

            function toggleUploadWrapper() {
                const currentFiles = pdfContainer.querySelectorAll(".pdf-single").length;
                uploadWrapper.style.display = currentFiles >= MAX_FILES ? "none" : "block";
            }

            async function renderFileThumbnail(element, fileType) {
                const fileUrl = element.getAttribute("data-pdf-url");
                const canvas = element.querySelector(".pdf-preview");
                const thumbnail = element.querySelector(".pdf-thumbnail");

                if (fileType.startsWith("image/")) {
                    // For image files, directly set the thumbnail
                    thumbnail.src = fileUrl;
                } else if (fileType === "application/pdf") {
                    // For PDFs, use PDF.js to render the thumbnail
                    try {
                        const ctx = canvas.getContext("2d");
                        const loadingTask = pdfjsLib.getDocument(fileUrl);
                        const pdf = await loadingTask.promise;
                        const page = await pdf.getPage(1);

                        const viewport = page.getViewport({
                            scale: 0.5
                        });
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        await page.render({
                            canvasContext: ctx,
                            viewport,
                        }).promise;

                        thumbnail.src = canvas.toDataURL();
                    } catch (error) {
                        console.error("Error rendering PDF thumbnail:", error);
                    }
                } else {
                    // Handle unsupported file types (fallback)
                    thumbnail.src = "{{ asset('public/assets/admin/img/blank2.png') }}";
                }

                thumbnail.style.display = "block";
                canvas.style.display = "none";
            }
        });
        // ----- mutiple document upload ends
    </script>

    <script>
        "use strict";
        $(document).on('click', '.add-btn', function() {
            let newDiv = $('#input-container').clone();
            newDiv.find('.add-btn')
                .removeClass('add-btn text--primary')
                .addClass('remove-btn text--danger')
                .html('<i class="tio-clear-circle-outlined"></i>');

            // Append the new div after the last existing input 
            newDiv.insertBefore('.equal-width:last');
        });

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.equal-width').remove();
        });
    </script>
@endpush
