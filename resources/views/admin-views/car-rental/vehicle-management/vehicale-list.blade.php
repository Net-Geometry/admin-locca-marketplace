@extends('layouts.admin.app')

@section('title', translate('messages.vehicale_list'))

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
                        <span>{{ translate('messages.Auto_Focus_Car_Service') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <div class="row g-2 mb-20">
                    <div class="col-sm-6 col-md-4">
                        <div class="select-item">
                            <label for="brand-select" class="input-label">{{ translate('messages.brand') }}</label>
                            <select id="brand-select" class="js-data-example-ajax form-control set-filter opacity-70"
                                required>
                                <option value="" selected disabled>{{ translate('messages.Type your business name') }}
                                </option>
                                <option value="1">{{ translate('messages.brand 1') }}</option>
                                <option value="2">{{ translate('messages.brand 2') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="select-item">
                            <label for="category-select" class="input-label">{{ translate('messages.category') }}</label>
                            <select id="category-select" class="js-data-example-ajax form-control set-filter opacity-70"
                                required>
                                <option value="" selected disabled>{{ translate('messages.Type your business name') }}
                                </option>
                                <option value="1">{{ translate('messages.category 1') }}</option>
                                <option value="2">{{ translate('messages.category 2') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="select-item">
                            <label for="type-select" class="input-label">{{ translate('messages.type') }}</label>
                            <select id="type-select" class="js-data-example-ajax form-control set-filter opacity-70"
                                required>
                                <option value="" selected disabled>
                                    {{ translate('messages.Type your business name') }}
                                </option>
                                <option value="1">{{ translate('messages.type 1') }}</option>
                                <option value="2">{{ translate('messages.type 2') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="btn--container justify-content-end mt-4">
                            <button type="reset" id="reset_btn"
                                class="btn btn--reset min-w-120px">{{ translate('messages.reset') }}</button>
                            <button type="submit"
                                class="btn btn--primary min-w-120px">{{ translate('messages.filter') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title text--title">
                        {{ translate('messages.Total_Vehicles') }}
                        <span class="badge badge-soft-dark ml-2 rounded-circle" id="itemCount">14</span>
                    </h5>
                    <form class="search-form flex-grow-1 max-w-353px">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                name="search" class="form-control"
                                placeholder="{{ translate('Search by provider name, owner info...') }}"
                                aria-label="{{ translate('messages.Search by provider name, owner info...') }}">
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
                    <a class="btn btn--primary font-weight-bold float-right mr-2 mb-0"
                        href="javascript:">{{ translate('messages.new_vehicle') }}</a>
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
                            <th class="border-0">{{ translate('messages.Vehicle_Info') }}</th>
                            <th class="border-0">{{ translate('messages.Category') }}</th>
                            <th class="border-0">{{ translate('messages.Brand') }}</th>
                            <th class="border-0">{{ translate('messages.Total_Trip') }}</th>
                            <th class="border-0">{{ translate('messages.Trip Fair') }}</th>
                            <th class="text-center border-0">{{ translate('messages.New_Tag') }}</th>
                            <th class="text-center border-0">{{ translate('messages.Status') }}</th>
                            <th class="text-center border-0">{{ translate('messages.Action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="text--title">
                                    <div class="font-medium">
                                        {{ translate('messages.F Premio 2006') }}
                                    </div>
                                    <div class="opacity-lg">
                                        Nator Kha 21-3214
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text--title font-medium">
                                    Sedan
                                </div>
                            </td>
                            <td>
                                <div class="text--title font-medium">
                                    Toyota
                                </div>
                            </td>
                            <td>
                                <div class="text--title font-medium">
                                    110
                                </div>
                            </td>
                            <td>
                                <div class="text--title">
                                    <div>
                                        <span class="opacity-lg">Hourly: </span>
                                        <span class="font-semibold">$35.5</span>
                                    </div>
                                    <div>
                                        <span class="opacity-lg">Distance Wise: </span>
                                        <span class="font-semibold">$35.5</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckboxNewTag">
                                        <input type="checkbox" class="toggle-switch-input redirect-url" data-url=""
                                            id="stocksCheckboxNewTag">
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckboxSTatus">
                                        <input type="checkbox" class="toggle-switch-input redirect-url" data-url=""
                                            id="stocksCheckboxSTatus" checked="">
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                    </a>
                                    <a class="btn action-btn btn-outline-primary" href="javascript:"
                                        title="{{ translate('messages.edit_store') }}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                        data-message="{{ translate('You want to remove this store') }}"
                                        title="{{ translate('messages.delete_store') }}"><i
                                            class="tio-delete-outlined"></i>
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
