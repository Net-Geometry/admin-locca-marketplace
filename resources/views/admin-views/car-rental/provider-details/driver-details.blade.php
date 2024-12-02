@extends('layouts.admin.app')

@section('title', translate('messages.Driver_Details'))

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
                            <img src="{{ asset('public/assets/admin/img/car-logo.png') }}" alt="">
                        </span>
                        <span>{{ translate('messages.Driver_Details') }}
                    </h1></span>
                    </h1>
                </div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <button type="button" data-toggle="modal" data-target="#driverDeleteModal" class="btn btn--cancel h--45px d-flex gap-2 align-items-center">
                        <i class="tio-delete"></i>
                        {{ translate('messages.delete') }}
                    </button>
                    <a href="javascript:" class="btn btn--reset d-flex justify-content-between align-items-center gap-4 lh--1 h--45px">
                        {{ translate('messages.status') }}
                        <label class="toggle-switch toggle-switch-sm" for="status">
                            <input type="checkbox" data-url="#" class="toggle-switch-input"
                                id="status" checked="">
                            <span class="toggle-switch-label mx-auto">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </a>
                    <a href="javascript:" class="btn btn--primary h--45px d-flex gap-2 align-items-center">
                        <i class="tio-edit"></i>
                        {{ translate('messages.Edit_Driver') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-20">
            <div class="card-body p-4">
                <div class="card border p-3 p-sm-4 shadow-none mb-3">
                    <div class="media align-items-sm-center flex-column flex-sm-row">
                        <div class="mb-3 mb-sm-0">
                            <img height="115" class="aspect-ratio-1 w-auto rounded mr-4 onerror-image"
                            src="{{ asset('/public/assets/admin/img/900x400/img1.jpg') }}"
                            alt="">
                        </div>
                        <div class="media-body text--title d-flex flex-column flex-lg-row gap-3">
                            <div class="mr-0 mr-lg-5">
                                <h3 class="fs-20 mb-0">John Doe</h3>
                                <div class="d-flex gap-3"> 
                                    <span class="min-w-110px">Phone</span>
                                    <span>: +88023387462</span>
                                </div>
                                <div class="d-flex gap-3">
                                    <span class="min-w-110px">Email</span>
                                    <span>: jhone@gmail.com</span>
                                </div>
                            </div>
                            <div class="mr-0 mr-lg-5">
                                <h5 class="">Identity Information</h5>
                                <div class="d-flex gap-3"> 
                                    <span class="min-w-110px">Identity Type</span>
                                    <span>: NID</span>
                                </div>
                                <div class="d-flex gap-3">
                                    <span class="min-w-110px">Identity Number</span>
                                    <span>: 12345678</span>
                                </div>
                            </div>
                            <div>
                                <h5 class="">Vendor Info</h5>
                                <div class="align-items-center d-flex gap-2 resturant--information-single text-left">
                                    <img height="45" class="aspect-ratio-1 onerror-image rounded" src="{{ asset('/public/assets/admin/img/900x400/img1.jpg') }}" alt="Image Description">
                                    <div class="text--title">
                                        <h5 class="text-capitalize font-semibold text-hover-primary d-block mb-1">
                                            K Car Service
                                            <span class="btn btn--warning fs-12 rounded-20 text-white py-1 px-2 ml-1">
                                                <i class="tio-star mr-1"></i>4.0
                                            </span>
                                        </h5>
                                        <span class="opacity-lg">
                                            +880 123654789
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h5 class="text--title mb-20">Identity Image</h5>
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <img width="275" class="aspect-2-1 object--cover rounded-10" src="{{ asset('public/assets/admin/img/user-2.png') }}" alt="Identity image">
                        </div>
                        <div>
                            <img width="275" class="aspect-2-1 object--cover rounded-10" src="{{ asset('public/assets/admin/img/profile.jpg') }}" alt="Identity image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper gap-20px">
                    <h5 class="card-title text--title flex-grow-1">{{ translate('messages.Total_Trips') }}</h5>
                    <form class="search-form flex-grow-1 max-w-353px">
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
                            <th class="border-0">{{ translate('messages.Vehicle_Info') }}</th>
                            <th class="border-0">{{ translate('messages.Trip_Type') }}</th>
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
    <!--Dirver delete Modal -->
    <div class="modal fade" id="driverDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="driverDeleteModalLabel">
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
                    <h3 class="font-medium text--title">Confirm Driver Deletion</h3>
                    <div class="fs-13">Are you sure you want to delete this Driver & remove it permanently?</div>
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
@endpush
