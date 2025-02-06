@extends('layouts.admin.app')

@section('title', translate('messages.Add New Vehicle'))

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
                    </h1>
                </div>
            </div>
        </div>
        @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
        @php($language = $language->value ?? null)
        <!-- End Page Header -->

        <form action="" method="post" enctype="multipart/form-data">
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
                                                            ({{ translate('messages.Default') }})<span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="name[]" id="default_name"
                                                               class="form-control"
                                                               value="{{ old('name.0') }}"
                                                               placeholder="{{ translate('messages.type_vehicle_name') }}"
                                                               required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="default">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                               for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                                            ({{ translate('messages.default') }})</label>
                                                        <textarea type="text" name="description[]" placeholder="{{ translate('messages.type_short_description') }}"
                                                                  class="form-control min-h-90px ckeditor">{{ old('description.0') }}</textarea>
                                                    </div>
                                                </div>
                                                @foreach (json_decode($language) as $key => $lang)
                                                    <div class="d-none lang_form" id="{{ $lang }}-form">
                                                        <div class="form-group mb-20">
                                                            <label class="input-label font-semibold"
                                                                   for="{{ $lang }}_name">{{ translate('messages.vehicle_name') }}
                                                                ({{ strtoupper($lang) }})
                                                            </label>
                                                            <input type="text" name="name[]"
                                                                   id="{{ $lang }}_name" class="form-control" value="{{ old('name.'.$key+1) }}"
                                                                   placeholder="{{ translate('messages.vehicle_name') }}">
                                                        </div>
                                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label font-semibold"
                                                                   for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                                                ({{ strtoupper($lang) }})</label>
                                                            <textarea type="text" name="description[]" placeholder="{{ translate('messages.vehicle_description') }}"
                                                                      class="form-control min-h-90px ckeditor">{{ old('description.'.$key+1) }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div id="default-form">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                               for="exampleFormControlInput1">{{ translate('messages.vehicle_name') }}
                                                            ({{ translate('messages.default') }})</label><span class="text-danger">*</span>
                                                        <input type="text" name="name[]" class="form-control"
                                                               placeholder="{{ translate('messages.vehicle_name') }}" required>
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="default">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label font-semibold"
                                                               for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                                        </label>
                                                        <textarea type="text" name="description[]" placeholder="{{ translate('messages.vehicle_description') }}"
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
                                            {{ translate('Vehicle_Thumbnail') }}<span class="text-danger">*</span>
                                        </label>
                                        <div class="mb-20">
                                            <p class="fs-12">
                                                {{ translate('JPG, JPEG, PNG Less Than 1MB') }} <strong class="font-semibold">({{ translate('Ratio 2:1') }})</strong>
                                            </p>
                                        </div>
                                        <div class="upload-file image-general d-inline-block w-auto">
                                            <a href="javascript:void(0);" class="remove-btn opacity-0 z-index-99">
                                                <i class="tio-clear"></i>
                                            </a>
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input"
                                                accept=".webp, .jpg, .jpeg, .png"  value="" required>
                                            <label
                                                class="upload-file-wrapper height-150px max-w-300px aspect-2-1">
                                                <div class="upload-file-textbox text-center w-100">
                                                    <img width="34" height="34" src="{{ asset('public/assets/admin/img/document-upload.svg') }}" alt="">
                                                    <h6 class="mt-2 font-semibold text-center">
                                                        <span>{{ translate('Click to upload') }}</span>
                                                        <br>
                                                        {{ translate('or drag and drop') }}
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img ratio-2" width="300" height="150" loading="lazy" style="display: none;" src="" alt="">
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
                                    {{ translate('messages.Images') }}<span class="text-danger">*</span>
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.JPG, JPEG, PNG Less Than 1MB') }}
                                    <span class="font-semibold"> {{ translate('(Ratio 2:1)') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-body py-1">
                            {{-- <div>
                                <div class="row" id="multiImg"></div>
                            </div> --}}
                            <div class="d-flex pt-20 pb-2 overflow-x-auto">
                               <div class="d-flex gap-3 flex-shrink-0" id="image_container">
                                   <div class="upload-file text-wrapper h--100px w--200px flex-shrink-0"
                                        id="image_upload_wrapper">
                                       <input type="file" name="images[]"
                                              class="upload-file__input multiple_image_input" accept=".webp, .jpg,.jpeg,.png" multiple required>
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
                                        <label class="input-label" for="choice_provider">{{ translate('messages.provider') }}<span class="text-danger">*</span></label>
                                        <select name="provider_id" id="choice_provider" class="form-control js-select2-custom"
                                                data-placeholder="{{ translate('messages.select_vehicle_provider') }}" required>
                                            <option value="" selected disabled>{{ translate('messages.select_vehicle_provider') }}</option>
                                            @foreach($providers as $provider)
                                                <option value="{{ $provider->id }}"
                                                    {{ (old('provider_id') == $provider->id || request()->provider_id == $provider->id) ? 'selected' : '' }}>
                                                {{ $provider->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_brand">{{ translate('messages.brand') }}<span class="text-danger">*</span></label>
                                        <select name="brand_id" id="choice_brand" class="form-control js-select2-custom"
                                                data-placeholder="{{ translate('messages.select_vehicle_brand') }}" required>
                                            <option value="" selected disabled>{{ translate('messages.select_vehicle_brand') }}</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Model') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="model" class="form-control" placeholder="Model Name" value="{{ old('model') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_category">{{ translate('messages.category') }}<span class="text-danger">*</span></label>
                                        <select name="category_id" id="choice_category" class="form-control js-select2-custom"
                                                data-placeholder="{{ translate('messages.select_vehicle_category') }}" required>
                                            <option value="" selected disabled>{{ translate('messages.select_vehicle_category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_type">{{ translate('messages.type') }}<span class="text-danger">*</span></label>
                                        <select name="type" id="choice_type" class="form-control js-select2-custom"
                                                data-placeholder="{{ translate('messages.select_vehicle_type') }}" required>
                                            <option value="" selected disabled>{{ translate('messages.select_vehicle_type') }}</option>
                                            <option value="family" {{ old('type') == 'family' ? 'selected' : '' }}>{{ translate('messages.family') }}</option>
                                            <option value="luxury" {{ old('type') == 'luxury' ? 'selected' : '' }}>{{ translate('messages.Luxury') }}</option>
                                            <option value="affordable" {{ old('type') == 'affordable' ? 'selected' : '' }}>{{ translate('messages.Affordable') }}</option>
                                            <option value="executives" {{ old('type') == 'executives' ? 'selected' : '' }}>{{ translate('messages.Executives') }}</option>
                                            <option value="compact" {{ old('type') == 'compact' ? 'selected' : '' }}>{{ translate('messages.Compact') }}</option>
                                            <option value="full_size" {{ old('type') == 'full_size' ? 'selected' : '' }}>{{ translate('messages.Full-Size') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Engine Capacity (cc)') }}<span class="text-danger">*</span></label>
                                        <input type="number" name="engine_capacity" class="form-control" placeholder="Ex: 450" value="{{ old('engine_capacity') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Engine Power (hp)') }}<span class="text-danger">*</span></label>
                                        <input type="number" name="engine_power" class="form-control" placeholder="Ex: 100" value="{{ old('engine_power') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Seating Capacity') }}<span class="text-danger">*</span></label>
                                        <input type="number" name="seating_capacity" class="form-control" placeholder="Input how many person can seat" value="{{ old('seating_capacity') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Air Condition') }}<span class="text-danger">*</span></label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="1" name="air_condition" {{ old('air_condition') == '1' ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ translate('messages.yes') }}</span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="0" name="air_condition" {{ old('air_condition') == '0' ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ translate('messages.no') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_fuel_type">{{ translate('messages.fuel_type') }}<span class="text-danger">*</span></label>
                                        <select name="fuel_type" id="choice_fuel_type" class="form-control js-select2-custom"
                                                data-placeholder="{{ translate('messages.select_fuel_type') }}" required>
                                            <option value="" selected disabled>{{ translate('messages.select_vehicle_fuel_type') }}</option>
                                            <option value="octan" {{ old('fuel_type') == 'octan' ? 'selected' : '' }}>{{ translate('messages.Octan') }}</option>
                                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>{{ translate('messages.diesel') }}</option>
                                            <option value="CNG" {{ old('fuel_type') == 'CNG' ? 'selected' : '' }}>{{ translate('messages.CNG') }}</option>
                                            <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>{{ translate('messages.Petrol') }}</option>
                                            <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>{{ translate('messages.Electric') }}</option>
                                            <option value="jet_fuel" {{ old('fuel_type') == 'jet_fuel' ? 'selected' : '' }}>{{ translate('messages.Jet Fuel') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="choice_transmission_type">{{ translate('messages.transmission_type') }}<span class="text-danger">*</span></label>
                                        <select name="transmission_type" id="choice_transmission_type" class="form-control js-select2-custom"
                                                data-placeholder="{{ translate('messages.select_vehicle_transmission') }}" required>
                                            <option value="" selected disabled>{{ translate('messages.select_vehicle_transmission') }}</option>
                                            <option value="automatic" {{ old('transmission_type') == 'automatic' ? 'selected' : '' }}>{{ translate('Automatic') }}</option>
                                            <option value="manual" {{ old('transmission_type') == 'manual' ? 'selected' : '' }}>{{ translate('Manual') }}</option>
                                            <option value="continuously_variable" {{ old('transmission_type') == 'continuously_variable' ? 'selected' : '' }}>{{ translate('Continuously Variable') }}</option>
                                            <option value="dual_clutch" {{ old('transmission_type') == 'dual_clutch' ? 'selected' : '' }}>{{ translate('Dual-Clutch') }}</option>
                                            <option value="semi_automatic" {{ old('transmission_type') == 'semi_automatic' ? 'selected' : '' }}>{{ translate('Semi-Automatic') }}</option>
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
                                <input class="form-check-input single-select position-relative m-0" type="checkbox" name="multiple_vehicles">
                            </label>
                        </div>
                        <div class="card-body d-flex flex-column gap-20px">
                            <div class="d-flex gap-20px flex-column flex-md-row equal-width" id="input-container">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                           for="">{{ translate('messages.VIN Number') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="vehicle[vin_number][]" class="form-control"
                                           placeholder="Type your vin number" value="" required>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                           for="">{{ translate('messages.License Plate Number') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="vehicle[license_plate_number][]" class="form-control"
                                           placeholder="Type your license plate number" value="" required>
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
                                        <label class="input-label" for="">{{ translate('messages.Distance Wise Price ($)') }}<span class="text-danger">*</span></label>
                                        <div class="border resturant-type-group">
                                            <label class="align-items-center d-flex form-check item">
                                                <input class="form-check-input single-select" type="checkbox" name="trip_hourly"
                                                       value="hourly" {{ old('trip_hourly') == 'hourly' ? 'checked' : '' }}>
                                                <span class="form-check-label ml-2 mt-1">
                                                    {{ translate('messages.hourly') }}
                                                </span>
                                            </label>
                                            <label class="align-items-center d-flex form-check item">
                                                <input class="form-check-input single-select" type="checkbox" name="trip_distance"
                                                       value="distance_wise" {{ old('trip_distance') == 'distance_wise' ? 'checked' : '' }}>
                                                <span class="form-check-label ml-2 mt-1">
                                                    {{ translate('messages.Distance Wise') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Hourly Wise Price ($/per hour)') }}<span class="text-danger">*</span></label>
                                        <input type="number" name="hourly_price" class="form-control"
                                               placeholder="Ex: 35.25" min="0.01" step="0.01" value="{{ old('hourly_price') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Distance Wise Price ($/per km)') }}<span class="text-danger">*</span></label>
                                        <input type="number" name="distance_price" class="form-control"
                                               placeholder="Ex: 35.25" min="0.01" step="0.01" value="{{ old('distance_price') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label font-semibold" for="">{{ translate('messages.Discount') }}
                                            <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                  data-original-title="{{ translate('messages.The discount value should not be higher than the hourly price or distance-wise price') }}">
                                                <i class="tio-info text--title opacity-60"></i>
                                            </span>
                                        </label>
                                        <div class="custom-group-btn border">
                                            <div class="flex-sm-grow-1">
                                                <input id="discount_input" type="number" name="discount_price" class="form-control h--45px border-0 pl-unset"
                                                       placeholder="Ex: 10" min="0" step="0.001" value="{{ old('discount_price') }}">
                                            </div>
                                            <div class="flex-shrink-0">
                                                <select name="discount_type" id="discount_type" class="custom-select ltr border-0">
                                                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>%</option>
                                                    <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>
                                                        {{ \App\CentralLogics\Helpers::currency_symbol() }}
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
                                <select name="tag[]" id="pickup_zones12"
                                        class="form-control js-select2-custom select2-hidden-accessible" multiple="multiple">
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
                                    {{ translate('messages.Vehicle_Documents') }}<span class="text-danger">*</span>
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
                                        <input type="file" name="documents[]"
                                            class="upload-file__input multiple_document_input" accept="*"
                                            multiple required>
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
        $(document).on('reset', 'form', function() {
            $(this).find('select').each(function() {
                var select = $(this);
                select.val('').trigger('change'); // Reset Select2 dropdowns
            });
        });
        // ---- single image upload starts
        $(document).ready(function () {
            // Handle file input change
            $('.single_file_input').on('change', function (event) {
                var file = event.target.files[0];
                var $card = $(event.target).closest('.upload-file');
                var $textbox = $card.find('.upload-file-textbox');
                var $imgElement = $card.find('.upload-file-img');
                var $removeBtn = $card.find('.remove-btn');

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $textbox.hide();
                        $imgElement.attr('src', e.target.result).show();
                        $removeBtn.css('opacity', 1);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle remove button click
            $('.remove-btn').click(function () {
                var $card = $(this).closest('.upload-file');
                $card.find('.single_file_input').val('');
                $card.find('.upload-file-textbox').show();
                $card.find('.upload-file-img').hide().attr('src', '');
                $(this).css('opacity', 0);
            });

            // Handle reset button click
            $('#reset_btn').click(function () {
                var $cards = $('.upload-file');
                $cards.each(function () {
                    $(this).find('.single_file_input').val('');
                    $(this).find('.upload-file-textbox').show();
                    $(this).find('.upload-file-img').hide().attr('src', '');
                    $(this).find('.remove-btn').css('opacity', 0);
                });
            });
        });
         // ---- single image upload ends
    </script>

    <script>
        // ----- mutiple image upload
        $(document).ready(function () {
            const MAX_FILE_SIZE_MB = 1; // Maximum file size in MB
            const MAX_FILES = 5;
            const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
            const imageContainer = document.getElementById("image_container");
            const imageUploadWrapper = document.getElementById("image_upload_wrapper");
            const inputElement = document.querySelector('.multiple_image_input');
            const fileSet = new Set(); // To keep track of files

            inputElement.addEventListener('change', function (event) {
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
                        toastr.error('{{ translate('please_only_input_png_or_jpg_type_file') }}', {
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

                    // Add to the file set and create preview
                    if (!fileSet.has(file.name)) {
                        fileSet.add(file.name);

                        const fileURL = URL.createObjectURL(file);
                        const imageSingle = document.createElement("div");
                        imageSingle.className = "image-single h-100 max-w-200px p-0";
                        imageSingle.innerHTML = `
                            <a href="javascript:void(0);" class="remove-btn" onclick="removeImage(event, this, '${file.name}')">
                                <i class="tio-clear"></i>
                            </a>
                            <img class="img--vertical-2 rounded-10" width="200" height="100" loading="lazy" src="${fileURL}" alt="">
                        `;
                        imageContainer.appendChild(imageSingle);
                    }
                });

                toggleUploadWrapper();
            });

            window.removeImage = function (event, element, fileName) {
                event.stopPropagation();
                const imageSingle = element.closest(".image-single");
                imageSingle.remove();
                fileSet.delete(fileName); // Remove the file from the set
                toggleUploadWrapper();
            };

            function toggleUploadWrapper() {
                const currentFiles = imageContainer.querySelectorAll(".image-single").length;
                imageUploadWrapper.style.display = currentFiles >= 5 ? "none" : "block";
            }
           // Handle reset button click
           $('#reset_btn').click(function () {
                // Select and remove only the uploaded image elements
                const uploadedImages = imageContainer.querySelectorAll(".image-single");
                uploadedImages.forEach(image => image.remove());

                // Clear the file set
                fileSet.clear();

                // Ensure the upload wrapper is visible
                imageUploadWrapper.style.display = "block";
            });

        });
        // ----- mutiple image upload ends

        // ----- mutiple document upload
        $(document).ready(function () {
            const MAX_FILES = 5;
            const pdfContainer = document.getElementById("pdf-container");
            const documentUploadWrapper = document.getElementById("upload-wrapper");
            const uploadedFiles = new Map(); // Store files with unique names as keys

            // Handle file selection and upload
            document.querySelector('.multiple_document_input').addEventListener('change', function (event) {
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
                    if (!uploadedFiles.has(file.name)) {
                        uploadedFiles.set(file.name, file); // Store the file with its name as the key

                        const fileURL = URL.createObjectURL(file);
                        const fileName = file.name;
                        const fileType = file.type;

                        const pdfSingle = document.createElement("div");
                        pdfSingle.className = "pdf-single";
                        pdfSingle.setAttribute("data-file-name", fileName);
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

                        // Log file details in the console
                        console.log(`File added: ${fileName}, URL: ${fileURL}`);

                        // Render the thumbnail (if applicable)
                        renderFileThumbnail(pdfSingle, fileType);

                        // Show success notification
                        toastr.success("File added successfully.", {
                            CloseButton: true,
                            ProgressBar: true,
                        });
                    }
                });

                toggleUploadWrapper();

                // Clear file input after upload
                // event.target.value = "";

            });

            // Remove document handler
            window.removeDocument = function (event, element) {
                event.stopPropagation();
                const pdfSingle = element.closest(".pdf-single");
                const fileName = pdfSingle.getAttribute("data-file-name");

                // Remove file from the Map
                uploadedFiles.delete(fileName);

                pdfSingle.remove();
                toggleUploadWrapper();
            };

            // Toggle visibility of upload wrapper
            function toggleUploadWrapper() {
                const currentFiles = pdfContainer.querySelectorAll(".pdf-single").length;
                documentUploadWrapper.style.display = currentFiles >= MAX_FILES ? "none" : "block";
            }

            // Render file thumbnail (image, PDF, or other file types)
            async function renderFileThumbnail(element, fileType) {
                const fileUrl = element.getAttribute("onclick").match(/'(.*?)'/)[1];
                const canvas = element.querySelector(".pdf-preview");
                const thumbnail = element.querySelector(".pdf-thumbnail");

                try {
                    if (fileType.startsWith("image/")) {
                        thumbnail.src = fileUrl; // Directly use the image URL
                    } else if (fileType === "application/pdf") {
                        const ctx = canvas.getContext("2d");
                        const loadingTask = pdfjsLib.getDocument(fileUrl);
                        const pdf = await loadingTask.promise;
                        const page = await pdf.getPage(1);

                        const viewport = page.getViewport({ scale: 0.5 });
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        await page.render({ canvasContext: ctx, viewport }).promise;
                        thumbnail.src = canvas.toDataURL(); // Render PDF thumbnail
                    } else {
                        // Use a fallback thumbnail for unsupported file types
                        thumbnail.src = "{{ asset('public/assets/admin/img/blank2.png') }}";
                    }

                    thumbnail.style.display = "block";
                    canvas.style.display = "none";
                } catch (error) {
                    console.error("Error rendering file thumbnail:", error);
                }
            }

            // Handle form submission
            $('form').on('submit', function (e) {
                // e.preventDefault();

                const formData = new FormData(this);

                // Append all files to FormData
                uploadedFiles.forEach((file, fileName) => {
                    formData.append('documents[]', file, fileName);
                });

                // Log form data to the console
                console.log('Files submitted:');
                uploadedFiles.forEach((file, fileName) => {
                    console.log(`${fileName}:`, file);
                });
            });

            // Reset button handler
            $('#reset_btn').click(function () {
                const uploadedDocuments = pdfContainer.querySelectorAll(".pdf-single");
                uploadedDocuments.forEach((doc) => doc.remove());
                uploadedFiles.clear();
                documentUploadWrapper.style.display = "block";
            });
        });

        // ----- mutiple document upload ends
    </script>

    <script>
        "use strict";
        toggleButton();

        $('input[name="multiple_vehicles"]').change(function () {
            toggleButton();
            if (!$(this).is(':checked')) {
                $('.equal-width').not('#input-container').remove();
            }
        });

        function toggleButton() {
            if ($('input[name="multiple_vehicles"]').is(':checked')) {
                $('.add-btn').show();
            } else {
                $('.add-btn').hide();
            }
        }

        $(document).on('click', '.add-btn', function() {
            let newDiv = $('<div class="d-flex gap-20px flex-column flex-md-row equal-width">\
                    <div class="form-group mb-0">\
                        <label class="input-label" for="">{{ translate("messages.VIN Number") }}</label>\
                        <input type="text" name="vehicle[vin_number][]" class="form-control" placeholder="Type your vin number" value="">\
                    </div>\
                    <div class="form-group mb-0">\
                        <label class="input-label" for="">{{ translate("messages.License Plate Number") }}</label>\
                        <input type="text" name="vehicle[license_plate_number][]" class="form-control" placeholder="Type your license plate number" value="">\
                    </div>\
                    <button type="button" class="btn remove-btn shadow-none text--danger p-0 fs-32 lh--1 text-left mt-md-4">\
                        <i class="tio-clear-circle-outlined"></i>\
                    </button>\
                </div>');

            newDiv.insertBefore('.equal-width:last');
        });

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.equal-width').remove();
        });

        $(document).ready(function () {
            const $tripHourly = $('input[name="trip_hourly"]');
            const $tripDistance = $('input[name="trip_distance"]');
            const $hourlyPrice = $('input[name="hourly_price"]');
            const $distancePrice = $('input[name="distance_price"]');

            function updateInputs() {
                if (!$tripHourly.is(':checked')) {
                    $hourlyPrice.prop('disabled', true).val('');
                } else {
                    $hourlyPrice.prop('disabled', false);
                }

                if (!$tripDistance.is(':checked')) {
                    $distancePrice.prop('disabled', true).val('');
                } else {
                    $distancePrice.prop('disabled', false);
                }

                if (!$tripHourly.is(':checked') && !$tripDistance.is(':checked')) {
                    $tripHourly.prop('checked', true);
                    $hourlyPrice.prop('disabled', false);
                }
            }

            $tripHourly.change(updateInputs);
            $tripDistance.change(updateInputs);

            updateInputs();
        });

        $(document).ready(function() {
            $('#pickup_zones12').select2({
                placeholder: "Type and press Enter",
                tags: true,
                tokenSeparators: [',', ' ', ';'],
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term
                    };
                },
                insertTag: function (data, tag) {
                    data.push(tag);
                }
            });
        });

        $(document).ready(function () {
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
    </script>
@endpush
