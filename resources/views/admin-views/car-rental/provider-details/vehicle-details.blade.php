@extends('layouts.admin.app')

@section('title', translate('messages.vehicle_details'))

@push('css_or_js')
@endpush

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
        <div class="row review--information-wrapper g-2 mb-3">
            <div class="col-lg-9">
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row align-items-md-center">
                            <div class="col-lg-8 col-md-6 mb-3 mb-md-0">
                                <div class="media food--media gap-3 align-items-center">
                                    <img class="avatar avatar-xxl avatar-4by3 w--290 h--145 onerror-image"
                                        src="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                        alt="Image Description">
                                    <div class="d-block">
                                        <h4>F Premio 2006</h4>
                                        <h5>Nator Kha 21-3214</h5>
                                        <div class="fs-12">
                                            If you’re looking for a pie with a bit more heft, a meat pizza is a perfect
                                            and
                                            popular choice. If you’re looking for If you’re looking for a pie with a bit
                                            more heft, a meat pizza
                                            <span class="text--primary font-medium">See more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mx-auto">
                                <div class="rating--review">
                                    <h1 class="title text--primary border-line d-flex align-items-center">
                                        <span>4.0<span class="out-of">/5</span></span>
                                        <div class="info font-medium">2 Reviews</div>
                                    </h1>
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
                    </div>
                    <!-- End Body -->
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <a class="resturant--information-single"
                            href="http://localhost/Backend-6amMart/admin/store/view/45">
                            <img class="img--65 rounded mx-auto mb-3 onerror-image" data-onerror-image=""
                                src="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}" alt="Image Description">
                            <div class="text-center text--title">
                                <h5 class="text-capitalize font-semibold text-hover-primary d-block mb-1">
                                    Auto Focus Car Service
                                </h5>
                                <span class="opacity-lg">
                                    House:20, Road:30, Mirpur 12
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-20">
            <!-- Table -->
            <div class="table-responsive">
                <table id="" class="table table-borderless table-thead-bordered table-nowrap card-table similar">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('messages.Price') }}</th>
                            <th class="border-0">{{ translate('messages.Category') }}</th>
                            <th class="border-0">{{ translate('messages.Brand') }}</th>
                            <th class="border-0">{{ translate('messages.Type') }}</th>
                            <th class="border-0">{{ translate('messages.VIN_Number') }}</th>
                            <th class="border-0">{{ translate('messages.Registration_No.') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        <tr>
                            <td>
                                <div>
                                    <div>Hourly : <span class="font-semibold">$ 400</span></div>
                                    <div>Distance Wise : <span class="font-semibold">$ 30</span></div>
                                    <div>Discount : <span class="font-semibold">30%</span></div>
                                </div>
                            </td>
                            <td>Sedan</td>
                            <td>Toyota</td>
                            <td>Family</td>
                            <td>123456123578</td>
                            <td>Nator Kha 21-3214</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- End Table -->
        </div>
        <div class="card mb-20">
            <!-- Table -->
            <div class="table-responsive">
                <table id="" class="table table-borderless table-thead-bordered table-nowrap card-table similar">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('messages.Air_Condition') }}</th>
                            <th class="border-0">{{ translate('messages.Fuel_Type') }}</th>
                            <th class="border-0">{{ translate('messages.Transmission') }}</th>
                            <th class="border-0">{{ translate('messages.Break_System') }}</th>
                            <th class="border-0">{{ translate('messages.Engine_Capacity') }}</th>
                            <th class="border-0">{{ translate('messages.Engine_Power') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        <tr>
                            <td>Yes</td>
                            <td>Diesel</td>
                            <td>Manual Gear</td>
                            <td>Hydraulic ABS</td>
                            <td>1100 cc</td>
                            <td>250hp</td>
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
                        {{ translate('messages.Documents_And_Images') }}
                    </h5>
                    <p class="fs-12">
                        {{ translate('messages.Here you can see all images & document for the provider') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <h5 class="text-title font-bold mb-10px"> {{ translate('messages.Documents') }}</h5>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="pdf-single" data-pdf-url="{{ asset('public/assets/admin/img/pdf/sample.pdf') }}"
                        onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <iframe src="{{ asset('public/assets/admin/img/pdf/sample.pdf') }}" frameborder="0"></iframe>
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="download-btn" onclick="downloadPdf(event, this)">
                                <i class="tio-download-to"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="{{ asset('public/assets/admin/img/pdf/pdf.png') }}" width="34"
                                    alt="PDF Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span>Trade License Documents.pdf</span>
                                    <span class="opacity-50">Click to view the file</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pdf-single" data-pdf-url="{{ asset('public/assets/admin/img/pdf/sample.pdf') }}"
                        onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <iframe src="{{ asset('public/assets/admin/img/pdf/sample.pdf') }}" frameborder="0"></iframe>
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="download-btn" onclick="downloadPdf(event, this)">
                                <i class="tio-download-to"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="{{ asset('public/assets/admin/img/pdf/pdf.png') }}" width="34"
                                    alt="PDF Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span>Trade License Documents.pdf</span>
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
                                        <div title="Car Rental Service" class="text--primary">
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
    <script>
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
    </script>
@endpush
