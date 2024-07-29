@extends('layouts.admin.app')

@section('title', translate('messages.external_configuration_settings'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/business.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('business_setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>
        <!-- Page Header -->

        <!-- End Page Header -->
        <form action="{{ route('admin.business-settings.update-external-configuration') }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="row g-2">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 mt-5">
                                    @php($activationMode = \App\Models\ExternalConfiguration::where('key', 'activation_mode')->first())
                                    @php($activationMode = $activationMode ? $activationMode->value : 0)
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('messages.activation_mode') }}
                                                </span>
                                            </span>
                                            <input type="checkbox" value="1"
                                                   class="toggle-switch-input"
                                                   name="activation_mode" id="websocket"
                                                {{ $activationMode == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @php($drivemondBaseUrl = \App\Models\ExternalConfiguration::where('key', 'drivemond_base_url')->first())
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                               for="drivemondBaseUrl">{{ translate('messages.drivemond_base_url') }}</label>
                                        <input type="url" id="drivemondBaseUrl" name="drivemond_base_url"
                                               value="{{ $drivemondBaseUrl->value ?? '' }}"
                                               class="form-control"
                                               placeholder="{{ translate('messages.Ex: https://drivemond.com') }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @php($drivemondToken = \App\Models\ExternalConfiguration::where('key', 'drivemond_token')->first())
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                               for="drivemondToken">{{ translate('messages.drivemond_token') }}</label>
                                        <input id="drivemondToken" maxlength="64" minlength="64" type="text"
                                               value="{{ $drivemondToken->value ?? '' }}" name="drivemond_token"
                                               class="form-control"
                                               placeholder="{{ translate('messages.enter_drivemond_token') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @php($systemSelfToken = \App\Models\ExternalConfiguration::where('key', 'system_self_token')->first())
                                    <div class="form-group mb-0">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label"
                                                   for="systemSelfToken">{{ translate('messages.system_self_token') }}</label>
                                            <a href="javascript:void(0)" class="text-primary"
                                               id="generateSystemSelfToken">{{translate("generate_system_self_token")}}</a>
                                        </div>

                                        <div class="position-relative">
                                            <input id="systemSelfToken" maxlength="64" minlength="64" type="text"
                                                   value="{{ $systemSelfToken->value ?? '' }}" name="system_self_token"
                                                   class="form-control"
                                                   placeholder="{{ translate('messages.generate_system_self_token') }}"
                                                   required>
                                            <a href="javascript:void(0)" class="generate-code form-control text-primary"
                                               id="copyButton"><i class="tio-copy"></i> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="reset" id="reset_btn"
                                        class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit" id="submit"
                                        class="btn btn--primary">{{ translate('messages.save_information') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
