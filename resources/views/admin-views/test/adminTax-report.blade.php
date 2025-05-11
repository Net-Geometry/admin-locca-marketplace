@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">

   
    <!--- Admin Tax Report -->
    <h2 class="mb-20">Admin Tax Report</h3>
    <div class="card p-20 mb-20">
        <div class="mb-20">
            <h3 class="mb-1">Generate Tax Report</h3>
            <p class="mb-0 fz-12">To generate you tax report please select & input following field and submit for the result.</p>
        </div>
        <div class="bg--secondary rounded p-20 mb-20">
            <div class="row g-lg-4 g-md-3 g-2">            
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-lg-4 gap-3">
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Date Range Type</span>
                            <select class="custom-select custom-select-color border rounded w-100">
                                <option>
                                    This Fiscal Year
                                </option>
                                <option>
                                    Custom Date Range
                                </option>
                                <option>
                                    Custom Date Range
                                </option>
                            </select>
                        </div>
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Tax on Order Commission</span>
                            <select required name="" id="select_customer_fiscal1" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate">
                                <option value="all">all</option>
                                <option value="2">Income Tax (15%)</option>
                                <option value="2">VAT (5%)</option>
                            </select>
                        </div>
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Tax on Delivery charge</span>
                            <select required name="" id="select_customer_fiscal2" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate">
                                <option value="all">all</option>
                                <option value="2">VAT (5%)</option>
                                <option value="2">GST (7%)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-lg-4 gap-3">
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Select How to calculate tax</span>
                            <select class="custom-select custom-select-color border rounded w-100">
                                <option>
                                    Same Tax for All Income Source
                                </option>
                                <option>
                                    Different Tax for Different Income Source
                                </option>
                                <option>
                                    Same Tax for All Income Source
                                </option>
                            </select>
                        </div>
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Tax on Service charge</span>
                            <select required name="" id="select_customer_fiscal-3" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate">
                                <option value="all">all</option>
                                <option value="2">Service Tax (5%)</option>
                                <option value="2">GST (5%)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end gap-2">
            <button type="button" class="btn bg--secondary h--42px title-clr px-4">Reset</button>
            <button type="button" class="btn btn--primary">Submit</button>
        </div>
    </div>
    <div class="card p-20 mb-20">
        <div class="row g-lg-4 g-3">
            <div class="col-md-6">
                <div class="bg-opacity-primary-10 rounded p-20 d-flex align-items-center gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-3 title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-toal-amount.png')}}" alt="img">
                        Total Income
                    </div>
                    <h3 class="theme-clr fw-bold mb-0">$ 12,345.25</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-opacity-warning-10 rounded p-20 d-flex align-items-center gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-3 title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-tax-amount.png')}}" alt="img">
                        Total Tax
                    </div>
                    <h3 class="text-danger fw-bold mb-0">$ 00.00</h3>
                </div>
            </div>
        </div>
    </div>
    <!--- Vendor Tax Report Here -->
    <div class="card p-20 mb-20">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-20">
            <h4 class="mb-0">Tax Report List</h4>
            <div class="search--button-wrapper justify-content-end">
                <form class="search-form min--260">
                    <div class="input-group input--group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                placeholder="{{ translate('messages.Ex:') }} 10010" value="{{ request()?->search ?? null}}" aria-label="{{translate('messages.search')}}">
                                
                                <input type="hidden" name="parcel_order" value="">
                                
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                </form>
                @if(request()->get('search'))
                    <button type="reset" class="btn btn--primary ml-2 location-reload-to-base" data-url="{{url()->full()}}">{{translate('messages.reset')}}</button>
                @endif
                <!-- Datatable Info -->
                <div id="datatableCounterInfo" class="mr-2 mb-2 mb-sm-0 initial-hidden">
                    <div class="d-flex align-items-center">
                        <span class="font-size-sm mr-3">
                        <span id="datatableCounter">0</span>
                        {{translate('messages.selected')}}
                        </span>
                    </div>
                </div>
                <div class="hs-unfold mr-2">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px" href="javascript:;"
                        data-hs-unfold-options='{
                            "target": "#usersExportDropdown__admin", "type": "css-animation" }'>
                        <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                    </a>
                    <div id="usersExportDropdown__admin" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                        <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                        <a id="export-excel" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                            .{{translate('messages.csv')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table fz--14px">
                <thead class="thead-light">
                <tr>
                    <th class="border-0">sl</th>
                    <th class="border-0">Income Source</th>
                    <th class="border-0">Total Income</th>
                    <th class="border-0">Tax Amount</th>
                    <th class="border-0 text-center">Action</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            Vendor Subscription
                        </td>
                        <td>
                            $ 1,030.25
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-120px">Total Tax (12%)</span>  <span>$ 160.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">VAT (5%)</span> <span>$ 50.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">GST (7%)</span> <span>$ 110.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a class="btn btn-sm theme-border action-btn theme-hover theme-clr" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            Service Charge
                        </td>
                        <td>
                            $ 830.25
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-120px">Total Tax (12%)</span>  <span>$ 160.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">VAT (5%)</span> <span>$ 50.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">GST (7%)</span> <span>$ 110.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a class="btn btn-sm theme-border action-btn theme-hover theme-clr" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3
                        </td>
                        <td>
                            Delivery Commission
                        </td>
                        <td>
                            $ 230.25
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-120px">Total Tax (12%)</span>  <span>$ 160.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">VAT (5%)</span> <span>$ 50.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">GST (7%)</span> <span>$ 110.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a class="btn btn-sm theme-border action-btn theme-hover theme-clr" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4
                        </td>
                        <td>
                            Vendor Subscription
                        </td>
                        <td>
                            $ 1,030.25
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-120px">Total Tax (12%)</span>  <span>$ 160.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">VAT (5%)</span> <span>$ 50.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-120px">GST (7%)</span> <span>$ 110.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a class="btn btn-sm theme-border action-btn theme-hover theme-clr" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Error Tax Report List Table -->
    <div class="card p-20">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-20">
            <h4 class="mb-0">Tax Report List</h4>
            <div class="search--button-wrapper justify-content-end">
                <form class="search-form min--260">
                    <div class="input-group input--group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                placeholder="{{ translate('messages.Ex:') }} 10010" value="{{ request()?->search ?? null}}" aria-label="{{translate('messages.search')}}">
                                
                                <input type="hidden" name="parcel_order" value="">
                                
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                </form>
                @if(request()->get('search'))
                    <button type="reset" class="btn btn--primary ml-2 location-reload-to-base" data-url="{{url()->full()}}">{{translate('messages.reset')}}</button>
                @endif
                <!-- Datatable Info -->
                <div id="datatableCounterInfo" class="mr-2 mb-2 mb-sm-0 initial-hidden">
                    <div class="d-flex align-items-center">
                        <span class="font-size-sm mr-3">
                        <span id="datatableCounter">0</span>
                        {{translate('messages.selected')}}
                        </span>
                    </div>
                </div>
                <div class="hs-unfold mr-2">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px" href="javascript:;"
                        data-hs-unfold-options='{
                            "target": "#usersExportDropdown__admin3", "type": "css-animation" }'>
                        <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                    </a>
                    <div id="usersExportDropdown__admin3" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                        <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                        <a id="export-excel" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                            .{{translate('messages.csv')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive datatable-custom">
            <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table fz--14px">
                <thead class="thead-light">
                <tr>
                    <th class="border-0">sl</th>
                    <th class="border-0">Income Source</th>
                    <th class="border-0">Total Income</th>
                    <th class="border-0">Tax Amount</th>
                    <th class="border-0 text-center">Action</th>
                </tr>
                </thead>
    
                <tbody>
                    <tr>
                        <td colspan="5" class="py-5">
                            <div class="text-center max-w-700 mx-auto py-5">
                            <img src="{{asset('/public/assets/admin/img/tax-error.png')}}" alt="img" class="mb-20">
                                <h4 class="mb-2">No Tax Report Generated</h4>
                                <p class="mb-0 fz-12px">To generate your tax report please select & input above field and submit for the result.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--- Tax Details Page -->
    <h2 class="mb-20 mt-5">Tax Details</h2>
    <div class="bg--secondary rounded p-20">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-15">
            <div>
                <h5 class="mb-1">Vendor Commission Taxes</h5>
                <p class="fz-12px mb-0">Date: 25 Feb, 2024 - 25 Apr, 2024</p>
            </div>
            <div class="hs-unfold mr-2 hungar-export">
                <a class="js-hs-unfold-invoker btn btn-sm btn-primary dropdown-toggle h--40px" href="javascript:;"
                    data-hs-unfold-options='{
                        "target": "#usersExportDropdown4", "type": "css-animation" }'>
                    <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                </a>
                <div id="usersExportDropdown4" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                    <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                    <a id="export-excel" class="dropdown-item" href="javascript:;">
                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                alt="Image Description">
                        {{translate('messages.excel')}}
                    </a>
                    <a id="export-csv" class="dropdown-item" href="javascript:;">
                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                        .{{translate('messages.csv')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="bg-white p-12 w-100 rounded d-flex align-items-center justify-content-between">
                    Total Order <h4 class="theme-clr fw-bold mb-0">$ 12,345.25</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-12 w-100 rounded d-flex align-items-center justify-content-between">
                    Total Order Amount <h4 class="theme-clr fw-bold mb-0">$12,345.25</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-12 w-100 rounded d-flex align-items-center justify-content-between">
                    Total Commission <h4 class="cus-warning-light-clr fw-bold mb-0">$325.00</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white p-12 w-100 rounded d-flex align-items-center justify-content-between">
                    Total Tax Amount <h4 class="cus-warning-clr fw-bold mb-0">$325.00</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-20 mt-5">
        <div class="table-responsive datatable-custom">
            <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table fz--14px">
                <thead class="thead-light">
                <tr>
                    <th class="border-0">SL</th>
                    <th class="border-0">Order ID</th>
                    <th class="border-0">Order Amount</th>
                    <th class="border-0">Tax Type</th>
                    <th class="border-0">Tax Amount</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            #100124
                        </td>
                        <td>
                            $ 30.25
                        </td>
                        <td>
                            Order Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    Total: <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    VAT: <span>$ 3.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    GST: <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            #100124
                        </td>
                        <td>
                            $ 30.25
                        </td>
                        <td>
                            Order Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    Total: <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    VAT: <span>$ 3.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    GST: <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3
                        </td>
                        <td>
                            #100124
                        </td>
                        <td>
                            $ 30.25
                        </td>
                        <td>
                            Order Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    Total: <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    VAT: <span>$ 3.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    GST: <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4
                        </td>
                        <td>
                            #100124
                        </td>
                        <td>
                            $ 30.25
                        </td>
                        <td>
                            Order Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    Total: <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    VAT: <span>$ 3.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    GST: <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5
                        </td>
                        <td>
                            #100124
                        </td>
                        <td>
                            $ 30.25
                        </td>
                        <td>
                            Order Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    Total: <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    VAT: <span>$ 3.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    GST: <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- End Table -->
    </div>
</div>



@endsection

@push('script_2')

@endpush
