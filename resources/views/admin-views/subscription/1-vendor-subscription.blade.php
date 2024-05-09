@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start">
                        <img src="{{asset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Green Mart Subscription')}}</h1>
                        </div>
                    </div>
                </div>
                <div class="min--200">
                    <select name="zone_id" class="form-control js-select2-custom set-filter" data-url="{{ url()->full() }}" data-filter="zone_id" id="zone">
                        <option value="all">{{translate('All Zones')}}</option>
                        @foreach(\App\Models\Zone::orderBy('name')->get() as $z)
                            <option value="{{$z['id']}}" {{isset($zone) && $zone->id == $z['id']?'selected':''}}>
                                {{($z['name'])}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-20">
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-1" href="#">
                        <h4 class="title text--title">2,324</h4>
                        <span class="subtitle">Total Subscribed User</span>
                        <img src="{{asset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-3" href="#">
                        <h4 class="title text--title">2,000</h4>
                        <span class="subtitle">Active Subscriptions</span>
                        <img src="{{asset('public/assets/admin/img/subscription-plan/active-user.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-6" href="#">
                        <h4 class="title text--title">324</h4>
                        <span class="subtitle">Expired Subscription</span>
                        <img src="{{asset('public/assets/admin/img/subscription-plan/expired-user.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <a class="__card-2 __bg-4" href="#">
                        <h4 class="title text--title">324</h4>
                        <span class="subtitle">Expiring Soon </span>
                        <img src="{{asset('public/assets/admin/img/subscription-plan/expired-soon.png')}}" alt="report/new" class="card-icon" width="35px">
                    </a>
                </div>
            </div>
        </div>
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    <span>Total transactions</span> <strong>0</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--success">
                <i class="tio-checkmark-circle-outlined success--icon"></i>
                <div>
                    <span>Total earning</span> <strong>0.00 ৳</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--warning">
                <i class="tio-atm"></i>
                <div>
                    <span>EARNED THIS MONTH</span> <strong>0.00 ৳</strong>
                </div>
            </li>
        </ul>
        <div class="card">
            <div class="card-header flex-wrap py-2 border-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h4 class="mb-0">Store List</h4>
                    <span class="badge badge-soft-dark rounded-circle">75</span>
                </div>
                <div class="search--button-wrapper justify-content-end">
                    <div class="max-sm-flex-1">
                        <select class="custom-select h--40px py-0 status-filter">
                            <option value="all">
                                All Subscription
                            </option>
                            <option value="approved">
                                Approved Subscription
                            </option>
                            <option value="denied">
                                Denied Subscription
                            </option>
                            <option value="pending">
                                Pending Subscription
                            </option>

                        </select>
                    </div>
                    <form class="search-form">
                        <div class="input-group input--group">
                            <input name="search" type="search" value="" class="form-control h--40px" placeholder="Ex :Search by name & package name" aria-label="Search here">
                            <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                        </div>
                    </form>
                    <!-- Unfold -->
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
                    <!-- End Unfold -->
                </div>
                <!-- End Row -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless middle-align __txt-14px">
                        <thead class="thead-light white--space-false">
                            <th class="border-top px-4 border-bottom text-center">SL</th>
                            <th class="border-top px-4 border-bottom">Store Info</th>
                            <th class="border-top px-4 border-bottom">Package Name</th>
                            <th class="border-top px-4 border-bottom">Package Price</th>
                            <th class="border-top px-4 border-bottom">Exp Date</th>
                            <th class="border-top px-4 border-bottom">Used</th>
                            <th class="border-top px-4 border-bottom text-center">Status</th>
                            <th class="border-top px-4 border-bottom text-center">Action</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 text-center">1</td>
                                <td class="px-4">
                                    <a href="" alt="view restaurant" class="table-rest-info">
                                        <img src="">
                                        <div class="info">
                                            <span class="d-block text-title">
                                                Green Mart<br>
                                                <span class="rating text-star"><i class="tio-star"></i> 0</span>
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4">
                                    <div>Standard</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">$399</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">01-Jul-2023</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title pl-3">02</div>
                                </td>
                                <td class="px-4 text-center">
                                    <div>
                                        <span class="badge badge-soft-success">Active</span>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 text-center">2</td>
                                <td class="px-4">
                                    <a href="" alt="view restaurant" class="table-rest-info">
                                        <img src="">
                                        <div class="info">
                                            <span class="d-block text-title">
                                                Green Mart<br>
                                                <span class="rating text-star"><i class="tio-star"></i> 0</span>
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4">
                                    <div>Standard</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">$399</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">01-Jul-2023</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title pl-3">02</div>
                                </td>
                                <td class="px-4 text-center">
                                    <div>
                                        <span class="badge badge-soft-success">Active</span>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 text-center">3</td>
                                <td class="px-4">
                                    <a href="" alt="view restaurant" class="table-rest-info">
                                        <img src="">
                                        <div class="info">
                                            <span class="d-block text-title">
                                                Green Mart<br>
                                                <span class="rating text-star"><i class="tio-star"></i> 0</span>
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4">
                                    <div>Standard</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">$399</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">01-Jul-2023</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title pl-3">02</div>
                                </td>
                                <td class="px-4 text-center">
                                    <div>
                                        <span class="badge badge-soft-success">Active</span>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('script_2')

@endpush

