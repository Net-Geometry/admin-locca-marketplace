@extends('layouts.admin.app')

@section('title',translate('messages.login_page_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap align-items-center justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/app.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.login_page_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->




    </div>

@endsection

@push('script_2')

@endpush
