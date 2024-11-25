@extends('layouts.vendor.app')

@section('title', translate('messages.vehicle_details'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('/public/assets/admin/vendor/simplebar/dist/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/assets/admin/vendor/drift-zoom/dist/drift-basic.min.css') }}">
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
                        <span>{{ translate('messages.F Premio 2006') }}
                    </h1></span>
                    </h1>
                </div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:" class="btn btn--primary float-right px-5 mb-0">
                        {{ translate('messages.edit') }}
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
                                            id="image1">
                                            <img class="cz-image-zoom img-responsive w-100"
                                                src="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                data-zoom="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                alt="Product" width="">
                                            <div class="cz-image-zoom-pane"></div>
                                        </div>
                                    </div>
                                    <div class="owl-item">
                                        <div class="product-preview-item d-flex align-items-center justify-content-center "
                                            id="image2">
                                            <img class="cz-image-zoom img-responsive w-100"
                                                src="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                data-zoom="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                alt="Product" width="">
                                            <div class="cz-image-zoom-pane"></div>
                                        </div>
                                    </div>
                                    <div class="owl-item">
                                        <div class="product-preview-item d-flex align-items-center justify-content-center "
                                            id="image3">
                                            <img class="cz-image-zoom img-responsive w-100"
                                                src="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                data-zoom="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                alt="Product" width="">
                                            <div class="cz-image-zoom-pane"></div>
                                        </div>
                                    </div>
                                    <div class="owl-item">
                                        <div class="product-preview-item d-flex align-items-center justify-content-center "
                                            id="image4">
                                            <img class="cz-image-zoom img-responsive w-100"
                                                src="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                data-zoom="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                                alt="Product" width="">
                                            <div class="cz-image-zoom-pane"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cz">
                                <div class="table-responsive" data-simplebar>
                                    <div class="d-flex">
                                        <div id="sync2" class="owl-carousel owl-theme product-thumb-slider">
                                            <div class="">
                                                <a class="product-preview-thumb color-variants-preview-box-CD5C5C active d-flex align-items-center justify-content-center"
                                                    id="preview-imgCD5C5C" href="#image1">
                                                    <img alt="Product"
                                                        src="{{ asset('public/assets/admin/img/car-demo.png') }}">
                                                </a>
                                            </div>
                                            <div class="">
                                                <a class="product-preview-thumb color-variants-preview-box-9370DB  d-flex align-items-center justify-content-center"
                                                    id="preview-img9370DB" href="#image2">
                                                    <img alt="Product"
                                                        src="{{ asset('public/assets/admin/img/car-demo.png') }}">
                                                </a>
                                            </div>
                                            <div class="">
                                                <a class="product-preview-thumb color-variants-preview-box-98FB98  d-flex align-items-center justify-content-center"
                                                    id="preview-img98FB98" href="#image3">
                                                    <img alt="Product"
                                                        src="{{ asset('public/assets/admin/img/car-demo.png') }}">
                                                </a>
                                            </div>
                                            <div class="">
                                                <a class="product-preview-thumb  d-flex align-items-center justify-content-center"
                                                    id="preview-img3" href="#image4">
                                                    <img alt="Product"
                                                        src="{{ asset('public/assets/admin/img/car-demo.png') }}">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div>
                            <div class="d-flex flex-column-reverse flex-lg-row gap-20px gap-lg-40px">
                                <ul class="nav nav-tabs mb-3 flex-grow-1 flex-nowrap">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active" href="#" id="default-link">Default</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic(SA)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic(SA)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic(SA)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic(SA)</a>
                                    </li>
                                </ul>
                                <div class="floating-review-wrapper">
                                    <div class="rating--review border rounded">
                                        <h5 class="title border-line font-medium d-flex align-items-center lh--1 mb-0">
                                            <span class="fs-14">
                                                <span class="font-bold">4.0</span>
                                                <span class="color-758590">/5</span>
                                            </span>
                                            <div class="info text--title fs-14">2 Reviews</div>
                                        </h5>
                                    </div>
                                    <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right">
                                        <!-- Review Ratings -->
                                        <li class="d-flex align-items-center font-size-sm">
                                            <span class="progress-name mr-3">Excellent</span>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar" role="progressbar" style="width: 20%;"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-3">20</span>
                                        </li>
                                        <!-- End Review Ratings -->

                                        <!-- Review Ratings -->
                                        <li class="d-flex align-items-center font-size-sm">
                                            <span class="progress-name mr-3">Good</span>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar" role="progressbar" style="width: 5%;"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-3">5</span>
                                        </li>
                                        <!-- End Review Ratings -->

                                        <!-- Review Ratings -->
                                        <li class="d-flex align-items-center font-size-sm">
                                            <span class="progress-name mr-3">Average</span>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar" role="progressbar" style="width: 5%;"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-3">5</span>
                                        </li>
                                        <!-- End Review Ratings -->

                                        <!-- Review Ratings -->
                                        <li class="d-flex align-items-center font-size-sm">
                                            <span class="progress-name mr-3">Below average</span>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar" role="progressbar" style="width: 2%;"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-3">2</span>
                                        </li>
                                        <!-- End Review Ratings -->

                                        <!-- Review Ratings -->
                                        <li class="d-flex align-items-center font-size-sm">
                                            <span class="progress-name mr-3">Poor</span>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar" role="progressbar" style="width: 1%;"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-3">1</span>
                                        </li>
                                        <!-- End Review Ratings -->
                                    </ul>
                                </div>
                            </div>
                            <div class="lang_form text--title" id="default-form">
                                <h3 class="text--title fs-20 ont-bold mb-10px">F Premio 2006</h3>
                                <h5 class="text--title font-semibold opacity-lg mb-10px">Description:</h5>
                                <div class="fs-12 opacity-lg">
                                    If you’re looking for a pie with a bit more heft, a meat pizza is a perfect and popular
                                    choice. If you’re looking for If you’re looking for a pie with a bit more heft, a meat
                                    pizza If you’re looking for a pie with a bit more heft, a meat pizza is a perfect and
                                    popular choice. If you’re looking for If you’re looking for a pie with a bit more heft,
                                    a meat pizza If you’re looking for a pie with a bit more heft, a meat pizza is a perfect
                                    and popular choice. If you’re looking for If you’re looking for a pie with a bit more
                                    heft, a meat pizza If you’re looking for a pie with a bit more heft, a meat pizza is a
                                    perfect and popular choice. If you’re looking for If you’re looking for a pie with a bit
                                    more <a href="#" class="text--info font-medium">See more</a>
                                </div>
                            </div>
                            <div class="lang_form d-none text--title" id="en-form">
                                <h3 class="text--title fs-20 ont-bold mb-10px">F Premio 2006</h3>
                                <h5 class="text--title font-semibold opacity-lg mb-10px">Description:</h5>
                                <div class="fs-12 opacity-lg">
                                    If you’re looking for a pie with a bit more heft, a meat pizza is a perfect and popular
                                    choice. If you’re looking for If you’re looking for a pie with a bit more heft, a meat
                                    pizza If you’re looking for a pie with a bit more heft, a meat pizza is a perfect and
                                    popular choice. If you’re looking for If you’re looking for a pie with a bit more heft,
                                    a meat pizza If you’re looking for a pie with a bit more heft, a meat pizza is a perfect
                                    and popular choice. If you’re looking for If you’re looking for a pie with a bit more
                                    heft, a meat pizza If you’re looking for a pie with a bit more heft, a meat pizza is a
                                    perfect and popular choice. If you’re looking for If you’re looking for a pie with a bit
                                    more <span class="text--primary font-medium">See more</span>
                                </div>
                            </div>
                            <div class="lang_form d-none text--title" id="ar-form">
                                <h3 class="text--title fs-20 ont-bold mb-10px">F Premio 2006</h3>
                                <h5 class="text--title font-semibold opacity-lg mb-10px">Description:</h5>
                                <div class="fs-12 opacity-lg">
                                    If you’re looking for a pie with a bit more heft, a meat pizza is a perfect and popular
                                    choice. If you’re looking for If you’re looking for a pie with a bit more heft, a meat
                                    pizza If you’re looking for a pie with a bit more heft, a meat pizza is a perfect and
                                    popular choice. If you’re looking for If you’re looking for a pie with a bit more heft,
                                    a meat pizza If you’re looking for a pie with a bit more heft, a meat pizza is a perfect
                                    and popular choice. If you’re looking for If you’re looking for a pie with a bit more
                                    heft, a meat pizza If you’re looking for a pie with a bit more heft, a meat pizza is a
                                    perfect and popular choice. If you’re looking for If you’re looking for a pie with a bit
                                    more <span class="text--primary font-medium">See more</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-20">
            <div class="col-lg-12">
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
                                            <div class="d-flex"> <span class="min-w-110px">Brand</span><span
                                                    class="font-semibold">: Toyota</span></div>
                                            <div class="d-flex"><span class="min-w-110px">Category</span><span
                                                    class="font-semibold">: SUV</span></div>
                                            <div class="d-flex"><span class="min-w-110px">Type</span><span
                                                    class="font-semibold">: Family</span></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="d-flex"> <span class="min-w-110px">Hourly</span><span
                                                    class="font-semibold">: $ 400</span></div>
                                            <div class="d-flex"><span class="min-w-110px">KM Wise</span><span
                                                    class="font-semibold">:
                                                    $ 30</span></div>
                                            <div class="d-flex"><span class="min-w-110px">Discount</span><span
                                                    class="font-semibold">: 30%</span></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between gap-20px">
                                            <div class="flex-grow-1">
                                                <div class="d-flex"> <span class="min-w-110px">Air Condition</span><span
                                                        class="font-semibold">: Yes</span></div>
                                                <div class="d-flex"><span class="min-w-110px">Transmission</span><span
                                                        class="font-semibold">:
                                                        Manual Gear</span></div>
                                                <div class="d-flex"><span class="min-w-110px">Fuel Type</span><span
                                                        class="font-semibold">: Diesel</span></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex"> <span class="min-w-110px">Engine Capacity</span><span
                                                        class="font-semibold">: 1100 cc</span></div>
                                                <div class="d-flex"><span class="min-w-110px">Break System</span><span
                                                        class="font-semibold">:
                                                        Hydraulic ABS</span></div>
                                                <div class="d-flex"><span class="min-w-110px">Engine Power</span><span
                                                        class="font-semibold">: 250hp</span></div>
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
                                    <div class="flex-grow-1 font-semibold text--title">
                                        <div class="opacity-70 mb-2">Vehicle 1</div>
                                        <div class="border rounded p-3 d-flex gap-4 justify-content-between">
                                            <div>
                                                <div class="fs-12 opacity-60">VIN Number</div>
                                                <div>123456123578</div>
                                            </div>
                                            <div>
                                                <div class="fs-12 opacity-60">Registration No.</div>
                                                <div>Nator Kha 21-3214</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 font-semibold text--title">
                                        <div class="opacity-70 mb-2">Vehicle 1</div>
                                        <div class="border rounded p-3 d-flex gap-4 justify-content-between">
                                            <div>
                                                <div class="fs-12 opacity-60">VIN Number</div>
                                                <div>123456123578</div>
                                            </div>
                                            <div>
                                                <div class="fs-12 opacity-60">Registration No.</div>
                                                <div>Nator Kha 21-3214</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 font-semibold text--title">
                                        <div class="opacity-70 mb-2">Vehicle 1</div>
                                        <div class="border rounded p-3 d-flex gap-4 justify-content-between">
                                            <div>
                                                <div class="fs-12 opacity-60">VIN Number</div>
                                                <div>123456123578</div>
                                            </div>
                                            <div>
                                                <div class="fs-12 opacity-60">Registration No.</div>
                                                <div>Nator Kha 21-3214</div>
                                            </div>
                                        </div>
                                    </div>
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
                    <div class="pdf-single" data-pdf-url="{{ asset('public/assets/admin/img/pdf/sample.pdf') }}"
                        onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <canvas class="pdf-preview" style="display: none;"></canvas>
                            <img class="pdf-thumbnail" src="{{ asset('public/assets/admin/img/blank2.png') }}"
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
                                    <span class="opacity-50">Click to view the file</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pdf-single" data-pdf-url="{{ asset('public/assets/admin/img/profile.jpg') }}"
                        onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <canvas class="pdf-preview" style="display: none;"></canvas>
                            <img class="pdf-thumbnail" src="{{ asset('public/assets/admin/img/blank2.png') }}"
                                alt="File Thumbnail">
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="download-btn" onclick="downloadPdf(event, this)"
                                title="">
                                <i class="tio-download-to"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="{{ asset('public/assets/admin/img/picture.svg') }}" width="34"
                                    alt="Document Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span class="file-name"></span>
                                    <span class="opacity-50">Click to view the file</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title text--title">
                        {{ translate('messages.Reviews') }}
                        <span class="badge badge-soft-dark ml-2" id="itemCount">14</span>
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
                                href="{{ route('admin.store.export', ['type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{ route('admin.store.export', ['type' => 'csv', request()->getQueryString()]) }}">
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
                        <tr>
                            <td>1</td>
                            <td>#10003278</td>

                            <td>
                                <div class="table-rest-info d-block">
                                    <div class="info">
                                        <div title="Car Rental Service" class="text--info">
                                            {{ translate('messages.Jhone Doe III') }}
                                        </div>
                                        <div>
                                            <span class="font-light">
                                                +98347568987
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-semibold text--warning">
                                    <i class="fs-13 tio-star"></i>
                                    4.5
                                </div>
                                <div class="line--limit-2 max-w--220px">
                                    {{ translate('messages.Gas Stove is very important in our daily life, most importantly it cooks food. So, when a gas stove breaks down it requires urgent servicing.') }}
                                </div>
                            </td>
                            <td>
                                12 Aug 2022
                                <br>
                                11: 55 am
                            </td>
                            <td>
                                <div class="line--limit-2 max-w--220px">
                                    {{ translate('messages.Gas Stove is very important in our daily life, most importantly it cooks food. So, when a gas stove breaks down it requires urgent servicing.') }}
                                </div>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="publishCheckbox47">
                                    <input type="checkbox" data-url="#" class="toggle-switch-input redirect-url"
                                        id="publishCheckbox47" checked="">
                                    <span class="toggle-switch-label mx-auto">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="page-area mt-3">
                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true">‹</span>
                        </li>
                        <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" rel="next" aria-label="Next »">›</a>
                        </li>
                    </ul>
                </nav>

            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection


@push('script_2')
    <script src="{{ asset('/public/assets/admin/vendor/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('/public/assets/admin/vendor/drift-zoom/dist/Drift.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    {{-- old document view --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            function openPdf(element) {
                const pdfUrl = element.getAttribute("data-pdf-url");
                window.open(pdfUrl, "_blank");
            }

            function downloadPdf(event, buttonElement) {
                event.stopPropagation();

                const pdfUrl = buttonElement.closest(".pdf-single").getAttribute("data-pdf-url");

                const link = document.createElement('a');
                link.href = pdfUrl;
                link.download = "Trade License Documents.pdf";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
            window.openPdf = openPdf;
            window.downloadPdf = downloadPdf;
        });
    </script> --}}

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
