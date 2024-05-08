@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

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
                    <a href="" class="nav-link active">Package Details</a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">Transactions</a>
                </li>
            </ul>
        </div>
        <div class="card mb-20">
            <div class="card-header border-0">
                <div class="w-100 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h3 class="text--title card-title">Overview</h3>
                        <div>See overview of all the packages</div>
                    </div>
                    <div class="status-filter-wrap m-0">
                        <div class="statistics-btn-grp">
                            <label>
                                <input type="radio" name="statistics" value="this_year" class="order_stats_update" hidden="" checked>
                                <span>This Year</span>
                            </label>
                            <label>
                                <input type="radio" name="statistics" value="this_month" class="order_stats_update" hidden="">
                                <span>This Month</span>
                            </label>
                            <label>
                                <input type="radio" name="statistics" value="this_week" class="order_stats_update" hidden="">
                                <span>This Week</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-20">
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-3">
                            <a class="__card-2 __bg-1" href="#">
                                <h4 class="title">2,324</h4>
                                <span class="subtitle">Total Subscribed User</span>
                                <img src="{{asset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="report/new" class="card-icon" width="35px">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="__card-2 __bg-3" href="#">
                                <h4 class="title">2,000</h4>
                                <span class="subtitle">Active Subscriptions</span>
                                <img src="{{asset('public/assets/admin/img/subscription-plan/active-user.png')}}" alt="report/new" class="card-icon" width="35px">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="__card-2 __bg-6" href="#">
                                <h4 class="title">324</h4>
                                <span class="subtitle">Expired Subscription</span>
                                <img src="{{asset('public/assets/admin/img/subscription-plan/expired-user.png')}}" alt="report/new" class="card-icon" width="35px">
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="__card-2 __bg-4" href="#">
                                <h4 class="title">324</h4>
                                <span class="subtitle">Expiring Soon </span>
                                <img src="{{asset('public/assets/admin/img/subscription-plan/expired-soon.png')}}" alt="report/new" class="card-icon" width="35px">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-sm-6 col-lg-4">
                        <a class="order--card h-100" href="">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('public/assets/admin/img/plan/free.png')}}" alt="dashboard" class="oder--card-icon" width="20">
                                    <span>Free Trial</span>
                                </h6>
                                <span class="card-title text-success">
                                    345
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <a class="order--card h-100" href="">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('public/assets/admin/img/plan/renewed.png')}}" alt="dashboard" class="oder--card-icon" width="20">
                                    <span>Total Renewed</span>
                                </h6>
                                <span class="card-title text-0661CB">
                                    345
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <a class="order--card h-100" href="">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('public/assets/admin/img/plan/total.png')}}" alt="dashboard" class="oder--card-icon" width="20">
                                    <span>Total Earning</span>
                                </h6>
                                <span class="card-title text-danger">
                                    $ 2,543
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card __billing-subscription mb-3">
            <div class="card-header border-0 align-items-center">
                <h4 class="card-title align-items-center gap-2">
                    <span class="card-header-icon">
                        <img width="25" src="{{asset('public/assets/admin/img/subscription-plan/subscribed-user.png')}}" alt="">
                    </span>
                    <span>Package details</span>
                </h4>
                <div class="d-flex gap-2 align-items-center justify-content-center">
                    <label class="toggle-switch toggle-switch-sm"> Status:&nbsp;
                        <input type="checkbox" data-url="" class="toggle-switch-input status_change_alert" checked="">
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                    <div>
                        <a class="btn btn--primary py-2" href="" title="Edit Package"><i class="tio-edit"> </i> Edit</a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="__bg-F8F9FC-card __plan-details">
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                        <div class="left">
                            <h3 class="name">Basic Plan</h3>
                            <div class="font-medium text--title">Most popular plan for small business or startup</div>
                        </div>
                        <h3 class="right">$70 /<small class="font-medium text--title">3 month</small></h3>
                    </div>

                <div class="check--grid-wrapper mt-3 max-w-850px">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">400 Order</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">POS Access</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">400 Order</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">400 Products Upload</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">Mobile App Access</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">400 Order</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">POS Access</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">400 Order</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">400 Products Upload</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{asset('/public/assets/admin/img/subscription-plan/check.png')}}" alt="">
                            <span class="form-check-label text-dark">Mobile App Access</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>
    </div>




@endsection

@push('script_2')

@endpush

