@extends('layouts.admin.app')

@section('title', translate('messages.provider_list'))

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
                                {{ translate('messages.Provider_List') }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-1">
                    <h4 class="title">540</h4>
                    <span class="subtitle"> {{ translate('messages.Total_Provider') }}
                    </span>
                    <img class="resturant-icon"
                        src="http://localhost/Backend-6amMart/public/assets/admin/img/total-store.png" alt="store">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-3">
                    <h4 class="title">190</h4>
                    <span class="subtitle"> {{ translate('messages.Active_Provider') }}</span>
                    <img class="resturant-icon"
                        src="http://localhost/Backend-6amMart/public/assets/admin/img/active-store.png" alt="store">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-4">
                    <h4 class="title">50</h4>
                    <span class="subtitle"> {{ translate('messages.Inactive_Provider') }}</span>
                    <img class="resturant-icon"
                        src="http://localhost/Backend-6amMart/public/assets/admin/img/close-store.png" alt="store">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-2">
                    <h4 class="title">200</h4>
                    <span class="subtitle">
                        {{ translate('messages.Newly_joined') }}
                    </span>
                    <img class="resturant-icon" src="http://localhost/Backend-6amMart/public/assets/admin/img/add-store.png"
                        alt="store">
                </div>
            </div>
        </div>
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title text--title flex-grow-1">
                        {{ translate('messages.Total_Providers') }}
                        <span class="badge badge-soft-dark ml-2 rounded-circle">23</span>
                    </h5>
                    @if (!isset(auth('admin')->user()->zone_id))
                        <div class="select-item min--280">
                            <select name="zone_id" class="form-control js-select2-custom set-filter"
                                data-url="{{ url()->full() }}" data-filter="zone_id">
                                <option value="" {{ !request('zone_id') ? 'selected' : '' }}>
                                    {{ translate('messages.All_Zones') }}</option>
                                @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                                    <option value="{{ $z['id'] }}"
                                        {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                        {{ $z['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <form class="search-form flex-grow-1 max-w-353px">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                name="search" class="form-control"
                                placeholder="{{ translate('Search by provider name, owner info...') }}"
                                aria-label="{{ translate('messages.search') }}">
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
                        href="javascript:">{{ translate('messages.new_provider') }}</a>
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
                            <th class="border-0">{{ translate('messages.total_vehicle') }}</th>
                            <th class="border-0">{{ translate('messages.total_driver') }}</th>
                            <th class="border-0">{{ translate('messages.total_trip') }}</th>
                            <th class="text-center border-0">{{ translate('messages.business_featured') }}</th>
                            <th class="text-center border-0">{{ translate('messages.status') }}</th>
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
                                            <span class="font-light d-flex align-items-center gap-1">
                                                <i class="tio-star"></i>
                                                <span>4.2</span>
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
                                                +8801721345243
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td>21</td>
                            <td>21</td>
                            <td>
                                33<span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="Total Trip"><img class="w--11"
                                        src="http://localhost/Backend-6amMart/public/assets/admin/img/info-circle.svg"
                                        alt="Total Trip"></span>
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm d-flex justify-content-center"
                                    for="featuredCheckbox">
                                    <input type="checkbox" data-url="javascript:"
                                        class="toggle-switch-input redirect-url" id="featuredCheckbox">
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>

                            <td>
                                <label class="toggle-switch toggle-switch-sm  d-flex justify-content-center"
                                    for="stocksCheckbox">
                                    <input type="checkbox" data-url="javascript:"
                                        data-message="{{ translate('messages.you_want_to_change_this_store_status') }}"
                                        class="toggle-switch-input status_change_alert" id="stocksCheckbox" checked>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
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
                <nav class="d-flex justify-content-end gap-3">
                    <div class="d-flex align-items-baseline gap-3">
                        <span class="text-14 text--title ">1-5 of 13</span>
                        <nav class="w-auto">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link text--title  text-14" href="#" aria-label="Previous">
                                        <span aria-hidden="true">‹</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link text--title fw-bold text-14" href="#" aria-label="Next">
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
                        <li class="page-item active" aria-current="page"><span class="page-link btn-light">1</span></li>
                        <li class="page-item"><a class="page-link btn-light" href="javascript:">2</a></li>
                        <li class="page-item"><a class="page-link btn-light" href="javascript:">3</a></li>
                        <li class="page-item">
                            <a class="page-link btn-light" href="javascript:" rel="next" aria-label="Next »">›</a>
                        </li>
                    </ul>
                </nav>

            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->


    </div>
@endsection

@push('script')
@endpush

@push('script_2')
@endpush
