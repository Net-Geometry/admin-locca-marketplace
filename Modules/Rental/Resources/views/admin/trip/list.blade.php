@extends('layouts.admin.app')

@section('title', translate('messages.all_trips'))

@push('css_or_js')
    <style>
        [data-toggle="tooltip"] img {
           width: 37px;
        }
    </style>
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
                            <span class="badge badge-soft-dark ml-2" id="itemCount">{{ $total }}</span>
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header justify-content-between gap-3 py-2 flex-wrap">
                <form action="" method="get" class="search-form flex-grow-1 max-w-450px">
                    <!-- Search -->
                    <input type="hidden" value="{{request()?->status  }}" name="status" >
                    <div class="input-group input--group">
                        <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                               name="search" class="form-control"
                               placeholder="{{ translate('Search by trip ID, customer name, email') }}"
                               aria-label="{{ translate('messages.Search by trip ID, customer name, email') }}">
                        <button type="submit" class="btn btn--secondary bg--primary"><i class="tio-search"></i></button>
                    </div>
                    <!-- End Search -->
                </form>
                @if (request()->get('search'))
                    <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                @endif
                <div class="search--button-wrapper justify-content-end gap-20px">
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
                               href="{{ route('admin.rental.trip.export', ['type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                     src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                     alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                               href="{{ route('admin.rental.trip.export', ['type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                     src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                     alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white h--40px filter-button-show" href="javascript:;">
                            <i class="tio-filter-list mr-1"></i> {{ translate('messages.filter') }} <span class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                        </a>
                    </div>
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
                        <th class="border-0">{{ translate('messages.Vendor') }}</th>
                        <th class="border-0">{{ translate('messages.Driver_Info') }}</th>
                        <th class="border-0">{{ translate('messages.Vehicle_Info') }}</th>
                        <th class="border-0">{{ translate('messages.Trip_Type') }}</th>
                        <th class="text-end border-0">{{ translate('messages.Trip_Amount') }}</th>
                        <th class="text-center border-0">{{ translate('messages.Trip_Status') }}</th>
                        <th class="text-center border-0">{{ translate('messages.Action') }}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($trips as $key=> $trip)
                    <tr>
                        <td>{{$key+$trips->firstItem()}}</td>
                        <td class="text--title font-semibold">
                            <a href="{{ route('admin.rental.trip.details', $trip->id) }}">{{ $trip->id }}</a>
                        </td>
                        <td>
                            <div class="text--title">
                                {{ $trip->BookingDate }}
                                <br>
                                {{ $trip->BookingTime }}
                            </div>
                        </td>
                        <td>
                            <div class="text--title">
                                {{ $trip->ScheduleDate }}
                                <br>
                                {{ $trip->ScheduleTime }}
                            </div>
                        </td>
                        <td>
                            <div class="text--title">
                                @if ($trip->customer)
                                    <div class="font-medium">
                                        {{ $trip->customer->fullName }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ $trip->customer->email }}
                                    </div>

                                @elseif($trip?->user_info['contact_person_name'])
                                    <div class="font-medium">
                                        {{$trip?->user_info['contact_person_name'] }}
                                    </div>
                                    <div class="opacity-lg">
                                        {{ $trip?->user_info['contact_person_email'] }}
                                    </div>
                                @else
                                    {{ translate('messages.Guest_user') }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text--title font-medium w--150px white--space-initial">
                                {{ $trip->provider->name }}
                            </div>
                        </td>
                        @php
                            $maxDisplay = 3;
                            $totalVehicle = count($trip->assignedVehicle);
                            $totalDriver = count($trip->assignedDriver);
                            $totalTripe = count($trip->trip_details);
                        @endphp
                        <td>
                            @if($totalDriver)
                                @if($totalDriver > 1)
                                    <div class="d-flex align-items-center gap-2" data-html="true" data-toggle="tooltip"
                                         title="<div class='d-flex flex-column p-2'>
                                             @foreach($trip->assignedDriver as $index => $tooltipDriver)
                                                <div class='media gap-3 {{ !$loop->last ? 'border-bottom mb-2 pb-2' : '' }}'>
                                                    <img src='{{ $tooltipDriver->driver['imageFullUrl'] }}' class='rounded ratio-1-1' width='40' alt='...'>
                                                    <div class='media-body'>
                                                        <h5 class='d-flex align-items-center gap-2 text-white mb-0'>{{ $tooltipDriver->driver['fullName'] }}</h5>
                                                        <div class='d-flex align-items-center gap-2 fs-10'>{{ $tooltipDriver->driver->email }}</div>
                                                    </div>
                                                </div>
                                             @endforeach
                                         </div>">
                                        <div class="d-flex">
                                            @foreach ($trip->assignedDriver->take($maxDisplay) as $key => $assignedDriver)
                                                <img width="35" class="rounded-circle aspect-1-1 border border-white shadow-sm {{ $key > 0 ? 'ml-n2' : '' }}" src="{{ $assignedDriver->driver['imageFullUrl'] }}" alt="">
                                            @endforeach
                                        </div>
                                        @if ($totalDriver > $maxDisplay)
                                            <span>+{{ $totalDriver - $maxDisplay }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text--title">
                                        @if ($trip->assignedDriver->isNotEmpty())
                                            <div class="font-medium">
                                                {{ $trip->assignedDriver->first()?->driver?->fullName }}
                                            </div>
                                            <div class="opacity-lg">
                                                {{ $trip->assignedDriver->first()?->driver?->email }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="text-muted fs-12 mt-1">
                                    {{ translate('messages.Unassigned') }}
                                </div>
                            @endif
                        </td>

                        <td>
                            @if($totalVehicle > 0)
                                <div class="text-primary text-underline font-weight-medium" data-html="true" data-toggle="tooltip"
                                     title="<div class='d-flex flex-column p-2'>
                                         @foreach($trip->trip_details as $index => $detail)
                                            <div class='media gap-3 {{ !$loop->last ? 'border-bottom mb-2 pb-2' : '' }}'>
                                                <img src='{{ $detail->vehicle['thumbnailFullUrl'] }}' class='rounded ratio-1-1' width='40' alt='...'>
                                                <div class='media-body'>
                                                    <h5 class='d-flex align-items-center gap-2 text-white mb-0'>{{ $detail->vehicle_details['name'] }}</h5>
                                                    <div class='d-flex align-items-center gap-2 fs-10'>{{ translate('messages.car_Assigned') }}: {{ $detail->tripVehicleDetails->count() }}</div>
                                                </div>
                                            </div>
                                         @endforeach
                                    </div>">
                                    {{ $totalVehicle }} {{ translate('messages.vehicles') }}
                                </div>
                            @else
                                <div class="text--warning font-medium">
                                    {{ translate('messages.Unassigned') }}
                                </div>
                            @endif

                        </td>
                        <td>
                            <div class="text--title">
                                <div class="font-medium">
                                    {{ $trip->trip_type }}
                                </div>
                                <div class="opacity-lg">
                                    {{ $trip->scheduled ? translate('messages.Instant') : translate('messages.scheduled') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text--title text-end">
                                <div class="font-semobold">
                                    {{ \App\CentralLogics\Helpers::format_currency($trip->trip_amount) }}
                                </div>
                                <div class="opacity-lg font-medium text--success">
                                    {{ ucwords($trip->payment_status) }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <label class="badge badge-soft-info border-0">
                                    {{ ucwords($trip->trip_status) }}
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="btn--container justify-content-center">
                                <a class="btn action-btn btn--primary btn-outline-primary" href="{{route("admin.rental.trip.generate-invoice",["id" => $trip->id])}}"
                                   title="{{ translate('messages.download') }}"><i class="tio-download-to"></i>
                                </a>
                                <a class="btn action-btn btn--primary btn-outline-primary" href="{{ route('admin.rental.trip.details', $trip->id) }}"
                                   title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            @if(count($trips) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $trips->appends($_GET)->links() !!}
            </div>
            @if(count($trips) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
            @endif
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

    <div id="datatableFilterSidebar" class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow initial-hidden">
        <div class="card card-lg sidebar-card sidebar-footer-fixed">
            <div class="card-header">
                <h4 class="card-header-title">{{translate('messages.order_filter')}}</h4>

                <!-- Toggle Button -->
                <a class="js-hs-unfold-invoker_ btn btn-icon btn-sm btn-ghost-dark ml-2 filter-button-hide" href="javascript:;">
                    <i class="tio-clear tio-lg"></i>
                </a>
                <!-- End Toggle Button -->
            </div>
            @php
                $filterCount = 0;
                if(isset($zone_ids) && count($zone_ids) > 0) $filterCount += 1;
                if(isset($provider_ids) && count($provider_ids)>0) $filterCount += 1;

                if($status == 'all')
                {
                    if(isset($tripStatus) && count($tripStatus) > 0) $filterCount += 1;
                    if(isset($scheduled) && $scheduled == 1) $filterCount += 1;
                }

                if(isset($from_date) && isset($to_date)) $filterCount += 1;
                if(isset($order_type)) $filterCount += 1;
            @endphp
                <!-- Body -->
            <form class="card-body sidebar-body sidebar-scrollbar" action="" method="get" id="order_filter_form">
                <input type="hidden" name="status" value="{{ request()->status }}">
                <small class="text-cap mb-3">{{translate('messages.zone')}}</small>

                <div class="mb-2 initial--21">
                    <select name="zone_ids[]" id="zone_ids" class="form-control js-select2-custom" multiple="multiple">
                        @foreach(\App\Models\Zone::all() as $zone)
                            <option value="{{$zone->id}}" {{isset($zone_ids)?(in_array($zone->id, $zone_ids)?'selected':''):''}}>{{$zone->name}}</option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">
                <small class="text-cap mb-3">{{translate('messages.store')}}</small>
                <div class="mb-2 initial--21">
                    <select name="provider_ids[]" id="provider_ids" class="form-control js-select2-custom test-class" multiple="multiple">
                        @foreach(\App\Models\Store::all() as $store)
                            <option value="{{$store->id}}"
                                    @if(isset($provider_ids) && in_array($store->id, $provider_ids))
                                        selected
                                    @endif>
                                {{$store->name}}
                            </option>
                        @endforeach
                    </select>
                </div>


                <hr class="my-4">
                @if($status == 'all')
                    <small class="text-cap mb-3">{{translate('messages.order_status')}}</small>

                    <!-- Custom Checkbox -->
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus2" name="tripStatus[]" class="custom-control-input" value="pending" {{isset($tripStatus)?(in_array('pending', $tripStatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus2">{{translate('messages.pending')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus1" name="tripStatus[]" class="custom-control-input" value="confirmed" {{isset($tripStatus)?(in_array('confirmed', $tripStatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus1">{{translate('messages.confirmed')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus3" name="tripStatus[]" class="custom-control-input" value="ongoing" {{isset($tripStatus)?(in_array('ongoing', $tripStatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus3">{{translate('messages.processing')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus5" name="tripStatus[]" class="custom-control-input" value="completed" {{isset($tripStatus)?(in_array('completed', $tripStatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus5">{{translate('messages.delivered')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus8" name="tripStatus[]" class="custom-control-input" value="canceled" {{isset($tripStatus)?(in_array('canceled', $tripStatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus8">{{translate('messages.canceled')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus7" name="tripStatus[]" class="custom-control-input" value="payment_failed" {{isset($tripStatus)?(in_array('payment_failed', $tripStatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus7">{{translate('messages.failed')}}</label>
                    </div>
                @endif

                <hr class="my-4">

                <small class="text-cap mb-3">{{translate('messages.date_between')}}</small>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group m-0">
                            <input type="date" name="from_date" class="form-control" id="date_from" value="{{isset($from_date)?$from_date:''}}">
                        </div>
                    </div>
                    <div class="col-12 text-center">----{{ translate('messages.to') }}----</div>
                    <div class="col-12">
                        <div class="form-group">
                            <input type="date" name="to_date" class="form-control" id="date_to" value="{{isset($to_date)?$to_date:''}}">
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer sidebar-footer">
                    <div class="row gx-2">
                        <div class="col">
                            <button type="reset" class="btn btn-block btn-white" id="reset">{{ translate('Clear all filters') }}</button>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-block btn-primary">{{ translate('messages.save') }}</button>
                        </div>
                    </div>
                </div>
                <!-- End Footer -->
            </form>
        </div>
    </div>
@endsection


@push('script_2')
    <script src="{{asset('public/assets/admin')}}/js/view-pages/order-list.js"></script>

    <script>
        $(document).ready(function () {

            @if($filterCount > 0)
                $('#filter_count').html({{$filterCount}});
            @endif

            $('#zone_ids').on('change', function () {
                $('#provider_ids').val(null).trigger('change');
                $('#provider_ids').trigger('change');
            });

            $('#provider_ids').select2({
                ajax: {
                    url: '{{url('/')}}/admin/store/get-providers',
                    data: function (params) {
                        return {
                            q: params.term,
                            zone_ids: $('#zone_ids').val(),
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#reset').on('click', function(){
                location.href = '{{url('/')}}/admin/rental/trip?status=all';
            });
        });

    </script>
@endpush
