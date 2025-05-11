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
                            <select required name="" id="tax__rate" class="form-control js-select2-custom" multiple="multiple" placeholder="Type & Select Tax Rate">
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
                            When you change <span class="font-semibold title-clr">Tax Type</span> it will effects on all your tax calculation. Please make sure when you change Tax Type.  
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
                                Please specify the tax rate while creating a category from <span class="font-semibold theme-clr text-decoration-underline">Category List.</span> If you already created category without tax then go to category edit & update tax.                                
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

    

</div>

<a class="btn btn-primary offcanvas-trigger" data-target="#offcanvas__customBtn" href="#" role="button">
    Open Offcanvas 1
</a>

<a class="btn btn-secondary offcanvas-trigger" data-target="#offcanvas__customBtn2" href="#" role="button">
    Open Offcanvas 2
</a>

<!--  Offcanvas -->
<div id="offcanvas__customBtn" class="custom-offcanvas">
    <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
        <h3 class="mb-0">How Addon Activation Works</h2>
        <button type="button" class="btn-close w-25px h-25px rounded-circle d-center bg--secondary text-dark offcanvas-close fz-15px p-0" aria-label="Close">&times;</button>
    </div>
    <div class="custom-offcanvas-body p-20">
        <div class="accordion mx-450" id="accordionExample">
            <div class="accordion-item mb-15 custom-accordion-style bg--secondary rounded">
                <h5 class="accordion-header mb-0">
                    <button class="accordion-button border w-100 p-15 d-flex align-items-center bg-transparent gap-xl-3 gap-2 border-0 fz-15 font-semibold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <span class="btn p-2 d-center border w-35px h-35px rounded-circle bg-white-n theme-hover"><i class="tio-chevron-down"></i></span> Our Addons
                    </button>
                </h5>
                <div id="collapseOne" class="accordion-collapse collapse show" data-parent="#accordionExample">
                    <div class="accordion-body bg--secondary-n pt-0 p-15">
                        <div class="bg-white-n rounded p-15">
                            <div class="mb-15">
                                <h5 class="black-color mb-mb-0 font-normal d-block">Vendor App</h5>
                                <p class="fz-12 text-c mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus. Nullam in feugiat est. Nam in interdum ligula, non elementum purus. Aenean eu lectus diam. To get the Vendor App <a href="#0" class="text-primary text-decoration-underline">Visit Here.</a></p>
                            </div>
                            <div class="position-relative">
                                <div class="single-item-slider2 dots-style2 owl-carousel bg--secondary p-15">
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-nav w-100 z-999 d-flex align-items-center justify-content-between top-50 position-absolute gap-3 mt-3">
                                    <button class="custom-prev btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-left"></i></button>
                                    <button class="custom-next btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-right"></i></button></button>
                                </div>
                            </div>
                        </div>
                        <div class="custom-nav d-flex align-items-center justify-content-center gap-3 mt-3">
                            <button class="custom-prev btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-left"></i></button>
                            <div class="slide-counter slide-counter2"></div>
                            <button class="custom-next btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-right"></i></button></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-15 custom-accordion-style bg--secondary rounded">
                <h5 class="accordion-header mb-0">
                    <button class="accordion-button border w-100 p-15 d-flex align-items-center bg-transparent gap-xl-3 gap-2 border-0 fz-15 font-semibold collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <span class="btn p-2 d-center border w-35px h-35px rounded-circle bg-white-n theme-hover"><i class="tio-chevron-down"></i></span> How To Active Addons
                    </button>
                </h5>
                <div id="collapseTwo" class="accordion-collapse collapse" data-parent="#accordionExample">
                    <div class="accordion-body bg--secondary-n pt-0 p-15">
                        <div class="bg-white-n rounded p-15">
                            <div class="mb-15">
                                <h5 class="black-color mb-mb-0 font-normal d-block">Vendor App</h5>
                                <p class="fz-12 text-c mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus. Nullam in feugiat est. Nam in interdum ligula, non elementum purus. Aenean eu lectus diam. To get the Vendor App <a href="#0" class="text-primary text-decoration-underline">Visit Here.</a></p>
                            </div>
                            <div class="position-relative">
                                <div class="single-item-slider2 dots-style2 owl-carousel bg--secondary p-15">
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-center">
                                            <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-nav w-100 z-999 d-flex align-items-center justify-content-between top-50 position-absolute gap-3 mt-3">
                                    <button class="custom-prev btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-left"></i></button>
                                    <button class="custom-next btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-right"></i></button></button>
                                </div>
                            </div>
                        </div>
                        <div class="custom-nav d-flex align-items-center justify-content-center gap-3 mt-3">
                            <button class="custom-prev btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-left"></i></button>
                            <div class="slide-counter slide-counter2"></div>
                            <button class="custom-next btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-right"></i></button></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item custom-accordion-style bg--secondary rounded">
                <h5 class="accordion-header mb-0">
                    <button class="accordion-button border w-100 p-15 d-flex align-items-center bg-transparent gap-xl-3 gap-2 border-0 fz-15 font-semibold collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <span class="btn p-2 d-center border w-35px h-35px rounded-circle bg-white-n theme-hover"><i class="tio-chevron-down"></i></span> Why You Need to Active The Addons
                    </button>
                </h5>
                <div id="collapseThree" class="accordion-collapse collapse" data-parent="#accordionExample">
                    <div class="accordion-body bg--secondary-n pt-0 p-15">
                        <div class="bg-white-n rounded p-15">
                            <div class="position-relative">
                                <div class="single-item-slider2 dots-style2 owl-carousel">
                                    <div class="item mb-10px">
                                        <div class="text-start">
                                            <h5 class="black-color font-normal mb-15 d-block">Vendor App</h5>
                                            <ol class="p-0 ps-20 d-flex flex-column gap-2">
                                                <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus.</li>
                                                <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio.</li>
                                                <li class="fz-12px">Laoreet pharetra auctor eget, fringilla nec lectus. Nullam.</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-start">
                                            <h5 class="black-color font-normal mb-15 d-block">Vendor App</h5>
                                            <ol class="p-0 ps-20 d-flex flex-column gap-2">
                                                <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus.</li>
                                                <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio.</li>
                                                <li class="fz-12px">Laoreet pharetra auctor eget, fringilla nec lectus. Nullam.</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="item mb-10px">
                                        <div class="text-start">
                                            <h5 class="black-color font-normal mb-15 d-block">Vendor App</h5>
                                            <ol class="p-0 ps-20 d-flex flex-column gap-2">
                                                <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus.</li>
                                                <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio.</li>
                                                <li class="fz-12px">Laoreet pharetra auctor eget, fringilla nec lectus. Nullam.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-nav d-flex align-items-center justify-content-center gap-3 mt-3">
                            <button class="custom-prev btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-left"></i></button>
                            <div class="slide-counter slide-counter2"></div>
                            <button class="custom-next btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-right"></i></button></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</div>

<div id="offcanvas__customBtn2" class="custom-offcanvas">
    <div class="custom-offcanvas-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
        <h2 class="mb-0">2222</h2>
        <button type="button" class="btn-close offcanvas-close" aria-label="Close">&times;</button>
    </div>
    <div class="custom-offcanvas-body p-3">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, neque! Voluptatibus facere enim obcaecati, quos dolorum blanditiis voluptatum fugiat reiciendis.</p>
    </div>
    <div class="custom-offcanvas-footer p-3 border-top">
        <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-outline-primary w-100">Reset</button>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </div>
    </div>
</div>
<div id="offcanvasOverlay" class="offcanvas-overlay"></div>
@endsection

@push('script_2')

@endpush
