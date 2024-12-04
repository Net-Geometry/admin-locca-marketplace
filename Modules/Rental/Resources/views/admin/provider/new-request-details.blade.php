@extends('layouts.admin.app')

@section('title',$store->name)

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        @include('admin-views.vendor.view.partials._header',['store'=>$store])
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title m-0 d-flex align-items-center">
                    <span class="card-header-icon mr-2">
                        <i class="tio-shop-outlined"></i>
                    </span>
                    <span class="ml-1">{{translate('messages.store_info')}}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-6">
                        <div class="resturant--info-address">
                            <div class="logo">
                                <img class="onerror-image" data-onerror-image="{{asset('public/assets/admin/img/100x100/1.png')}}"
                                src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/100x100/1.png') }}"

                                alt="{{$store->name}} Logo">
                            </div>
                            <ul class="address-info list-unstyled list-unstyled-py-3 text-dark">
                                <li>
                                    <h5 class="name">{{$store->name}}</h5>
                                </li>
                                <li>

                                    <i class="tio-city nav-icon"></i>
                                    <span>{{translate('messages.address')}}</span> <span>:</span> &nbsp; <span>

                                    <a href="https://www.google.com/maps/search/?api=1&query={{ data_get($store,'latitude',0)}},{{ data_get($store,'longitude',0)}}" target="_blank">{{$store->address}}</a></span>

                                </li>

                                <li>
                                    <i class="tio-call-talking nav-icon"></i>
                                    <span>{{translate('messages.email')}}</span> <span>:</span> &nbsp; <a href="mailto:{{$store->email}}"><span>{{$store->email}}</span></a>
                                </li>
                                <li>
                                    <i class="tio-email nav-icon"></i>
                                    <span>{{translate('messages.phone')}}</span> <span>:</span> &nbsp; <a href="tel:{{$store->phone}}"><span>{{$store->phone}}</span></a>
                                </li>
                                <li>
                                    <i class="tio-map nav-icon"></i>
                                    <span>{{translate('messages.Zone')}}</span> <span>:</span> &nbsp; <span>{{$store?->zone?->name ?? translate('zone_deleted')}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div id="map" class="single-page-map"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-3 g-3">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-user"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.owner_info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="resturant--info-address">
                            <div class="avatar avatar-xxl avatar-circle avatar-border-lg">
                                <img class="avatar-img onerror-image" data-onerror-image="{{asset('public/assets/admin/img/160x160/img1.jpg')}}"

                                src="{{ $store->vendor->image ?? asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                alt="Image Description">
                            </div>
                            <ul class="address-info address-info-2 list-unstyled list-unstyled-py-3 text-dark">
                                <li>
                                    <h5 class="name">{{$store->vendor->f_name}} {{$store->vendor->l_name}}</h5>
                                </li>
                                <li>
                                    <i class="tio-call-talking nav-icon"></i>
                                    <span class="pl-1"><a href="mailto:{{$store->vendor->email}}">{{$store->vendor->email}}</a> </span>
                                </li>
                                <li>
                                    <i class="tio-email nav-icon"></i>
                                    <span class="pl-1"> <a href="tel:{{$store->vendor->phone}}"> {{$store->vendor->phone}} </a></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-crown"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.Business_Plan')}}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="resturant--info-address">
                            <ul class="address-info address-info-2 list-unstyled list-unstyled-py-3 text-dark">

                            @if ($store->store_business_model == 'commission')
                            <li>
                                <span>  <strong>{{translate('messages.Business_Plan')}}</span></strong>  <span>:</span> &nbsp; {{ translate($store->store_business_model) }}
                            </li>
                            @php($admin_commission = \App\Models\BusinessSetting::where(['key' => 'admin_commission'])->first()?->value)
                            <li>
                                <span><strong>{{translate('messages.Commission_percentage')}}</strong></span> <span>:</span> &nbsp; {{ $store->comission > 0 ?  $store->comission : $admin_commission }} %
                            </li>
                            @elseif ($store->store_business_model == 'subscription')
                                <li>
                                    <span>  <strong>{{translate('messages.Business_Plan')}}</span></strong>  <span>:</span> &nbsp; {{ translate($store->store_business_model) }} &nbsp;
                                    @if ($store?->store_sub_update_application->is_trial == '1')
                                    <small> <span class="badge badge-info" >{{ translate('messages.Free_trial')}}</span> </small>
                                    @endif
                                </li>
                                <li>
                                    <span> <strong>{{translate('messages.Package_name')}}</strong></span> <span>:</span> &nbsp; {{ $store?->store_sub_update_application?->package?->package_name  ?? translate('Pacakge_not_found!!!')}}
                                </li>
                            @elseif ($store->store_business_model == 'unsubscribed')
                                <li>
                                    <span>  <strong>{{translate('messages.Business_Plan')}}</span></strong>  <span>:</span> &nbsp; {{ translate($store->store_business_model) }} &nbsp;

                                    <small> <span class="badge badge-danger" >{{ translate('messages.Expired')}}</span> </small>

                                </li>
                                <li>
                                    <span> <strong>{{translate('messages.Package_name')}}</strong></span> <span>:</span> &nbsp; {{ $store?->store_sub_update_application?->package?->package_name  ?? translate('Pacakge_not_found!!!')}}
                                </li>
                                @elseif($store->store_business_model == 'none' && $store->package_id )
                                    <li>
                                    <span>  <strong>{{translate('messages.Business_Plan')}}</span></strong>  <span>:</span> &nbsp; {{translate('messages.Subscription')}}
                                </li>
                                    <li>
                                    <span>  <strong>{{translate('messages.Package_Name')}}</span></strong>  <span>:</span> &nbsp; {{App\Models\SubscriptionPackage::where('id',$store->package_id)->first()?->package_name   }}
                                </li>
                                    <li>
                                    <span>  <strong>{{translate('Payment_status')}}</span></strong>  <span>:</span> &nbsp; {{ translate('messages.payment_failed')   }}
                                </li>
                                @else
                                    <li>
                                    <span>  <strong>{{translate('messages.Business_Plan')}}</span></strong>  <span>:</span> &nbsp; {{ translate('Have_n’t_Selected_Yet.') }}
                                </li>
                            @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection

@push('script_2')
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initMap&v=3.45.8" ></script>
    <script>
        "use strict";
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        const myLatLng = { lat: {{$store->latitude}}, lng: {{$store->longitude}} };
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
