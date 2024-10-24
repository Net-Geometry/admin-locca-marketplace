@extends('layouts.admin.app')

@section('title', translate('messages.New Provider Request - Details'))

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
                        <span>{{ translate('messages.Provider_Details') }}
                    </h1></span>
                    </h1>
                </div>
                <div class="d-flex align-items-start flex-wrap gap-2">
                    <a href="javascript:" class="btn btn--primary-light float-right mb-0">
                        <i class="tio-edit"></i> {{ translate('messages.edit_provider') }}
                    </a>
                    <a class="btn btn--warning-light font-weight-bold float-right request_alert mb-0" data-url="javascript:"
                        data-message="{{ translate('messages.you_want_to_deny_this_application') }}" href="javascript:"><i
                            class="tio-clear font-weight-bold pr-1"></i>
                        {{ translate('messages.reject') }}</a>
                    <a class="btn btn--primary font-weight-bold float-right mr-2 request_alert mb-0" data-url="javascript:"
                        data-message="{{ translate('messages.you_want_to_approve_this_application') }}"
                        href="javascript:"><i
                            class="tio-done font-weight-bold pr-1"></i>{{ translate('messages.approve') }}</a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
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
                            <img src="{{ asset('public/assets/admin/img/icons/zone.png') }}" width="36" height="36"
                                class="rounded" alt="">
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
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Registration_Information') }}
                    </h5>
                    <p class="fs-12">
                        {{ translate('messages.Here you can see all the information that provider submit during registration') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.General_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info address-info-2 p-0 text-dark">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Provider Name') }}</span>
                                            <span>: {{ translate('messages.Auto Focus Car Service') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Business Zone') }}</span>
                                            <span>: {{ translate('messages.Dhanmondi') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Pickup Zone') }}</span>
                                            <span>: {{ translate('messages.Dhanmondi, Mirpur, Uttara') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.Owner_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info address-info-2 p-0 text-dark">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.First Name') }}</span>
                                            <span>: {{ translate('messages.Jonathan') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Last Zone') }}</span>
                                            <span>: {{ translate('messages.Kent') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Phone') }}</span>
                                            <span>: {{ translate('+9155 4564545') }}</span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card __bg-FAFAFA border-0">
                            <div class="card-body">
                                <h5 class="mb-10px font-bold"> {{ translate('messages.Login_Information') }}
                                </h5>
                                <div class="resturant--info-address">
                                    <ul class="address-info address-info-2 p-0 text-dark">
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Email') }}</span>
                                            <span>: {{ translate('messages.admin@companyname.com') }}</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <span class="label">{{ translate('messages.Password') }}</span>
                                            <span>: {{ translate('*************') }}</span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
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
