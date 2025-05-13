@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!--- Intigration Menu -->
    <div class="bg--secondary rounded p-30 mb-20">
        <div class="row g-3 g-sm-4 g-md-3 align-items-md-center">
            <div class="col-md-6 col-lg-6 col-xl-7 col-xxl-8">
                <div class="">
                    <h2 class="mb-lg-3 mb-2 max-w-450px">Upgrade your restaurant with Menumium’s Smart QR Code Free For Lifetime!</h3>
                    <p class="fz--14px mx-650 mb-4">Menumium is a complete restaurant management system developed by 6amTech. It helps food entrepreneurs change how their businesses run. Here's how our digital menu system helps you meet customer expectations while making management easier.</p>
                    <div class="d-flex flex-wrap align-items-center gap-3 pt-lg-1">
                        <button type="button" class="btn btn--primary btn-outline-primary">Visit Menumium</button>
                        <button type="button" class="btn btn--primary offcanvas-trigger" data-target="#offcanvas__customBtnToken">Generate Token</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-5 col-xxl-4">
                <div class="d-flex justify-content-md-end">
                    <img width="384" src="{{asset('/public/assets/admin/img/intigration-menu.png')}}" alt="img" class="rounded" >
                </div>
            </div>
        </div>
    </div>
    <div class="card p-30 mb-20">
        <div class="d-flex flex-lg-nowrap flex-wrap align-items-center justify-content-between gap-3 px-xxl-3 py-xxl-0 py-2">
            <div class="d-flex align-items-start gap-2">
                <img src="{{asset('/public/assets/admin/img/hour-saved.png')}}" alt="icon">
                <p class="max-w-187px fz--14px mb-0">20+ Hours Saved on Admin Tasks</p>
            </div>
            <div class="support-line d-lg-block d-none"></div>
            <div class="d-flex align-items-start gap-2">
                <img src="{{asset('/public/assets/admin/img/resturant-built.png')}}" alt="icon">
                <p class="max-w-187px fz--14px mb-0">Built with Insights from 50+ Restaurants</p>
            </div>
            <div class="support-line d-lg-block d-none"></div>
            <div class="d-flex align-items-start gap-2">
                <img src="{{asset('/public/assets/admin/img/support-available.png')}}" alt="icon">
                <p class="max-w-187px fz--14px mb-0">Live expert support, available 24/7</p>
            </div>
            <div class="support-line d-lg-block d-none"></div>
            <div class="d-flex align-items-start gap-2">
                <img src="{{asset('/public/assets/admin/img/qr-code.png')}}" alt="icon">
                <p class="max-w-187px fz--14px mb-0">Offline-Ready with QR Code Menus</p>
            </div>
        </div>
    </div>
    <div class="card p-30">
        <h3 class="mb-xl-5 mb-4 text-capitalize text-center">4 easy steps to start Integration</h3>
        <div class="steps-integration-wrap position-relative">
            <ul class="step-integration-inner justify-content-lg-center list-none p-0 m-0 d-flex scrollbar-w-0 nav flex-nowrap overflow-x-auto white-nowrap">
                <li class="min-w-370 text-wrap mx-auto text-center">
                    <div class="min-w-220 mx-auto">
                        <div class="integration-step position-relative bg-white-n d-center rounded-circle w-35px h-35px mx-auto mb-20">
                            <span class="bg-primary rounded-circle d-center text-white fz-12px font-bold w-20px h-20px">1</span>
                        </div>
                        <img src="{{asset('/public/assets/admin/img/manumium-portal.png')}}" alt="icon" class="mb-3">
                        <p class="fz--14px mb-0">Go to <a href="#0" class="theme-clr font-medium text-underline">Manumium Portal</a> and create an account.</p>
                    </div>
                </li>
                <li class="min-w-370 text-wrap mx-auto text-center">
                    <div class="min-w-220 mx-auto">
                        <div class="integration-step position-relative bg-white-n d-center rounded-circle w-35px h-35px mx-auto mb-20">
                            <span class="bg-primary rounded-circle d-center text-white fz-12px font-bold w-20px h-20px">2</span>
                        </div>
                        <img src="{{asset('/public/assets/admin/img/creating-token.png')}}" alt="icon" class="mb-3">
                        <p class="fz--14px mb-0">After creating account back to 6amMart and get the <a href="#0" class="theme-clr font-medium text-underline">Token.</a></p>
                    </div>
                </li>
                <li class="min-w-370 text-wrap mx-auto text-center">
                    <div class="min-w-220 mx-auto">
                        <div class="integration-step position-relative bg-white-n d-center rounded-circle w-35px h-35px mx-auto mb-20">
                            <span class="bg-primary rounded-circle d-center text-white fz-12px font-bold w-20px h-20px">3</span>
                        </div>
                        <img src="{{asset('/public/assets/admin/img/integration-token.png')}}" alt="icon" class="mb-3">
                        <p class="fz--14px mb-0">Copy & Paste the token in <a href="#0" class="theme-clr font-medium text-underline">Integration Page</a> Token Field.</p>
                    </div>
                </li>
                <li class="min-w-370 text-wrap mx-auto text-center">
                    <div class="min-w-220 mx-auto">
                        <div class="integration-step position-relative bg-white-n d-center rounded-circle w-35px h-35px mx-auto mb-20">
                            <span class="bg-primary rounded-circle d-center text-white fz-12px font-bold w-20px h-20px">4</span>
                        </div>
                        <img src="{{asset('/public/assets/admin/img/food-list.png')}}" alt="icon" class="mb-3">
                        <p class="fz--14px mb-0">If input the token properly, then automatically get 6amMart <a href="#0" class="theme-clr font-medium text-underline">Food List</a> at Menumium.</p>
                    </div>
                </li>
            </ul>
            <div class="slide-cus__prev position-absolute z-2 top-50">
                <button class="border-0 shadow-md w-35px h-35px d-flex align-items-center justify-content-center p-0 bg-primary rounded-circle text-white">
                    <i class="tio-arrow-long-left fz-18 text-white"></i>
                </button>
            </div>
            <div class="slide-cus__next position-absolute z-2 top-50">
                <button class="border-0 shadow-md w-35px h-35px d-flex align-items-center justify-content-center p-0 bg-primary rounded-circle text-white">
                    <i class="tio-arrow-long-right fz-18 text-white"></i>
                </button>
            </div>
        </div>
    </div>
