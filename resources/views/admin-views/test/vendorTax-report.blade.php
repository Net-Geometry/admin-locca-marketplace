@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">

   
    <!--- Vendor Tax Report -->
    <h2 class="mb-20">Vendor Tax Report</h3>
    <div class="card p-20 mb-20">
        <div class="row g-lg-4 g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <div class="form-group mb-0">
                    <label class="input-label mb-2 d-block title-clr fw-normal" for="exampleFormControlInput1">Date Range</label>
                    <input type="date" name="start_date" value="" class="form-control" id="date_from" required>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="mb-2 d-block title-clr fw-normal">Select Vendor</span>
                <select class="custom-select custom-select-color border rounded w-100">
                    <option>
                        All Vendor
                    </option>
                    <option>
                        Single Vendor
                    </option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn min-w-135px btn--primary">Filter</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-20 mb-20">
        <div class="row g-lg-4 g-3">
            <div class="col-md-6 col-xl-4">
                <div class="bg--secondary rounded p-15 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2 font-semibold title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-total-order.png')}}" alt="img">
                        Total Orders
                    </div>
                    <h3 class="theme-clr fw-bold mb-0">124</h3>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="bg--secondary rounded p-15 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2 font-semibold title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-toal-amount.png')}}" alt="img">
                        Total Order Amount
                    </div>
                    <h3 class="text-success fw-bold mb-0">$12,345.25</h3>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="bg--secondary rounded p-15 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2 font-semibold title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-tax-amount.png')}}" alt="img">
                        Total Tax Amount
                    </div>
                    <h3 class="text-danger fw-bold mb-0">$325.00</h3>
                </div>
            </div>
        </div>
    </div>
    <!--- Vendor Tax Report Here -->
    <div class="card p-20 mt-5">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-20">
            <h4 class="mb-0">All Vendor Taxes</h4>
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
                            "target": "#usersExportDropdown", "type": "css-animation" }'>
                        <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                    </a>
                    <div id="usersExportDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
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
                    <th class="border-0">Vendor Info</th>
                    <th class="border-0">Total Order</th>
                    <th class="border-0">Total Order Amount</th>
                    <th class="border-0">Tax Amount</th>
                    <th class="border-0 text-end">Action</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            <span class="fz-14 title-clr">
                                Hungary Puppet
                                <span class="fz-11 d-block">+880 23456 2356</span>
                            </span>
                        </td>
                        <td>
                            42
                        </td>
                        <td>
                            $ 1,830.25
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
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a class="btn btn-sm btn--primary action-btn btn-outline-primary" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                                <a class="btn btn-sm action-btn success-border btn-outline-varify text-success" href="#0">
                                    <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.87499 4.31958H7.37499V0.56958H3.625V4.31958H1.125L5.5 9.31957L9.87499 4.31958ZM0.5 10.5696H10.5V11.8196H0.5V10.5696Z" fill="#04BB7B"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            <span class="fz-14 title-clr">
                                BFC
                                <span class="fz-11 d-block">+880 23456 2356</span>
                            </span>
                        </td>
                        <td>
                            20
                        </td>
                        <td>
                            $ 1,030.25
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    Total: <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    VAT: <span>$ 2.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    GST: <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a class="btn btn-sm btn--primary action-btn btn-outline-primary" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                                <a class="btn btn-sm action-btn success-border btn-outline-varify text-success" href="#0">
                                    <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.87499 4.31958H7.37499V0.56958H3.625V4.31958H1.125L5.5 9.31957L9.87499 4.31958ZM0.5 10.5696H10.5V11.8196H0.5V10.5696Z" fill="#04BB7B"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3
                        </td>
                        <td>
                            <span class="fz-14 title-clr">
                                New Vendor
                                <span class="fz-11 d-block">+880 23456 2356</span>
                            </span>
                        </td>
                        <td>
                            32
                        </td>
                        <td>
                            $ 830.25
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
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a class="btn btn-sm btn--primary action-btn btn-outline-primary" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                                <a class="btn btn-sm action-btn success-border btn-outline-varify text-success" href="#0">
                                    <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.87499 4.31958H7.37499V0.56958H3.625V4.31958H1.125L5.5 9.31957L9.87499 4.31958ZM0.5 10.5696H10.5V11.8196H0.5V10.5696Z" fill="#04BB7B"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4
                        </td>
                        <td>
                            <span class="fz-14 title-clr">
                                Happy Tummy
                                <span class="fz-11 d-block">+880 23456 2356</span>
                            </span>
                        </td>
                        <td>
                            18
                        </td>
                        <td>
                            $ 230.25
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
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a class="btn btn-sm btn--primary action-btn btn-outline-primary" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                                <a class="btn btn-sm action-btn success-border btn-outline-varify text-success" href="#0">
                                    <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.87499 4.31958H7.37499V0.56958H3.625V4.31958H1.125L5.5 9.31957L9.87499 4.31958ZM0.5 10.5696H10.5V11.8196H0.5V10.5696Z" fill="#04BB7B"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5
                        </td>
                        <td>
                            <span class="fz-14 title-clr">
                                Fresh Item
                                <span class="fz-11 d-block">+880 23456 2356</span>
                            </span>
                        </td>
                        <td>
                            36
                        </td>
                        <td>
                            $ 1,530.25
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
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a class="btn btn-sm btn--primary action-btn btn-outline-primary" href="#0">
                                    <i class="tio-invisible"></i>
                                </a>
                                <a class="btn btn-sm action-btn success-border btn-outline-varify text-success" href="#0">
                                    <svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.87499 4.31958H7.37499V0.56958H3.625V4.31958H1.125L5.5 9.31957L9.87499 4.31958ZM0.5 10.5696H10.5V11.8196H0.5V10.5696Z" fill="#04BB7B"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- End Table -->
    </div>

    <!--- Vendor Tax Details Page -->
    <h2 class="mb-20 mt-5">Vendor Tax Details Page</h3>
    <div class="bg--secondary rounded p-20">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-15">
            <div>
                <h5 class="mb-1">Hungary Puppet</h3>
                <p class="fz-12px mb-0">Date: 25 Feb, 2024 - 25 Apr, 2024</p>
            </div>
            <div class="hs-unfold mr-2 hungar-export">
                <a class="js-hs-unfold-invoker btn btn-sm btn-primary dropdown-toggle h--40px" href="javascript:;"
                    data-hs-unfold-options='{
                        "target": "#usersExportDropdown2", "type": "css-animation" }'>
                    <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                </a>
                <div id="usersExportDropdown2" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
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
        <div class="d-flex align-items-center gap-3 justify-content-between flex-md-nowrap flex-wrap">
            <div class="bg-white p-12 w-100 rounded d-flex align-items-center justify-content-between">
                Total Order Amount <span class="title-clr">$1,245.25</span>
            </div>
            <div class="bg-white p-12 w-100 rounded d-flex align-items-center justify-content-between">
                Total Tax Amount <span class="title-clr">$45.25</span>
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
