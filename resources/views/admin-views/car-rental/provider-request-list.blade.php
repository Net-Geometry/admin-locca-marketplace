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
            <ul class="nav nav--tabs nav--tabs__style2">
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
                            <h5 class="card-title">{{ translate('messages.Total_Providers') }}</h5>
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                        name="search" class="form-control"
                                        placeholder="{{ translate('ex_:_Search_Store_Name') }}"
                                        aria-label="{{ translate('messages.search') }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>
                            @if (request()->get('search'))
                                <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                    data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                            @endif


                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40"
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
                                    <th class="border-0">{{ translate('messages.store_information') }}</th>
                                    <th class="border-0">{{ translate('messages.owner_information') }}</th>
                                    <th class="border-0">{{ translate('messages.zone') }}</th>
                                    <th class="text-uppercase border-0">{{ translate('messages.featured') }}</th>
                                    <th class="text-uppercase border-0">{{ translate('messages.status') }}</th>
                                    <th class="text-center border-0">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                {{-- @foreach ($stores as $key => $store)
                        <tr>
                            <td>{{$key+$stores->firstItem()}}</td>
                            <td>
                                <div>
                                    <a href="{{route('admin.store.view', $store->id)}}" class="table-rest-info" alt="view store">
                                    <img class="img--60 circle onerror-image" data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}"

                                            src="{{ $store['logo_full_url'] ?? asset('public/assets/admin/img/160x160/img1.jpg') }}"

                                            >
                                        <div class="info"><div title="{{ $store?->name }}" class="text--title">
                                            {{Str::limit($store->name,20,'...')}}
                                            </div>
                                            <div class="font-light">
                                                {{translate('messages.id')}}:{{$store->id}}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </td>

                            <td>
                                <span title="{{ $store?->vendor?->f_name.' '.$store?->vendor?->l_name }}" class="d-block font-size-sm text-body">
                                    {{Str::limit($store->vendor->f_name.' '.$store->vendor->l_name,20,'...')}}
                                </span>
                                <div>
                                    <a href="tel:{{ $store['phone'] }}">
                                        {{$store['phone']}}
                                    </a>
                                </div>
                            </td>
                            <td>
                                {{$store->zone?$store->zone->name:translate('messages.zone_deleted')}}
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="featuredCheckbox{{$store->id}}">
                                    <input type="checkbox" data-url="{{route('admin.store.featured',[$store->id,$store->featured?0:1])}}" class="toggle-switch-input redirect-url" id="featuredCheckbox{{$store->id}}" {{$store->featured?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>

                            <td>
                                @if (isset($store->vendor->status))
                                    @if ($store->vendor->status)
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$store->id}}">
                                        <input type="checkbox" data-url="{{route('admin.store.status',[$store->id,$store->status?0:1])}}" data-message="{{translate('messages.you_want_to_change_this_store_status')}}" class="toggle-switch-input status_change_alert" id="stocksCheckbox{{$store->id}}" {{$store->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                    @else
                                    <span class="badge badge-soft-danger">{{translate('messages.denied')}}</span>
                                    @endif
                                @else
                                    <span class="badge badge-soft-danger">{{translate('messages.pending')}}</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--warning btn-outline-warning"
                                            href="{{route('admin.store.view', $store->id)}}"
                                            title="{{ translate('messages.view') }}"><i
                                                class="tio-visible-outlined"></i>
                                        </a>
                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                    href="{{route('admin.store.edit',[$store['id']])}}" title="{{translate('messages.edit_store')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                    data-id="vendor-{{$store['id']}}" data-message="{{translate('You want to remove this store')}}" title="{{translate('messages.delete_store')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('admin.store.delete',[$store['id']])}}" method="post" id="vendor-{{$store['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach --}}
                            </tbody>
                        </table>

                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->

            </div>
            <div class="tab-pane fade" id="rejected-request" role="tabpanel" aria-labelledby="rejected-request-tab">
                Rejected Request
            </div>
        </div>


    </div>
@endsection

@push('script')
@endpush

@push('script_2')
@endpush
