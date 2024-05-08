@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm">
                    <div class="d-flex align-items-start">
                        <img src="{{asset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Subscription Package')}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
            <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
                <li class="nav-item">
                    <a href="" class="nav-link">Package Details</a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link active">Transactions</a>
                </li>
            </ul>
        </div>
        <div class="card mb-20">
            <div class="card-header border-0">
                <h3 class="text--title card-title">Filter Option</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize">{{translate('Duration')}}</label>
                            <select class="form-control">
                                <option value="">All Time</option>
                                <option value="">This Year</option>
                                <option value="">This Month</option>
                                <option value="">This Week</option>
                                <option value="">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize" for="date_from">{{translate('start_date')}}</label>
                            <input type="date" name="start_date" class="form-control" id="date_from" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize" for="date_to">{{translate('end_date')}}</label>
                            <input type="date" name="expire_date" class="form-control" id="date_to" required>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="reset" class="btn btn--reset">Reset</button>
                    <button type="submit" class="btn btn--primary">Submit</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header flex-wrap py-2 border-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <h4 class="mb-0">Transaction History</h4>
                    <span class="badge badge-soft-dark rounded-circle">75</span>
                </div>
                <div class="search--button-wrapper justify-content-end">
                    <div class="max-sm-flex-1">
                        <select class="custom-select h--40px py-0 status-filter">
                            <option value="all">
                                All
                            </option>
                            <option value="approved">
                                Approved
                            </option>
                            <option value="denied">
                                Denied
                            </option>
                            <option value="pending">
                                Pending
                            </option>

                        </select>
                    </div>
                    <form class="search-form">
                        <div class="input-group input--group">
                            <input name="search" type="search" value="" class="form-control h--40px" placeholder="Ex : Search by ID or store name" aria-label="Search here">
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
                            <th class="border-top px-4 border-bottom">Transaction ID</th>
                            <th class="border-top px-4 border-bottom"><div class="text-title">Transaction Date</div></th>
                            <th class="border-top px-4 border-bottom">Store</th>
                            <th class="border-top px-4 border-bottom">Pricing</th>
                            <th class="border-top px-4 border-bottom">Payment Type</th>
                            <th class="border-top px-4 border-bottom text-center">Action</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 text-center">1</td>
                                <td class="px-4">
                                    <div class="text-title">89662389643</div>
                                </td>
                                <td class="px-4">
                                    <div class="pl-4">22 July, 2022</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">Hungry Puppets</div>
                                </td>
                                <td class="px-4">
                                    <div class="w--85px text-title text-right pr-5">$99</div>
                                </td>
                                <td class="px-4">
                                    <div>
                                        <div class="text-title">Auto Renewal</div>
                                        <div class="text-success font-medium">Paid by Stripe</div>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 text-center">2</td>
                                <td class="px-4">
                                    <div class="text-title">89662389643</div>
                                </td>
                                <td class="px-4">
                                    <div class="pl-4">22 July, 2022</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">Hungry Puppets 
                                        <span title="<div class='text-left'>Expiring Soon <br /> Expiration Date: 22 Octuber, 2022</div>" data-toggle="tooltip" data-html="true">
                                            <img src="{{asset('/public/assets/admin/img/invalid.svg')}}" alt="">
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="w--85px text-title text-right pr-5">$99</div>
                                </td>
                                <td class="px-4">
                                    <div>
                                        <div class="text-title">Auto Renewal</div>
                                        <div class="text-success font-medium">Paid by Stripe</div>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 text-center">3</td>
                                <td class="px-4">
                                    <div class="text-title">89662389643</div>
                                </td>
                                <td class="px-4">
                                    <div class="pl-4">22 July, 2022</div>
                                </td>
                                <td class="px-4">
                                    <div class="text-title">Hungry Puppets</div>
                                </td>
                                <td class="px-4">
                                    <div class="w--85px text-title text-right pr-5">$99</div>
                                </td>
                                <td class="px-4">
                                    <div>
                                        <div class="text-title">Auto Renewal</div>
                                        <div class="text-success font-medium">Paid by Stripe</div>
                                    </div>
                                </td>
                                <td class="px-4">
                                    <div class="btn--container justify-content-center">
                                        <a class="btn action-btn btn--warning btn-outline-warning" href="">
                                            <i class="tio-print"></i>
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

