@extends('layouts.admin.app')

@section('title', translate('messages.request_list'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img class="onerror-image" data-onerror-image="{{ asset('/public/assets/admin/img/grocery.svg') }}"
                            src="{{ asset('/public/assets/admin/img/100x100/2.jpg') }}" width="38" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title text-title mb-0">
                                {{ translate('messages.Provider_Request_List') }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="mt-30 mb-30">
            <ul class="nav nav--tabs nav--tabs__style2 dark">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-request-tab" data-toggle="tab" href="#pending-request"
                        role="tab" aria-controls="pending-request"
                        aria-selected="true">{{ translate('Pending_Request') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rejected-request-tab" data-toggle="tab" href="#rejected-request" role="tab"
                        aria-controls="rejected-request" aria-selected="false">{{ translate('Rejected_Request') }}</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pending-request" role="tabpanel"
                aria-labelledby="pending-request-tab">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header py-2">
                        <div class="search--button-wrapper">
                            <h5 class="card-title text--title">{{ translate('messages.Total_Providers') }}</h5>
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                        name="search" class="form-control"
                                        placeholder="{{ translate('Search by provider name, owner info...') }}"
                                        aria-label="{{ translate('messages.search') }}">
                                    <button type="submit" class="btn  btn--secondary bg--primary"><i
                                            class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>
                            @if (request()->get('search'))
                                <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                    data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                            @endif


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
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false

                        }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{ translate('sl') }}</th>
                                    <th class="border-0">{{ translate('messages.provider') }}</th>
                                    <th class="border-0">{{ translate('messages.owner_info') }}</th>
                                    <th class="border-0">{{ translate('messages.business_address') }}</th>
                                    <th class="text-uppercase border-0">{{ translate('messages.business_plan') }}</th>
                                    <th class="text-center border-0">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="table-rest-info">
                                            <img class="img--60 onerror-image"
                                                data-onerror-image="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                src="{{ $store['logo_full_url'] ?? asset('public/assets/admin/img/160x160/img1.jpg') }}">
                                            <div class="info">
                                                <div title="Car Rental Service" class="text--title">
                                                    {{ translate('messages.Car_Rental_Service') }}
                                                </div>
                                                <div>
                                                    <span class="font-light">
                                                        +8801721345243
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="table-rest-info d-block">
                                            <div class="info">
                                                <div title="Car Rental Service" class="text--title">
                                                    {{ translate('messages.Cameron_Williamson') }}
                                                </div>
                                                <div>
                                                    <span class="font-light">
                                                        jennings@example.com
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td><span class="line-limit-2 word-break">
                                            {{ translate('messages.4517 Washington Ave. Manchester, Kentucky 3949') }}</span>
                                    </td>
                                    <td>
                                        <div class="table-rest-info d-block">
                                            <div class="info">
                                                <div title="Car Rental Service" class="text--title">
                                                    {{ translate('messages.Subscription') }}
                                                </div>
                                                <div>
                                                    <span class="font-light">
                                                        {{ translate('messages.Standard') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">

                                            <button type="button"
                                                class="btn action-btn btn--varify btn-outline-varify shadow-none"
                                                data-deny="approve" data-toggle="modal"
                                                data-target="#exampleModal--approve"><i class="tio-done"></i>
                                            </button>
                                            <button type="button"
                                                class="btn action-btn btn--danger btn-outline-danger shadow-none"
                                                data-deny="cancel" data-toggle="modal"
                                                data-target="#exampleModal--cancel"><i class="tio-clear"></i>
                                            </button>
                                            <a class="btn action-btn btn--primary btn-outline-primary shadow-none"
                                                href="javascript:" title="{{ translate('messages.view') }}"><i
                                                    class="tio-visible-outlined"></i>
                                            </a>
                                        </div>
                                        <form action="" method="post" id="">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="page-area mt-3">
                        <nav class="d-flex justify-content-end gap-3">
                            <div class="d-flex align-items-baseline gap-3">
                                <span class="text-14 text--title ">1-5 of 13</span>
                                <nav class="w-auto">
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link text--title  text-14" href="#"
                                                aria-label="Previous">
                                                <span aria-hidden="true">‹</span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link text--title fw-bold text-14" href="#"
                                                aria-label="Next">
                                                <span aria-hidden="true">›</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <ul class="pagination">

                                <li class="page-item" aria-disabled="true" aria-label="« Previous">
                                    <span class="page-link btn-light" aria-hidden="true">‹</span>
                                </li>
                                <li class="page-item active" aria-current="page"><span
                                        class="page-link btn-light">1</span></li>
                                <li class="page-item"><a class="page-link btn-light" href="javascript:">2</a></li>
                                <li class="page-item"><a class="page-link btn-light" href="javascript:">3</a></li>
                                <li class="page-item">
                                    <a class="page-link btn-light" href="javascript:" rel="next"
                                        aria-label="Next »">›</a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->

            </div>
            <div class="tab-pane fade" id="rejected-request" role="tabpanel" aria-labelledby="rejected-request-tab">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header py-2">
                        <div class="search--button-wrapper">
                            <h5 class="card-title text--title">{{ translate('messages.Total_Providers') }}</h5>
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                        name="search" class="form-control"
                                        placeholder="{{ translate('Search by provider name, owner info...') }}"
                                        aria-label="{{ translate('messages.search') }}">
                                    <button type="submit" class="btn btn--primary"><i class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>
                            @if (request()->get('search'))
                                <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                    data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                            @endif


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
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false

                        }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{ translate('sl') }}</th>
                                    <th class="border-0">{{ translate('messages.provider') }}</th>
                                    <th class="border-0">{{ translate('messages.owner_info') }}</th>
                                    <th class="border-0">{{ translate('messages.business_address') }}</th>
                                    <th class="text-uppercase border-0">{{ translate('messages.business_plan') }}</th>
                                    <th class="text-center border-0">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="table-rest-info">
                                            <img class="img--60 onerror-image"
                                                data-onerror-image="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                                src="{{ $store['logo_full_url'] ?? asset('public/assets/admin/img/160x160/img1.jpg') }}">
                                            <div class="info">
                                                <div title="Car Rental Service" class="text--title">
                                                    {{ translate('messages.Car_Rental_Service') }}
                                                </div>
                                                <div>
                                                    <span class="font-light">
                                                        +8801721345243
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="table-rest-info d-block">
                                            <div class="info">
                                                <div title="Car Rental Service" class="text--title">
                                                    {{ translate('messages.Cameron_Williamson') }}
                                                </div>
                                                <div>
                                                    <span class="font-light">
                                                        jennings@example.com
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td><span class="line-limit-2 word-break">
                                            {{ translate('messages.4517 Washington Ave. Manchester, Kentucky 3949') }}</span>
                                    </td>
                                    <td>
                                        <div class="table-rest-info d-block">
                                            <div class="info">
                                                <div title="Car Rental Service" class="text--title">
                                                    {{ translate('messages.Subscription') }}
                                                </div>
                                                <div>
                                                    <span class="font-light">
                                                        {{ translate('messages.Standard') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">

                                            <a class="btn action-btn btn--varify btn-outline-varify" href="javascript:"
                                                title="{{ translate('messages.edit_store') }}"><i class="tio-done"></i>
                                            </a>
                                            <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                                title="{{ translate('messages.view') }}"><i
                                                    class="tio-visible-outlined"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="page-area mt-3">
                        <nav class="d-flex justify-content-end gap-3">
                            <div class="d-flex align-items-baseline gap-3">
                                <span class="text-14 text--title ">1-5 of 13</span>
                                <nav class="w-auto">
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link text--title  text-14" href="#"
                                                aria-label="Previous">
                                                <span aria-hidden="true">‹</span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link text--title fw-bold text-14" href="#"
                                                aria-label="Next">
                                                <span aria-hidden="true">›</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <ul class="pagination">

                                <li class="page-item" aria-disabled="true" aria-label="« Previous">
                                    <span class="page-link btn-light" aria-hidden="true">‹</span>
                                </li>
                                <li class="page-item active" aria-current="page"><span
                                        class="page-link btn-light">1</span></li>
                                <li class="page-item"><a class="page-link btn-light" href="javascript:">2</a></li>
                                <li class="page-item"><a class="page-link btn-light" href="javascript:">3</a></li>
                                <li class="page-item">
                                    <a class="page-link btn-light" href="javascript:" rel="next"
                                        aria-label="Next »">›</a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>


    </div>

    {{-- Approve Modal --}}
    <div class="modal fade" id="exampleModal--approve" tabindex="-1" aria-labelledby="exampleModalLabel--approve"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body pt-5 p-md-5">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('public/assets/admin/img/new-img/close-icon-dark.svg') }}" alt="">
                    </button>

                    <div class="d-flex justify-content-center mb-4">
                        <img width="75" height="75" src="{{ asset('public/assets/admin/img/tick.png') }}"
                            class="rounded-circle" alt="">
                    </div>

                    <h3 class="text--title mb-6 font-medium text-center">
                        {{ translate('Are you sure, want to approve the request?') }}</h3>
                    <form method="post" action="">
                        @csrf
                        <div class="form-floating">
                            <label for="add-your-note"
                                class="font-medium input-label text--title">{{ translate('Approval Note') }}<span
                                    class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="Approval Note"><img
                                        src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                        alt="Cancellation
                                        Note"></span></label>
                            <div class="mb-30">
                                <textarea class="form-control h--90" placeholder="{{ translate('Type your Approval Note') }}" name=""
                                    id="add-your-note" required></textarea>
                                <div>0/60</div>
                            </div>
                            <input type="hidden" value="deny" name="status">
                            <div class="d-flex justify-content-end gap-3">
                                <button type="button" data-dismiss="modal" aria-label="Close"
                                    class="btn btn--reset">{{ translate('Cancel') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel modal --}}
    <div class="modal fade" id="exampleModal--cancel" tabindex="-1" aria-labelledby="exampleModalLabel--cancel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body pt-5 p-md-5">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('public/assets/admin/img/new-img/close-icon-dark.svg') }}" alt="">
                    </button>

                    <div class="d-flex justify-content-center mb-4">
                        <img width="75" height="75" src="{{ asset('public/assets/admin/img/icons/delete.png') }}"
                            class="rounded-circle" alt="">
                    </div>

                    <h3 class="text--title mb-6 font-medium text-center">
                        {{ translate('Are you sure, want to cancel the request?') }}</h3>
                    <form method="post" action="">
                        @csrf
                        <div class="form-floating">
                            <label for="add-your-note"
                                class="font-medium input-label text--title">{{ translate('Cancellation Note') }}<span
                                    class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="Cancellation Note"><img
                                        src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                        alt="Cancellation
                                        Note"></span></label>
                            <div class="mb-30">
                                <textarea class="form-control h--90" placeholder="{{ translate('Type your Cancellation Note') }}" name=""
                                    id="add-your-note" required></textarea>
                                <div>0/60</div>
                            </div>
                            <input type="hidden" value="deny" name="status">
                            <div class="d-flex justify-content-end gap-3">
                                <button type="button" data-dismiss="modal" aria-label="Close"
                                    class="btn btn--reset">{{ translate('Cancel') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
@endpush

@push('script_2')
@endpush
