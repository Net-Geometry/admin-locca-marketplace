@extends('layouts.admin.app')

@section('title', translate('messages.trip_list'))

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
                        <span>{{ translate('messages.Auto_Focus_Car_Service') }}
                    </h1></span>
                    </h1>
                </div>
            </div>

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs mb-2">
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'overview' ? 'active' : '' }}"
                            href="javascript:">{{ translate('messages.overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title active {{ request('tab') == 'trip_list' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.trip_list') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'driver_list' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.driver_list') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'vehicles' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.vehicles') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'reviews' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.reviews') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'discount' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.discounts') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'transaction' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.transactions') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'settings' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.settings') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'conversations' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('Conversations') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'meta-data' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('meta_data') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title  {{ request('tab') == 'disbursements' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.disbursements') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title  {{ request('tab') == 'business_plan' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.business_plan') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-end g-4">
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label class="input-label">{{ translate('messages.type') }}</label>
                            <select name="type" id="type" class="from-control js-select2-custom">
                                <option value="date-wise">Date Wise</option>
                                <option value="date-wise">Date Wise</option>
                                <option value="date-wise">Date Wise</option>
                                <option value="date-wise">Date Wise</option>
                                <option value="date-wise">Date Wise</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label class="input-label">{{ translate('messages.from_date') }}</label>
                            <input type="date" name="from_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label class="input-label">{{ translate('messages.to_date') }}</label>
                            <input type="date" name="to_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6">
                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                            <button type="submit" class="btn btn--primary">{{ translate('messages.update') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="order--card flex-grow-1" href="javascript:">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle m-0">
                        <span>{{ translate('All') }}</span>
                    </h6>
                    <span class="card-title text-title">20</span>
                </div>
            </a>

            <a class="order--card flex-grow-1" href="javascript:">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle m-0">
                        <span>{{ translate('messages.ongoing') }}</span>
                    </h6>
                    <span class="card-title text--warning">100</span>
                </div>
            </a>

            <a class="order--card flex-grow-1" href="javascript:">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle m-0">
                        <span>{{ translate('messages.pending') }}</span>
                    </h6>
                    <span class="card-title text--info">200</span>
                </div>
            </a>

            <a class="order--card flex-grow-1" href="javascript:">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle m-0">
                        <span>{{ translate('messages.completed') }}</span>
                    </h6>
                    <span class="card-title text--success">60</span>
                </div>
            </a>

            <a class="order--card flex-grow-1" href="javascript:">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle m-0">
                        <span>{{ translate('messages.canceled') }}</span>
                    </h6>
                    <span class="card-title text--danger">60</span>
                </div>
            </a>
        </div>

        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title text--title flex-grow-1">
                        {{ translate('messages.Total_Trips') }}
                        <span class="badge badge-soft-dark ml-2" id="itemCount">2</span>
                    </h5>
                    <form class="search-form flex-grow-1 max-w-353px">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                name="search" class="form-control"
                                placeholder="{{ translate('Search by provider name, owner info...') }}"
                                aria-label="{{ translate('messages.Search by provider name, owner info...') }}">
                            <button type="submit" class="btn btn--secondary bg--primary"><i class="tio-search"></i></button>
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

                        <div id="usersExportDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
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
                            <th class="border-0">{{ translate('messages.Trip ID') }}</th>
                            <th class="border-0">{{ translate('messages.Trip_Date') }}</th>
                            <th class="border-0">{{ translate('messages.Customer_Info') }}</th>
                            <th class="border-0">{{ translate('messages.Driver_Info') }}</th>
                            <th class="border-0">{{ translate('messages.Vehicle_Info') }}</th>
                            <th class="border-0">{{ translate('messages.Trip_Type') }}</th>
                            <th class="text-end border-0">{{ translate('messages.Trip_Amount') }}</th>
                            <th class="text-center border-0">{{ translate('messages.Trip_Status') }}</th>
                            <th class="text-center border-0">{{ translate('messages.Action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="text--title font-semibold">
                                    {{ translate('messages.1234567') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    24 August 2024
                                    <br>
                                    05:30 PM
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Cameron_Williamson') }}
                                    </div>
                                    <div class="opacity-lg">
                                        jennings@example.com
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex">
                                        <img width="35" class="rounded-circle aspect-1-1 border border-white shadow-sm" src="{{ asset('public/assets/admin/img/admin.png') }}" alt="">
                                        <img width="35" class="rounded-circle aspect-1-1 border border-white shadow-sm ml-n2" src="{{ asset('public/assets/admin/img/admin.png') }}" alt="">
                                        <img width="35" class="rounded-circle aspect-1-1 border border-white shadow-sm ml-n2" src="{{ asset('public/assets/admin/img/admin.png') }}" alt="">
                                    </div>
                                    <span>+2</span>
                                </div>
                                <div class="text-muted fs-12 mt-1">
                                    {{ translate('messages.driver_Assigned') }}
                                </div>
                            </td>
                            <td>
                                <div class="text-primary text-underline font-weight-medium" data-html="true" data-toggle="tooltip"
                                title="<div class='d-flex flex-column p-2'>
                                    <div class='media gap-3 border-bottom mb-2 pb-2'>
                                        <img src='{{ asset('public/assets/admin/img/admin.png') }}' class='rounded ratio-1-1' width='40' alt='...'>
                                        <div class='media-body'>
                                             <h5 class='d-flex align-items-center gap-2 text-white mb-0'>{{ translate('messages.toyota_Hiace') }}: 3</h5>
                                             <div class='d-flex align-items-center gap-2 fs-10'>{{ translate('messages.car_Assigned') }}: 04</div>
                                        </div>
                                    </div>
                                    <div class='media gap-3 border-bottom mb-2 pb-2'>
                                        <img src='{{ asset('public/assets/admin/img/admin.png') }}' class='rounded ratio-1-1' width='40' alt='...'>
                                        <div class='media-body'>
                                             <h5 class='d-flex align-items-center gap-2 text-white mb-0'>{{ translate('messages.toyota_Hiace') }}: 3</h5>
                                             <div class='d-flex align-items-center gap-2 fs-10'>{{ translate('messages.car_Assigned') }}: 04</div>
                                        </div>
                                    </div>
                                    <div class='media gap-2'>
                                        <img src='{{ asset('public/assets/admin/img/admin.png') }}' class='rounded ratio-1-1' width='40' alt='...'>
                                        <div class='media-body'>
                                             <h5 class='d-flex align-items-center gap-2 text-white mb-0'>{{ translate('messages.toyota_Hiace') }}: 3</h5>
                                             <div class='d-flex align-items-center gap-2 fs-10'>{{ translate('messages.car_Assigned') }}: 04</div>
                                        </div>
                                    </div>
                                </div>">
                                    5 {{ translate('messages.vehicles') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Hourly') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Instant') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title text-end">
                                    <div class="font-semobold">
                                        {{ translate('messages.$1,550.35') }}
                                    </div>
                                    <div class="opacity-lg font-medium text--success">
                                        {{ translate('messages.Paid') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <label class="badge badge-soft-info border-0">
                                        {{ translate('messages.Pending') }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="text--title font-semibold">
                                    {{ translate('messages.1234567') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    24 August 2024
                                    <br>
                                    05:30 PM
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Cameron_Williamson') }}
                                    </div>
                                    <div class="opacity-lg">
                                        jennings@example.com
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--warning font-medium">
                                    {{ translate('messages.Unassigned') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.F Premio 2006') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Nator Kha 21-3214') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Hourly') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Instant') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title text-end">
                                    <div class="font-semobold">
                                        {{ translate('messages.$1,550.35') }}
                                    </div>
                                    <div class="opacity-lg font-medium text--success">
                                        {{ translate('messages.Paid') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <label class="badge badge-soft-danger border-0">
                                        {{ translate('messages.Canceled') }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <div class="text--title font-semibold">
                                    {{ translate('messages.1234567') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    24 August 2024
                                    <br>
                                    05:30 PM
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Cameron_Williamson') }}
                                    </div>
                                    <div class="opacity-lg">
                                        jennings@example.com
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Brooklyn Simmons') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.cruz@example.com') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.F Premio 2006') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Nator Kha 21-3214') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Hourly') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Instant') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title text-end">
                                    <div class="font-semobold">
                                        {{ translate('messages.$1,550.35') }}
                                    </div>
                                    <div class="opacity-lg font-medium text--danger">
                                        {{ translate('messages.Unpaid') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <label class="badge badge-soft-warning border-0">
                                        {{ translate('messages.Ongoing') }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <div class="text--title font-semibold">
                                    {{ translate('messages.1234567') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    24 August 2024
                                    <br>
                                    05:30 PM
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Cameron_Williamson') }}
                                    </div>
                                    <div class="opacity-lg">
                                        jennings@example.com
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--warning font-medium">
                                    {{ translate('messages.Unassigned') }}
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.F Premio 2006') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Nator Kha 21-3214') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.Hourly') }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ translate('messages.Instant') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title text-end">
                                    <div class="font-semobold">
                                        {{ translate('messages.$1,550.35') }}
                                    </div>
                                    <div class="opacity-lg font-medium text--success">
                                        {{ translate('messages.Paid') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <label class="badge badge-soft-success border-0">
                                        {{ translate('messages.Completed') }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                    </a>
                                </div>
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
@endpush
