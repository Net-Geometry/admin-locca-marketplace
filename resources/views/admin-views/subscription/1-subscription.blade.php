@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img src="{{asset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title mb-0">{{translate('Subscription Package List')}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="max-w-542 mx-auto py-sm-5 py-4">
                    <img class="mb-4" src="{{asset('/public/assets/admin/img/empty-subscription.svg')}}" alt="img">
                    <h4 class="mb-3">{{translate('Create Subscription Plan')}}</h4>
                    <p class="mb-4">
                        {{translate('Add new subscription packages to the list. So that Providers get more options to join the business for the growth and success.')}}<br>
                    </p>
                    <a href="" class="btn btn--primary border-0"><i class="tio-add"></i> {{translate('Add Subcription Package')}}</a>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('script_2')

@endpush

