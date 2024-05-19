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
                    <div class="stepper-item active">
                        <div class="step-name">{{ translate('Complete') }}</div>
                    </div>
                </div>
            <!-- Stepper -->
        

            <form class="reg-form js-validate" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card __card mb-3">
                    <div class="card-header border-0 pb-0 text-center pt-5">
                        <img src="{{asset('/public/assets/landing/img/Success.gif')}}" width="40" alt="" class="mb-4">
                        <h5 class="card-title text-center">
                            {{ translate('Congratulations!') }}
                        </h5>
                    </div>
                    <div class="card-body p-4 pb-5">
                        <div class="register-congrats-txt">
                            Thank you for your subscription purchase! Your payment was successfully processed. Please note that your subscription will be activated once it has been approved by our Admin Team. To explore the site <a href="" class="text-base font-bold">visit here</a>
                        </div>
                        <div class="text-center py-2">
                            or
                        </div>
                        <div class="text-center">
                            <a href="" class="text-base font-bold">Continue to Home Page</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @endsection
    @push('script_2')

    @endpush
