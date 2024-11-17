@extends('layouts.admin.app')

@section('title', translate('Order Details'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        <span class="page-header-icon">
                            <img src="{{ asset('/public/assets/admin/img/car-logo.png') }}" class="w--20" alt="">
                        </span>
                        <span>
                            {{ translate('trips_details') }}
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- Page Header -->

        <div class="row flex-xl-nowrap" id="printableArea">
            <div class="col-lg-8 order-print-area-left">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header align-items-stretch flex-column border-0 pb-0">
                        <div class="d-flex align-items-start justify-content-between flex-wrap mb-2">
                            <div class="order-invoice-left d-flex d-sm-block justify-content-between">
                                <div>
                                    <h1 class="page-header-title d-flex align-items-center __gap-5px">
                                        Trip ID # 1000078
                                    </h1>
                                    <span class="mt-2 d-block d-flex align-items-center __gap-5px">
                                        Placed on 12 Aug, 2022, 12:45
                                        <br>
                                        Schedule At 14 Aug, 2022, 12:45
                                    </span>
                                    <div class="fs-14 text-title mt-2 pt-1 mb-2 d-flex align-items-center __gap-5px">
                                        <span>Provider</span> <span>:</span>
                                        <span class="font-bold">Auto Focus Car Service</span>
                                        <button type="button" class="btn btn--primary-light px-2 py-1 shadow-none"
                                            data-toggle="modal" data-target="#locationModal">
                                            <i class="tio-poi"></i> Map View
                                        </button>
                                    </div>
                                    <div class="fs-14 text-title mt-2 pt-1 mb-2 d-flex align-items-center __gap-5px">
                                        <span>Trip Type</span> <span>:</span>
                                        <span class="font-bold">Hourly</span>
                                        <span>(Scheduled)</span>
                                    </div>
                                    <div class="fs-14 text-title mt-2 pt-1 mb-2 d-flex align-items-center __gap-5px">
                                        <span>Total Hour</span> <span>:</span>
                                        <span class="font-bold">5 hrs</span>
                                    </div>
                                </div>
                                <div class="d-sm-none">
                                    <a class="btn btn--primary print--btn font-regular d-flex align-items-center __gap-5px"
                                        href="#">
                                        <i class="tio-print mr-sm-1"></i>
                                        <span>{{ translate('messages.print_invoice') }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="order-invoice-right mt-3 mt-sm-0">
                                <div class="btn--container ml-auto align-items-center justify-content-end">

                                    <button class="btn btn--primary btn-outline-primary font-bold edit-order"
                                        type="button">
                                        <i class="tio-edit"></i> Edit Trip
                                    </button>
                                    <a class="btn btn--primary print--btn font-bold d-none d-sm-block" href="#">
                                        <i class="tio-print mr-sm-1"></i> <span>Print invoice</span>
                                    </a>
                                </div>
                                <div class="text-right mt-3 order-invoice-right-contents text-capitalize">
                                    <h6>
                                        <span>Trip Status</span> <span>:</span>
                                        <span class="badge badge--accepted ml-2 ml-sm-3 text-capitalize">
                                            Confirmed
                                        </span>
                                    </h6>
                                    <h6>
                                        <span>Payment status</span> <span>:</span>
                                        <strong class="text-danger">Unpaid</strong>

                                    </h6>
                                    <h6>
                                        <span>Payment method</span> <span>:</span>
                                        <span class="font-semibold">SSL Commerz</span>
                                    </h6>
                                    <h6>
                                        <span>Reference Code </span> <span>:</span>
                                        <span class="font-semibold">68973</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="__bg-FAFAFA p-2 rounded">
                            <h6 class="fs-14 text-title opacity-lg">
                                Note:
                                <span class="opacity-70 font-regular">Please provide Good quality car</span>
                            </h6>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body px-0">
                        <!-- item cart -->
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0">#</th>
                                        <th class="border-0">Vehicle Details</th>
                                        <th class="border-0">Unite Fair</th>
                                        <th class="border-0">Total Hour</th>
                                        <th class="text-right  border-0">Price</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>
                                            <!-- Static Count Number -->
                                            <div>
                                                1
                                            </div>
                                            <!-- Static Count Number -->
                                        </td>
                                        <td>
                                            <div class="media media--sm">
                                                <a class="avatar avatar-xl mr-3" href="#">
                                                    <img class="img-fluid rounded aspect-ratio-1 onerror-image"
                                                        src="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        alt="Image Description">
                                                </a>
                                                <div class="media-body">
                                                    <div class="fs-12 text--title">
                                                        <div class="fz-12 font-semibold line--limit-1">
                                                            F Premio 2006</div>
                                                        <div><span class="font-semibold mr-2">License No. :</span>Nator Kha
                                                            21-3214</div>
                                                        <div><span class="font-semibold mr-2">Category :</span>SUV</div>
                                                        <div><span class="font-semibold mr-2">Brand :</span>Toyota</div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-14 text--title">
                                                $ 45.24 hourly
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-14 text--title">
                                                5
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="fs-14 text--title">
                                                $ 1,350.25
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- End Media -->
                                </tbody>
                            </table>
                        </div>
                        <div class="mx-3">
                            <hr>
                        </div>
                        <div class="row justify-content-md-end mb-3 mt-4 mx-0">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-right text-title">
                                    <dt class="col-6 font-regular">Trip Fare</dt>
                                    <dd class="col-6">
                                        $ 1,350.25</dd>

                                    <dt class="col-6">Subtotal</dt>
                                    <dd class="col-6 font-semibold">
                                        $ 1,350.25
                                    </dd>

                                    <dt class="col-6 font-regular">Coupon discount</dt>
                                    <dd class="col-6">
                                        -$ 350.25
                                    </dd>

                                    <dt class="col-6 font-regular">Vat/tax:</dt>
                                    <dd class="col-6 text-right">
                                        +$ 10
                                    </dd>

                                    <dt class="col-6 font-bold">Total:</dt>
                                    <dd class="col-6 font-bold">$ 1,010.00</dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4 order-print-area-right">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Trip Setup</h5>
                    </div>
                    <div class="card-body">
                        <div class="hs-unfold w-100 mb-20">
                            <label for="" class="font-semibold text-title">Trip Status</label>
                            <div class="dropdown">
                                <button
                                    class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center w-100"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Accepted
                                </button>
                                <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item  route-alert"
                                        data-url="http://localhost/Backend-6amMart/admin/order/status?id=100639&amp;order_status=pending"
                                        data-message="Change status to pending ?" href="javascript:">Pending</a>
                                    <a class="dropdown-item  route-alert"
                                        data-url="http://localhost/Backend-6amMart/admin/order/status?id=100639&amp;order_status=confirmed"
                                        data-message="Change status to confirmed ?" href="javascript:">Confirmed</a>
                                    <a class="dropdown-item  route-alert"
                                        data-url="http://localhost/Backend-6amMart/admin/order/status?id=100639&amp;order_status=processing"
                                        data-message="Change status to processing ?" href="javascript:">Processing</a>
                                    <a class="dropdown-item  route-alert"
                                        data-url="http://localhost/Backend-6amMart/admin/order/status?id=100639&amp;order_status=handover"
                                        data-message="Change status to handover ?" href="javascript:">Handover</a>
                                    <a class="dropdown-item  route-alert"
                                        data-url="http://localhost/Backend-6amMart/admin/order/status?id=100639&amp;order_status=picked_up"
                                        data-message="Change status to out for delivery ?" href="javascript:">Out for
                                        delivery</a>
                                    <a class="dropdown-item  route-alert"
                                        data-url="http://localhost/Backend-6amMart/admin/order/status?id=100639&amp;order_status=delivered"
                                        data-message="Change status to delivered (payment status will be paid if not)"
                                        href="javascript:">Delivered</a>
                                    <a class="dropdown-item  canceled-status">Canceled</a>
                                </div>

                            </div>
                        </div>
                        <div class="hs-unfold w-100 mb-20">
                            <label for="" class="font-semibold text-title">Payment Status</label>
                            <div class="dropdown">
                                <button
                                    class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center w-100"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Unpaid
                                </button>
                                <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item  route-alert" data-url="#"
                                        data-message="Change status to Paid ?" href="javascript:">Paid</a>
                                </div>

                            </div>
                        </div>
                        <button type="button" class="btn btn--primary w-100"><i class="tio-bike"></i> <span
                                class="ml-2">Assign Driver</span></button>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title mb-3 d-flex flex-wrap align-items-center">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>Deliveryman</span>



                            <a type="button" href="#myModal" class="text--base cursor-pointer ml-auto"
                                data-toggle="modal" data-target="#myModal">
                                Change
                            </a>
                        </h5>
                        <a class="media align-items-center deco-none customer--information-single"
                            href="http://localhost/Backend-6amMart/admin/users/delivery-man/preview/6">
                            <div class="avatar avatar-circle">
                                <img class="avatar-img onerror-image"
                                    data-onerror-image="http://localhost/Backend-6amMart/public/assets/admin/img/160x160/img1.jpg"
                                    src="http://localhost/Backend-6amMart/public/assets/admin/img/160x160/img2.jpg"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <span class="text-body d-block text-hover-primary mb-1">William Damian</span>

                                <span class="text--title font-semibold d-flex align-items-center">
                                    <i class="tio-shopping-basket-outlined mr-2"></i>
                                    9
                                    Orders delivered
                                </span>

                                <span class="text--title font-semibold d-flex align-items-center">
                                    <i class="tio-call-talking-quiet mr-2"></i>
                                    +8801900000000
                                </span>

                                <span class="text--title font-semibold d-flex align-items-center">
                                    <i class="tio-email-outlined mr-2"></i>
                                    Damian@gmail.com
                                </span>

                            </div>
                        </a>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Last location</h5>
                        </div>
                        <span class="d-block">
                            <a target="_blank"
                                href="http://maps.google.com/maps?z=12&amp;t=m&amp;q=loc:23.8373795+90.3757282">
                                <i class="tio-map"></i> 1005, Dhaka District, BD<br>
                            </a>
                        </span>
                    </div>
                </div>


                <div class="card mt-2">
                    <div class="card-body pt-3">
                        <span class="badge badge-soft-success py-2 d-block qcont">
                            Guest user
                        </span>


                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">
                                <span class="card-header-icon">
                                    <i class="tio-user"></i>
                                </span>
                                <span>Delivery info</span>
                            </h5>
                            <a class="link d-flex" data-toggle="modal" data-target="#shipping-address-modal"
                                href="javascript:"><i class="tio-edit"></i></a>
                        </div>
                        <div class="delivery--information-single mt-3">
                            <span class="name">Name</span>
                            <span class="info">Ghhh</span>
                            <span class="name">Contact</span>
                            <a class="deco-none info" href="tel:+8801600000000">
                                +8801600000000</a>


                            <hr class="w-100">
                            <div>
                                <a target="_blank" class="d-flex align-items-center"
                                    href="http://maps.google.com/maps?z=12&amp;t=m&amp;q=loc:23.793544663762145+90.41166342794895">
                                    <i class="tio-poi"></i>4B Kemal Ataturk Ave, Dhaka 1212, Bangladesh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Customer Card -->

                <!-- Restaurant Card -->
                <div class="card mt-2">
                    <!-- Body -->
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>Store information</span>
                        </h5>
                        <a class="media align-items-center deco-none resturant--information-single"
                            href="http://localhost/Backend-6amMart/admin/store/view/5?module_id=1">
                            <div class="avatar avatar-circle">
                                <img class="avatar-img w-75px onerror-image"
                                    data-onerror-image="http://localhost/Backend-6amMart/public/assets/admin/img/100x100/1.png"
                                    src="http://localhost/Backend-6amMart/public/assets/admin/img/160x160/img1.jpg"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                    Family supermarket
                                </span>
                                <span>18 Orders</span>
                                <span class="text--title font-semibold d-flex align-items-center">
                                    <i class="tio-call-talking-quiet mr-2"></i>02000000003
                                </span>
                                <span class="text--title d-flex align-items-center">
                                    <i class="tio-email mr-2"></i>grocery.store3@demo.com
                                </span>
                            </div>
                        </a>
                        <hr>
                        <span class="d-block">
                            <a target="_blank" class="d-flex align-items-center __gap-5px"
                                href="http://maps.google.com/maps?z=12&amp;t=m&amp;q=loc:26.558691413230427+81.06887096259652">
                                <i class="tio-poi"></i> <span>House: 00, Road: 00, City-000, Country</span><br>
                            </a>
                        </span>
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    @endsection

    @push('script_2')
    @endpush
