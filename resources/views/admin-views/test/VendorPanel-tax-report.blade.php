@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">

   
    <!--- Tax Report -->
    <h2 class="mb-20">Tax Report</h3>
    <div class="card p-20 mb-20">
        <div class="row g-lg-4 g-3 align-items-end justify-content-between">
            <div class="col-lg-4 col-md-6">
                <div class="form-group mb-0">
                    <label class="input-label mb-2 d-block title-clr fw-normal" for="exampleFormControlInput1">Date Range</label>
                    <input type="date" name="start_date" value="" class="form-control" id="date_from" required>
                </div>
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
            <div class="col-md-6 col-xl-3">
                <div class="bg-opacity-warning-5 h-100 rounded p-24">
                    <img src="{{asset('/public/assets/admin/img/tax-report-pen.png')}}" alt="img" class="mb-20">
                    <h2 class="cus-warning-clr mb-1">$ 12,345.25</h2>
                    <span class="font-medium mb-0">Total Orders</span>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="bg-opacity-primary-5 h-100 rounded p-24">
                    <img src="{{asset('/public/assets/admin/img/tax-report-pen.png')}}" alt="img" class="mb-20">
                    <h2 class="theme-clr mb-1">$ 12,345.25</h2>
                    <span class="font-medium mb-0">Total Order Amount</span>
                </div>
            </div>
            <div class="col-md-612 col-xl-6">
                <div class="bg-opacity-warning-5 h-100 rounded p-24 d-flex flex-sm-nowrap flex-wrap gap-3">
                    <div class="w-xxl-100 w-sm-50">
                        <img src="{{asset('/public/assets/admin/img/tax-report-pen.png')}}" alt="img" class="mb-20">
                        <h2 class="text-success mb-1">$ 345.25</h2>
                        <span class="font-medium mb-0">Total Tax Amount</span>
                    </div>                    
                    <div class="tax-report-vat w-100">
                        <div class="d-flex flex-column gap-1">
                            <div class="d-content-between gap-2 bg-white-n rounded py-2 px-2 fz-12px font-semibold">
                                VAT (5%) <span class="title-clr">$ 12,345.25</span>
                            </div>
                            <div class="d-content-between gap-2 bg-white-n rounded py-2 px-2 fz-12px font-semibold">
                                GST (7%) <span class="title-clr">$ 12,345.25</span>
                            </div>
                            <div class="d-content-between gap-2 bg-white-n rounded py-2 px-2 fz-12px font-semibold">
                                Income Tax (15%) <span class="title-clr">$ 12,345.25</span>
                            </div>
                            <div class="d-content-between gap-2 bg-white-n rounded py-2 px-2 fz-12px font-semibold">
                                Land Tax (5%) <span class="title-clr">$ 12,345.25</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-20">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-20">
            <h4 class="mb-0">All Taxes</h4>
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
                    <th class="border-0">Order ID</th>
                    <th class="border-0">Order Date</th>
                    <th class="border-0">Order Amount</th>
                    <th class="border-0">Tax Type</th>
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
                            #100124
                        </td>
                        <td>
                            25 Feb, 2024
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
                                    <span class="min-w-50">Total:</span> <span>$6.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">VAT:</span> <span>$ 3.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">GST:</span> <span>$3.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm mx-auto btn--primary action-btn btn-outline-primary offcanvas-trigger" href="#0" data-target="#offcanvas__customBtn__adminDetails">
                                <i class="tio-invisible"></i>
                            </a>
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
                            25 Feb, 2024
                        </td>
                        <td>
                            $ 23.25
                        </td>
                        <td>
                            Category Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-50">Total:</span> <span>$$ 5.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">GST:</span> <span>$ 2.80</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm mx-auto btn--primary action-btn btn-outline-primary offcanvas-trigger" href="#0" data-target="#offcanvas__customBtn__adminDetails">
                                <i class="tio-invisible"></i>
                            </a>
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
                            25 Feb, 2024
                        </td>
                        <td>
                            $ 12.25
                        </td>
                        <td>
                            Order Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-50">Total:</span> <span>$$ 2.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">VAT:</span> <span>$ 1.10</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">GST:</span> <span>$ 0.90</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm mx-auto btn--primary action-btn btn-outline-primary offcanvas-trigger" href="#0" data-target="#offcanvas__customBtn__adminDetails">
                                <i class="tio-invisible"></i>
                            </a>
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
                            25 Feb, 2024
                        </td>
                        <td>
                            $ 13.25
                        </td>
                        <td>
                            Food Wise
                        </td>
                        <td>
                           <div class="d-flex flex-column gap-1">
                                <div class="d-flex fz-14 gap-3 align-items-center title-clr">
                                    <span class="min-w-50">Total:</span> <span>$ 2.10</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">VAT:</span> <span>$ 1.10</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm mx-auto btn--primary action-btn btn-outline-primary offcanvas-trigger" href="#0" data-target="#offcanvas__customBtn__adminDetails">
                                <i class="tio-invisible"></i>
                            </a>
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
                            25 Feb, 2024
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
                                    <span class="min-w-50">Total:</span> <span>$ 20.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">VAT:</span> <span>$ 15.00</span>
                                </div>
                                <div class="d-flex fz-11 gap-3 align-items-center">
                                    <span class="min-w-50">GST:</span> <span>$ 5.00</span>
                                </div>
                           </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm mx-auto btn--primary action-btn btn-outline-primary offcanvas-trigger" href="#0" data-target="#offcanvas__customBtn__adminDetails">
                                <i class="tio-invisible"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="offcanvas__customBtn__adminDetails" class="custom-offcanvas d-flex flex-column justify-content-between">
    <div>
        <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
            <h3 class="mb-0">Details</h2>
            <button type="button" class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary text-dark offcanvas-close fz-15px p-0" aria-label="Close">&times;</button>
        </div>
        <div class="custom-offcanvas-body p-20">
            <div class="bg--secondary rounded p-20 mb-20 w-100">
                <div class="mb-15">
                    <div class="d-flex align-items-center gap-3 mb-xl-3 mb-3">
                        <h4 class="mb-0">Order ID #100245</h4> <span class="bg-opacity-info-10 rounded py-2 px-3 font-semibold fz-12px theme-clr">Pending</span>
                    </div>
                    <span class="fz--14px title-clr d-block mb-1">Date: 25 Feb, 2024 10:30 PM</span>
                    <div class="d-flex align-items-center gap-3">
                        <span class="fz--14px title-clr">Payment Status: </span>  <span class="bg-opacity-success-10 rounded py-2 px-3 font-semibold fz-12px text-success">Paid</span>                 
                    </div>
                </div>
                <div class="border d-flex align-items-center bg-white-n justify-content-between rounded p-12 mb-20 fz--14px">
                    Order Amount 
                    <span class="title-clr font-semibold">$1,245.25</span>                    
                </div>
                <div class="bg-white-n rounded p-12">
                    <div class="d-flex align-items-center fz-12px justify-content-between mb-2">
                        VAT
                        <span class="title-clr font-semibold">$25.25</span>                    
                    </div>
                    <div class="d-flex align-items-center fz-12px justify-content-between mb-2">
                        GST
                        <span class="title-clr font-semibold">$20.00</span>                    
                    </div>
                    <div class="d-flex align-items-center fz--14px border-top pt-2 justify-content-between">
                        GST
                        <span class="title-clr font-semibold">$45.25</span>                    
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
<div id="offcanvasOverlay" class="offcanvas-overlay"></div>
@endsection

@push('script_2')

@endpush