</div>


<div id="offcanvas__customBtnToken" class="custom-offcanvas d-flex flex-column justify-content-between">
    <div>
        <div class="custom-offcanvas-header d-flex justify-content-between align-items-center px-3 pt-3">
            <h3 class="mb-0"></h2>
            <button type="button" class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary text-dark offcanvas-close fz-15px p-0" aria-label="Close">&times;</button>
        </div>
        <div class="custom-offcanvas-body p-20">
            <div class="bg--secondary rounded p-20 mb-20 w-100">
                <div class="mb-15">
                    <h4 class="mb-2">Generate Token</h4>
                    <p class="fz-12px mb-0">Here you can generate token to connect <a href="#0" class="title-clr font-medium">6amMart</a> & <a href="#0" class="title-clr font-medium">Menumium.</a> To generate click on <a href="#0" class="title-clr font-medium">Magic</a> button. Then save the token to work it properly & copy to paste it in Menumium Intigration page.</p>
                </div>
                <div>
                    <div class="d-flex align-items-center rounded overflow-hidden mb-30">
                        <div class="custom-copy-text position-relative h--45px w-100">
                            <input type="text" class="text-inside form-control rounded-0 pe-30" value="KrcE28TcSn.8?olrsUQoP" />
                            <span class="copy-btn position-absolute end-10 top-50 cursor-pointer text-primary"><i class="tio-copy"></i></span>
                        </div>
                        <button type="button" class="btn px-3 h--45px rounded-0 d-center btn-primary">
                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_13899_41220)">
                                <path d="M7.4314 6.50133L10.2227 3.71067L12.8894 6.37733L10.0987 9.16867L7.4314 6.50133ZM6.48873 7.444L1.1554 12.7773C0.891746 13.0411 0.712209 13.3771 0.639482 13.7429C0.566755 14.1087 0.604103 14.4878 0.746805 14.8323C0.889508 15.1769 1.13116 15.4714 1.44121 15.6786C1.75126 15.8859 2.1158 15.9966 2.48873 15.9967C2.7365 15.9974 2.98196 15.949 3.21085 15.8541C3.43974 15.7592 3.6475 15.6198 3.82207 15.444L9.1554 10.1107L6.48873 7.444ZM13.0447 11.5333L13.9334 13.308L14.8221 11.5333L16.6001 10.6413L14.8221 9.752L13.9334 7.97467L13.0447 9.752L11.2667 10.6413L13.0447 11.5333ZM5.04473 3.55867L5.9334 5.33333L6.82207 3.55533L8.60007 2.66667L6.82207 1.778L5.9334 0L5.04473 1.778L3.26673 2.66667L5.04473 3.55867ZM13.4887 3.11467L14.2667 4.66667L15.0447 3.11133L16.6001 2.33333L15.0447 1.55533L14.2667 0L13.4887 1.55533L11.9334 2.33333L13.4887 3.11467Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_13899_41220">
                                <rect width="16" height="16" fill="white" transform="translate(0.600098)"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>                    
                    <button type="button" class="btn btn--primary w-100" disabled>Save Token</button>
                </div>
            </div>
            <div class="d-flex gap-2 bg-opacity-warning-10 rounded py-2 px-3 fz-12px mb-10px">
                <svg width="25" height="25" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_14052_228)">
                    <path d="M7.6001 14C8.98457 14 10.3379 13.5895 11.4891 12.8203C12.6402 12.0511 13.5374 10.9579 14.0673 9.67879C14.5971 8.3997 14.7357 6.99224 14.4656 5.63437C14.1955 4.2765 13.5288 3.02922 12.5498 2.05026C11.5709 1.07129 10.3236 0.404603 8.96573 0.134506C7.60786 -0.13559 6.2004 0.003033 4.92131 0.532846C3.64223 1.06266 2.54898 1.95987 1.77981 3.11101C1.01064 4.26216 0.600098 5.61553 0.600098 7C0.602105 8.8559 1.34025 10.6352 2.65257 11.9475C3.96489 13.2599 5.7442 13.998 7.6001 14ZM7.6001 2.91667C7.77316 2.91667 7.94233 2.96799 8.08622 3.06413C8.23011 3.16028 8.34227 3.29694 8.40849 3.45682C8.47472 3.61671 8.49205 3.79264 8.45829 3.96237C8.42452 4.13211 8.34119 4.28802 8.21882 4.41039C8.09645 4.53276 7.94054 4.6161 7.7708 4.64986C7.60107 4.68362 7.42514 4.66629 7.26525 4.60006C7.10536 4.53384 6.96871 4.42169 6.87256 4.27779C6.77642 4.1339 6.7251 3.96473 6.7251 3.79167C6.7251 3.55961 6.81729 3.33705 6.98138 3.17295C7.14547 3.00886 7.36803 2.91667 7.6001 2.91667ZM7.01676 5.83334H7.6001C7.90952 5.83334 8.20626 5.95625 8.42506 6.17505C8.64385 6.39384 8.76676 6.69058 8.76676 7V10.5C8.76676 10.6547 8.70531 10.8031 8.59591 10.9125C8.48651 11.0219 8.33814 11.0833 8.18343 11.0833C8.02872 11.0833 7.88035 11.0219 7.77095 10.9125C7.66156 10.8031 7.6001 10.6547 7.6001 10.5V7H7.01676C6.86206 7 6.71368 6.93855 6.60429 6.82915C6.49489 6.71975 6.43343 6.57138 6.43343 6.41667C6.43343 6.26196 6.49489 6.11359 6.60429 6.00419C6.71368 5.8948 6.86206 5.83334 7.01676 5.83334Z" fill="#FFBB38"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_14052_228">
                    <rect width="14" height="14" fill="white" transform="translate(0.600098)"/>
                    </clipPath>
                    </defs>
                </svg>
                <p class="mb-0"> You must save the token after generate. Without saving the <span class="title-clr font-semibold">Token,</span> integration will not work properly.</p>
            </div>
            <div class="d-flex gap-2 bg-opacity-info-10 rounded py-2 px-3 fz-12px">
                <svg width="30" height="30" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_14052_1006)">
                    <path d="M10.3905 1.44293L11.1109 0.270433C11.2789 -0.0049006 11.6382 -0.0900672 11.913 0.0790994C12.1877 0.247683 12.2735 0.606433 12.1043 0.881183L11.3839 2.05368C11.2742 2.23335 11.0823 2.33193 10.8863 2.33193C10.7819 2.33193 10.6769 2.30393 10.5818 2.2456C10.3071 2.07702 10.2213 1.71768 10.3905 1.44293ZM3.765 2.0531C3.87466 2.23277 4.06716 2.33193 4.26316 2.33193C4.367 2.33193 4.472 2.30393 4.56708 2.24618C4.84183 2.0776 4.92816 1.71885 4.75958 1.4441L4.04033 0.271016C3.87175 -0.00373391 3.51241 -0.0894839 3.23825 0.0785161C2.9635 0.247099 2.87716 0.605849 3.04575 0.880599L3.765 2.0531ZM2.48983 3.3411L1.441 2.83885C1.15166 2.69827 0.801081 2.82193 0.663414 3.11302C0.523998 3.40352 0.647081 3.75177 0.937581 3.8906L1.98641 4.39285C2.0675 4.43193 2.15325 4.4506 2.23783 4.4506C2.45541 4.4506 2.66425 4.32868 2.764 4.11927C2.90341 3.82877 2.78033 3.47993 2.48983 3.3411ZM7.51991 2.33368C4.30341 2.38677 1.75425 5.87277 3.50075 9.23043C3.76616 9.74027 4.15991 10.1673 4.58283 10.5563C4.74325 10.7039 4.8675 10.8865 4.97366 11.0825H7.01591V8.05852C6.33866 7.81702 5.84925 7.17535 5.84925 6.41585C5.84925 6.09327 6.11 5.83252 6.43258 5.83252C6.75516 5.83252 7.01591 6.09327 7.01591 6.41585C7.01591 6.73843 7.27725 6.99918 7.59925 6.99918C7.92125 6.99918 8.18258 6.73785 8.18258 6.41585C8.18258 6.09385 8.44333 5.83252 8.76591 5.83252C9.0885 5.83252 9.34925 6.09327 9.34925 6.41585C9.34925 7.17535 8.85983 7.81702 8.18258 8.05852V11.0825H10.2056C10.3386 10.8544 10.5101 10.6392 10.7347 10.4519C11.1191 10.1311 11.4807 9.77352 11.703 9.32493C12.7162 7.27568 12.3143 5.09052 10.8712 3.67185C9.97225 2.78752 8.78166 2.31443 7.51991 2.33368ZM5.26125 12.4119C5.21458 13.2671 5.85508 13.9998 6.71141 13.9998H8.47366C9.27925 13.9998 9.932 13.347 9.932 12.5414V12.2498H5.25483C5.25483 12.3046 5.26475 12.3559 5.26125 12.4119ZM14.5485 3.09143C14.4155 2.79802 14.0707 2.6656 13.7773 2.79977L12.6503 3.30843C12.3563 3.44085 12.2257 3.78618 12.3587 4.0796C12.4561 4.29543 12.6678 4.42318 12.8907 4.42318C12.9706 4.42318 13.0522 4.40685 13.1298 4.37127L14.2568 3.8626C14.5508 3.73018 14.6815 3.38485 14.5485 3.09143Z" fill="#5C8FFC"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_14052_1006">
                    <rect width="14" height="14" fill="white" transform="translate(0.600098)"/>
                    </clipPath>
                    </defs>
                </svg>
                <p class="mb-0"> After copy & save the token paste it <span class="title-clr font-semibold">Menumim Dashboard > Integration > 6amMart Integration.</span></p>
            </div>
        </div>
    </div>
</div>
<div id="offcanvasOverlay" class="offcanvas-overlay"></div>
@endsection

@push('script_2')

@endpush
