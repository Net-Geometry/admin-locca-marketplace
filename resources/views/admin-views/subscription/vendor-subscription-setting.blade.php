@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start">
                        <img src="{{asset('/public/assets/admin/img/store.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Subscription Settings')}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0 align-items-center">
                <div class="w-100 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h3 class="text--title card-title">Offer Free Trial</h3>
                        <div>You can offer vendors a free trial to experience the system overall.</div>
                    </div>
                    <label class="toggle-switch toggle-switch-sm"> Status:&nbsp;
                        <input type="checkbox" data-url="" class="toggle-switch-input status_change_alert" checked="">
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="card-body py-2">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-4 col-xl-5">
                                    <div class="pr-xl-4">
                                        <label class="form-label">Select Days</label>
                                        <input type="number" class="form-control" placeholder="120">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-3 mr-auto">
                                    <label class="form-label d-none d-sm-block">&nbsp;</label>
                                    <select class="form-control">
                                        <option>Day</option>
                                        <option>Month</option>
                                        <option>Year</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-xl-2">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <button type="submit" class="btn px-xl-5 btn--primary w-100 h--45px">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-header border-0 align-items-center">
                <div class="w-100 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h3 class="text--title card-title">Show Deadline Warning</h3>
                        <div>Select the number of days before the warning will be shown with a countdown to the end of the free trial</div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-4 col-xl-5">
                                    <div class="pr-xl-4">
                                        <label class="form-label">Select Days</label>
                                        <input type="number" class="form-control" placeholder="120">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4 col-xl-5">
                                    <div class="pr-xl-4">
                                        <label class="form-label">Type Message</label>
                                        <input type="number" class="form-control" placeholder="Your subscription ending soon. ">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-2">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <button type="submit" class="btn px-xl-5 btn--primary w-100 h--45px">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('script_2')

@endpush

