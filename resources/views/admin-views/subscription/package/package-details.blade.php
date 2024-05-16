@extends('layouts.admin.app')

@section('title',translate('messages.Subscription'))

@section('subscription_index')
active
@endsection
@push('css_or_js')

@endpush

@section('content')

<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center py-2">
            <div class="col-sm">
                <div class="d-flex align-items-start">
                    <img src="{{asset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                    <div class="w-0 flex-grow pl-2">
                        <h1 class="page-header-title">{{translate('Subscription Package')}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a href="#" class="nav-link active">{{ translate('Package_Details') }}</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.business-settings.subscriptionackage.transaction',$subscriptionackage->id) }}" class="nav-link">{{ translate('Transactions') }}</a>
            </li>
        </ul>
    </div>


    <div class="card mb-20">
        <div class="card-header border-0">
            <div class="w-100 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h3 class="text--title card-title">{{ translate('Overview') }}</h3>
                    <div>{{ translate('See_overview_of_all_the_packages') }}</div>
                </div>
                <div class="status-filter-wrap m-0">
                    <div class="statistics-btn-grp">
                        <label>
                            <input type="radio" name="statistics" value="all" class="order_stats_update" hidden="" checked>
                            <span>{{ translate('All') }}</span>
                        </label>
                        <label>
                            <input type="radio" name="statistics" value="this_year" class="order_stats_update" hidden="">
                            <span>{{ translate('This_Year') }}</span>
                        </label>
                        <label>
                            <input type="radio" name="statistics" value="this_month" class="order_stats_update" hidden="">
                            <span>{{ translate('This_Month') }}</span>
                        </label>
                        <label>
                            <input type="radio" name="statistics" value="this_week" class="order_stats_update" hidden="">
                            <span>{{ translate('This_Week') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div id="set_data">
            @include('admin-views.subscription.package.partial._over-view-data',['over_view_data'=>$over_view_data])
        </div>

    </div>




    <div class="card __billing-subscription mb-3">
        <div class="card-header border-0 align-items-center">
            <h4 class="card-title align-items-center gap-2">
                <span class="card-header-icon">
                    <img width="25" src="{{asset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="">
                </span>
                <span>{{ translate('Package_details') }}</span>
            </h4>
            <div class="d-flex gap-2 align-items-center justify-content-center">
                <label class="toggle-switch toggle-switch-sm"> {{ translate('Status') }}:&nbsp;
                    <input type="checkbox" data-url="{{route('admin.business-settings.subscriptionackage.status',[$subscriptionackage->id,$subscriptionackage->status?0:1])}}" class="toggle-switch-input status_change_alert" {{$subscriptionackage->status?'checked':''}}>
                    <span class="toggle-switch-label">
                        <span class="toggle-switch-indicator"></span>
                    </span>
                </label>
                <div>
                    <a class="btn btn--primary py-2" href="{{ route('admin.business-settings.subscriptionackage.edit',$subscriptionackage->id) }}" title="Edit Package"><i class="tio-edit"> </i> {{ translate('Edit') }}</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="__bg-F8F9FC-card __plan-details">
                <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                    <div class="left">
                        <h3 class="name">{{ $subscriptionackage->package_name }}</h3>
                        <div class="font-medium text--title">{{ $subscriptionackage->text }}</div>
                    </div>
                    <h3 class="right">{{\App\CentralLogics\Helpers::format_currency($subscriptionackage->price) }}
                        /<small class="font-medium text--title">{{ $subscriptionackage->validity }} {{
                            translate('messages.days') }}</small></h3>
                </div>

                <div class="check--grid-wrapper mt-3 max-w-850px">


                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @if ( $subscriptionackage->max_order == 'unlimited' )
                            <span class="form-check-label text-dark">{{ translate('messages.unlimited_orders') }}</span>
                            @else
                            <span class="form-check-label text-dark"> {{ $subscriptionackage->max_order }} {{
                                translate('messages.Orders') }}</span>
                            @endif
                        </div>
                    </div>


                    <div>
                        <div class="d-flex align-items-center gap-2">
                            @if ( $subscriptionackage->pos == 1 )
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @else
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                            @endif
                            <span class="form-check-label text-dark">{{ translate('messages.POS') }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            @if ( $subscriptionackage->mobile_app == 1 )
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @else
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                            @endif
                            <span class="form-check-label text-dark">{{ translate('messages.Mobile_App') }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            @if ( $subscriptionackage->self_delivery == 1 )
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @else
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                            @endif
                            <span class="form-check-label text-dark">{{ translate('messages.self_delivery') }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @if ( $subscriptionackage->max_product == 'unlimited' )
                            <span class="form-check-label text-dark">{{ translate('messages.unlimited_item_Upload')
                                }}</span>
                            @else
                            <span class="form-check-label text-dark"> {{ $subscriptionackage->max_product }} {{
                                translate('messages.product_Upload') }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            @if ( $subscriptionackage->review == 1 )
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @else
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                            @endif
                            <span class="form-check-label text-dark">{{ translate('messages.review') }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            @if ( $subscriptionackage->chat == 1 )
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            @else
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check-1.png')}}" alt="">
                            @endif
                            <span class="form-check-label text-dark">{{ translate('messages.chat') }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>




@endsection

@push('script_2')
<script>
"use strict";
        $('.status_change_alert').on('click', function (event) {
        let url = $(this).data('url');
        let message = $(this).data('message');
        status_change_alert(url, message, event)
    })

    function status_change_alert(url, message, e) {
        e.preventDefault();
        Swal.fire({
            title: '{{ translate('Are_you_sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('no') }}',
            confirmButtonText: '{{ translate('yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href=url;
            }
        })
    }


    $(document).on('click', '.order_stats_update', function () {
        $.get({
            url: '{{route('admin.business-settings.subscriptionackage.overView',$subscriptionackage->id)}}',
            dataType: 'json',
            data: {
                type: $(this).val(),
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#set_data').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });


</script>
@endpush
