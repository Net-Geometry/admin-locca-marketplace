@extends('layouts.admin.app')

@section('title',$store->name)

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">

        @include('rental::admin.provider.details.partials._header',['store'=>$store])

        <!-- Page Heading -->
        @if($store->vendor->status)
            <div class="row g-3 text-capitalize">
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-md-4">
                    <div class="card h-100 card--bg-1">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                            <h5 class="cash--subtitle text-white">
                                {{translate('messages.collected_cash_by_store')}}
                            </h5>
                            <div class="d-flex align-items-center justify-content-center mt-3">
                                <div class="cash-icon mr-3">
                                    <img src="{{asset('public/assets/admin/img/cash.png')}}" alt="img">
                                </div>
                                <h2 class="cash--title text-white">{{\App\CentralLogics\Helpers::format_currency($wallet->collected_cash)}}</h2>
                            </div>
                        </div>
                        <div class="card-footer pt-0 bg-transparent border-0">
                            <button class="btn text-white text-capitalize bg--title h--45px w-100" id="collect_cash"
                                    type="button" data-toggle="modal" data-target="#collect-cash"
                                    title="Collect Cash">{{ translate('messages.collect_cash_from_store') }}
                            </button>
                            {{-- <a class="btn text-white text-capitalize bg--title h--45px w-100" href="{{$store->vendor->status ? route('admin.transactions.account-transaction.index') : '#'}}" title="{{translate('messages.goto_account_transaction')}}">{{translate('messages.collect_cash_from_store')}}</a> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <!-- Panding Withdraw Card Example -->
                        <div class="col-sm-6">
                            <div class="resturant-card card--bg-2">
                                <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->pending_withdraw)}}</h4>
                                <div class="subtitle">{{translate('messages.pending_withdraw')}}</div>
                                <img class="resturant-icon w--30"
                                     src="{{asset('public/assets/admin/img/transactions/pending.png')}}"
                                     alt="transaction">
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-sm-6">
                            <div class="resturant-card card--bg-3">
                                <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->total_withdrawn)}}</h4>
                                <div class="subtitle">{{translate('messages.total_withdrawal_amount')}}</div>
                                <img class="resturant-icon w--30"
                                     src="{{asset('public/assets/admin/img/transactions/withdraw-amount.png')}}"
                                     alt="transaction">
                            </div>
                        </div>

                        <!-- Collected Cash Card Example -->
                        <div class="col-sm-6">
                            <div class="resturant-card card--bg-4">
                                <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->balance>0?$wallet->balance:0)}}</h4>
                                <div class="subtitle">{{translate('messages.withdraw_able_balance')}}</div>
                                <img class="resturant-icon w--30"
                                     src="{{asset('public/assets/admin/img/transactions/withdraw-balance.png')}}"
                                     alt="transaction">
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-sm-6">
                            <div class="resturant-card card--bg-1">
                                <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->total_earning)}}</h4>
                                <div class="subtitle">{{translate('messages.total_earning')}}</div>
                                <img class="resturant-icon w--30"
                                     src="{{asset('public/assets/admin/img/transactions/earning.png')}}"
                                     alt="transaction">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
        <div class="card mt-4 p-4">
            <div class="row g-2" id="order_stats">
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                {{translate('All')}}
                            </h6>
                            <span class="card-title text--info">
                                {{ $store->trips->count() }}
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                {{translate('Completed')}}
                            </h6>
                            <span class="card-title text--success">
                                {{ $store->trips->where('trip_status', 'completed')->count() }}
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                {{translate('Canceled')}}
                            </h6>
                            <span class="card-title text--danger">
                                {{ $store->trips->where('trip_status', 'canceled')->count() }}
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                {{translate('Cancellation rate')}}
                            </h6>
                            <span class="card-title text--warning">
                                12%
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
            </div>
        </div>
        <div class="taxi-banner radius-10 mt-4 mb-20"
             style="background-image: url('{{ $store->cover_photo_full_url ?? asset('public/assets/admin/img/100x100/1.png') }}'); background-repeat: no-repeat; background-position: center; background-size: cover;">
            <div class="taxi-info-wrapper d-flex flex-wrap flex-sm-nowrap gap-30px">
                <div class="logo">
                    <img data-onerror-image="{{asset('public/assets/admin/img/100x100/1.png')}}"
                         src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/100x100/1.png') }}" width="150" class="rounded-8"
                         alt="">
                </div>
                <div class="taxi-info">
                    <h3 class="fs-20 fw-bold text--title mb-20"> {{ $store->name }}</h3>
                    <div class="details d-flex flex-wrap flex-column flex-sm-row gap-40px">
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/zone.png') }}" width="36" height="36"
                                 class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.Business_Address') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">{{$store->address}}</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/job-type.png') }}" width="36"
                                 height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.Business_Plan') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">{{ ucwords($store->store_business_model) }}</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/wallet.png') }}" width="36"
                                 height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.Approx. Pickup Time') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">{{ $store->delivery_time }}</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/vehicle-type.png') }}" width="36"
                                 height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.VAT / TAX') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">{{ $store->tax }} %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Registration_Information') }}
                    </h5>
                    <p class="fs-12">
                        {{ translate('messages.Here you can see all the information that provider submit during registration') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0 h-100">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.General_Information') }}
                                </h5>
                                @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                                @php($language = $language->value ?? null)
                                @php($defaultLang = 'en')
                                <div class="div">
                                    @if ($language)
                                        <ul class="nav nav-tabs mb-4">
                                            <li class="nav-item">
                                                <a class="nav-link lang_link active" href="#"
                                                   id="default-link">{{ translate('Default') }}</a>
                                            </li>
                                            @foreach (json_decode($language) as $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link" href="#"
                                                       id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if ($language)
                                        <div class="lang_form" id="default-form">
                                            <div class="resturant--info-address">
                                                <ul class="address-info address-info-2 p-0 text-dark">
                                                    <li class="d-flex align-items-start">
                                                        <span class="label min-w-sm-auto">{{ translate('messages.Vendor Name') }}</span>
                                                        <span>: {{$store->name}} {{$store->name}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-start">
                                                        <span class="label min-w-sm-auto">{{ translate('messages.Business Address') }}</span>
                                                        <span>: {{$store->address}} </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        @foreach (json_decode($language) as $lang)
                                                <?php
                                                if(count($store?->translations ?? [])){
                                                    $translate = [];
                                                    foreach($store['translations'] as $t)
                                                    {
                                                        if($t->locale == $lang && $t->key=="name"){
                                                            $translate[$lang]['name'] = $t->value;
                                                        }
                                                    }
                                                }
                                                ?>
                                            <div class="d-none lang_form" id="{{ $lang }}-form">
                                                <div class="resturant--info-address">
                                                    <ul class="address-info address-info-2 p-0 text-dark">
                                                        <li class="d-flex align-items-start">
                                                            <span class="label min-w-sm-auto">{{ translate('messages.Provider Name') }}</span>
                                                            <span>: {{$translate[$lang]['name']??''}}</span>
                                                        </li>
                                                        <li class="d-flex align-items-start">
                                                            <span class="label min-w-sm-auto">{{ translate('messages.Business Address') }}</span>
                                                            <span>: {{$store->address}} </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div id="default-form">
                                            <div class="resturant--info-address">
                                                <ul class="address-info address-info-2 p-0 text-dark">
                                                    <li class="d-flex align-items-start">
                                                        <span class="label min-w-sm-auto">{{ translate('messages.Provider Name') }}</span>
                                                        <span>: {{ $store->name }} {{ $store->name }}</span>
                                                    </li>
                                                    <li class="d-flex align-items-start">
                                                        <span class="label min-w-sm-auto">{{ translate('messages.Business Address') }}</span>
                                                        <span>: {{ $store->address }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0 h-100">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.Owner_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info address-info-2 p-0 text-dark">
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.First Name') }}</span>
                                            <span>: {{$store->vendor->f_name}} </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.Last Zone') }}</span>
                                            <span>: {{$store->vendor->l_name}}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.Phone') }}</span>
                                            <span>: {{$store->vendor->phone}}</span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0 h-100">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.Pickup_Zone') }}
                                </h5>
                                <div class="d-flex gap-2 gap-sm-3 flex-wrap">
                                    @foreach(json_decode($store->pickup_zone_id) ?? [] as $pickup)
                                            <?php
                                            $zoneName = $store->pickupZones[$pickup] ?? 'Unknown Zone';
                                            ?>
                                        <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                            {{ $zoneName }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0 h-100">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.Login_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info address-info-2 p-0 text-dark">
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.Email') }}</span>
                                            <span>: {{ $store->vendor->email }}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.Password') }}</span>
                                            <span>: {{ translate('*************') }}</span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="collect-cash" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('messages.collect_cash_from_store')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.transactions.account-transaction.store')}}" method='post'
                          id="add_transaction">
                        @csrf
                        <input type="hidden" name="type" value="store">
                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                        <div class="form-group">
                            <label class="input-label">{{translate('messages.payment_method')}} <span
                                    class="input-label-secondary text-danger">*</span></label>
                            <input class="form-control" type="text" name="method" id="method" required maxlength="191"
                                   placeholder="{{translate('messages.Ex_:_Card')}}">
                        </div>
                        <div class="form-group">
                            <label class="input-label">{{translate('messages.reference')}}</label>
                            <input class="form-control" type="text" name="ref" id="ref" maxlength="191">
                        </div>
                        <div class="form-group">
                            <label class="input-label">{{translate('messages.amount')}} <span
                                    class="input-label-secondary text-danger">*</span></label>
                            <input class="form-control" type="number" min=".01" step="0.01" name="amount" id="amount"
                                   max="999999999999.99" placeholder="{{translate('messages.Ex_:_1000')}}">
                        </div>
                        <div class="btn--container justify-content-end">
                            <button type="submit" id="submit_new_customer"
                                    class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initMap&v=3.45.8"></script>
    <script>
        "use strict";
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        const myLatLng = {lat: {{$store->latitude}}, lng: {{$store->longitude}}};
        let map;
        initMap();

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });
            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{$store->name}}",
            });
        }

        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        function request_alert(url, message) {
            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }

        $('#add_transaction').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.transactions.account-transaction.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{translate('messages.transaction_saved')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.store.view', $store->id)}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
