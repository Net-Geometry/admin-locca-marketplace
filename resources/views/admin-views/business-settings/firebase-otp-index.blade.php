@extends('layouts.admin.app')

@section('title', translate('Firebase OTP Verification'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/firebase_auth.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('Firebase OTP Verification')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <form
            action="{{env('APP_MODE')!='demo'?route('admin.business-settings.third-party.firebase_otp_update',['recaptcha']):'javascript:'}}"
            method="post">
            @csrf
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-6 col-sm-6">
                                    @php($firebase_otp_verification = \App\Models\BusinessSetting::where('key', 'firebase_otp_verification')->first())
                                    @php($firebase_otp_verification = $firebase_otp_verification ? $firebase_otp_verification->value : '')
                                    <div class="form-group mb-0">

                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('Firebase_OTP_Verification_Status') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                      data-toggle="tooltip" data-placement="right"
                                                      data-original-title="{{ translate('If_this_field_is_active_customers_get_the_OTP_through_Firebase.') }}"><img
                                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.firebase_otp_verification') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                                   data-id="firebase_otp_verification"
                                                   data-type="toggle"
                                                   data-image-on="{{ asset('/public/assets/admin/img/modal/show-earning-in-apps-on.png') }}"
                                                   data-image-off="{{ asset('/public/assets/admin/img/modal/show-earning-in-apps-off.png') }}"
                                                   data-title-on="{{translate('Want_to_enable')}} <strong>{{translate('Cash_In_Hand_Overflow')}}</strong>"
                                                   data-title-off="{{translate('Want_to_disable')}} <strong>{{translate('Cash_In_Hand_Overflow')}}</strong> "
                                                   data-text-on="<p>{{ translate('If_enabled,_stores_have_to_provide_collected_cash_by_them_self') }}</p>"
                                                   data-text-off="<p>{{ translate('If_disabled,_stores_do_not_have_to_provide_collected_cash_by_them_self') }}</p>"
                                                   class="status toggle-switch-input dynamic-checkbox-toggle"
                                                   value="1"
                                                   name="firebase_otp_verification" id="firebase_otp_verification"
                                                {{ $firebase_otp_verification == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    @php($firebase_web_api_key = \App\Models\BusinessSetting::where('key', 'firebase_web_api_key')->first())
                                    <div class="form-group mb-0">
                                        <label class=" input-label text-capitalize"
                                               for="firebase_web_api_key">
                                            <span>
                                                {{ translate('Web_API_key') }}
                                            </span>

                                            <span class="form-label-secondary"
                                                  data-toggle="tooltip" data-placement="right"
                                                  data-original-title="{{ translate('Enter_the_maximum_cash_amount_stores_can_hold._If_this_number_exceeds,_stores_will_be_suspended_and_not_receive_any_orders.') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.dm_cancel_order_hint') }}"></span>
                                        </label>
                                        <input type="text" name="firebase_web_api_key" class="form-control"
                                               id="firebase_web_api_key"
                                               value="{{ $firebase_web_api_key ? $firebase_web_api_key->value : '' }}"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                        class="btn btn--primary call-demo">{{ translate('save_information') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>



@endsection
