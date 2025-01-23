@extends('layouts.admin.app')

@section('title', translate('messages.Update Provider'))

@push('css_or_js')
    <style>
        #pac-input1 {
            position: absolute;
            height: 40px;
            border: 1px solid #ddd;
            outline: none;
            box-shadow: none;
            top: 10px !important;
            left: 78% !important;
            transform: translateX(-50%);
            z-index: 5;
            width: 25%;
            padding: 10px;
            font-size: 16px;
        }

    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-20">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ $store->name}}
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

        <form action="" method="post" enctype="multipart/form-data" id="providerFormSubmit">
            @csrf

            {{-- timeline --}}
            <div id="businessSetup">
                <div class="custom-timeline d-flex flex-wrap gap-40px text-title mb-2">
                    <h4 class="single"><span class="count">1</span>{{translate('Business Basic Setup')}}</h4>
                    <h4 class="single opacity-70"><span class="count2">2</span>{{translate('Business Plan Setup')}}</h4>
                </div>

                <div class="row g-2">
                    <div class="col-lg-12">
                        <div class="card mt-4">
                            <div class="card-header">
                                <div>
                                    <h5 class="text-title mb-1">
                                        {{ translate('messages.General_Info') }}
                                    </h5>
                                    <p class="fs-12 mb-0">
                                        {{ translate('messages.Update the basic information of the provider ') }}
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
                                                        <div class="lang_form"
                                                             id="default-form">
                                                            <div class="form-group">
                                                                <label class="input-label"
                                                                       for="default_name">{{ translate('messages.name') }}
                                                                    ({{ translate('messages.Default') }})
                                                                </label>
                                                                <input type="text" name="name[]" id="default_name"
                                                                       class="form-control" placeholder="{{ translate('messages.provider_name') }}" value="{{$store->getRawOriginal('name')}}"
                                                                       required
                                                                >
                                                            </div>
                                                            <input type="hidden" name="lang[]" value="default">
                                                            <div class="form-group mb-0">
                                                                <label class="input-label"
                                                                       for="exampleFormControlInput1">{{ translate('messages.address') }} ({{ translate('messages.default') }})</label>
                                                                <textarea type="text" name="address[]" placeholder="{{translate('messages.provider address')}}" class="form-control min-h-90px ckeditor">{{$store->getRawOriginal('address')}}</textarea>
                                                            </div>
                                                        </div>
                                                        @foreach (json_decode($language) as $lang)
                                                                <?php
                                                                if(count($store['translations'])){
                                                                    $translate = [];
                                                                    foreach($store['translations'] as $t)
                                                                    {
                                                                        if($t->locale == $lang && $t->key=="name"){
                                                                            $translate[$lang]['name'] = $t->value;
                                                                        }
                                                                        if($t->locale == $lang && $t->key=="address"){
                                                                            $translate[$lang]['address'] = $t->value;
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            <div class="d-none lang_form"
                                                                 id="{{ $lang }}-form">
                                                                <div class="form-group">
                                                                    <label class="input-label"
                                                                           for="{{ $lang }}_name">{{ translate('messages.name') }}
                                                                        ({{ strtoupper($lang) }})
                                                                    </label>
                                                                    <input type="text" name="name[]" id="{{ $lang }}_name"
                                                                           class="form-control" value="{{ $translate[$lang]['name']??'' }}" placeholder="{{ translate('messages.provider_name') }}"
                                                                    >
                                                                </div>
                                                                <input type="hidden" name="lang[]" value="{{ $lang }}">
                                                                <div class="form-group mb-0">
                                                                    <label class="input-label"
                                                                           for="exampleFormControlInput1">{{ translate('messages.address') }} ({{ strtoupper($lang) }})</label>
                                                                    <textarea type="text" name="address[]" placeholder="{{translate('messages.provider address')}}" class="form-control min-h-90px ckeditor">{{ $translate[$lang]['address']??'' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div id="default-form">
                                                            <div class="form-group">
                                                                <label class="input-label"
                                                                       for="exampleFormControlInput1">{{ translate('messages.name') }} ({{ translate('messages.default') }})</label>
                                                                <input type="text" name="name[]" class="form-control"
                                                                       placeholder="{{ translate('messages.provider_name') }}" required>
                                                            </div>
                                                            <input type="hidden" name="lang[]" value="default">
                                                            <div class="form-group mb-0">
                                                                <label class="input-label"
                                                                       for="exampleFormControlInput1">{{ translate('messages.address') }}
                                                                </label>
                                                                <textarea type="text" name="address[]" placeholder="{{translate('messages.provider address')}}" class="form-control min-h-90px ckeditor"></textarea>
                                                            </div>
                                                        </div>
                                                    @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex flex-column flex-sm-row gap-4">
                                            <div class="__custom-upload-img mr-lg-5">
                                                @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                                @php($logo = $logo->value ?? '')
                                                <label class="form-label">
                                                    {{ translate('logo') }} <span class="text--primary">({{ translate('1:1') }})</span>
                                                </label>
                                                <label class="text-center position-relative">
                                                    <img class="img--110 min-height-170px min-width-170px onerror-image image--border" id="logoImageViewer"
                                                         data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                         src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}"
                                                         alt="logo image" />
                                                    <div class="icon-file-group">
                                                        <div class="icon-file">
                                                            <i class="tio-edit"></i>
                                                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                                                   accept=".jpg, .png, .jpeg|image/*">
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="__custom-upload-img">
                                                @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                                @php($icon = $icon->value ?? '')
                                                <label class="form-label">
                                                    {{ translate('Cover') }}  <span class="text--primary">({{ translate('3:2') }})</span>
                                                </label>
                                                <label class="text-center position-relative">
                                                    <img class="img--vertical min-height-170px min-width-170px onerror-image image--border" id="coverImageViewer"
                                                         data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                         src="{{ $store->cover_photo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}"
                                                         alt="Fav icon" />
                                                    <div class="icon-file-group">
                                                        <div class="icon-file">
                                                            <i class="tio-edit"></i>
                                                            <input type="file" name="cover_photo" id="coverImageUpload"  class="custom-file-input"
                                                                   accept=".jpg, .png, .jpeg|image/*">
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
                                        {{ translate('messages.Update the necessary information to operate the business') }}
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
                                                      data-original-title="{{ translate('messages.Select the zone from where the business will be operated') }}">
                                                    <i class="tio-info text--title opacity-60"></i>
                                                </span>
                                            </label>
                                            <select name="zone_id" id="choice_zones" required
                                                    class="form-control js-select2-custom"
                                                    data-placeholder="{{ translate('messages.select_zone') }}">
                                                <option value="" selected disabled>{{ translate('messages.select_zone') }}</option>
                                                @foreach(\App\Models\Zone::active()->get() as $zone)
                                                    @if(isset(auth('admin')->user()->zone_id))
                                                        @if(auth('admin')->user()->zone_id == $zone->id)
                                                            <option value="{{$zone->id}}" {{$store->zone_id == $zone->id? 'selected': ''}}>{{$zone->name}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{$zone->id}}" {{$store->zone_id == $zone->id? 'selected': ''}}>{{$zone->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-5 pickup-zone-tag">
                                            <label class="input-label font-semibold"
                                                   for="pickup_zones">{{ translate('messages.pickup_zone') }}<span
                                                    class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.Select zones from where customer can choose their pickup locations for trip booking') }}">
                                                    <i class="tio-info text--title opacity-60"></i>
                                                </span></label>
                                            <select name="pickup_zones[]" id="pickup_zones" class="form-control multiple-select2" multiple="multiple">
                                                @foreach ($zones as $zone)
                                                    <?php
                                                        $pickupZoneIds = json_decode($store->pickup_zone_id) ?? [];
                                                    ?>

                                                    @if (in_array($zone->id, $pickupZoneIds))
                                                        <option value="{{ $zone->id }}" selected>{{ $zone->name }}</option>
                                                    @else
                                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="input-label" for="latitude">{{translate('messages.latitude')}}
                                                <span
                                                    class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{translate('messages.provider_lat_lng_warning')}}">
                                                <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{translate('messages.provider_lat_lng_warning')}}">
                                            </span>
                                            </label>
                                            <input type="text" id="latitude"
                                                   name="latitude" class="form-control"
                                                   placeholder="{{ translate('messages.Ex:') }} -94.22213" value="{{$store->latitude}}" required readonly>
                                        </div>
                                        <div class="form-group mb-5">
                                            <label class="input-label" for="longitude">{{translate('messages.longitude')}}
                                                <span
                                                    class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{translate('messages.provider_lat_lng_warning')}}">
                                                <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{translate('messages.provider_lat_lng_warning')}}">
                                            </span>
                                            </label>
                                            <input type="text"
                                                   name="longitude" class="form-control"
                                                   placeholder="{{ translate('messages.Ex:') }} 103.344322" id="longitude" value="{{$store->longitude}}" required readonly>
                                        </div>
                                        <div class="form-group mb-5">
                                            <label class="input-label font-semibold"
                                                   for="tax">{{ translate('messages.Vat/Tax') }}
                                                (%)</label>
                                            <input type="number" name="tax" class="form-control"
                                                   placeholder="{{ translate('messages.vat/tax') }}" min="0"
                                                   step=".01" required value="{{$store->tax}}">
                                        </div>
                                        <div class="position-relative">
                                            <label class="input-label font-semibold"
                                                   for="tax">{{ translate('Approx. Pickup Time') }}</label>
                                            <div class="custom-group-btn">
                                                <div class="item flex-sm-grow-1">
                                                    <label class="floating-label"
                                                           for="min">{{ translate('Min') }}:</label>
                                                    <input id="min" type="number" name="minimum_delivery_time"
                                                           value="{{ $delivery_time_start }}"
                                                           class="form-control h--45px border-0"
                                                           placeholder="{{ translate('messages.Ex :') }} 20"
                                                           pattern="^[0-9]{2}$" required>
                                                </div>
                                                <div class="separator"></div>
                                                <div class="item flex-sm-grow-1">
                                                    <label class="floating-label"
                                                           for="max">{{ translate('Max') }}:</label>
                                                    <input id="max" type="number" name="maximum_delivery_time"
                                                           value="{{ $delivery_time_end }}"
                                                           class="form-control h--45px border-0"
                                                           placeholder="{{ translate('messages.Ex :') }} 30" pattern="[0-9]{2}$"
                                                           required>
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
                                        <input id="pac-input1" class="controls rounded" data-toggle="tooltip"
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
                                        {{ translate('messages.Update the information of the Owner who operate the business') }}
                                    </p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="input-label" for="f_name">{{translate('messages.first_name')}}</label>
                                            <input type="text" name="f_name" class="form-control" placeholder="{{translate('messages.first_name')}}"
                                                   value="{{$store->vendor->f_name}}"  required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="input-label" for="l_name">{{translate('messages.last_name')}}</label>
                                            <input type="text" name="l_name" class="form-control" placeholder="{{translate('messages.last_name')}}"
                                                   value="{{$store->vendor->l_name}}"  required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="input-label" for="phone">{{translate('messages.phone')}}</label>
                                            <input type="tel" id="phone" name="phone" class="form-control"
                                                   placeholder="{{ translate('messages.Ex:') }} 017********" value="{{$store->vendor->phone}}"
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
                                        {{ translate('messages.Update the necessary information to account information') }}
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
                                                   value="{{$store->email}}" required>
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
                                                       data-msg="Your password is invalid. Please try again."
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
                                                       data-msg="Password does not match the confirm password."
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
                        <div class="btn--container justify-content-end mt-3">
                            <button type="reset" id="reset_btn"
                                    class="btn btn--warning-light min-w-100px justify-content-center">{{ translate('messages.reset') }}</button>
                            <button type="submit"
                                    class="btn btn--primary min-w-100px justify-content-center">{{ translate('messages.update & next') }}</button>
                        </div>
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
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=drawing,places&v=3.45.8">
    </script>

    <script>
        $.fn.select2DynamicDisplay = function () {
            const limit = 100;
            function updateDisplay($element) {
                var $rendered = $element
                    .siblings(".select2-container")
                    .find(".select2-selection--multiple")
                    .find(".select2-selection__rendered");
                var $container = $rendered.parent();
                var containerWidth = $container.width();
                var totalWidth = 0;
                var itemsToShow = [];
                var remainingCount = 0;

                // Get all selected items
                var selectedItems = $element.select2("data");

                // Create a temporary container to measure item widths
                var $tempContainer = $("<div>")
                    .css({
                        display: "inline-block",
                        padding: "0 15px",
                        "white-space": "nowrap",
                        visibility: "hidden",
                    })
                    .appendTo($container);

                // Calculate the width of items and determine how many fit
                selectedItems.forEach(function (item) {
                    var $tempItem = $("<span>")
                        .text(item.text)
                        .css({
                            display: "inline-block",
                            padding: "0 12px",
                            "white-space": "nowrap",
                        })
                        .appendTo($tempContainer);

                    var itemWidth = $tempItem.outerWidth(true);

                    if (totalWidth + itemWidth <= containerWidth - 40) {
                        totalWidth += itemWidth;
                        itemsToShow.push(item);
                    } else {
                        remainingCount = selectedItems.length - itemsToShow.length;
                        return false;
                    }
                });

                $tempContainer.remove();

                const $searchForm = $rendered.find(".select2-search");

                var html = "";
                itemsToShow.forEach(function (item) {
                    html += `<li class="name">
                                        <span>${item.text}</span>
                                        <span class="close-icon" data-id="${item.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                            </svg>
                                        </span>
                                        </li>`;
                });
                if (remainingCount > 0) {
                    html += `<li class="ms-auto">
                                        <div class="more">+${remainingCount}</div>
                                        </li>`;
                }

                if (selectedItems.length < limit) {
                    html += $searchForm.prop("outerHTML");
                }

                $rendered.html(html);

                function debounce(func, wait) {
                    let timeout;
                    return function (...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }

                $(".select2-search input").on(
                    "input",
                    debounce(function () {
                        const inputValue = $(this).val().toLowerCase();
                        const $listItems = $(".select2-results__options li");
                        let matches = 0;

                        $listItems.each(function () {
                            const itemText = $(this).text().toLowerCase();
                            const isMatch = itemText.includes(inputValue);
                            $(this).toggle(isMatch);
                            if (isMatch) matches++;
                        });

                        if (matches === 0) {
                            $(".select2-results__options").append(
                                '<li class="no-results">No results found</li>'
                            );
                        } else {
                            $(".no-results").remove();
                        }
                    }, 100)
                );

                $(".select2-search input").on("keydown", function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        const inputValue = $(this).val().toLowerCase();
                        const $listItems = $(".select2-results__options li:not(.no-results)");
                        const matchedItem = $listItems.filter(function () {
                            return $(this).text().toLowerCase() === inputValue;
                        });

                        if (matchedItem.length > 0) {
                            matchedItem.trigger("mouseup"); // Select the matched item
                        }

                        $(this).val("");
                    }
                });
            }
            return this.each(function () {
                var $this = $(this);

                $this.select2({
                    tags: true,
                    maximumSelectionLength: limit,
                });

                // Bind change event to update display
                $this.on("change", function () {
                    updateDisplay($this);
                });

                // Initial display update
                updateDisplay($this);

                $(window).on("resize", function () {
                    updateDisplay($this);
                });
                $(window).on("load", function () {
                    updateDisplay($this);
                });

                // Handle the click event for the remove icon
                $(document).on(
                    "click",
                    ".select2-selection__rendered .close-icon",
                    function (e) {
                        e.stopPropagation();
                        var $removeIcon = $(this);
                        var itemId = $removeIcon.data("id");
                        var $this2 = $removeIcon
                            .closest(".select2")
                            .siblings(".multiple-select2");
                        $this2.val(
                            $this2.val().filter(function (id) {
                                return id != itemId;
                            })
                        );
                        $this2.trigger("change");
                    }
                );
            });
        };
        $(".multiple-select2").select2DynamicDisplay();
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

        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
        @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)

        let myLatlng = { lat: {{$store->latitude}}, lng: {{$store->longitude}} };
        const map = new google.maps.Map(document.getElementById("map"), {
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
            new google.maps.Marker({
                position: { lat: {{$store->latitude}}, lng: {{$store->longitude}} },
                map,
                title: "{{$store->name}}",
            });
            infoWindow.open(map);
            const input = document.getElementById("pac-input1");
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
        $('.get_zone_data').on('change',function (){
            let id = $(this).val();
            $.get({
                url: '{{url('/')}}/admin/zone/get-coordinates/'+id,
                dataType: 'json',
                success: function (data) {
                    if(zonePolygon)
                    {
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
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                        });
                        let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        })
        $(document).on('ready', function () {
            // Function to update the map
            function updateZone(id) {
                $.get({
                    url: '{{url('/')}}/admin/zone/get-coordinates/' + id,
                    dataType: 'json',
                    success: function (data) {
                        if (zonePolygon) {
                            zonePolygon.setMap(null); // Remove the previous polygon
                        }

                        // Create the new polygon
                        zonePolygon = new google.maps.Polygon({
                            paths: data.coordinates,
                            strokeColor: "#FF0000",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: 'white',
                            fillOpacity: 0,
                        });

                        zonePolygon.setMap(map);

                        // Extend bounds and fit the map
                        zonePolygon.getPaths().forEach(function (path) {
                            path.forEach(function (latlng) {
                                bounds.extend(latlng);
                                map.fitBounds(bounds);
                            });
                        });

                        map.setCenter(data.center);

                        // Add click listener to show info window
                        google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                            infoWindow.close();
                            infoWindow = new google.maps.InfoWindow({
                                position: mapsMouseEvent.latLng,
                                content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                            });

                            let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                            coordinates = JSON.parse(coordinates);

                            document.getElementById('latitude').value = coordinates['lat'];
                            document.getElementById('longitude').value = coordinates['lng'];
                            infoWindow.open(map);
                        });
                    },
                });
            }

            // Initial load when page is ready (for the default zone)
            let id = $('#choice_zones').val();
            updateZone(id);

            // Update when the zone changes
            $('#choice_zones').on('change', function () {
                let newId = $(this).val(); // Get the new zone ID
                updateZone(newId); // Update the map with new coordinates
            });
        });

        $("#vendor_form").on('keydown', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })

        let initialSelectedZones = [];
        $(".multiple-select2 option:selected").each(function () {
            initialSelectedZones.push($(this).val());
        });

        $('#reset_btn').click(function () {
            const defaultLogo = "{{ $store->logo_full_url ?? asset('public/assets/admin/img/upload.png') }}";
            const defaultCoverPhoto = "{{ $store->cover_photo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}";

            if ($('#customFileEg1').val()) {
                $('#logoImageViewer').attr('src', defaultLogo);
                $('#customFileEg1').val(null);
            } else {
                $('#logoImageViewer').attr('src', defaultLogo);
            }

            if ($('#coverImageUpload').val()) {
                $('#coverImageViewer').attr('src', defaultCoverPhoto);
                $('#coverImageUpload').val(null);
            } else {
                $('#coverImageViewer').attr('src', defaultCoverPhoto);
            }

            const zoneValue = "{{ $store->zone_id }}";
            if (zoneValue) {
                $('#choice_zones').val(zoneValue).trigger('change');
            }

            $('#module_id').val(null).trigger('change');
            zonePolygon.setMap(null);
            $('#coordinates').val(null);
            $('#latitude').val(null);
            $('#longitude').val(null);
            $(".multiple-select2").val(initialSelectedZones).trigger("change");
        });


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
        // ---- file upload with textbox
        $(document).ready(function() {
            function handleImageUpload(inputSelector, imgViewerSelector, textBoxSelector) {
                const inputElement = $(inputSelector);

                // Handle input change for file selection
                inputElement.on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Handle drag-and-drop functionality
                const dropZone = inputElement.closest('.image--border');

                dropZone.on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });

                dropZone.on('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });

                dropZone.on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const file = e.originalEvent.dataTransfer.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Apply functionality to each upload element
            handleImageUpload(
                '#coverImageUpload',
                '#coverImageViewer',
                '#coverImageViewer ~ .upload-file__textbox'
            );

            handleImageUpload(
                '#customFileEg1',
                '#logoImageViewer',
                '#logoImageViewer ~ .upload-file__textbox'
            );
        });
        // ---- file upload with textbox ends
    </script>

    <script>
        $(document).on('ready', function() {
            $('.plan-slider').owlCarousel({
                loop: false,
                margin: 30,
                responsiveClass: true,
                nav: false,
                dots: false,
                items: 3,
                center: true,
                startPosition: 1,

                responsive: {
                    0: {
                        items: 1.1,
                        margin: 10,
                    },
                    375: {
                        items: 1.3,
                        margin: 30,
                    },
                    576: {
                        items: 1.7,
                    },
                    768: {
                        items: 2.2,
                        margin: 40,
                    },
                    992: {
                        items: 3,
                        margin: 40,
                    },
                    1200: {
                        items: 4,
                        margin: 40,
                    }
                }
            })

            $('#nextStep').on('click', function () {
                $('#businessSetup').removeClass('d-block').addClass('d-none');
                $('#businessPlan').removeClass('d-none').addClass('d-block');
            });

            $('#backBusinessSetup').on('click', function () {
                $('#businessSetup').removeClass('d-none').addClass('d-block');
                $('#businessPlan').removeClass('d-block').addClass('d-none');
            });
        });

    </script>

    <script>
        $(window).on('load', function() {
            $('input[name="business_plan"]').each(function() {
                if ($(this).is(':checked')) {
                    if ($(this).val() == 'subscription-base') {
                        $('#subscription-plan').show()
                    } else {
                        $('#subscription-plan').hide()
                    }
                }
            })
            $('input[name="package_id"]').each(function() {
                if ($(this).is(':checked')) {
                    $(this).closest('.__plan-item').addClass('active')
                }
            })
        })
        $('input[name="business_plan"]').on('change', function() {
            if ($(this).val() == 'subscription-base') {
                $('#subscription-plan').slideDown()
            } else {
                $('#subscription-plan').slideUp()
            }
        })
        $('input[name="package_id"]').on('change', function() {
            $('input[name="package_id"]').each(function() {
                $(this).closest('.__plan-item').removeClass('active')
            })
            $(this).closest('.__plan-item').addClass('active')
        })
        $('#reset-btn').on('click', function() {
            location.reload()
        })
    </script>
@endpush
