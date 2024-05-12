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
        

            <form class="js-validate" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card __card mb-3">
                    <div class="card-header border-0">
                        <h5 class="card-title text-center">
                            {{ translate('Choose Your Business Plan') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="plan-check-item">
                                    <input type="radio" name="plan-name" value="commision" class="d-none" checked>
                                    <div class="plan-check-item-inner">
                                        <h5>Commision Base</h5>
                                        <p>
                                            Store will pay 15% commission to 6amMart from each order. You will get access of all the features and options  in store panel , app and interaction with user.
                                        </p>
                                    </div>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="plan-check-item">
                                    <input type="radio" name="plan-name" value="subscription" class="d-none" >
                                    <div class="plan-check-item-inner">
                                        <h5>Subscription Base</h5>
                                        <p>
                                            Run restaurant by puchasing subsciption  packages. You will have access the features of in restaurant panel , app and interaction with user according to the subscription packages.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div id="subscription-plan">
                            <br>
                            <div class="card-header px-0 m-0 border-0">
                                <h5 class="card-title text-center">
                                    {{ translate('Choose Subscription Package') }}
                                </h5>
                            </div>
                            <div class="row justify-content-center g-4">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label class="__plan-item">
                                        <input type="radio" name="subscription-plan" value="" class="d-none">
                                        <div class="inner-div">
                                            <div class="text-center">
                                                <h3 class="title">BASIC</h3>
                                                <h2 class="price">15%</h2>
                                                <div class="day-count">60 days</div>
                                            </div>
                                            <ul class="info">
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Free Support 24/7</span>
                                                </li>
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Databases</span>
                                                </li>
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Email</span>
                                                </li>
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Unlimited Traffic</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label class="__plan-item">
                                        <input type="radio" name="subscription-plan" value="" checked class="d-none">
                                        <div class="inner-div">
                                            <div class="text-center">
                                                <h3 class="title">STANDARED</h3>
                                                <h2 class="price">15%</h2>
                                                <div class="day-count">60 days</div>
                                            </div>
                                            <ul class="info">
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Free Support 24/7</span>
                                                </li>
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Databases</span>
                                                </li>
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Email</span>
                                                </li>
                                                <li>
                                                    <i class="tio-checkmark-circle"></i> <span>Unlimited Traffic</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label class="__plan-item">
                                        <input type="radio" name="subscription-plan" value="" class="d-none">
                                        <div class="inner-div">
                                            <div class="text-center">
                                                <h3 class="title">PREMIUM</h3>
                                                <h2 class="price">15%</h2>
                                                <div class="day-count">60 days</div>
                                            </div>
                                            <ul class="info">
                                                <li>
                                                    <span>Free Support 24/7</span>
                                                </li>
                                                <li>
                                                    <span>Databases</span>
                                                </li>
                                                <li>
                                                    <span>Email</span>
                                                </li>
                                                <li>
                                                    <span>Unlimited Traffic</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
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

    <script>
        $(window).on('load', function(){
            $('input[name="plan-name"]').each(function(){
                if($(this).is(':checked')){
                    if($(this).val() == 'subscription'){
                        $('#subscription-plan').show()
                    }else {
                        $('#subscription-plan').hide()
                    }
                }
            })
            $('input[name="subscription-plan"]').each(function(){
                if($(this).is(':checked')){
                    $(this).closest('.__plan-item').addClass('active')
                }
            })
        })
        $('input[name="plan-name"]').on('change', function(){
            if($(this).val() == 'subscription'){
                $('#subscription-plan').slideDown()
            }else {
                $('#subscription-plan').slideUp()
            }
        })
        $('input[name="subscription-plan"]').on('change', function(){
            $('input[name="subscription-plan"]').each(function(){
                $(this).closest('.__plan-item').removeClass('active')
            })
            $(this).closest('.__plan-item').addClass('active')
        })
    </script>

    @endpush
