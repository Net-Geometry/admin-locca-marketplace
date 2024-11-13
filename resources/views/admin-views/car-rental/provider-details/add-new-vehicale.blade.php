@extends('layouts.admin.app')

@section('title', translate('messages.Provider Details - Add New Vehicale'))



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

            <div class="row g-2">
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
                                                    <div class="form-group">
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
                                                        <div class="form-group">
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
                                                    <div class="form-group">
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
                                    <div class="d-flex flex-wrap flex-sm-nowrap">
                                        <div class="__custom-upload-img mr-lg-5">
                                            @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                            @php($logo = $logo->value ?? '')
                                            <label class="form-label mb-1">
                                                {{ translate('logo') }}
                                            </label>
                                            <div class="mb-20">
                                                <p class="fs-12 max-width-170px">JPG, JPEG, PNG Less Than 1MB <strong
                                                        class="font-semibold">(Ratio
                                                        1:1)</strong></p>
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
                                                <p class="fs-12">
                                                    JPG, JPEG, PNG Less Than 1MB
                                                    <br>
                                                    <strong class="font-semibold">(Ratio
                                                        2:1)</strong>
                                                </p>
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
                                    {{ translate('messages.Vehicle_Information') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row my-0">
                                <div class="col-lg-4">
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Engine Capacity (cc)') }}
                                        </label>
                                        <input type="number" name="" class="form-control" placeholder="Ex: 450"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Engine Power (hp)') }}
                                        </label>
                                        <input type="number" name="" class="form-control" placeholder="Ex: 100"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Seating Capacity') }}
                                        </label>
                                        <input type="number" name="" class="form-control"
                                            placeholder="Input how many person can seat" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                        <div class="custom-group-btn">
                                            <div class="item flex-sm-grow-1">
                                                <label class="floating-label"
                                                    for="min">{{ translate('Min') }}:</label>
                                                <input id="min" type="number" name="min"
                                                    value="{{ $delivery_time_start }}"
                                                    class="form-control h--45px border-0"
                                                    placeholder="{{ translate('messages.Ex :') }} 20"
                                                    pattern="^[0-9]{2}$" required
                                                    value="{{ old('minimum_delivery_time') }}">
                                            </div>
                                            <div class="separator"></div>
                                            <div class="item flex-sm-grow-1">
                                                <label class="floating-label"
                                                    for="max">{{ translate('Max') }}:</label>
                                                <input id="max" type="number" name="max"
                                                    value="{{ $delivery_time_end }}"
                                                    class="form-control h--45px border-0"
                                                    placeholder="{{ translate('messages.Ex :') }} 30" pattern="[0-9]{2}$"
                                                    required value="{{ old('maximum_delivery_time') }}">
                                            </div>
                                            <div class="separator"></div>
                                            <div class="item flex-shrink-0">
                                                <select name="delivery_time_type" id="delivery_time_type"
                                                    class="custom-select border-0">
                                                    <option value="min"
                                                        {{ $delivery_time_type == 'min' ? 'selected' : '' }}>
                                                        {{ translate('messages.minutes') }}
                                                    </option>
                                                    <option value="hours"
                                                        {{ $delivery_time_type == 'hours' ? 'selected' : '' }}>
                                                        {{ translate('messages.hours') }}
                                                    </option>
                                                    <option value="days"
                                                        {{ $delivery_time_type == 'days' ? 'selected' : '' }}>
                                                        {{ translate('messages.days') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

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
                            <div class="row my-0">


                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Distance Wise Price ($)') }}
                                        </label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check mr-2 mr-md-4">
                                                <input class="form-check-input single-select" type="checkbox"
                                                    value="hourly" checked>
                                                <span class="form-check-label">
                                                    {{ translate('messages.hourly') }}
                                                </span>
                                            </label>
                                            <label class="form-check mr-2 mr-md-4">
                                                <input class="form-check-input single-select" type="checkbox"
                                                    value="Distance Wise">
                                                <span class="form-check-label">
                                                    {{ translate('messages.Distance Wise') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Distance Wise Price ($)') }}
                                        </label>
                                        <input type="number" name="" class="form-control"
                                            placeholder="Ex: 35.25" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="">{{ translate('messages.Discount') }}
                                        </label>
                                        <input type="number" name="" class="form-control"
                                            placeholder="Ex: 35.25" value="">
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
                            <div class="form-group mb-5 pickup-zone-tag">
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
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn"
                            class="btn btn--light">{{ translate('messages.reset') }}</button>
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

    <script>
        "use strict";

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
    <script>
        document.querySelectorAll('.single-select').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    document.querySelectorAll('.single-select').forEach((cb) => {
                        if (cb !== this) cb.checked = false;
                    });
                }
            });
        });
    </script>
@endpush
