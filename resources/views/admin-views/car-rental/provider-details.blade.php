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
                    <a href="javascript:" class="btn btn--primary float-right mb-0">
                        <i class="tio-edit"></i> {{ translate('messages.edit_provider') }}
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
                        <a class="nav-link {{ request('tab') == null ? 'active' : '' }}"
                            href="javascript:">{{ translate('messages.overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'trip' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.trip') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'driver_list' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.driver_list') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'vehicles' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.vehicles') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'reviews' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.reviews') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'discount' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.discounts') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'transaction' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.transactions') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'settings' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.settings') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'conversations' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('Conversations') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'meta-data' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('meta_data') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  {{ request('tab') == 'disbursements' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.disbursements') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  {{ request('tab') == 'business_plan' ? 'active' : '' }}" href="javascript:"
                            aria-disabled="true">{{ translate('messages.business_plan') }}</a>
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
                            <img src="{{ asset('public/assets/admin/img/cash.png') }}" width="48" height="48"
                                alt="img">
                        </div>
                        <h2 class="text--title font-bold">
                            <span class="fs-16">$</span>
                            <span class="fs-24">3,348,45</span>
                        </h2>
                        <h5 class="text--title font-regular mb-20">
                            {{ translate('messages.cash_collected_by_provider') }}
                        </h5>
                        <button class="btn btn btn--primary h--45px" id="collect_cash" type="button" data-toggle="modal"
                            data-target="#collect-cash"
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
                                src="{{ asset('public/assets/admin/img/transactions/pending.png') }}" alt="transaction">
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-sm-6">
                        <div class="resturant-card card--bg-3">
                            <h2 class="title font-bold">
                                <span class="fs-16">$</span>
                                <span class="fs-24">200</span>
                            </h2>
                            <h5 class="text--title font-regular">{{ translate('messages.total_withdrawal_amount') }}</h5>
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
                            <h5 class="text--title font-regular">{{ translate('messages.withdraw_able_balance') }}</h5>
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
                                src="{{ asset('public/assets/admin/img/transactions/earning.png') }}" alt="transaction">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="taxi-banner radius-10 mt-4 mb-20">
            <div class="taxi-info-wrapper d-flex flex-wrap flex-sm-nowrap gap-30px">
                <div class="logo">
                    <img src="{{ asset('public/assets/admin/img/placeholder.png') }}" width="150" class="rounded-8"
                        alt="">
                </div>
                <div class="taxi-info">
                    <h3 class="fs-20 fw-bold text--title mb-20"> {{ translate('messages.Auto_Focus_Car_Service') }}</h3>
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
                                <h5 class="lh--12 mb-0 color-3C3C3C"> {{ translate('messages.Approx. Pickup Time') }}
                                </h5>
                                <span class="fs-13 lh--12 color-484848">30 Minutes</span>
                            </div>
                        </div>
                        <div class="details-single d-flex align-items-center gap-2">
                            <img src="{{ asset('public/assets/admin/img/icons/vehicle-type.png') }}" width="36"
                                height="36" class="rounded" alt="">
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
        <!-- End Page Header -->
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
                                <h5 class="mb-10px font-bold text-title"> {{ translate('messages.General_Information') }}
                                </h5>
                                <div class="resturant--info-address">
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
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold text-title"> {{ translate('messages.Owner_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info flex-wrap p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.First Name') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.Jonathan') }}
                                                </span>
                                            </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Last Zone') }}</span>
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

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold text-title"> {{ translate('messages.Login_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info flex-wrap p-0 text-title">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Email') }}</span>
                                            <span>: <span class="font-semibold">
                                                    {{ translate('messages.admin@companyname.com') }}
                                                </span>
                                            </span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Password') }}</span>
                                            <span>: <span class="font-semibold">{{ translate('*************') }}</span>
                                            </span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold text-title"> {{ translate('messages.Bank_Information') }}
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
                <div class="pdf-single">
                    <iframe src="https://pdfobject.com/pdf/sample.pdf" height="150" frameborder="0"></iframe>
                    <div class="overlay">
                        <a href="https://pdfobject.com/pdf/sample.pdf" download class="download-btn">
                            <i class="tio-download-to"></i>
                        </a>
                        <div class="pdf-info d-flex gap-10px">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22 pdf-logo"
                                alt="PDF Logo">
                            <p class="pdf-name text--title">Trade License Documents.pdf</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush

@push('script_2')
@endpush
