@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <h2 class="mb-20">Setup Tax Calculation</h3>
    <div class="card p-20 mb-20">
        <div class="row g-md-3 g-2 justify-content-between">
            <div class="col-md-8">
                <h3 class="mb-1">Allow Tax Calculation For Vendor ? </h3>
                <p class="fz-12 mb-0">To active tax calculation turn on the status.</p>
            </div>
            <div class="col-md-4 col-xxl-3">
                <label class="border d-flex align-items-center justify-content-between rounded p-10px px-3">
                    Status 
                    <div class="toggle-switch ml-auto justify-content-end toggle-switch-sm" for="status">
                        <input type="checkbox" class="toggle-switch-input" id="status">
                            <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </div>
                </label> 
            </div>
        </div>
    </div>
    <div class="card p-20">
        <div class="bg--secondary p-15 rounded mb-20">
            <div class="mb-20">
                <h4 class="mb-1">Tax calculation based on Product Price </h4>
                <p class="fz-12 mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="bg-white border rounded p-15">
                <div class="row g-lg-4 g-md-3 g-2">
                    <div class="col-md-6">
                        <div class="custom-radio d-flex align-items-start gap-2">
                            <input class="w-20px h-20px" type="radio" id="include1" name="status" value="1" checked>
                            <label for="include1" class="fz-14 mb-0">
                                <h5 class="mb-1">Calculate Tax Include Product Price</h5>
                                <p class="mb-0 fz-11 fw-normal">Calculate Tax Include Product Price
                                By selecting this option you will need to setup same tax rate for all types of income source.</p>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-radio d-flex align-items-start gap-2">
                            <input class="w-20px h-20px" type="radio" id="include2" name="status" value="1">
                            <label for="include2" class="fz-14 mb-0">
                                <h5 class="mb-1">Calculate Tax Exclude Product Price</h5>
                                <p class="mb-0 fz-11 fw-normal">By selecting this option you will need to setup individual tax rate for different types of income source.</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg--secondary rounded p-20 mb-20">
            <div class="row g-lg-4 g-md-3 g-2">
                <div class="col-md-6">
                    <h3 class="mb-1">Tax Rate Setup</h3>
                    <p class="mb-0 fz-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-lg-4 gap-3">
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Select Tax Rate</span>
                            <select class="custom-select custom-select-color border rounded w-100">
                                <option>
                                    Type & Select Tax Rate
                                </option>
                                <option>
                                    Order Wise
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg--secondary rounded p-20 mb-20">
            <div class="row g-lg-4 g-md-3 g-2">
                <div class="col-md-6">
                    <h3 class="mb-1">Basic Setup</h3>
                    <p class="mb-0 fz-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-lg-4 gap-3">
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Select Tax Type</span>
                            <select class="custom-select custom-select-color border rounded w-100">
                                <option>
                                    Order Wise
                                </option>
                                <option>
                                    Category Wise
                                </option>
                                <option>
                                    Product Wise
                                </option>
                            </select>
                        </div>
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Select Tax Rate</span>
                            <select required name="" id="select_customer" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate">
                                <option value="all">all</option>
                                <option value="2">VAT (5%)</option>
                                <option value="2">GST (7%)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg--secondary rounded p-20 mb-20">
            <div class="row g-lg-4 g-md-3 g-2">
                <div class="col-md-6">
                    <h3 class="mb-1">Basic Setup</h3>
                    <p class="mb-0 fz-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <div class="danger-notes-bg px-2 py-2 rounded fz-11 d-flex gap-2 align-items-center mt-10px">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_13920_306)">
                            <path d="M13.464 9.10734L8.75069 1.664C8.35402 1.09234 7.69485 0.748169 7.00069 0.748169C6.30652 0.748169 5.64735 1.0865 5.23319 1.6815L0.543187 9.09567C-0.051813 9.94734 -0.162646 10.9682 0.25152 11.7557C0.659854 12.5432 1.51735 12.9923 2.59069 12.9923H11.4107C12.4899 12.9923 13.3415 12.5432 13.7499 11.7557C14.1582 10.9682 14.0474 9.95317 13.464 9.10734ZM6.41735 4.24817C6.41735 3.92734 6.67985 3.66484 7.00069 3.66484C7.32152 3.66484 7.58402 3.92734 7.58402 4.24817V7.74817C7.58402 8.069 7.32152 8.3315 7.00069 8.3315C6.67985 8.3315 6.41735 8.069 6.41735 7.74817V4.24817ZM7.00069 11.2482C6.51652 11.2482 6.12569 10.8573 6.12569 10.3732C6.12569 9.889 6.51652 9.49817 7.00069 9.49817C7.48485 9.49817 7.87569 9.889 7.87569 10.3732C7.87569 10.8573 7.48485 11.2482 7.00069 11.2482Z" fill="#FF4040"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_13920_306">
                            <rect width="14" height="14" fill="white" transform="translate(0 0.164795)"/>
                            </clipPath>
                            </defs>
                        </svg>
                        <span>
                            When you change <span class="fw-semibold title-clr">Tax Type</span> it will effects on all your tax calculation. Please make sure when you change Tax Type.  
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-lg-4 gap-3">
                        <div>
                            <span class="mb-2 d-block title-clr fw-normal">Select Tax Type</span>
                            <select class="custom-select custom-select-color border rounded w-100">
                                <option>                                    
                                    Category Wise
                                </option>
                                <option>
                                    Order Wise
                                </option>
                                <option>
                                    Product Wise
                                </option>
                            </select>
                        </div>
                        <div class="info-notes-bg px-2 py-2 rounded fz-11 d-flex gap-2 align-items-center">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_13899_104013)">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.3125 2.53979V1.28979C10.3125 1.11729 10.1725 0.977295 10 0.977295C9.8275 0.977295 9.6875 1.11729 9.6875 1.28979V2.53979C9.6875 2.71229 9.8275 2.85229 10 2.85229C10.1725 2.85229 10.3125 2.71229 10.3125 2.53979Z" fill="#245BD1"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.34578 4.31882L4.47078 3.44382C4.34891 3.32195 4.15078 3.32195 4.02891 3.44382C3.90703 3.5657 3.90703 3.76382 4.02891 3.8857L4.90391 4.7607C5.02578 4.88257 5.22391 4.88257 5.34578 4.7607C5.46766 4.63882 5.46766 4.4407 5.34578 4.31882Z" fill="#245BD1"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.125 9.10229H1.875C1.7025 9.10229 1.5625 9.24229 1.5625 9.41479C1.5625 9.58729 1.7025 9.72729 1.875 9.72729H3.125C3.2975 9.72729 3.4375 9.58729 3.4375 9.41479C3.4375 9.24229 3.2975 9.10229 3.125 9.10229Z" fill="#245BD1"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.90391 14.0688L4.02891 14.9438C3.90703 15.0657 3.90703 15.2638 4.02891 15.3857C4.15078 15.5076 4.34891 15.5076 4.47078 15.3857L5.34578 14.5107C5.46766 14.3888 5.46766 14.1907 5.34578 14.0688C5.22391 13.9469 5.02578 13.9469 4.90391 14.0688Z" fill="#245BD1"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.6539 14.5107L15.5289 15.3857C15.6508 15.5076 15.8489 15.5076 15.9708 15.3857C16.0927 15.2638 16.0927 15.0657 15.9708 14.9438L15.0958 14.0688C14.9739 13.9469 14.7758 13.9469 14.6539 14.0688C14.532 14.1907 14.532 14.3888 14.6539 14.5107Z" fill="#245BD1"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.875 9.72729H18.125C18.2975 9.72729 18.4375 9.58729 18.4375 9.41479C18.4375 9.24229 18.2975 9.10229 18.125 9.10229H16.875C16.7025 9.10229 16.5625 9.24229 16.5625 9.41479C16.5625 9.58729 16.7025 9.72729 16.875 9.72729Z" fill="#245BD1"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.0958 4.7607L15.9708 3.8857C16.0927 3.76382 16.0927 3.5657 15.9708 3.44382C15.8489 3.32195 15.6508 3.32195 15.5289 3.44382L14.6539 4.31882C14.532 4.4407 14.532 4.63882 14.6539 4.7607C14.7758 4.88257 14.9739 4.88257 15.0958 4.7607Z" fill="#245BD1"/>
                                <path d="M7.5 16.6023V15.6648C7.5 14.9773 7.1875 14.321 6.625 13.9148C5.25 12.8835 4.375 11.2585 4.375 9.41477C4.375 6.10227 7.25 3.44602 10.625 3.82102C13.2188 4.10227 15.2812 6.16477 15.5938 8.75852C15.8438 10.8835 14.9062 12.7898 13.375 13.9148C12.8125 14.321 12.5 14.9773 12.5 15.6648V16.6023H7.5Z" fill="#BED2FE"/>
                                <path d="M7.5 16.2898H12.5V18.2273C12.5 18.5398 12.25 18.7898 11.9375 18.7898H11.25C11.25 19.4773 10.6875 20.0398 10 20.0398C9.3125 20.0398 8.75 19.4773 8.75 18.7898H8.0625C7.75 18.7898 7.5 18.5398 7.5 18.2273V16.2898Z" fill="#245BD1"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_13899_104013">
                                <rect width="20" height="20" fill="white" transform="translate(0 0.664795)"/>
                                </clipPath>
                                </defs>
                            </svg>
                            <span>
                                Please specify the tax rate while creating a category from <span class="fw-semibold theme-clr text-decoration-underline">Category List.</span> If you already created category without tax then go to category edit & update tax.                                
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg--secondary rounded p-20">
            <div class="row g-lg-4 g-md-3 g-2">
                <div class="col-md-6">
                    <h3 class="mb-1">Additional Setup</h3>
                    <p class="mb-0 fz-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-lg-4 gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-1 justify-content-between mb-2">
                                <span class="title-clr fw-normal">Tax on Service Charge</span>
                                <label class="toggle-switch toggle-switch-sm" for="services__charge">
                                    <input type="checkbox" class="toggle-switch-input" id="services__charge" checked>
                                        <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                            <select required name="" id="select_customer2" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate">
                                <option value="all">all</option>
                                <option value="2">VAT (5%)</option>
                                <option value="2">Service Tax (5%)</option>
                            </select>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-1 justify-content-between mb-2">
                                <span class="title-clr fw-normal">Tax on Packaging charge</span>
                                <label class="toggle-switch toggle-switch-sm" for="packaging__charge">
                                    <input type="checkbox" class="toggle-switch-input" id="packaging__charge">
                                        <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                            <select required name="" id="select_customer3" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate" disabled>
                                <option value="all">all</option>
                                <option value="2">VAT (5%)</option>
                                <option value="2">Select tax</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-4 gap-md-3 gap-2">
        <button type="button" class="btn bg--secondary h--42px title-clr px-4">Reset</button>
        <button type="button" class="btn btn--primary">Save Information</button>
    </div>



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
                    <div class="d-flex align-items-center gap-2 fw-semibold title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-total-order.png')}}" alt="img">
                        Total Orders
                    </div>
                    <h3 class="theme-clr fw-bold mb-0">124</h3>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="bg--secondary rounded p-15 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2 fw-semibold title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-toal-amount.png')}}" alt="img">
                        Total Order Amount
                    </div>
                    <h3 class="text-success fw-bold mb-0">$12,345.25</h3>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="bg--secondary rounded p-15 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2 fw-semibold title-clr">
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
                    <div class="d-flex align-items-center gap-3 fw-semibold title-clr">
                        <img src="{{asset('/public/assets/admin/img/t-toal-amount.png')}}" alt="img">
                        Total Income
                    </div>
                    <h3 class="theme-clr fw-bold mb-0">$ 12,345.25</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-opacity-warning-10 rounded p-20 d-flex align-items-center gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-3 fw-semibold title-clr">
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
    <h2 class="mb-20 mt-5">Tax Details</h3>
    <div class="bg--secondary rounded p-20">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-15">
            <div>
                <h5 class="mb-1">Vendor Commission Taxes</h3>
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




<!--  Offcanvas -->
<form action="" method="post" id="">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="tax-management__edit" aria-labelledby="tax-management__editLabel">
        <div class="offcanvas-header ">
            <h2 class="mb-0">Create Tax</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, neque! Voluptatibus facere enim obcaecati, quos dolorum blanditiis voluptatum fugiat reiciendis.</p>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex justify-content-center gap-2 px-3 py-sm-3 py-2">
                <button type="button" class="btn btn--primary w-100 btn-outline-primary">Reset</button>
                <button type="button" class="btn btn--primary w-100">Submit</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('script_2')
@endpush
