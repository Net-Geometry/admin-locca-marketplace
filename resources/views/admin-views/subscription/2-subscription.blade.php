@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

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
                            <h1 class="page-header-title mb-0">{{translate('Subscription Package List')}} <span class="badge badge-soft-dark ml-2">03</span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    <a href="" class="btn btn--primary border-0"><i class="tio-add"></i> {{translate('Add Subcription Package')}}</a>
                    <!-- Static Export Button -->
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless middle-align __txt-14px">
                        <thead class="thead-light white--space-false">
                            <th class="border-top border-bottom text-center">SL</th>
                            <th class="border-top border-bottom">Package Name</th>
                            <th class="border-top border-bottom">
                                <div class="text-title">Pricing</div>
                            </th>
                            <th class="border-top border-bottom">Duration</th>
                            <th class="border-top border-bottom text-center">Current Subscriber</th>
                            <th class="border-top border-bottom">Status</th>
                            <th class="border-top border-bottom text-center">Action</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <div class="text-title">Basic Plan</div>
                                </td>
                                <td>
                                    <div class="w--85px text-title text-right pr-5">$99</div>
                                </td>
                                <td>
                                    <div class="text-title">30 days</div>
                                </td>
                                <td>
                                    <div class="text-title text-center">356</div>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm">
                                        <input type="checkbox" class="toggle-switch-input">
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--primary btn-outline-primary" href="">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <div class="text-title">Standared Plan</div>
                                </td>
                                <td>
                                    <div class="w--85px text-title text-right pr-5">$99</div>
                                </td>
                                <td>
                                    <div class="text-title">30 days</div>
                                </td>
                                <td>
                                    <div class="text-title text-center">356</div>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm">
                                        <input type="checkbox" class="toggle-switch-input">
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--primary btn-outline-primary" href="">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <div class="text-title">Premium Plan</div>
                                </td>
                                <td>
                                    <div class="w--85px text-title text-right pr-5">$99</div>
                                </td>
                                <td>
                                    <div class="text-title">30 days</div>
                                </td>
                                <td>
                                    <div class="text-title text-center">356</div>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm">
                                        <input type="checkbox" class="toggle-switch-input">
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--primary btn-outline-primary" href="">
                                            <i class="tio-edit"></i>
                                        </a>
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

