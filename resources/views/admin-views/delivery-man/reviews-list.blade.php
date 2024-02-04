@extends('layouts.admin.app')

@section('title',translate('messages.Review List'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-break">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/delivery-man.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.deliveryman_reviews')}}
                    <span class="badge badge-soft-dark ml-2" id="itemCount">
                        {{$reviews->total()}}
                    </span>
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header py-2 border-0">
                        <span class="card-header-title"></span>
                        <div class="search--button-wrapper justify-content-end">
                            <div class="col-sm-auto min--240">
                                <select name="deliveryman_id" class="form-control js-select2-custom set-filter theme-style"
                                data-filter="deliveryman_id"
                                        data-url="{{ url()->full() }}">
                                    <option value="all">{{ translate('messages.All_DeliveryMan') }}</option>
                                    @foreach(\App\Models\DeliveryMan::oldest()->where('application_status' , 'approved')->get(['id','f_name','l_name' ]) as $deliveryMan)
                                        <option
                                            value="{{$deliveryMan->id}}" {{$deliveryMan->id == request()?->deliveryman_id ? 'selected':''}}>
                                            {{$deliveryMan->f_name.' '. $deliveryMan->l_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <form class="search-form theme-style">

                                <div class="input-group input--group">
                                    <input id="datatableSearch" name="search" type="search" class="form-control" placeholder="{{translate('ex_: search_delivery_man_,_email_or_phone')}}" value="{{ request()->get('search') }}" aria-label="{{translate('messages.search_here')}}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>
                            @if(request()->get('search'))
                            <button type="reset" class="btn btn--primary ml-2 location-reload-to-base" data-url="{{url()->full()}}">{{translate('messages.reset')}}</button>
                            @endif

                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                                    data-hs-unfold-options='{
                                            "target": "#usersExportDropdown",
                                            "type": "css-animation"
                                        }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.users.delivery-man.reviews.export', ['type'=>'excel',request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="{{route('admin.users.delivery-man.reviews.export', ['type'=>'csv',request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging": false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{translate('SL')}}</th>
                                <th class="border-0">{{translate('Order_ID')}}</th>
                                <th class="border-0">{{translate('messages.deliveryman')}}</th>
                                <th class="border-0">{{translate('messages.customer')}}</th>
                                <th class="border-0">{{translate('messages.rating')}}</th>
                                <th class="border-0">{{translate('messages.review')}}</th>
                                <th class="border-0 text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($reviews as $key=>$review)
                                @if(isset($review->delivery_man))
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <a href="#" class="text-dark">10098</a>
                                        </td>
                                        <td>
                                        <span class="d-block font-size-sm text-body">
                                            <a href="{{route('admin.users.delivery-man.preview',[$review['delivery_man_id']])}}" class="media gap-2 align-items-center text-dark">
                                                <img src="{{asset('public/assets/admin/img/admin.jpeg')}}" class="rounded-circle object-cover" width="48" height="48" alt="">
                                                <div class="meida-body">
                                                    <div>{{$review->delivery_man->f_name.' '.$review->delivery_man->l_name}}</div>
                                                    <div>+8801704560222</div>
                                                </div>
                                            </a>
                                        </span>
                                        </td>
                                        <td>
                                            @if ($review->customer)
                                            <a href="{{route('admin.users.customer.view',[$review->user_id])}}" class="text-dark">
                                                {{$review->customer?$review->customer->f_name:""}} {{$review->customer?$review->customer->l_name:""}}
                                            </a>
                                            @else
                                                <div class="text-muted">{{translate('messages.customer_not_found')}}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <label class="badge badge-soft-warning mb-0 d-flex align-items-center gap-1 justify-content-center">
                                                    <span class="d-inline-block mt-3px">{{$review->rating}}</span>
                                                     <i class="tio-star"></i>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                           <div class="cursor-pointer text-wrap max-349 min-w-100px max-text-2-line" data-toggle="tooltip" data-placement="top" title="{{$review->comment}}">
                                            {{$review->comment}}
                                           </div>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn action-btn btn--warning btn-outline-warning" href="#" title="View" data-toggle="modal" data-target="#deliverymanReviewModal">
                                                    <i class="tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($reviews) !== 0)
                        <hr>
                        @endif
                        <div class="page-area">
                            {!! $reviews->links() !!}
                        </div>
                        @if(count($reviews) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
  
    <!-- Modal -->
    <div class="modal fade" id="deliverymanReviewModal" tabindex="-1" role="dialog" aria-labelledby="deliverymanReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center d-flex flex-column align-items-center mb-3">
                    <h5>Deliveryman Review</h5>
                    <div class="fs-12 mb-1">Order# <span class="font-semibold text-dark">100098</span></div>
                    <div class="text-muted fs-12">12 July, 2024 4:30 pm</div>
                </div>

                <div class="p-3 card rounded mb-3">
                    <div class="media gap-3">
                        <img width="100" height="100" class="rounded object-cover" src="{{asset('public/assets/admin/img/admin.jpeg')}}" alt="">
                        <div class="media-body">
                            <h5>Justin Septimus</h5>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="tio-android-phone"></i>
                                <a href="tel:0123456789" class="text-dark">+880372786552</a>
                            </div>
                            <div class="d-flex">
                                <label class="badge badge-soft-warning mb-0 d-flex align-items-center gap-1 justify-content-center">
                                    <span class="d-inline-block mt-3px">5</span>
                                    <i class="tio-star"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 card rounded">
                    <h5 class="text-warning">Review</h5>
                    <p>Air max are always very comfortable fit, clean. just perfect in every way. just the box was too small and scrunched the sneakers up a little bit. </p>
                </div>
            </div>
        </div>
        </div>
    </div>

@endsection
