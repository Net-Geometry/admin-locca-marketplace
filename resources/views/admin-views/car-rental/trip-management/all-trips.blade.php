@extends('layouts.admin.app')

@section('title', translate('messages.all_trips'))

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
                            <img src="{{ asset('public/assets/admin/img/zone.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ translate('messages.All_Trips') }}
                    </h1></span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper gap-20px">
                    <h5 class="card-title text--title">{{ translate('messages.Total_Trips') }}</h5>
                    <form class="search-form">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                name="search" class="form-control"
                                placeholder="{{ translate('Search by trip ID, customer name...') }}"
                                aria-label="{{ translate('messages.Search by trip ID, customer name...') }}">
                            <button type="submit" class="btn btn--secondary bg--primary"><i
                                    class="tio-search"></i></button>

                        </div>
                        <!-- End Search -->
                    </form>
                    @if (request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                    @endif
                    <!-- Unfold -->
                    <div class="hs-unfold m-0">
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
                    <a href="#" class="text--title font-semibold"><i class="tio-filter-list"></i>
                        {{ translate('messages.Filter') }}</a>
                    <a href="#" class="text--title font-semibold"><i class="tio-column-view-outlined"></i>
                        {{ translate('messages.Columns') }}</a>
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
                            <th class="border-0">{{ translate('messages.Booking_Date') }}</th>
                            <th class="border-0">{{ translate('messages.Schedule_At') }}</th>
                            <th class="border-0">{{ translate('messages.Customer_Info') }}</th>
                            <th class="border-0">{{ translate('messages.Provider') }}</th>
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
                                <div class="text--title font-medium w--150px white--space-initial">
                                    {{ translate('messages.Auto Focus Car Service') }}
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
                                    <label class="badge badge-soft-info border-0">
                                        {{ translate('messages.Pending') }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.download') }}"><i class="tio-download-to"></i>
                                    </a>
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
        <!-- End Card -->
    </div>
@endsection


@push('script_2')
@endpush
