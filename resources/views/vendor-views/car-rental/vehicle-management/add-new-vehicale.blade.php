@extends('layouts.vendor.app')

@section('title', translate('messages.Vehicle List - Add New Vehicale'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-20">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/car-logo.png') }}" alt="">
                        </span>
                        <span>{{ translate('messages.Add New Vehicle') }}
                    </h1></span>
                    </h1>
                </div>
            </div>
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

        <form action="{{ route('admin.store.store') }}" method="post" enctype="multipart/form-data" class="js-validate"
            id="vendor_form">
            @csrf

            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="card mt-4">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.General_Information') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="card __bg-FAFAFA border-0">
                                        <div class="card-body">
                                            @if ($language)
                                                <ul class="nav nav-tabs border-0 mb-4">
                                                    <li class="nav-item">
                                                        <a class="nav-link lang_link active" href="#"
                                                            id="default-link">{{ translate('Default') }}</a>
                                                    </li>
                                                    @foreach (json_decode($language) as $lang)
                                                        <li class="nav-item">
                                                            <a class="nav-link lang_link" href="#"
                                                                id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            @if ($language)
                                                <div class="lang_form" id="default-form">
                                                    <div class="form-group mb-20">
                                                        <label class="input-label font-semibold"
                                                            for="default_name">{{ translate('messages.vehicle_name') }}
                                                            ({{ translate('messages.Default') }})
                                                        </label>
                                                        <input type="text" name="name[]" id="default_name"
                                                            class="form-control"
                                                            value="{{ translate('messages.F Premio 2006') }}"
                                                            placeholder="{{ translate('messages.type_vehicle_name') }}"
                                                            required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="default">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                            for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                                            ({{ translate('messages.default') }})</label>
                                                        <textarea type="text" name="address[]" placeholder="{{ translate('messages.type_business_address') }}"
                                                            class="form-control min-h-90px ckeditor"></textarea>
                                                    </div>
                                                </div>
                                                @foreach (json_decode($language) as $lang)
                                                    <div class="d-none lang_form" id="{{ $lang }}-form">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label font-semibold"
                                                                for="{{ $lang }}_name">{{ translate('messages.vehicle_name') }}
                                                                ({{ strtoupper($lang) }})
                                                            </label>
                                                            <input type="text" name="name[]"
                                                                id="{{ $lang }}_name" class="form-control"
                                                                placeholder="{{ translate('messages.store_name') }}">
                                                        </div>
                                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label font-semibold"
                                                                for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                                                ({{ strtoupper($lang) }})</label>
                                                            <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}"
                                                                class="form-control min-h-90px ckeditor"></textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div id="default-form">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                            for="exampleFormControlInput1">{{ translate('messages.vehicle_name') }}
                                                            ({{ translate('messages.default') }})</label>
                                                        <input type="text" name="name[]" class="form-control"
                                                            placeholder="{{ translate('messages.store_name') }}" required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="default">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                            for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                                        </label>
                                                        <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}"
                                                            class="form-control min-h-90px ckeditor"></textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="text-center">
                                        <label class="text--title fs-16 font-semibold mb-1">
                                            {{ translate('Vehicle_Thumbnail') }}
                                        </label>
                                        <div class="mb-20">
                                            <p class="fs-12">
                                                JPG, JPEG, PNG Less Than 1MB <strong class="font-semibold">(Ratio
                                                    2:1)</strong>
                                            </p>
                                        </div>
                                        <div class="upload-file text-wrapper">
                                            <input type="file" name=""
                                                class="upload-file__input single_file_input" accept=".jpg, .jpeg, .png"
                                                required>
                                            <div
                                                class="upload-file__img d-flex justify-content-center align-items-center height-150px max-w-300px m-auto p-0">
                                                <div class="upload-file__textbox text-center">
                                                    <img width="34" height="34"
                                                        src="{{ asset('public/assets/admin/img/document-upload.png') }}"
                                                        alt="" class="svg">
                                                    <h6 class="mt-2 font-semibold">
                                                        <span class="text-info">{{ translate('Click to upload') }}</span>
                                                        <br>
                                                        {{ translate('or drag and drop') }}
                                                    </h6>
                                                </div>
                                                <img class="upload-file__img__img ratio-2" width="300" height="150"
                                                    loading="lazy" style="display: none;" alt="">
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Images') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.JPG, JPEG, PNG Less Than 1MB') }}
                                    <span class="font-semibold"> {{ translate('(Ratio 2:1)') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-body py-1">
                            <div class="d-flex py-3 overflow-x-auto">
                                <div class="d-flex gap-3 flex-shrink-0" id="image_container">
                                    <div class="upload-file text-wrapper h--100px w--200px flex-shrink-0"
                                        id="image_upload_wrapper">
                                        <input type="file" name="files[]"
                                            class="upload-file__input multiple_image_input" accept=".jpg,.jpeg,.png"
                                            required multiple>
                                        <div
                                            class="upload-file__img d-flex gap-0 justify-content-center align-items-center h-100 max-w-300px p-0">
                                            <div class="upload-file__textbox">
                                                <img width="34" height="34"
                                                    src="{{ asset('public/assets/admin/img/document-upload.png') }}"
                                                    alt="" class="svg">
                                                <h6 class="mt-2 font-semibold">
                                                    <span class="text-info">{{ translate('Click to upload') }}</span><br>
                                                    {{ translate('or drag and drop') }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Vehicle_Information') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_brand">{{ translate('messages.brand') }}
                                        </label>
                                        <select name="" id="choice_brand" class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_vehicle_brand') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_vehicle_brand') }}</option>
                                            <option value="1">{{ translate('messages.brand 1') }}</option>
                                            <option value="2">{{ translate('messages.brand 2') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="choice_category">{{ translate('messages.category') }}
                                        </label>
                                        <select name="" id="choice_category"
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_vehicle_category') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_vehicle_category') }}</option>
                                            <option value="1">{{ translate('messages.category 1') }}</option>
                                            <option value="2">{{ translate('messages.category 2') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_type">{{ translate('messages.type') }}
                                        </label>
                                        <select name="" id="choice_type" class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_vehicle_type') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_vehicle_type') }}</option>
                                            <option value="1">{{ translate('messages.family') }}</option>
                                            <option value="2">{{ translate('messages.office') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Engine Capacity (cc)') }}
                                        </label>
                                        <input type="number" name="" class="form-control" placeholder="Ex: 450"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Engine Power (hp)') }}
                                        </label>
                                        <input type="number" name="" class="form-control" placeholder="Ex: 100"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Seating Capacity') }}
                                        </label>
                                        <input type="number" name="" class="form-control"
                                            placeholder="Input how many person can seat" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Air Condition') }}
                                        </label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="yes"
                                                    name="order_confirmation_model" id="order_confirmation_model"
                                                    checked="">
                                                <span class="form-check-label">
                                                    {{ translate('messages.yes') }}
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="no"
                                                    name="order_confirmation_model" id="order_confirmation_model2">
                                                <span class="form-check-label">
                                                    {{ translate('messages.no') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="choice_fuel_type">{{ translate('messages.fuel_type') }}
                                        </label>
                                        <select name="" id="choice_fuel_type"
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_fuel_type') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_vehicle_fuel_type') }}</option>
                                            <option value="1">{{ translate('messages.diesel') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="choice_transmission_type">{{ translate('messages.transmission_type') }}
                                        </label>
                                        <select name="" id="choice_transmission_type"
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_vehicle_transmission') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_vehicle_transmission') }}</option>
                                            <option value="1">{{ translate('messages.transmission 1') }}</option>
                                            <option value="2">{{ translate('messages.transmission 2') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="choice_break_system">{{ translate('messages.break_system') }}
                                        </label>
                                        <select name="" id="choice_break_system"
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_vehicle_break_system') }}">
                                            <option value="" disabled>
                                                {{ translate('messages.select_vehicle_break_system') }}</option>
                                            <option value="1" selected>{{ translate('messages.Disc Break') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Vehicle Identity') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                            <label class="d-flex align-items-center gap-2">
                                <span class="text--title">
                                    {{ translate('messages.Same Model Multiple Vehicles') }}
                                </span>
                                <input class="form-check-input single-select position-relative m-0" type="checkbox"
                                    value="Same Model Multiple Vehicles" checked>
                            </label>
                        </div>
                        <div class="card-body d-flex flex-column gap-20px">
                            <div class="d-flex gap-20px flex-column flex-md-row equal-width" id="input-container">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="">{{ translate('messages.VIN Number') }}</label>
                                    <input type="text" name="" class="form-control"
                                        placeholder="Type your business name" value="">
                                </div>
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="">{{ translate('messages.License Plate Number') }}</label>
                                    <input type="text" name="" class="form-control"
                                        placeholder="Type your license plate number" value="">
                                </div>
                                <button type="button"
                                    class="btn plus-btn shadow-none text--primary p-0 fs-32 lh--1 text-left mt-md-4 add-btn">
                                    <i class="tio-add-circle-outlined"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Pricing & Discounts') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Distance Wise Price ($)') }}
                                        </label>
                                        <div class="border resturant-type-group">
                                            <label class="align-items-center d-flex form-check item">
                                                <input class="form-check-input single-select" type="checkbox"
                                                    value="hourly" checked="">
                                                <span class="form-check-label ml-2 mt-1">
                                                    {{ translate('messages.hourly') }}
                                                </span>
                                            </label>
                                            <label class="align-items-center d-flex form-check item">
                                                <input class="form-check-input single-select" type="checkbox"
                                                    value="Distance Wise">
                                                <span class="form-check-label ml-2 mt-1">
                                                    {{ translate('messages.Distance Wise') }}
                                                </span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Hourly Wise Price ($)') }}
                                        </label>
                                        <input type="number" name="" class="form-control"
                                            placeholder="Ex: 35.25" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Distance Wise Price ($)') }}
                                        </label>
                                        <input type="number" name="" class="form-control"
                                            placeholder="Ex: 35.25" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label font-semibold"
                                            for="">{{ translate('messages.Discount') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.select_discount') }}">
                                                <i class="tio-info text--title opacity-60"></i>
                                            </span></label>
                                        <div class="custom-group-btn border">
                                            <div class="flex-sm-grow-1">
                                                <input id="min" type="number" name="min"
                                                    class="form-control h--45px border-0 pl-unset"
                                                    placeholder="{{ translate('messages.Ex: 10') }} 20">
                                            </div>
                                            <div class="flex-shrink-0">
                                                <select name="" id="" class="custom-select ltr border-0">
                                                    <option value="1" selected>
                                                        %
                                                    </option>
                                                    <option value="2">
                                                        %
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Search_Tags') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0 pickup-zone-tag">
                                <select name="pickup_zones[]" id="pickup_zones"
                                    class="form-control js-select2-custom select2-hidden-accessible" multiple="multiple">
                                    <option value="1" selected>{{ translate('messages.New_York_State') }}
                                    </option>
                                    <option value="2">{{ translate('messages.Washington') }}
                                        State</option>
                                    <option value="3">{{ translate('messages.Chicago_Municipal') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Vehicle_Documents') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex py-3 overflow-x-auto">
                                <div class="d-flex gap-3 flex-shrink-0" id="pdf-container">
                                    <div class="upload-file text-wrapper document-wrapper" id="upload-wrapper">
                                        <input type="file" name="files[]"
                                            class="upload-file__input multiple_document_input" accept="*" required
                                            multiple>
                                        <div
                                            class="upload-file__img d-flex justify-content-center align-items-center h-100 max-w-300px p-0">
                                            <div class="upload-file__textbox pdf">
                                                <img width="34" height="34"
                                                    src="{{ asset('public/assets/admin/img/document-upload.png') }}"
                                                    alt="" class="svg">
                                                <h6 class="font-semibold">
                                                    <span class="text-info">{{ translate('Click to upload') }}</span><br>
                                                    {{ translate('or drag and drop') }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Uploaded files will be appended here as .pdf-single divs -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
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
