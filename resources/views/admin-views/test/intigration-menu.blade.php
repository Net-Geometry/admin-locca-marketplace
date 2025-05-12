@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!--- Intigration Menu -->
    <h2 class="mb-20">Tax Report</h3>
    <div class="row g-3">
        <div class="col-lg-9">

        </div>
        <div class="col-lg-3">
            <img src="{{asset('/public/assets/admin/img/intigration-menu.png')}}" alt="img" class="w-100 rounded">
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
