@extends('layouts.admin.app')

@section('title', translate('messages.Storage_Connection'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/captcha.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.storage_connection_credentials_setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->
        <div class="card border-0">
            <div class="card-header card-header-shadow">
                <h5 class="card-title align-items-center">
                    {{translate('Storage_Connection_Settings')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('cash_on_delivery'))
                        <form action="{{route('admin.business-settings.third-party.payment-method-update',['cash_on_delivery'])}}"
                              method="post" id="cash_on_delivery_status_form">
                            @csrf
                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1">
                                        {{translate('Cash On Delivery')}}
                                    </span>
                                    <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled_Customers_will_be_able_to_select_COD_as_a_payment_method_during_checkout')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                </span>
                                <input type="hidden" name="toggle_type" value="cash_on_delivery">
                                <input
                                    type="checkbox" id="cash_on_delivery_status"
                                    data-id="cash_on_delivery_status"
                                    data-type="status"
                                    data-image-on="{{ asset('/public/assets/admin/img/modal/digital-payment-on.png') }}"
                                    data-image-off="{{ asset('/public/assets/admin/img/modal/digital-payment-off.png') }}"
                                    data-title-on="{{ translate('By Turning ON Cash On Delivery Option') }}"
                                    data-title-off="{{ translate('By Turning OFF Cash On Delivery Option') }}"
                                    data-text-on="<p>{{ translate('Customers will not be able to select COD as a payment method during checkout. Please review your settings and enable COD if you wish to offer this payment option to customers.') }}</p>"
                                    data-text-off="<p>{{ translate('Customers will be able to select COD as a payment method during checkout.') }}</p>"
                                    class="status toggle-switch-input dynamic-checkbox"
                                    name="status" value="1" {{$config?($config['status']==1?'checked':''):''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4">
                        @php($digital_payment=\App\CentralLogics\Helpers::get_business_settings('digital_payment'))
                        <form action="{{route('admin.business-settings.third-party.payment-method-update',['digital_payment'])}}"
                              method="post" id="digital_payment_status_form">
                            @csrf
                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1">
                                        {{translate('digital payment')}}
                                    </span>
                                    <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled_Customers_will_be_able_to_select_digital_payment_as_a_payment_method_during_checkout')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="Veg/non-veg toggle"> * </span>
                                </span>
                                <input type="hidden" name="toggle_type" value="digital_payment">
                                <input  type="checkbox" id="digital_payment_status"
                                        data-id="digital_payment_status"
                                        data-type="status"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/digital-payment-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/digital-payment-off.png') }}"
                                        data-title-on="{{ translate('By Turning ON Digital Payment Option') }}"
                                        data-title-off="{{ translate('By Turning OFF Digital Payment Option') }}"
                                        data-text-on="<p>{{ translate('Customers will not be able to select digital payment as a payment method during checkout. Please review your settings and enable digital payment if you wish to offer this payment option to customers.') }}</p>"
                                        data-text-off="<p>{{ translate('Customers will be able to select digital payment as a payment method during checkout.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox"
                                        name="status" value="1" {{$digital_payment?($digital_payment['status']==1?'checked':''):''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @php($config=\App\CentralLogics\Helpers::get_business_settings('s3_credential'))
        <div class="card mt-3">
            <div class="p-4 card-header-shadow">
                <h4 class="card-title align-items-center">
                    {{translate('S3_Credential')}}
                </h4>
                <span>{{ translate('The_Access_Key_ID_is_a_publicly_accessible_identifier_used_to_authenticate_requests_to_S3.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
            </div>
            <div class="card-body">
                <div class="mt-2 px-3">
                    <form
                        action="{{env('APP_MODE')!='demo'?route('admin.business-settings.third-party.storage_connection_update',['storage_connection']):'javascript:'}}"
                        method="post">
                        @csrf
                                <div class="border pt-5 radius-10 row mb-3">
                                    <div class="col-lg-4 col-sm-6 p-20">
                                        <label for="key" class="form-label">{{translate('messages.key')}}</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="form-group">
                                            <input id="key" type="text" class="form-control mb-2" name="key"
                                                   value="{{env('APP_MODE')!='demo'?$config['key']??"":''}}">
                                            <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border pt-5 radius-10 row mb-3">
                                    <div class="col-lg-4 col-sm-6 p-20">
                                        <label for="secret" class="form-label">{{translate('messages.secret')}}</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="form-group">
                                            <input id="secret" type="text" class="form-control mb-2" name="secret"
                                                   value="{{env('APP_MODE')!='demo'?$config['secret']??"":''}}">
                                            <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border pt-5 radius-10 row mb-3">
                                    <div class="col-lg-4 col-sm-6 p-20">
                                        <label for="region" class="form-label">{{translate('messages.region')}}</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="form-group">
                                            <input id="region" type="text" class="form-control mb-2" name="region"
                                                   value="{{env('APP_MODE')!='demo'?$config['region']??"":''}}">
                                            <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border pt-5 radius-10 row mb-3">
                                    <div class="col-lg-4 col-sm-6 p-20">
                                        <label for="bucket" class="form-label">{{translate('messages.bucket')}}</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="form-group">
                                            <input id="bucket" type="text" class="form-control mb-2" name="bucket"
                                                   value="{{env('APP_MODE')!='demo'?$config['bucket']??"":''}}">
                                            <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border pt-5 radius-10 row mb-3">
                                    <div class="col-lg-4 col-sm-6 p-20">
                                        <label for="url" class="form-label">{{translate('messages.url')}}</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="form-group">
                                            <input id="url" type="text" class="form-control mb-2" name="url"
                                                   value="{{env('APP_MODE')!='demo'?$config['url']??"":''}}">
                                            <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border pt-5 radius-10 row mb-3">
                                    <div class="col-lg-4 col-sm-6 p-20">
                                        <label for="end_point" class="form-label">{{translate('messages.end_point')}}</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="form-group">
                                            <input id="end_point" type="text" class="form-control mb-2" name="end_point"
                                                   value="{{env('APP_MODE')!='demo'?$config['end_point']??"":''}}">
                                            <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>
                                        </div>
                                    </div>
                                </div>
{{--                            <div class="col-lg-4 col-sm-6">--}}
{{--                                <label for="secret" class="form-label">{{translate('messages.secret')}}</label>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-8 col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input id="secret" type="text" class="form-control" name="secret"--}}
{{--                                            value="{{env('APP_MODE')!='demo'?$config['secret']??"":''}}">--}}
{{--                                    <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-4 col-sm-6">--}}
{{--                                <label for="region" class="form-label">{{translate('messages.region')}}</label>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-8 col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input id="region" type="text" class="form-control" name="region"--}}
{{--                                            value="{{env('APP_MODE')!='demo'?$config['region']??"":''}}">--}}
{{--                                    <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-4 col-sm-6">--}}
{{--                                <label for="bucket" class="form-label">{{translate('messages.bucket')}}</label>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-8 col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input id="bucket" type="text" class="form-control" name="bucket"--}}
{{--                                            value="{{env('APP_MODE')!='demo'?$config['bucket']??"":''}}">--}}
{{--                                    <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-4 col-sm-6">--}}
{{--                                <label for="url" class="form-label">{{translate('messages.url')}}</label>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-8 col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input id="url" type="text" class="form-control" name="url"--}}
{{--                                            value="{{env('APP_MODE')!='demo'?$config['url']??"":''}}">--}}
{{--                                    <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-4 col-sm-6">--}}
{{--                                <label for="end_point" class="form-label">{{translate('messages.end_point')}}</label>--}}
{{--                            </div>--}}
{{--                            <div class="col-lg-8 col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <input id="end_point" type="text" class="form-control" name="end_point"--}}
{{--                                            value="{{env('APP_MODE')!='demo'?$config['end_point']??"":''}}">--}}
{{--                                    <span>{{ translate('Learn_how_to_get_it.') }} <a href="#">{{ translate('Learn_More') }}</a></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary call-demo">{{translate('messages.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
