@extends('layouts.admin.app')

@section('title', translate('messages.edit_details'))



@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ translate('messages.new_provider_request') }}
                    </h1></span>
                    </h1>
                </div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a class="btn btn--warning-light font-weight-bold float-right request_alert mb-0" data-url="javascript:"
                        data-message="{{ translate('messages.you_want_to_deny_this_application') }}" href="javascript:"><i
                            class="tio-clear font-weight-bold pr-1"></i>
                        {{ translate('messages.reject') }}</a>
                    <a class="btn btn--primary font-weight-bold float-right mr-2 request_alert mb-0" data-url="javascript:"
                        data-message="{{ translate('messages.you_want_to_approve_this_application') }}"
                        href="javascript:"><i
                            class="tio-done font-weight-bold pr-1"></i>{{ translate('messages.update_&_approve') }}</a>
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

            <div class="row g-2">
                <div class="col-lg-12">
                    <div class="card mt-4">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.General_Info') }}
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
                                                <ul class="nav nav-tabs mb-4">
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
                                                    <div class="form-group">
                                                        <label class="input-label font-semibold"
                                                            for="default_name">{{ translate('messages.name') }}
                                                            ({{ translate('messages.Default') }})
                                                        </label>
                                                        <input type="text" name="name[]" id="default_name"
                                                            class="form-control"
                                                            value="{{ translate('messages.Auto Focus Car Service') }}"
                                                            placeholder="{{ translate('messages.store_name') }}" required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="default">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                            for="exampleFormControlInput1">{{ translate('messages.address') }}
                                                            ({{ translate('messages.default') }})</label>
                                                        <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}"
                                                            class="form-control min-h-90px ckeditor">{{ translate('House: 00, Road: 00, Test City') }}</textarea>
                                                    </div>
                                                </div>
                                                @foreach (json_decode($language) as $lang)
                                                    <div class="d-none lang_form" id="{{ $lang }}-form">
                                                        <div class="form-group">
                                                            <label class="input-label font-semibold"
                                                                for="{{ $lang }}_name">{{ translate('messages.name') }}
                                                                ({{ strtoupper($lang) }})
                                                            </label>
                                                            <input type="text" name="name[]"
                                                                id="{{ $lang }}_name" class="form-control"
                                                                placeholder="{{ translate('messages.store_name') }}">
                                                        </div>
                                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label font-semibold"
                                                                for="exampleFormControlInput1">{{ translate('messages.address') }}
                                                                ({{ strtoupper($lang) }})</label>
                                                            <textarea type="text" name="address[]" placeholder="{{ translate('messages.store') }}"
                                                                class="form-control min-h-90px ckeditor"></textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div id="default-form">
                                                    <div class="form-group">
                                                        <label class="input-label font-semibold"
                                                            for="exampleFormControlInput1">{{ translate('messages.name') }}
                                                            ({{ translate('messages.default') }})</label>
                                                        <input type="text" name="name[]" class="form-control"
                                                            placeholder="{{ translate('messages.store_name') }}" required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="default">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                            for="exampleFormControlInput1">{{ translate('messages.address') }}
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
                                    <div class="d-flex flex-wrap flex-sm-nowrap">
                                        <div class="__custom-upload-img mr-lg-5">
                                            @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                            @php($logo = $logo->value ?? '')
                                            <label class="form-label mb-1">
                                                {{ translate('logo') }}
                                            </label>
                                            <div class="mb-20">
                                                <span>JPG, JPEG, PNG Less Than 1MB <strong class="font-semibold">(Ratio
                                                        1:1)</strong></span>
                                            </div>
                                            <label class="position-relative d-inline-block">
                                                <img class="img--110 min-height-170px min-width-170px onerror-image image--border"
                                                    id="viewer"
                                                    data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                                    src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                    alt="logo image" />
                                                <div class="icon-file-group outside">
                                                    <div class="icon-file rounded-circle">
                                                        <i class="tio-edit"></i>
                                                        <input type="file" name="logo" id="customFileEg1"
                                                            class="custom-file-input"
                                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="__custom-upload-img">
                                            @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                            @php($icon = $icon->value ?? '')
                                            <label class="form-label mb-1">
                                                {{ translate('Cover') }}
                                            </label>
                                            <div class="mb-20">
                                                <span>JPG, JPEG, PNG Less Than 1MB <strong class="font-semibold">(Ratio
                                                        2:1)</strong></span>
                                            </div>
                                            <label class="position-relative d-inline-block">
                                                <img class="img--vertical min-height-170px min-width-170px onerror-image image--border"
                                                    id="coverImageViewer"
                                                    data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                    src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                    alt="Fav icon" />
                                                <div class="icon-file-group outside">
                                                    <div class="icon-file rounded-circle">
                                                        <i class="tio-edit"></i>
                                                        <input type="file" name="cover_photo" id="coverImageUpload"
                                                            class="custom-file-input"
                                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                    </div>
                                                </div>
                                            </label>
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
                                    {{ translate('messages.Business_Info') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 my-0">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label font-semibold"
                                            for="choice_zones">{{ translate('messages.business_zone') }}
                                            <span class="form-label-secondary" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.select_business_zone_for_map') }}">
                                                <i class="tio-info text--title opacity-60"></i>
                                            </span>
                                        </label>
                                        <select name="zone_id" id="choice_zones" required
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.select_zone') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.select_zone') }}</option>
                                            @foreach (\App\Models\Zone::active()->get() as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-5 pickup-zone-tag">
                                        <label class="input-label font-semibold"
                                            for="pickup_zones">{{ translate('messages.pickup_zone') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.select_pickup_zone_for_map') }}">
                                                <i class="tio-info text--title opacity-60"></i>
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
                                    <div class="form-group mb-5">
                                        <label class="input-label font-semibold"
                                            for="tax">{{ translate('messages.Vat/Tax') }}
                                            (%)</label>
                                        <input type="number" name="tax" class="form-control"
                                            placeholder="{{ translate('messages.vat/tax') }}" min="0"
                                            step=".01" required value="5">
                                    </div>
                                    <div class="position-relative">
                                        <label class="input-label font-semibold"
                                            for="tax">{{ translate('Approx. Pickup Time') }}</label>
                                        <input type="text" id="time_view"
                                            value="{{ $delivery_time_start }} to {{ $delivery_time_end }} {{ $delivery_time_type }}"
                                            class="form-control" readonly>
                                        <a href="javascript:void(0)" class="floating-date-toggler">&nbsp;</a>
                                        <span class="offcanvas"></span>
                                        <div class="floating--date" id="floating--date">
                                            <div class="card shadow--card-2">
                                                <div class="card-body">
                                                    <div class="floating--date-inner">
                                                        <div class="item">
                                                            <label class="input-label"
                                                                for="minimum_delivery_time">{{ translate('Minimum Time') }}</label>
                                                            <input id="minimum_delivery_time" type="number"
                                                                name="minimum_delivery_time"
                                                                value="{{ $delivery_time_start }}"
                                                                class="form-control h--45px"
                                                                placeholder="{{ translate('messages.Ex :') }} 30"
                                                                pattern="^[0-9]{2}$" required
                                                                value="{{ old('minimum_delivery_time') }}">
                                                        </div>
                                                        <div class="item">
                                                            <label class="input-label"
                                                                for="maximum_delivery_time">{{ translate('Maximum Time') }}</label>
                                                            <input id="maximum_delivery_time" type="number"
                                                                name="maximum_delivery_time"
                                                                value="{{ $delivery_time_end }}"
                                                                class="form-control h--45px"
                                                                placeholder="{{ translate('messages.Ex :') }} 60"
                                                                pattern="[0-9]{2}" required
                                                                value="{{ old('maximum_delivery_time') }}">
                                                        </div>
                                                        <div class="item smaller">
                                                            <select name="delivery_time_type" id="delivery_time_type"
                                                                class="custom-select">
                                                                <option value="min"
                                                                    {{ $delivery_time_type == 'min' ? 'selected' : '' }}>
                                                                    {{ translate('messages.minutes') }}</option>
                                                                <option value="hours"
                                                                    {{ $delivery_time_type == 'hours' ? 'selected' : '' }}>
                                                                    {{ translate('messages.hours') }}</option>
                                                                <option value="days"
                                                                    {{ $delivery_time_type == 'days' ? 'selected' : '' }}>
                                                                    {{ translate('messages.days') }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="item smaller">
                                                            <button type="button"
                                                                class="btn btn--primary delivery-time">{{ translate('done') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- new --}}
                                        {{-- <div class="input-group multiple-input-group">
                                            <div>
                                                <label for="">{{ translate('messages.Min') }}:</label>
                                                <input type="text" aria-label="Min Pickup Time" value="20"
                                                    placeholder="{{ translate('messages.Ex :') }} 20"
                                                    class="form-control">
                                            </div>
                                            <div>
                                                <label for="">{{ translate('messages.Max') }}:</label>
                                                <input type="text" aria-label="Max Pickup Time" value="30"
                                                    placeholder="{{ translate('messages.Ex :') }} 30"
                                                    class="form-control">
                                            </div>
                                            <div class="input-group-prepend">
                                                <select name="delivery_time_type" id="delivery_time_type"
                                                    class="custom-select">
                                                    <option value="min"
                                                        {{ $delivery_time_type == 'min' ? 'selected' : '' }}>
                                                        {{ translate('messages.minutes') }}</option>
                                                    <option value="hours"
                                                        {{ $delivery_time_type == 'hours' ? 'selected' : '' }}>
                                                        {{ translate('messages.hours') }}</option>
                                                    <option value="days"
                                                        {{ $delivery_time_type == 'days' ? 'selected' : '' }}>
                                                        {{ translate('messages.days') }}</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.search_your_location_here') }}"
                                        type="text" placeholder="{{ translate('messages.search_here') }}" />
                                    <div id="map" class="h-100"></div>
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
                                    {{ translate('messages.owner_information') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="f_name">{{ translate('messages.first_name') }}</label>
                                        <input type="text" name="f_name" class="form-control"
                                            placeholder="{{ translate('messages.first_name') }}" value="Jonathan"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="l_name">{{ translate('messages.last_name') }}</label>
                                        <input type="text" name="l_name" class="form-control"
                                            placeholder="{{ translate('messages.last_name') }}" value="Kent" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="phone">{{ translate('messages.phone') }}</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} 017********" value="123456789"
                                            required>
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
                                    {{ translate('messages.account_information') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} ex@example.com"
                                            value="auto.focus@gmail.com" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="js-form-message form-group mb-0">
                                        <label class="input-label"
                                            for="signupSrPassword">{{ translate('password') }}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}">
                                                <i class="tio-info text--title opacity-60"></i>
                                            </span></label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control"
                                                name="password" id="signupSrPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Your password is invalid. Please try again." value="12345678"
                                                data-hs-toggle-password-options='{
                                            "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                            }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="js-form-message form-group mb-0">
                                        <label class="input-label"
                                            for="signupSrConfirmPassword">{{ translate('messages.Confirm Password') }}</label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control"
                                                name="confirmPassword" id="signupSrConfirmPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Password does not match the confirm password." value="12345678"
                                                data-hs-toggle-password-options='{
                                                "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                "defaultClass": "tio-hidden-outlined",
                                                "showClass": "tio-visible-outlined",
                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                }'>
                                            <div class="js-toggle-password-target-2 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&callback=initMap&v=3.45.8">
    </script>

    <script>
        "use strict";

        $(document).on('ready', function() {
            $('.offcanvas').on('click', function() {
                $('.offcanvas, .floating--date').removeClass('active')
            })
            $('.floating-date-toggler').on('click', function() {
                $('.offcanvas, .floating--date').toggleClass('active')
            })
            @if (isset(auth('admin')->user()->zone_id))
                $('#choice_zones').trigger('change');
            @endif
        });

        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function() {
            readURL(this, 'coverImageViewer');
        });

        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        '{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
        @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
        let myLatlng = {
            lat: {{ $default_location ? $default_location['lat'] : '23.757989' }},
            lng: {{ $default_location ? $default_location['lng'] : '90.360587' }}
        };
        let map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: myLatlng,
        });
        let zonePolygon = null;
        let infoWindow = new google.maps.InfoWindow({
            content: "Click the map to get Lat/Lng!",
            position: myLatlng,
        });
        let bounds = new google.maps.LatLngBounds();

        function initMap() {
            // Create the initial InfoWindow.
            infoWindow.open(map);
            //get current location block
            infoWindow = new google.maps.InfoWindow();
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        myLatlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(myLatlng);
                        infoWindow.setContent("Location found.");
                        infoWindow.open(map);
                        map.setCenter(myLatlng);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
            //-----end block------
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            let markers = [];
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
        initMap();

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(
                browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation."
            );
            infoWindow.open(map);
        }
        $('#choice_zones').on('change', function() {
            let id = $(this).val();
            $.get({
                url: '{{ url('/') }}/admin/zone/get-coordinates/' + id,
                dataType: 'json',
                success: function(data) {
                    if (zonePolygon) {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPaths().forEach(function(path) {
                        path.forEach(function(latlng) {
                            bounds.extend(latlng);
                            map.fitBounds(bounds);
                        });
                    });
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(),
                                null, 2),
                        });
                        let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                            2);
                        coordinates = JSON.parse(coordinates);
                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        });

        $("#vendor_form").on('keydown', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })

        $('#reset_btn').click(function() {
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload.png') }}");
            $('#customFileEg1').val(null);
            $('#coverImageViewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
            $('#coverImageUpload').val(null);
            $('#choice_zones').val(null).trigger('change');
            $('#module_id').val(null).trigger('change');
            zonePolygon.setMap(null);
            $('#coordinates').val(null);
            $('#latitude').val(null);
            $('#longitude').val(null);
        })

        let zone_id = 0;
        $('#choice_zones').on('change', function() {
            if ($(this).val()) {
                zone_id = $(this).val();
            }
        });

        $('#module_id').select2({
            ajax: {
                url: '{{ url('/') }}/store/get-all-modules',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        zone_id: zone_id
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });


        $('.delivery-time').on('click', function() {
            let min = $("#minimum_delivery_time").val();
            let max = $("#maximum_delivery_time").val();
            let type = $("#delivery_time_type").val();
            $("#floating--date").removeClass('active');
            $("#time_view").val(min + ' to ' + max + ' ' + type);

        })
    </script>
@endpush
