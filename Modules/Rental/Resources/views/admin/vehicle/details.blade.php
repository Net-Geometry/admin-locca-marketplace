@extends('layouts.admin.app')

@section('title', translate('messages.vehicle_details'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('/public/assets/admin/vendor/simplebar/dist/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/assets/admin/vendor/drift-zoom/dist/drift-basic.min.css') }}">

    <style>
        .description-text {
            position: relative;
            overflow: hidden;
        }

        .full-description {
            display: none;
        }

        .see-more {
            color: #1a73e8;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/car-logo.png') }}" alt="">
                        </span>
                        <span>{{ $vehicle->name }}
                    </h1>
                </div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a class="btn btn--cancel h--45px d-flex gap-2 align-items-center form-alert" href="javascript:"
                       data-id="vehicle-{{$vehicle['id']}}" data-message="{{ translate('Want to delete this vehicle') }}" title="{{translate('messages.delete_vehicle')}}">
                        <i class="tio-delete"></i>
                        {{ translate('messages.delete') }}
                    </a>

                    <form action="{{route('admin.rental.provider.vehicle.delete',[$vehicle['id']])}}?vehicle_list={{request()->vehicle_list}}&provider_id={{request()->provider_id}}&provider_vehicle_list={{request()->provider_vehicle_list}}" method="post" id="vehicle-{{$vehicle->id}}">
                        @csrf @method('delete')
                    </form>
                    <a href="javascript:" class="btn btn--reset d-flex justify-content-between align-items-center gap-4 lh--1 h--45px">
                        {{ translate('messages.new_tag') }}
                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckboxNew{{$vehicle->id}}">
                            <input type="checkbox" data-url="{{route('admin.rental.provider.vehicle.new-tag',[$vehicle['id'],$vehicle->new_tag?0:1])}}"
                                   class="toggle-switch-input redirect-url" id="stocksCheckboxNew{{$vehicle->id}}" {{$vehicle->new_tag?'checked':''}}>
                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                        </label>
                    </a>
                    <a href="javascript:" class="btn btn--reset d-flex justify-content-between align-items-center gap-4 lh--1 h--45px">
                        {{ translate('messages.status') }}
                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$vehicle->id}}">
                            <input type="checkbox" data-url="{{route('admin.rental.provider.vehicle.status',[$vehicle['id'],$vehicle->status?0:1])}}"
                                   class="toggle-switch-input redirect-url" id="stocksCheckbox{{$vehicle->id}}" {{$vehicle->status?'checked':''}}>
                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                        </label>
                    </a>
                    <a href="{{ route('admin.rental.provider.vehicle.edit', $vehicle->id)}}" class="btn btn--primary h--45px d-flex gap-2 align-items-center">
                        <i class="tio-edit"></i>
                        {{ translate('messages.Edit_Vechicle') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card mb-20">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="cz-product-gallery mb-20 mb-lg-0">
                            <div class="cz-preview">
                                <div id="sync1" class="owl-carousel owl-theme product-thumbnail-slider">
                                    <div class="owl-item active">
                                        <div class="product-preview-item d-flex align-items-center justify-content-center active"
                                             id="000">
                                            <img class="cz-image-zoom img-responsive w-100"
                                                 src="{{ $vehicle['thumbnailFullUrl'] }}"
                                                 data-zoom="{{ $vehicle['thumbnailFullUrl'] }}"
                                                 alt="Product" width="">
                                            <div class="cz-image-zoom-pane"></div>
                                        </div>
                                    </div>
                                    @foreach($vehicle['imagesFullUrl'] as $key => $img)
                                        <div class="owl-item ">
                                            <div class="product-preview-item d-flex align-items-center justify-content-center active"
                                                 id="image{{$key}}">
                                                <img class="cz-image-zoom img-responsive w-100"
                                                     src="{{ $img }}"
                                                     data-zoom="{{ $img }}"
                                                     alt="Product" width="">
                                                <div class="cz-image-zoom-pane"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="cz">
                                <div class="table-responsive" data-simplebar>
                                    <div class="d-flex">
                                        <div id="sync2" class="owl-carousel owl-theme product-thumb-slider">

                                            <div class="">
                                                <a class="product-preview-thumb color-variants-preview-box-CD5C5C active d-flex align-items-center justify-content-center"
                                                   id="preview-imgCD5C5C" href="#000">
                                                    <img alt="Product"
                                                         src="{{ $vehicle['thumbnailFullUrl'] }}">
                                                </a>
                                            </div>
                                            @foreach($vehicle['imagesFullUrl'] as $key => $img)
                                            <div class="">
                                                <a class="product-preview-thumb color-variants-preview-box-CD5C5C active d-flex align-items-center justify-content-center"
                                                   id="preview-imgCD5C5C" href="#{{$key}}1">
                                                    <img alt="Product"
                                                         src="{{ $img }}">
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <div class="d-flex flex-column-reverse flex-lg-row gap-20px gap-lg-40px">
                                @if ($language)
                                    <ul class="nav nav-tabs border-0 mb-4 flex-grow-1 flex-nowrap">
                                        <li class="nav-item">
                                            <a class="nav-link lang_link active" href="#"
                                               id="default-link">{{ translate('Default') }}</a>
                                        </li>
                                        @foreach ($language as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link lang_link" href="#"
                                                   id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                    <div class="floating-review-wrapper">
                                        <div class="rating--review border rounded">
                                            <h5 class="title border-line font-medium d-flex align-items-center lh--1 mb-0">
                                                <span class="fs-14">
                                                    <span class="font-bold">{{ $avgRating }}</span>
                                                    <span class="color-758590">/5</span>
                                                </span>
                                                <div class="info text--title fs-14">{{ $totalReviews }} {{ translate('Reviews') }}</div>
                                            </h5>
                                        </div>
                                        <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right review-color-progress">
                                            <!-- Review Ratings -->
                                            <li class="d-flex align-items-center font-size-sm">
                                                <span class="progress-name mr-3">{{ translate('Excellent') }}</span>
                                                <div class="progress flex-grow-1">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalRating > 0 ? ($excellentCount / $totalRating) * 100 : 0 }}%;"
                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3">{{ $excellentCount }}</span>
                                            </li>
                                            <!-- End Review Ratings -->

                                            <!-- Review Ratings -->
                                            <li class="d-flex align-items-center font-size-sm">
                                                <span class="progress-name mr-3">{{ translate('Good') }}</span>
                                                <div class="progress flex-grow-1">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalRating > 0 ? ($goodCount / $totalRating) * 100 : 0 }}%;"
                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3">{{ $goodCount }}</span>
                                            </li>
                                            <!-- End Review Ratings -->

                                            <!-- Review Ratings -->
                                            <li class="d-flex align-items-center font-size-sm">
                                                <span class="progress-name mr-3">Average</span>
                                                <div class="progress flex-grow-1">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalRating > 0 ? ($averageCount / $totalRating) * 100 : 0 }}%;"
                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3">{{ $averageCount }}</span>
                                            </li>
                                            <!-- End Review Ratings -->

                                            <!-- Review Ratings -->
                                            <li class="d-flex align-items-center font-size-sm">
                                                <span class="progress-name mr-3">Below average</span>
                                                <div class="progress flex-grow-1">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalRating > 0 ? ($belowAverageCount / $totalRating) * 100 : 0 }}%;"
                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3">{{ $belowAverageCount }}</span>
                                            </li>
                                            <!-- End Review Ratings -->

                                            <!-- Review Ratings -->
                                            <li class="d-flex align-items-center font-size-sm">
                                                <span class="progress-name mr-3">Poor</span>
                                                <div class="progress flex-grow-1">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $totalRating > 0 ? ($poorCount / $totalRating) * 100 : 0 }}%;"
                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3">{{ $poorCount }}</span>
                                            </li>
                                            <!-- End Review Ratings -->
                                        </ul>
                                    </div>
                            </div>
                            @if ($language)
                                <div class="lang_form text--title" id="default-form">
                                    <h3 class="text--title fs-20 ont-bold mb-10px">{{$vehicle?->getRawOriginal('name')}}</h3>
                                    <h5 class="text--title font-semibold opacity-lg mb-10px">{{translate('Description')}}:</h5>
                                    <div class="fs-12 opacity-lg description-text">
                                        <span class="short-description">
                                            {{ Str::limit($vehicle?->getRawOriginal('description'), 1500) }}
                                        </span>
                                                                            <span class="full-description" style="display: none;">
                                            {{$vehicle?->getRawOriginal('description')}}
                                        </span>
                                        <!-- By default, "See more" button is hidden -->
                                        <a href="#" class="text--info font-medium see-more" style="display: none;">
                                            {{translate('See more')}}
                                        </a>
                                    </div>
                                </div>

                                @foreach ($language as $lang)
                                    @php
                                        if(count($vehicle['translations'])){
                                            $translate = [];
                                            foreach($vehicle['translations'] as $t)
                                            {
                                                if($t->locale == $lang && $t->key=="name"){
                                                    $translate[$lang]['name'] = $t->value;
                                                }
                                            }
                                        }
                                    @endphp
                                    <div class="lang_form d-none text--title" id="{{ $lang }}-form">
                                        <h3 class="text--title fs-20 ont-bold mb-10px">{{$translate[$lang]['name']??''}}</h3>
                                        <h5 class="text--title font-semibold opacity-lg mb-10px">{{translate('Description')}}:</h5>
                                        <div class="fs-12 opacity-lg description-text">
                                                <span class="short-description">
                                                    {{ Str::limit($vehicle?->getRawOriginal('description'), 2100) }}
                                                </span>
                                                <span class="full-description" style="display: none;">
                                                    {{$vehicle?->getRawOriginal('description')}}
                                                </span>
                                            <!-- By default, "See more" button is hidden -->
                                            <a href="#" class="text--info font-medium see-more" style="display: none;">
                                                {{translate('See more')}}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-20">
            <div class="col-lg-3 mb-20 mb-lg-0">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <a class="resturant--information-single" href="{{ route('admin.rental.provider.details', $vehicle->provider_id) }}">
                            <img class="img--65 rounded mx-auto mb-3 onerror-image" data-onerror-image=""
                                 src="{{ $vehicle?->provider['logoFullUrl'] }}" alt="Image Description">
                            <div class="text-center text--title">
                                <h5 class="text-capitalize font-semibold text-hover-primary d-block mb-1">
                                    {{ $vehicle?->provider?->name }}
                                </h5>
                                <span class="opacity-lg">
                                    {{ Str::limit($vehicle?->provider?->address, 40) }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card h-100">
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="" class="table table-borderless table-thead-bordered table-nowrap card-table">
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('messages.General_Info') }}</th>
                                <th class="border-0">{{ translate('messages.Fare_&_Discounts') }}</th>
                                <th class="border-0">{{ translate('messages.Other_Features') }}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            <tr>
                                <td>
                                    <div>
                                        <div class="d-flex">
                                            <span class="min-w-110px">{{ translate('Brand') }}</span>
                                            <span class="font-semibold">: {{ Str::limit($vehicle?->brand?->name, 15) }}</span>
                                        </div>

                                        <div class="d-flex">
                                            <span class="min-w-110px">{{ translate('Category') }}</span>
                                            <span class="font-semibold">: {{ Str::limit($vehicle?->category?->name, 15) }}</span>
                                        </div>
                                        <div class="d-flex"><span class="min-w-110px">{{ translate('Type') }}</span><span
                                                class="font-semibold">: {{ translate($vehicle?->type) }}</span></div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($vehicle->trip_hourly)
                                            <div class="d-flex"> <span class="min-w-110px">{{translate('Hourly')}}</span>
                                                <span class="font-semibold">: {{\App\CentralLogics\Helpers::format_currency($vehicle['hourly_price'])}}</span>
                                            </div>
                                        @endif
                                        @if($vehicle->trip_distance)
                                        <div class="d-flex"><span class="min-w-110px">{{ translate('Distance Wise')}}</span>
                                            <span class="font-semibold">:{{\App\CentralLogics\Helpers::format_currency($vehicle['distance_price'])}}</span>
                                        </div>
                                        @endif
                                        <div class="d-flex"><span class="min-w-110px">{{translate('Discount')}}</span><span
                                                class="font-semibold">: {{ $vehicle->discount_price }} {{ $vehicle->discount_type == 'percent' ? '%' : '$' }}</span></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between gap-20px">
                                        <div>
                                            <div class="d-flex"> <span class="min-w-110px">{{translate('Air Condition')}}</span><span
                                                    class="font-semibold">: {{ $vehicle->air_condition ? 'Yes' : 'No' }}</span></div>
                                            <div class="d-flex"><span class="min-w-110px">{{translate('Transmission')}}</span><span
                                                    class="font-semibold">:
                                                        {{ str_replace('_', ' ', translate($vehicle->transmission_type)) }}</span></div>
                                            <div class="d-flex"><span class="min-w-110px">{{translate('Fuel Type')}}</span><span
                                                    class="font-semibold">: {{ translate($vehicle->fuel_type) }}</span></div>
                                        </div>
                                        <div>
                                            <div class="d-flex"> <span class="min-w-110px">{{translate('Engine Capacity')}}</span><span
                                                    class="font-semibold">: {{ $vehicle->engine_capacity }}</span></div>
                                            <div class="d-flex"><span class="min-w-110px">{{translate('Seating Capacity')}}</span><span
                                                    class="font-semibold">:
                                                        {{ $vehicle->seating_capacity }}</span></div>
                                            <div class="d-flex"><span class="min-w-110px">{{translate('Engine Power')}}</span><span
                                                    class="font-semibold">: {{ $vehicle->engine_power }}</span></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <!-- End Table -->
                </div>
            </div>
        </div>

        <div class="card mb-20">
            <!-- Table -->
            <div class="table-responsive">
                <table id="" class="table table-borderless table-thead-bordered table-nowrap card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{ translate('messages.Identity_Info') }}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    <tr>
                        <td>
                            <div class="d-flex gap-20px">
                                @foreach($vehicle->vehicleIdentities as $multi)
                                <div class="flex-grow-1 font-semibold text--title">
                                    <div class="opacity-70 mb-2">{{translate('Vehicle')}} {{ $loop->iteration }}</div>
                                    <div class="border rounded p-3 d-flex gap-4 justify-content-between">
                                        <div>
                                            <div class="fs-12 opacity-60">{{translate('VIN Number')}}</div>
                                            <div>{{ $multi->vin_number }}</div>
                                        </div>
                                        <div>
                                            <div class="fs-12 opacity-60">{{translate('Registration No.')}}</div>
                                            <div>{{ $multi->license_plate_number }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <!-- End Table -->
        </div>
        <div class="card mb-20">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Additional_Documents') }}
                    </h5>
                    <p class="fs-12">
                        {{ translate('messages.Here you can see all images & document for the provider') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    @foreach($vehicle['documentsFullUrl'] as $doc)
                    <div class="pdf-single" data-pdf-url="{{ $doc }}"
                         onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <canvas class="pdf-preview" style="display: none;"></canvas>
                            <img class="pdf-thumbnail" src="{{ $doc }}"
                                 alt="File Thumbnail">
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="download-btn" onclick="downloadPdf(event, this)"
                               title="">
                                <i class="tio-download-to"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="{{ asset('public/assets/admin/img/document.svg') }}" width="34"
                                     alt="Document Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span class="file-name"></span>
                                    <span class="opacity-50">{{translate('Click to view the file')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title text--title">
                        {{ translate('messages.Reviews') }}
                        <span class="badge badge-soft-dark ml-2" id="itemCount">{{ $vehicleReview->total() }}</span>
                    </h5>
                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40 font-semibold"
                           href="javascript:;"
                           data-hs-unfold-options='{
                            "target": "#usersExportDropdown",
                            "type": "css-animation"
                        }'>
                            <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                        </a>

                        <div id="usersExportDropdown"
                             class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                            <a id="export-excel" class="dropdown-item"
                               href="{{ route('admin.rental.provider.vehicle.review.export', ['vehicle_id' => request()->id, 'type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                     src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                     alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                               href="{{ route('admin.rental.provider.vehicle.review.export', ['vehicle_id' => request()->id, 'type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                     src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                     alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                       class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{ translate('sl') }}</th>
                        <th class="border-0">{{ translate('messages.Review_ID') }}</th>
                        <th class="border-0">{{ translate('messages.Customer') }}</th>
                        <th class="border-0">{{ translate('messages.Review') }}</th>
                        <th class="border-0">{{ translate('messages.Date') }}</th>
                        <th class="border-0">{{ translate('messages.Provider_Reply') }}</th>
                        <th class="text-center border-0">{{ translate('messages.Status') }}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($vehicleReview as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>#{{ $review->id }}</td>

                            <td>
                                <div class="table-rest-info d-block">
                                    <div class="info">
                                        <div title="Car Rental Service" class="text--info">
                                            {{ $review->customer->fullName }}
                                        </div>
                                        <div>
                                                <span class="font-light">
                                                    {{ $review->customer->phone }}
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-semibold text--warning">
                                    <i class="fs-13 tio-star"></i>
                                    {{ $review->rating }}
                                </div>
                                @if($review->comment)
                                    <div class="line--limit-2 max-w--220px">
                                        {{ $review->comment  }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $review->reviewDate }}
                                <br>
                                {{ $review->reviewTime }}
                            </td>
                            <td>
                                <div class="line--limit-2 max-w--220px">
                                    {{ $review->reply ? $review->reply : 'N/A' }}
                                </div>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="publishCheckbox{{$review->id}}">
                                    <input type="checkbox" data-url="{{ route('admin.rental.provider.vehicle.review.status', $review->id) }}" class="toggle-switch-input redirect-url"
                                           id="publishCheckbox{{$review->id}}" {{ $review->status ? 'checked' : ''}}>
                                    <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            @if(count($vehicleReview) !== 0)
                <hr>
            @endif
            <div class="page-area mt-3">
                {!! $vehicleReview->appends($_GET)->links() !!}
            </div>
            @if(count($vehicleReview) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
            @endif
            <!-- End Table -->
        </div>
    </div>

    <!--Vehicle delete Modal -->
    <div class="modal fade" id="vehicleDeleteModal" tabindex="-1" role="dialog"
         aria-labelledby="vehicleDeleteModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-2 pb-0 justify-content-end flex-shrink-0">
                    <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body py-6 text-center">
                    <div class="mb-20">
                        <img width="80" class="aspect-ratio-1" src="{{ asset('public/assets/admin/img/modal/delete-icon.png') }}" alt="">
                    </div>
                    <h3 class="font-medium text--title">Confirm Vehicle Deletion</h3>
                    <div class="fs-13">Are you sure you want to delete this Vehicle & remove it permanently?</div>
                    <div class="btn--container justify-content-center mt-5">
                        <button type="reset" id="reset_btn"
                                class="btn btn--cancel min-w-120px">{{ translate('messages.not_now') }}</button>
                        <button type="submit"
                                class="btn btn--primary min-w-120px">{{ translate('messages.yes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection



@push('script_2')
    <script src="{{ asset('/public/assets/admin/vendor/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('/public/assets/admin/vendor/drift-zoom/dist/Drift.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.description-text').each(function () {
                const $descriptionText = $(this);
                const $shortDescription = $descriptionText.find('.short-description');
                const $fullDescription = $descriptionText.find('.full-description');
                const $seeMore = $descriptionText.find('.see-more');

                const fullDescriptionLength = $fullDescription.text().trim().length;

                if (fullDescriptionLength > 1500) {
                    $seeMore.show();
                } else {
                    $seeMore.hide();
                }

                $seeMore.on('click', function (e) {
                    e.preventDefault();

                    $shortDescription.toggle();
                    $fullDescription.toggle();

                    if ($fullDescription.is(':visible')) {
                        $(this).text('See less');
                    } else {
                        $(this).text('See more');
                    }
                });
            });
        });
    </script>

    <script>
        // ----- document view from file
        document.addEventListener("DOMContentLoaded", function() {

            async function renderFileThumbnail(element) {
                const fileUrl = element.getAttribute("data-pdf-url");
                const canvas = element.querySelector(".pdf-preview");
                const thumbnail = element.querySelector(".pdf-thumbnail");
                const fileNameSpan = element.querySelector(".file-name");
                const downloadButton = element.querySelector(".download-btn");

                // Extract file name and extension
                const fullFileName = fileUrl.split('/').pop();
                const fileExtension = fullFileName.split('.').pop().toLowerCase();
                const fileNameWithoutExtension = fullFileName.replace(/\.[^/.]+$/, '');

                // Truncate file name if it's too long
                const truncatedFileName =
                    fileNameWithoutExtension.length > 20 ?
                        `${fileNameWithoutExtension.substring(0, 17)}...` :
                        fileNameWithoutExtension;
                const displayedFileName = `${truncatedFileName}.${fileExtension}`;

                // Set the file name in the UI
                fileNameSpan.textContent = displayedFileName;
                downloadButton.setAttribute("title", fullFileName);

                // Handle PDF thumbnail generation
                if (fileExtension === "pdf") {
                    const ctx = canvas.getContext("2d");

                    try {
                        // Load the PDF using PDF.js
                        const loadingTask = pdfjsLib.getDocument(fileUrl);
                        const pdf = await loadingTask.promise;
                        const page = await pdf.getPage(1);

                        // Set scale and dimensions for the thumbnail
                        const viewport = page.getViewport({
                            scale: 0.5
                        });
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        // Render the first PDF page into the canvas
                        await page.render({
                            canvasContext: ctx,
                            viewport
                        }).promise;

                        // Convert canvas to image URL and set as the thumbnail
                        thumbnail.src = canvas.toDataURL();
                    } catch (error) {
                        console.error("Error rendering PDF thumbnail:", error);
                        // Fallback to blank image if there's an error
                        thumbnail.src = "{{ asset('public/assets/admin/img/blank2.png') }}";
                    }
                } else if (["jpg", "jpeg", "png", "gif", "bmp"].includes(fileExtension)) {
                    // Handle image file types (JPG, PNG, GIF, etc.)
                    thumbnail.src = fileUrl; // Set the image URL as the thumbnail
                } else {
                    // For non-PDF, non-image files (e.g., DOCX, XLSX, etc.)
                    const fileIconPath = `{{ asset('public/assets/admin/img/icons') }}/${fileExtension}.png`;
                    const fallbackIconPath =
                        "{{ asset('public/assets/admin/img/blank2.png') }}"; // Fallback image

                    // Check if a specific icon exists for the file type, otherwise use the fallback
                    const iconExists = await checkFileIconExistence(fileIconPath);

                    thumbnail.src = iconExists ? fileIconPath : fallbackIconPath;
                }

                // Show the thumbnail and hide the canvas
                thumbnail.style.display = "block";
                canvas.style.display = "none";
            }

            // Function to check if the icon exists
            async function checkFileIconExistence(iconPath) {
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => resolve(true); // Icon exists
                    img.onerror = () => resolve(false); // Icon doesn't exist
                    img.src = iconPath;
                });
            }

            // Iterate over all .pdf-single elements to render thumbnails
            document.querySelectorAll(".pdf-single").forEach(renderFileThumbnail);

            // Open the file in a new tab
            window.openPdf = function(element) {
                const fileUrl = element.getAttribute("data-pdf-url");
                window.open(fileUrl, "_blank");
            };

            // Download the file on button click
            window.downloadPdf = function(event, buttonElement) {
                event.stopPropagation();

                const fileUrl = buttonElement.closest(".pdf-single").getAttribute("data-pdf-url");
                const link = document.createElement("a");
                link.href = fileUrl;
                link.download = fileUrl.split("/").pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };

        });
        // ----- document view from file ends
    </script>


    <script>
        function imageZoom() {
            let elements = document.querySelectorAll(".cz-image-zoom");
            for (let i = 0; i < elements.length; i++) {
                new Drift(elements[i], {
                    paneContainer: elements[i].parentElement.querySelector(
                        ".cz-image-zoom-pane"
                    ),
                });
            }
        }

        // Call it initially
        imageZoom();


        const themeDirection = $("html").attr("dir");

        function renderOwlCarouselSilder() {
            var sync1 = $("#sync1");
            var sync2 = $("#sync2");
            var thumbnailItemClass = ".owl-item";
            var slides = sync1.owlCarousel({
                startPosition: 12,
                items: 1,
                loop: false,
                margin: 0,
                mouseDrag: true,
                touchDrag: true,
                pullDrag: false,
                scrollPerPage: true,
                autoplayHoverPause: false,
                nav: false,
                dots: false,
                rtl: themeDirection && themeDirection.toString() === "rtl",
            })
                .on("changed.owl.carousel", syncPosition);

            function syncPosition(el) {
                var owl_slider = $(this).data("owl.carousel");
                var loop = owl_slider.options.loop;

                var current = el.item.index;

                var owl_thumbnail = sync2.data("owl.carousel");
                var itemClass = "." + owl_thumbnail.options.itemClass;

                var thumbnailCurrentItem = sync2
                    .find(itemClass)
                    .removeClass("synced")
                    .eq(current);
                thumbnailCurrentItem.addClass("synced");

                if (!thumbnailCurrentItem.hasClass("active")) {
                    var duration = 500;
                    sync2.trigger("to.owl.carousel", [current, duration, true]);
                }

                // Re-initialize image zoom on the new slide
                setTimeout(function() {
                    imageZoom();
                }, 500); // Wait for the carousel to complete the slide change
            }

            var thumbs = sync2.owlCarousel({
                startPosition: 12,
                items: 4,
                loop: false,
                margin: 10,
                autoplay: false,
                nav: false,
                dots: false,
                rtl: themeDirection && themeDirection.toString() === "rtl",
                responsive: {
                    576: {
                        items: 4,
                    },
                    768: {
                        items: 4,
                    },
                    992: {
                        items: 4,
                    },
                    1200: {
                        items: 5,
                    },
                    1400: {
                        items: 5,
                    },
                },
                onInitialized: function(e) {
                    var thumbnailCurrentItem = $(e.target)
                        .find(thumbnailItemClass)
                        .eq(this._current);
                    thumbnailCurrentItem.addClass("synced");
                },
            })
                .on("click", thumbnailItemClass, function(e) {
                    e.preventDefault();
                    var duration = 500;
                    var itemIndex = $(e.target).parents(thumbnailItemClass).index();
                    sync1.trigger("to.owl.carousel", [itemIndex, duration, true]);
                })
                .on("changed.owl.carousel", function(el) {
                    var number = el.item.index;
                    var owl_slider = sync1.data("owl.carousel");
                    owl_slider.to(number, 500, true);
                });

            sync1.owlCarousel();
        }

        renderOwlCarouselSilder();
    </script>
@endpush
