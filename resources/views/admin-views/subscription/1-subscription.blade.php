@extends('layouts.admin.app')

@section('title',translate('messages.Subscription'))

@section('subscription_index')
active
@endsection

@push('css_or_js')

@endpush

@section('content')





        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center py-2">
                    <div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <img src="{{asset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                            <div class="w-0 flex-grow pl-2">
                                <h1 class="page-header-title mb-0">{{translate('Subscription Package List')}} <span class="badge badge-soft-dark ml-2">{{ $packages->total() > 0 ? $packages->total() : ''  }}</span></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


                @if ($packages->total() > 0)
                <div class="card mb-20">
                    <div class="card-header border-0">
                        <div class="w-100 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div>
                                <h3 class="text--title card-title">Overview</h3>
                                <div>See overview of all the packages</div>
                            </div>
                            <div class="status-filter-wrap m-0">
                                <div class="statistics-btn-grp">
                                    <label>
                                        <input type="radio" name="statistics" value="this_year" class="order_stats_update" hidden="" checked>
                                        <span>This Year</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="statistics" value="this_month" class="order_stats_update" hidden="">
                                        <span>This Month</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="statistics" value="this_week" class="order_stats_update" hidden="">
                                        <span>This Week</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="w-100">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-4">
                                    <a class="__card-1 h-100 __bg-1" href="#">
                                        <img src="{{asset('public/assets/admin/img/plan/basic.png')}}" class="icon" alt="report/new">
                                        <h6 class="subtitle">Basic</h6>
                                        <h3 class="title">$1,000</h3>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <a class="__card-1 h-100 __bg-4" href="#">
                                        <img src="{{asset('public/assets/admin/img/plan/standard.png')}}" class="icon" alt="report/new">
                                        <h6 class="subtitle">Standard</h6>
                                        <h3 class="title">$1,000</h3>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <a class="__card-1 h-100 __bg-8" href="#">
                                        <img src="{{asset('public/assets/admin/img/plan/pro.png')}}" class="icon" alt="report/new">
                                        <h6 class="subtitle">Pro</h6>
                                        <h3 class="title">$1,000</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0 px-3 py-2">
                        <div class="search--button-wrapper justify-content-end">
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input class="form-control" placeholder="{{ translate('Search by name') }}" name="search">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <!-- Static Export Button -->
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn font--sm"
                                    href="javascript:;"
                                    data-hs-unfold-options="{
                                        &quot;target&quot;: &quot;#usersExportDropdown&quot;,
                                        &quot;type&quot;: &quot;css-animation&quot;
                                    }"
                                    data-hs-unfold-target="#usersExportDropdown" data-hs-unfold-invoker="">
                                    <i class="tio-download-to mr-1"></i> {{ translate('export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right hs-unfold-content-initialized hs-unfold-css-animation animated hs-unfold-reverse-y hs-unfold-hidden">

                                    <span class="dropdown-header">{{ translate('download_options') }}</span>
                                    <a id="export-excel" class="dropdown-item"
                                        href="{{ route('admin.transactions.report.day-wise-report-export', ['type' => 'excel', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin/svg/components/excel.svg') }}"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item"
                                        href="{{ route('admin.transactions.report.day-wise-report-export', ['type' => 'csv', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>

                                </div>
                            </div>
                            <a href="{{ route('admin.business-settings.subscriptionackage.create') }}" class="btn btn--primary border-0"><i class="tio-add"></i> {{translate('Add Subcription Package')}}</a>
                            <!-- Static Export Button -->
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless middle-align __txt-14px">
                                <thead class="thead-light white--space-false">
                                    <th class="border-top border-bottom text-center"> {{ translate('messages.sl') }}</th>
                                    <th class="border-top border-bottom">{{translate('Package_Name')}}</th>
                                    <th class="border-top border-bottom"><div class="text-title">{{translate('messages.Pricing')}}</div> </th>
                                    <th class="border-top border-bottom">{{translate('messages.duration') }}</th>
                                    <th class="border-top border-bottom text-center">{{translate('Current_Subscriber')}} </th>
                                    <th class="border-top border-bottom">{{translate('messages.status')}}</th>
                                    <th class="border-top border-bottom text-center">{{translate('messages.actions')}}</th>
                                </thead>
                                <tbody>

                                    @foreach ($packages as $key => $package)
                                    <tr>
                                        <td class="text-center"> {{$key+$packages->firstItem()}}</td>
                                        <td>
                                            <div title="{{ $package->package_name }}" class="text-title">  <a class="text-dark" href="{{route('admin.business-settings.subscriptionackage.show',[$package['id']])}}">{{ Str::limit($package->package_name, 20, '...')   }}</a> </div>
                                        </td>
                                        <td>
                                            <div class="w--120px text-title text-right pr-5">{{ \App\CentralLogics\Helpers::format_currency($package->price) }}</div>
                                        </td>
                                        <td>
                                            <div class="text-title">{{$package->validity}} {{ translate('days') }}</div>
                                        </td>
                                        <td>
                                            <div class="text-title text-center">{{$package->current_subscribers_count ?? 0}}</div>
                                        </td>
                                        <td>
                                                <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$package->id}}">
                                                    <input type="checkbox" data-url="{{route('admin.business-settings.subscriptionackage.status',[$package->id,$package->status?0:1])}}" data-message="{{$package->status?translate('Do_You_Want_To_Disable_This_Package'):translate('Do_you_want_to_Active_This_Package')}}"
                                                    class="toggle-switch-input status_change_alert" id="stocksCheckbox{{$package->id}}" {{$package->status?'checked':''}}>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn action-btn btn--primary btn-outline-primary" href="{{ route('admin.business-settings.subscriptionackage.edit',$package->id) }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn action-btn btn--warning btn-outline-warning" href="{{route('admin.business-settings.subscriptionackage.show',[$package['id']])}}">
                                                    <i class="tio-invisible"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @else

                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="max-w-542 mx-auto py-sm-5 py-4">
                            <img class="mb-4" src="{{asset('/public/assets/admin/img/empty-subscription.svg')}}" alt="img">
                            <h4 class="mb-3">{{translate('Create Subscription Plan')}}</h4>
                            <p class="mb-4">
                                {{translate('Add new subscription packages to the list. So that Providers get more options to join the business for the growth and success.')}}<br>
                            </p>
                            <a href="{{ route('admin.business-settings.subscriptionackage.create') }}" class="btn btn--primary border-0"><i class="tio-add"></i> {{translate('Add Subcription Package')}}</a>
                        </div>
                    </div>
                </div>
                @endif


    </div>




@endsection

@push('script_2')
<script>
     "use strict";
            $('.status_change_alert').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            status_change_alert(url, message, event)
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href=url;
                }
            })
        }
</script>
@endpush

