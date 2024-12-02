@extends('layouts.admin.app')

@section('title', translate('messages.Edit Provider - Business Plan Setup'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
@endpush


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-20">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ translate('messages.Auto Focus Car Service') }}
                    </h1></span>
                    </h1>
                </div>
            </div>
        </div>
        @php
            $delivery_time_start = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode('-', $store->delivery_time)[0]
                : 10;
            $delivery_time_end = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode(' ', explode('-', $store->delivery_time)[1])[0]
                : 30;
            $delivery_time_type = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time ?? '')
                ? explode(' ', explode('-', $store->delivery_time)[1])[1]
                : 'min';
        @endphp
        @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
        @php($language = $language->value ?? null)
        @php($defaultLang = 'en')
        <!-- End Page Header -->

        {{-- timeline --}}
        <div class="custom-timeline d-flex flex-wrap gap-40px text-title mb-2">
            <h4 class="single text-primary checked"><span class="count">1</span>{{ translate('messages.Business Basic Setup') }}</h4>
            <h4 class="single font-semibold"><span class="count btn-primary">2</span>{{ translate('messages.Business Plan Setup') }}</h4>
        </div>

        <form action="{{ route('admin.store.store') }}" method="post" enctype="multipart/form-data" class="js-validate"
            id="vendor_form">
            @csrf

            <div class="row g-2">
                <div class="col-lg-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <div>
                                <h5 class="text-title mb-1">
                                    {{ translate('messages.Choose Business Plan') }}
                                </h5>
                                <p class="fs-12 mb-0">
                                    {{ translate('messages.Provider Logo & Covers') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="business-plan-card-wrapper">
                                        <input type="radio" name="business-plan" class="business-plan-radio" />
                                        <div class="business-plan-card">
                                            <h4 class="fs-16 title text-title mb-10px opacity-70">
                                                {{ translate('messages.Commission Base') }}
                                            </h4>
                                            <p class="fs-14 text-title opacity-70 mb-0">
                                                {{ translate('messages.You have to give a certain percentage of commission to admin for every Trip request.') }}
                                            </p>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <label class="business-plan-card-wrapper">
                                        <input type="radio" name="business-plan" class="business-plan-radio" checked />
                                        <div class="business-plan-card">
                                            <h4 class="fs-16 title text-title mb-10px opacity-70">
                                                {{ translate('messages.Subscription Base') }}
                                            </h4>
                                            <p class="fs-14 text-title opacity-70 mb-0">
                                                {{ translate('messages.You have to pay certain amount in every month/year to admin as subscription fee.') }}
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-lg-12 mt-20">
                                    <div>
                                        <div class="text-center mb-20">
                                            <h3 class="modal-title fs-16 opacity-lg font-bold">
                                                {{ translate('Choose Subscription Package') }}</h3>
                                        </div>
                                        <div class="plan-slider owl-theme owl-carousel owl-refresh">
                                            <div class="__plan-item hover">
                                                <div class="inner-div">
                                                    <div class="text-center">
                                                        <h3 class="title">{{ translate('messages.BASIC') }}</h3>
                                                        <h2 class="price">$20</h2>
                                                        <div class="day-count">60 days</div>
                                                    </div>
                                                    <ul class="info">

                                                        <li>
                                                            <i class="tio-checkmark-circle"></i> <span>
                                                                {{ translate('messages.Free Support 24/7') }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i>
                                                            <span>{{ translate('messages.Databases') }} </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i> <span>
                                                                {{ translate('messages.00 Monthly Trip') }}2
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i> <span>
                                                                {{ translate('messages.5 Vehicle Add') }}
                                                            </span>
                                                        </li>

                                                    </ul>

                                                </div>
                                            </div>
                                            <div class="__plan-item hover active">
                                                <div class="inner-div">
                                                    <div class="text-center">
                                                        <h3 class="title">{{ translate('messages.STANDARED') }}</h3>
                                                        <h2 class="price">$30</h2>
                                                        <div class="day-count">120 days</div>
                                                    </div>
                                                    <ul class="info">

                                                        <li>
                                                            <i class="tio-checkmark-circle"></i>
                                                            <span>{{ translate('messages. Free Support 24/7') }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i>
                                                            <span>{{ translate('messages.Databases') }} </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i>
                                                            <span>{{ translate('messages.1000 Monthly Trip') }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i>
                                                            <span>{{ translate('messages.15 Vehicle Add') }}
                                                            </span>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="__plan-item hover">
                                                <div class="inner-div">
                                                    <div class="text-center">
                                                        <h3 class="title">{{ translate('messages.PREMIUM') }}</h3>
                                                        <h2 class="price">$50</h2>
                                                        <div class="day-count">365 days</div>
                                                    </div>
                                                    <ul class="info">

                                                        <li>
                                                            <i class="tio-checkmark-circle"></i> <span>
                                                                {{ translate('messages.Free Support 24/7') }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i> <span>
                                                                {{ translate('messages.Databases') }} </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i>
                                                            <span>{{ translate('messages.Unlimited Trip') }}
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <i class="tio-checkmark-circle"></i> <span>
                                                                {{ translate('messages.Unlimited Vehicle') }}
                                                                Add
                                                            </span>
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
                </div>
            </div>
            <div class="col-lg-12">
                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" id="reset_btn"
                        class="btn btn--reset min-w-100px justify-content-center">{{ translate('messages.back') }}</button>
                    <button type="submit" class="btn btn--primary min-w-100px justify-content-center">{{ translate('messages.update') }}</button>
                </div>
            </div>
    </div>
    </form>


    </div>

@endsection

@push('script_2')
    <script>
        $('.plan-slider').owlCarousel({
            loop: false,
            margin: 30,
            responsiveClass: true,
            nav: false,
            dots: false,
            items: 3,
            center: true,
            startPosition: 1,

            responsive: {
                0: {
                    items: 1.1,
                    margin: 10,
                },
                375: {
                    items: 1.3,
                    margin: 30,
                },
                576: {
                    items: 1.7,
                },
                768: {
                    items: 2.2,
                    margin: 40,
                },
                992: {
                    items: 3,
                    margin: 40,
                },
                1200: {
                    items: 4,
                    margin: 40,
                }
            }
        })
    </script>
@endpush
