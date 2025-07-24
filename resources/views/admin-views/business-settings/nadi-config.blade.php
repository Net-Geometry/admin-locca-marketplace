@extends('layouts.admin.app')

@section('title', translate('nadi_configuration'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/setting.png')}}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.nadi_configuration') }}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.business-settings.third-party.nadi_config_update')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="api_key">{{translate('Nadi API Key')}}</label>
                                <input type="text" id="api_key" name="api_key" class="form-control" value="{{env('APP_MODE')!='demo'?$nadi_config['api_key']:''}}" {{env('APP_MODE')=='demo' ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="endpoint">{{translate('Nadi Endpoint URL')}}</label>
                                <input type="url" id="endpoint" name="endpoint" class="form-control" value="{{env('APP_MODE')!='demo'?$nadi_config['endpoint']:''}}" {{env('APP_MODE')=='demo' ? 'readonly' : ''}}>
                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE')=='demo'?'call-demo':''}}">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        @if(env('APP_MODE')=='demo')
        function call_demo() {
            toastr.info('{{translate('Update option is disabled for demo!')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
        @endif
    </script>
@endpush