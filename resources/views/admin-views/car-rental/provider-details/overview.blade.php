@extends('layouts.admin.app')

@section('title', translate('messages.Provider_Details'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ translate('messages.Auto_Focus_Car_Service') }}
                    </h1></span>
                    </h1>
                </div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:" class="btn btn--reset d-flex justify-content-between align-items-center gap-4 lh--1 h--45px">
                        {{ translate('messages.status') }}
                        <label class="toggle-switch toggle-switch-sm" for="status">
                            <input type="checkbox" data-url="#" class="toggle-switch-input"
                                id="status" checked="">
                            <span class="toggle-switch-label mx-auto">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </a>
                    <a href="javascript:" class="btn btn--primary float-right mb-0">
                        <i class="tio-edit"></i> {{ translate('messages.edit_vendor') }}
                    </a>
                </div>
            </div>

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs mb-2">
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == null ? 'active' : '' }}"
                            href="javascript:">{{ translate('messages.overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'trip' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.trip_list') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'driver_list' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.driver_list') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'vehicles' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.vehicles') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'reviews' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.reviews') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'discount' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.discounts') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'transaction' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.transactions') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'settings' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.settings') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'conversations' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('Conversations') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title {{ request('tab') == 'meta-data' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('meta_data') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title  {{ request('tab') == 'disbursements' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.disbursements') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title  {{ request('tab') == 'business_plan' ? 'active' : '' }}"
                            href="javascript:" aria-disabled="true">{{ translate('messages.business_plan') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <div class="row g-3 text-capitalize">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-md-4">
                <div class="card h-100 card--bg-1">
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                        <div class="cash-icon mb-20">
                            <img src="{{ asset('public/assets/admin/img/cash.png') }}" width="48"
                                height="48" alt="img">
                        </div>
                        <h2 class="text--title font-bold">
                            <span class="fs-16">$</span>
                            <span class="fs-24">3,348,45</span>
                        </h2>
                        <h5 class="text--title font-regular mb-20">
                            {{ translate('messages.cash_collected_by_provider') }}
                        </h5>
                        <button class="btn btn btn--primary h--45px" id="collect_cash" type="button"
                            data-toggle="modal" data-target="#collect-cash"
                            title="Collect Cash">{{ translate('messages.collect_cash_from_provider') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row g-3">
                    <!-- Panding Withdraw Card Example -->
                    <div class="col-sm-6">
                        <div class="resturant-card card--bg-2">
                            <h2 class="title font-bold">
                                <span class="fs-16">$</span>
                                <span class="fs-24">20</span>
                            </h2>
                            <h5 class="text--title font-regular">{{ translate('messages.pending_withdraw') }}</h5>
                            <img class="resturant-icon w--30"
                                src="{{ asset('public/assets/admin/img/transactions/pending.png') }}"
                                alt="transaction">
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-sm-6">
                        <div class="resturant-card card--bg-3">
                            <h2 class="title font-bold">
                                <span class="fs-16">$</span>
                                <span class="fs-24">200</span>
                            </h2>
                            <h5 class="text--title font-regular">
                                {{ translate('messages.total_withdrawal_amount') }}</h5>
                            <img class="resturant-icon w--30"
                                src="{{ asset('public/assets/admin/img/transactions/withdraw-amount.png') }}"
                                alt="transaction">
                        </div>
                    </div>

                    <!-- Collected Cash Card Example -->
                    <div class="col-sm-6">
                        <div class="resturant-card card--bg-4">
                            <h2 class="title font-bold">
                                <span class="fs-16">$</span>
                                <span class="fs-24">100</span>
                            </h2>
                            <h5 class="text--title font-regular">{{ translate('messages.withdraw_able_balance') }}
                            </h5>
                            <img class="resturant-icon w--30"
                                src="{{ asset('public/assets/admin/img/transactions/withdraw-balance.png') }}"
                                alt="transaction">
                        </div>
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-sm-6">
                        <div class="resturant-card card--bg-1">
                            <h2 class="title font-bold">
                                <span class="fs-16">$</span>
                                <span class="fs-24">100</span>
                            </h2>
                            <h5 class="text--title font-regular">{{ translate('messages.total_earning') }}</h5>
                            <img class="resturant-icon w--30"
                                src="{{ asset('public/assets/admin/img/transactions/earning.png') }}"
                                alt="transaction">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card mt-4 p-4">
            <div class="row g-2" id="order_stats">
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                All
                            </h6>
                            <span class="card-title text--info">
                                200
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                Completed
                            </h6>
                            <span class="card-title text--success">
                                200
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                Cancled
                            </h6>
                            <span class="card-title text--danger">
                                200
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!-- Card -->
                    <a class="order--card h-100" href="#">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                Cancelation rate
                            </h6>
                            <span class="card-title text--warning">
                                12%
                            </span>
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
            </div>
        </div>
        <div class="taxi-banner radius-10 mt-4 mb-20">
            <div class="taxi-info-wrapper d-flex flex-wrap flex-sm-nowrap gap-30px">
                <div class="logo">
                    <img src="{{ asset('public/assets/admin/img/placeholder.png') }}" width="150"
                        class="rounded-8" alt="">
                </div>
                <div class="taxi-info">
                    <h3 class="fs-20 fw-bold text--title mb-20">
                        {{ translate('messages.Auto_Focus_Car_Service') }}</h3>
                    <div class="details d-flex flex-wrap flex-column flex-sm-row gap-40px">
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/zone.png') }}" width="36"
                                height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.Business_Address') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">House: 00, Road: 00, Test City</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/job-type.png') }}" width="36"
                                height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.Business_Plan') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">Commission Base</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/wallet.png') }}" width="36"
                                height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C">
                                    {{ translate('messages.Approx. Pickup Time') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">30 Minutes</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/vehicle-type.png') }}"
                                width="36" height="36" class="rounded" alt="">
                            <div>
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.VAT / TAX') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">5 %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Provider_Information') }}
                    </h5>
                    <p class="fs-12">
                        {{ translate('messages.Here you can see all the information that provider submit during registration') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold text-title">
                                    {{ translate('messages.General_Information') }}
                                </h5>
                                <div class="resturant--info-address align-items-end">
                                    <ul class="address-info p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Provider Name') }}</span>
                                            <span>: <span
                                                    class="font-semibold">{{ translate('messages.Auto Focus Car Service') }}</span></span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Business Zone') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.Dhanmondi') }}
                                                </span>
                                            </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Pickup Zone') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.Dhanmondi, Mirpur, Uttara') }}
                                                </span></span>
                                        </li>
                                    </ul>
                                    <div class="hs-unfold mt-1">
                                        <button
                                            class="btn order--details-btn-sm btn--varify btn-outline-varify btn--sm font-regular d-flex align-items-center __gap-5px"
                                            data-toggle="modal" data-target="#locationModal"><i
                                                class="tio-poi"></i>
                                            {{ translate('messages.map_view') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold text-title">
                                    {{ translate('messages.Owner_Information') }}
                                </h5>
                                <div class="resturant--info-address align-items-start gap-3">
                                    <ul class="address-info flex-wrap p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.First Name') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.Jonathan') }}
                                                </span>
                                            </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Last Name') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.Kent') }}
                                                </span>
                                            </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Phone') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('+9155 4564545') }}
                                                </span>
                                            </span>
                                        </li>
                                    </ul>
                                    <ul class="address-info flex-wrap p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.Email') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.admin@companyname.com') }}
                                                </span>
                                            </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label min-w-sm-auto">{{ translate('messages.Password') }}</span>
                                            <span>: <span
                                                    class="font-semibold">{{ translate('*************') }}</span>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0 h-100">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.Pickup_Zone') }}
                                </h5>
                                <div class="d-flex gap-2 gap-sm-3 flex-wrap">
                                    <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                        New York State
                                    </label>
                                    <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                        Washington
                                    </label>
                                    <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                        Chicago Municipal
                                    </label>
                                    <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                        Chicago Municipal
                                    </label>
                                    <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                        Chicago Municipal
                                    </label>
                                    <label class="badge badge-soft-dark rounded-20 p-2 m-0 font-medium">
                                        Chicago Municipal
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold text-title">
                                    {{ translate('messages.Bank_Information') }}
                                </h5>
                                <div class="resturant--info-address align-items-start gap-3">
                                    <ul class="address-info flex-wrap p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.A/C Name') }}</span>
                                            <span>: <span
                                                    class="font-semibold">{{ translate('messages.Jonathan Kent') }}</span></span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.A/C No') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.123564785691234') }}
                                                </span>
                                            </span>
                                        </li>
                                    </ul>
                                    <ul class="address-info flex-wrap p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Bank Name') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.Bank of America') }}
                                                </span></span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Branch Name') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.California Main Branch') }}
                                                </span></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Documents_And_Images') }}
                    </h5>
                    <p class="fs-12">
                        {{ translate('messages.Here you can see all images & document for the provider') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <h5 class="text-title font-bold mb-10px"> {{ translate('messages.Documents') }}</h5>
                <div class="d-flex gap-3 flex-wrap mb-20">
                    <div class="pdf-single" data-pdf-url="{{ asset('public/assets/admin/img/pdf/sample.pdf') }}"
                        onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <canvas class="pdf-preview" style="display: none;"></canvas>
                            <img class="pdf-thumbnail" src="{{ asset('public/assets/admin/img/blank2.png') }}"
                                alt="File Thumbnail">
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="download-btn" onclick="downloadPdf(event, this)"
                                title="">
                                <i class="tio-download-to"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="{{ asset('public/assets/admin/img/document.svg') }}" width="34"
                                    alt="Document Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span class="file-name"></span>
                                    <span class="opacity-50">Click to view the file</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pdf-single" data-pdf-url="{{ asset('public/assets/admin/img/profile.jpg') }}"
                        onclick="openPdf(this)">
                        <div class="pdf-frame">
                            <canvas class="pdf-preview" style="display: none;"></canvas>
                            <img class="pdf-thumbnail" src="{{ asset('public/assets/admin/img/blank2.png') }}"
                                alt="File Thumbnail">
                        </div>
                        <div class="overlay">
                            <a href="javascript:void(0);" class="download-btn" onclick="downloadPdf(event, this)"
                                title="">
                                <i class="tio-download-to"></i>
                            </a>
                            <div class="pdf-info d-flex gap-10px align-items-center">
                                <img src="{{ asset('public/assets/admin/img/picture.svg') }}" width="34"
                                    alt="Document Logo">
                                <div class="fs-13 text--title d-flex flex-column">
                                    <span class="file-name"></span>
                                    <span class="opacity-50">Click to view the file</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="text-title font-bold mb-10px"> {{ translate('messages.Images') }}</h5>
                <div class="d-flex gap-3 flex-wrap mb-20px">
                    <div class="download-image-single">
                        <div class="image-frame">
                            <img src="{{ asset('public/assets/admin/img/user-2.png') }}" alt="provider image">
                        </div>
                        <div class="overlay bg-transparent">
                            <a href="{{ asset('public/assets/admin/img/user-2.png') }}" class="download-btn"
                                download="">
                                <i class="tio-download-to"></i>
                            </a>
                        </div>
                    </div>
                    <div class="download-image-single">
                        <div class="image-frame">
                            <img src="{{ asset('public/assets/admin/img/user-2.png') }}" alt="provider image">
                        </div>
                        <div class="overlay bg-transparent">
                            <a href="{{ asset('public/assets/admin/img/user-2.png') }}" class="download-btn"
                                download="">
                                <i class="tio-download-to"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-title font-bold" id="locationModalLabel">
                        {{ translate('messages.ABC Rent a Car') }}</h3>
                    <button type="button" class="close fs-24 m-0 p-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-4 mb-20">
                        <div>
                            <span class="text-title font-medium"> {{ translate('messages.Business Zone') }}</span>
                            <div class="mt-10px">
                                <button class="btn btn--primary font-medium zone-btn" data-zone="mirpur12">Mirpur
                                    12</button>
                            </div>
                        </div>
                        <div>
                            <span class="text-title font-medium"> {{ translate('messages.Pickup Zone') }}</span>
                            <div class="d-flex flex-wrap gap-10px mt-10px">
                                <button class="btn btn--reset font-medium zone-btn" data-zone="mirpur12">Mirpur
                                    12</button>
                                <button class="btn btn--reset font-medium zone-btn" data-zone="battali">Battali</button>
                                <button class="btn btn--reset font-medium zone-btn" data-zone="baoshila">Baoshila</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal_body_map">
                        <div class="location-map" id="location-map">
                            <div id="map" class="initial--25"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection


@push('script_2')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&callback=initMap&v=3.45.8">
    </script>
    <script>
        "use strict";

        // Define zone coordinates (use actual polygon coordinates)
        const zones = {
            mirpur12: [{
                    lat: 23.8172372,
                    lng: 90.3323452
                },
                {
                    lat: 23.8202372,
                    lng: 90.3323452
                },
                {
                    lat: 23.8202372,
                    lng: 90.3353452
                },
                {
                    lat: 23.8172372,
                    lng: 90.3353452
                },
                {
                    lat: 23.8162372,
                    lng: 90.3333452
                }
            ],
            battali: [{
                    lat: 23.8202372,
                    lng: 90.340454
                },
                {
                    lat: 23.8222372,
                    lng: 90.340454
                },
                {
                    lat: 23.8222372,
                    lng: 90.343454
                },
                {
                    lat: 23.8202372,
                    lng: 90.343454
                }
            ],
            baoshila: [{
                    lat: 23.8152372,
                    lng: 90.330454
                },
                {
                    lat: 23.8172372,
                    lng: 90.330454
                },
                {
                    lat: 23.8172372,
                    lng: 90.333454
                },
                {
                    lat: 23.8152372,
                    lng: 90.333454
                }
            ]
        };

        let map;
        let zonePolygons = {};
        let marker;

        initMap();

        function initMap() {
            const myLatLng = {
                lat: 23.8172372,
                lng: 90.3323452
            };
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });

            // Create polygons for each zone
            Object.keys(zones).forEach(zone => {
                const polygon = new google.maps.Polygon({
                    paths: zones[zone],
                    strokeColor: '#BFBFBF59',
                    strokeOpacity: 1,
                    strokeWeight: 2,
                    fillColor: '#BFBFBF59',
                    fillOpacity: 0.5,
                    map: map,
                });
                zonePolygons[zone] = polygon;
            });

            // Create a custom marker
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: {
                    url: "{{ asset('public/assets/admin/img/zone-status-on.png') }}",
                    scaledSize: new google.maps.Size(26, 40),
                    anchor: new google.maps.Point(15, 40)
                }
            });
        }

        // Function to calculate the centroid of a polygon
        function calculateCentroid(polygon) {
            let latSum = 0;
            let lngSum = 0;
            const paths = polygon.getPath().getArray();
            const n = paths.length;

            paths.forEach(latLng => {
                latSum += latLng.lat();
                lngSum += latLng.lng();
            });

            return {
                lat: latSum / n,
                lng: lngSum / n
            };
        }

        // Event listener for zone buttons
        document.querySelectorAll('.zone-btn').forEach(button => {
            button.addEventListener('click', () => {
                const selectedZone = button.getAttribute('data-zone');

                // Hide all zones initially
                Object.keys(zonePolygons).forEach(zone => {
                    zonePolygons[zone].setMap(null);
                });

                // Show selected zone
                zonePolygons[selectedZone].setMap(map);

                // Calculate the centroid of the selected zone
                const zoneCenter = calculateCentroid(zonePolygons[selectedZone]);
                marker.setPosition(zoneCenter);
                marker.setMap(map);
            });
        });
    </script>

    <script>
        // ----- document view from file
        document.addEventListener("DOMContentLoaded", function() {

            async function renderFileThumbnail(element) {
                const fileUrl = element.getAttribute("data-pdf-url");
                const canvas = element.querySelector(".pdf-preview");
                const thumbnail = element.querySelector(".pdf-thumbnail");
                const fileNameSpan = element.querySelector(".file-name");
                const downloadButton = element.querySelector(".download-btn");

                // Extract file name and extension
                const fullFileName = fileUrl.split('/').pop();
                const fileExtension = fullFileName.split('.').pop().toLowerCase();
                const fileNameWithoutExtension = fullFileName.replace(/\.[^/.]+$/, '');

                // Truncate file name if it's too long
                const truncatedFileName =
                    fileNameWithoutExtension.length > 20 ?
                    `${fileNameWithoutExtension.substring(0, 17)}...` :
                    fileNameWithoutExtension;
                const displayedFileName = `${truncatedFileName}.${fileExtension}`;

                // Set the file name in the UI
                fileNameSpan.textContent = displayedFileName;
                downloadButton.setAttribute("title", fullFileName);

                // Handle PDF thumbnail generation
                if (fileExtension === "pdf") {
                    const ctx = canvas.getContext("2d");

                    try {
                        // Load the PDF using PDF.js
                        const loadingTask = pdfjsLib.getDocument(fileUrl);
                        const pdf = await loadingTask.promise;
                        const page = await pdf.getPage(1);

                        // Set scale and dimensions for the thumbnail
                        const viewport = page.getViewport({
                            scale: 0.5
                        });
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        // Render the first PDF page into the canvas
                        await page.render({
                            canvasContext: ctx,
                            viewport
                        }).promise;

                        // Convert canvas to image URL and set as the thumbnail
                        thumbnail.src = canvas.toDataURL();
                    } catch (error) {
                        console.error("Error rendering PDF thumbnail:", error);
                        // Fallback to blank image if there's an error
                        thumbnail.src = "{{ asset('public/assets/admin/img/blank2.png') }}";
                    }
                } else if (["jpg", "jpeg", "png", "gif", "bmp"].includes(fileExtension)) {
                    // Handle image file types (JPG, PNG, GIF, etc.)
                    thumbnail.src = fileUrl; // Set the image URL as the thumbnail
                } else {
                    // For non-PDF, non-image files (e.g., DOCX, XLSX, etc.)
                    const fileIconPath = `{{ asset('public/assets/admin/img/icons') }}/${fileExtension}.png`;
                    const fallbackIconPath =
                        "{{ asset('public/assets/admin/img/blank2.png') }}"; // Fallback image

                    // Check if a specific icon exists for the file type, otherwise use the fallback
                    const iconExists = await checkFileIconExistence(fileIconPath);

                    thumbnail.src = iconExists ? fileIconPath : fallbackIconPath;
                }

                // Show the thumbnail and hide the canvas
                thumbnail.style.display = "block";
                canvas.style.display = "none";
            }

            // Function to check if the icon exists
            async function checkFileIconExistence(iconPath) {
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => resolve(true); // Icon exists
                    img.onerror = () => resolve(false); // Icon doesn't exist
                    img.src = iconPath;
                });
            }

            // Iterate over all .pdf-single elements to render thumbnails
            document.querySelectorAll(".pdf-single").forEach(renderFileThumbnail);

            // Open the file in a new tab
            window.openPdf = function(element) {
                const fileUrl = element.getAttribute("data-pdf-url");
                window.open(fileUrl, "_blank");
            };

            // Download the file on button click
            window.downloadPdf = function(event, buttonElement) {
                event.stopPropagation();

                const fileUrl = buttonElement.closest(".pdf-single").getAttribute("data-pdf-url");
                const link = document.createElement("a");
                link.href = fileUrl;
                link.download = fileUrl.split("/").pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };

        });
        // ----- document view from file ends
    </script>
@endpush
