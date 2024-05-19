@extends('layouts.landing.app')
@section('title', translate('messages.store_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/view-pages/vendor-registration.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/landing/css/select2.min.css') }}"/>
@endpush
@section('content')
    <section class="m-0 py-5">
        <div class="container">
            <!-- Page Header -->
            <div class="section-header">
                <h2 class="title mb-2">{{ translate('messages.store') }} <span class="text--base">{{translate('application')}}</span></h2>
            </div>
            @php($language=\App\Models\BusinessSetting::where('key','language')->first())
            @php($language = $language->value ?? null)
            @php($defaultLang = 'en')
            <!-- End Page Header -->

            <!-- Stepper -->
                <div class="stepper">
                    <div class="stepper-item active">
                        <div class="step-name">{{ translate('General Info') }}</div>
                    </div>
                    <div class="stepper-item active">
                        <div class="step-name">{{ translate('Business Plan') }}</div>
                    </div>
                    <div class="stepper-item">
                        <div class="step-name">{{ translate('Complete') }}</div>
                    </div>
                </div>
            <!-- Stepper -->
        

            <form class="reg-form js-validate" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card __card mb-3 pt-4">
                    <div class="card-header border-0">
                        <h5 class="card-title text-center">
                            {{ translate('Choose Your Business Plan') }}
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="card-header card-header-active">
                            <h5 class="card-title text-center font-semibold">
                                {{ translate('Continue with 7 Days Free Trial') }}
                            </h5>
                        </div>
                        <br>
                        <br>
                        <h6 class="text-16 mb-4">Pay Via Online <span class="font-regular text-body">(Faster &amp; secure way to pay bill)</span></h6>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{asset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{asset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>Bkash</span>
                                        <img class="ms-auto" src="{{asset('public/assets/admin/img/bkash1.png')}}" width="30" alt="">
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{asset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{asset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>Marcado pago</span>
                                        <img class="ms-auto" src="{{asset('public/assets/admin/img/marcado1.png')}}" width="30" alt="">
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{asset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{asset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>SSL COMMERZ</span>
                                        <img class="ms-auto" src="{{asset('public/assets/admin/img/sslcomz1.png')}}" width="60" alt="">
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{asset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{asset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>Marcado pago</span>
                                        <img class="ms-auto" src="{{asset('public/assets/admin/img/marcado1.png')}}" width="30" alt="">
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{asset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{asset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>PayStack</span>
                                        <img class="ms-auto" src="{{asset('public/assets/admin/img/paystack1.png')}}" width="30" alt="">
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="payment-item">
                                    <input type="radio" class="d-none" name="payment">
                                    <div class="payment-item-inner">
                                        <div class="check">
                                            <img src="{{asset('public/assets/admin/img/check-1.png')}}" class="uncheck" alt="">
                                            <img src="{{asset('public/assets/admin/img/check-2.png')}}" class="check" alt="">
                                        </div>
                                        <span>SSL COMMERZ</span>
                                        <img class="ms-auto" src="{{asset('public/assets/admin/img/sslcomz1.png')}}" width="60" alt="">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="text-end pt-5 d-flex flex-wrap justify-content-end gap-3">
                            <button type="button" class="cmn--btn btn--secondary shadow-none rounded-md border-0 outline-0">{{ translate('Back')
                                }}</button>
                            <button type="submit" class="cmn--btn rounded-md border-0 outline-0">{{ translate('Next')
                                }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @endsection
    @push('script_2')
    @endpush
