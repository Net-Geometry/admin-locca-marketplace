@extends('layouts.vendor.app')

@section('title', translate('Trip Details'))

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
                                        <span class="font-bold line--limit-1">Auto Focus Car Service</span>
                                        <button type="button"
                                            class="btn btn--primary-light px-2 py-1 shadow-none text-nowrap"
                                            data-toggle="modal" data-target="#providerLocationModal">
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

                                    <button class="btn btn--primary btn-outline-primary font-bold" type="button"
                                        data-toggle="modal" data-target="#editTripModal">
                                        <i class="tio-edit mr-sm-1"></i> Edit Trip
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
                                class="table table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0">#</th>
                                        <th class="border-0">Vehicle Details</th>
                                        <th class="border-0">Unite Fair</th>
                                        <th class="border-0">Quantity</th>
                                        <th class="text-right  border-0">Fare</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>
                                            <div>
                                                1
                                            </div>
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
                                            <div class="mt-2">
                                                <button
                                                    class="btn btn--primary btn-outline-primary p-5px rounded-20 d-flex align-items-center gap-1"
                                                    type="button" data-toggle="modal" data-target="#assignVehicleModal">
                                                    Assign Vehicle <span class="fs-24"><i
                                                            class="tio-add-circle"></i></span>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-14 text--title">
                                                $ 45.24 hourly
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-14 text--title font-bold">
                                                5
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="fs-14 text--title">
                                                $ 1,350.25
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                2
                                            </div>
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
                                            <div class="mt-2 bg--F6F6F6 p-2 radius-15 mb-4 d-inline-block">
                                                <div class="d-flex justify-content-between mb-10px text--title">
                                                    Assigned Vehicle
                                                    <button
                                                        class="btn btn--primary p-5px rounded-circle d-flex align-items-center justify-content-center"
                                                        type="button" data-toggle="modal"
                                                        data-target="#assignVehicleModal">
                                                        <i class="tio-edit fs-12"></i>
                                                    </button>
                                                </div>
                                                <div class="text-wrap">GHA-10-2345, GHA-10-2345, GHA-10-2345</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-14 text--title">
                                                $ 45.24 hourly
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fs-14 text--title font-bold">
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

                                    <dt class="col-6 font-regular text-uppercase">Vat/tax:</dt>
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
                        <h5 class="card-title">{{ translate('trip_setup') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="hs-unfold w-100 mb-20">
                            <label for="" class="font-semibold text-title">Trip Status</label>
                            <div class="dropdown">
                                <button
                                    class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center w-100"
                                    type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Confirmed
                                </button>
                                <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item  route-alert" data-url="#"
                                        data-message="Change status to pending ?" href="javascript:">Pending</a>
                                    <a class="dropdown-item active route-alert" data-url="#"
                                        data-message="Change status to confirmed ?" href="javascript:">Confirmed</a>
                                    <a class="dropdown-item  route-alert" data-url="#"
                                        data-message="Change status to processing ?" href="javascript:">Processing</a>
                                    <a class="dropdown-item  route-alert" data-url="#"
                                        data-message="Change status to handover ?" href="javascript:">Handover</a>
                                    <a class="dropdown-item  route-alert" data-url="#"
                                        data-message="Change status to out for delivery ?" href="javascript:">Out for
                                        delivery</a>
                                    <a class="dropdown-item  route-alert" data-url="#"
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
                                    <a class="dropdown-item active route-alert" data-url="#"
                                        data-message="Change status to Unpaid ?" href="javascript:">Unpaid</a>
                                </div>

                            </div>
                        </div>
                        <button type="button" 
                        class="btn btn--primary w-100"
                        data-toggle="modal" data-target="#assignDriverModal">
                            <i class="tio-bike"></i> 
                            <span class="ml-2">Assign Driver</span>
                        </button>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="position-relative">
                            <div class="map-fullscreen-btn_wrapper">
                                <button type="button" data-toggle="modal" data-target="#pickupDesModal"
                                    class="btn border-0 shadow--card-2">
                                    <i class="tio-fullscreen-1-1"></i>
                                </button>
                            </div>
                            <img class="aspect-2-1 object--cover w-100 max-h-160px rounded"
                                src="{{ asset('public/assets/admin/img/map-road.png') }}" alt="Map road">
                        </div>
                        <hr>
                        <ul class="trip-details-address text--title px-0 pt-2">
                            <li>
                                <span class="svg">
                                    <span class="text--title bg--F6F6F6 p-10px rounded"><i class="tio-poi"></i></span>
                                </span>
                                <span class="w-0 flex-grow-1">
                                    <span class="font-medium">Home:</span>
                                    <span class="opacity-70">Road 9/a, house - 666, Dhaka</span>
                                </span>
                            </li>
                            <li>
                                <span class="svg">
                                    <span class="text--title bg--F6F6F6 p-10px rounded"><i
                                            class="tio-navigate-outlined rotate-45 d-inline-block"></i></span>
                                </span>
                                <span class="w-0 flex-grow-1 font-medium">
                                    50 lake circus, kolabagan, Dhanmondi
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title mb-3 d-flex flex-wrap align-items-center">
                            <span>{{ translate('messages.Customer_Info') }}</span>
                        </h5>
                        <a class="media align-items-center deco-none customer--information-single" href="#">
                            <div class="avatar avatar-circle">
                                <img class="avatar-img onerror-image"
                                    data-onerror-image="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                    src="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <span class="text--title fs-14 font-semibold d-block text-hover-primary mb-1">Jhone
                                    Die</span>

                                <div class="text--title d-flex align-items-center gap-1">
                                    <span>
                                        <span class="font-bold">12</span>
                                        {{ translate('messages.order') }},
                                    </span>
                                    <span>
                                        <span class="font-bold">5</span>
                                        {{ translate('messages.trip') }}
                                    </span>
                                </div>

                                <div class="text--title">
                                    +90-495-303235
                                </div>

                                <div class="text--title">
                                    doe@gmail,com
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title mb-3 d-flex flex-wrap align-items-center">
                            <span>{{ translate('messages.Provider_Info') }}</span>
                        </h5>
                        <a class="media align-items-center deco-none resturant--information-single" href="#">
                            <div class="avatar avatar-circle">
                                <img class="avatar-img w-75px border-000-01 onerror-image"
                                    data-onerror-image="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                    src="{{ asset('public/assets/admin/img/160x160/img1.jpg') }}"
                                    alt="Image Description">
                            </div>
                            <div class="media-body">
                                <div class="text--title fs-14 font-semibold d-block text-hover-primary mb-1">
                                    Auto Focus Car Service
                                </div>

                                <div class="text--title">
                                    <span class="font-bold">205</span>
                                    {{ translate('messages.Trip_served') }}
                                </div>

                                <div class="text--title d-flex align-items-center">
                                    +90-495-303235
                                </div>

                                <div class="text--title d-flex align-items-baseline">
                                    <i class="tio-poi mr-2"></i>
                                    Șoseaua Gheorghe Ionescu Sisești nr
                                    236, ‘București, Romania
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>

    <!--Assign Driver Modal -->
    <div class="modal fade" id="assignDriverModal" tabindex="-1" role="dialog"
        aria-labelledby="assignDriverModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header p-2 pb-0 justify-content-end flex-shrink-0">
                    <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body px-4 py-0">
                    <h5 class="font-bold">Assign Driver</h5>
                    <div class="fs-12">1 Vehicle need to assign driver</div>
                    <div class="card shadow-none">
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0">Vehicle List</th>
                                        <th class="border-0">Selected Driver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="media media--sm">
                                                <a class="mr-3" href="#">
                                                    <img width="60" height="40" class="img--ratio-2 onerror-image rounded h--40px"
                                                        src="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        alt="Image Description">
                                                </a>
                                                <div class="media-body">
                                                    <div class="fs-12 text--title">
                                                        <div class="font-bold">Mahindra XUV700 AX7</div>
                                                        <div class="font-semibold opacity-60">Car No: GHA-10-2345</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                           <div class="d-flex flex-column w-100">
                                                <select name="" id=""
                                                    class="form-control js-select2-custom"
                                                    data-placeholder="{{ translate('messages.select_vehicle_transmission') }}">
                                                    <option value="" selected disabled>
                                                    <span class="fs-12 text--title">Select Vendors</span>
                                                    </option>
                                                    <option value="1" selected>
                                                    <span class="fs-12 text--title">Ellison Cardenas Trading</span>
                                                    <br>
                                                    <span class="fs-10 text--title opacity-70">(+416465456)</span>
                                                    </option>
                                                    <option value="2">
                                                    <span class="fs-12 text--title">Ellison Cardenas Trading</span> 
                                                    <br>
                                                    <span class="fs-10 text--title opacity-70">(+416465456)</span>
                                                    </option>
                                                </select>
                                           </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="media media--sm">
                                                <a class="mr-3" href="#">
                                                    <img width="60" height="40" class="img--ratio-2 onerror-image rounded h--40px"
                                                        src="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        alt="Image Description">
                                                </a>
                                                <div class="media-body">
                                                    <div class="fs-12 text--title">
                                                        <div class="font-bold">Mahindra XUV700 AX7</div>
                                                        <div class="font-semibold opacity-60">Car No: GHA-10-2345</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                           <div class="d-flex flex-column w-100">
                                                <select name="" id=""
                                                    class="form-control js-select2-custom"
                                                    data-placeholder="{{ translate('messages.select_vehicle_transmission') }}">
                                                    <option value="" selected disabled>
                                                    <span class="fs-12 text--title">Select Vendors</span>
                                                    </option>
                                                    <option value="1" selected>
                                                    <span class="fs-12 text--title">Ellison Cardenas Trading</span>
                                                    <br>
                                                    <span class="fs-10 text--title opacity-70">(+416465456)</span>
                                                    </option>
                                                    <option value="2">
                                                    <span class="fs-12 text--title">Ellison Cardenas Trading</span> 
                                                    <br>
                                                    <span class="fs-10 text--title opacity-70">(+416465456)</span>
                                                    </option>
                                                </select>
                                           </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="media media--sm">
                                                <a class="mr-3" href="#">
                                                    <img width="60" height="40" class="img--ratio-2 onerror-image rounded h--40px"
                                                        src="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                                        alt="Image Description">
                                                </a>
                                                <div class="media-body">
                                                    <div class="fs-12 text--title">
                                                        <div class="font-bold">Mahindra XUV700 AX7</div>
                                                        <div class="font-semibold opacity-60">Car No: GHA-10-2345</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                           <div class="d-flex flex-column w-100">
                                                <select name="" id=""
                                                    class="form-control js-select2-custom"
                                                    data-placeholder="{{ translate('messages.select_vehicle_transmission') }}">
                                                    <option value="" selected disabled>
                                                    <span class="fs-12 text--title">Select Vendors</span>
                                                    </option>
                                                    <option value="1" selected>
                                                    <span class="fs-12 text--title">Ellison Cardenas Trading</span>
                                                    <br>
                                                    <span class="fs-10 text--title opacity-70">(+416465456)</span>
                                                    </option>
                                                    <option value="2">
                                                    <span class="fs-12 text--title">Ellison Cardenas Trading</span> 
                                                    <br>
                                                    <span class="fs-10 text--title opacity-70">(+416465456)</span>
                                                    </option>
                                                </select>
                                           </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 flex-shrink-0">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--warning-light min-w-120px">{{ translate('messages.cancel') }}</button>
                        <button type="submit"
                            class="btn btn--primary min-w-120px">{{ translate('messages.assign') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Assign Vehicle Modal -->
    <div class="modal fade" id="assignVehicleModal" tabindex="-1" role="dialog"
        aria-labelledby="assignVehicleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-2 pb-0 justify-content-end flex-shrink-0">
                    <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body px-4 py-0">
                    <div class="media media--sm flex-wrap mb-20">
                        <a class="mr-3" href="#">
                            <img width="160" class="img-fluid rounded aspect-2-1 onerror-image"
                                src="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                data-onerror-image="{{ asset('public/assets/admin/img/car-demo.png') }}"
                                alt="Image Description">
                        </a>
                        <div class="media-body">
                            <div class="text--title">
                                <div class="fs-20 font-semibold line--limit-1">
                                    F Premio 2006</div>
                                <div class="mb-2"><span class="font-semibold">Vendor :</span>Auto Focus Car Service</div>
                               <div class="d-flex flex-wrap gap-2 gap-sm-4">
                                <div><span class="font-semibold">Category :</span>SUV</div>
                                <div><span class="font-semibold">Brand :</span>Toyota</div>
                               </div>

                            </div>
                        </div>
                    </div>
                    <h5 class="font-bold">Vehicles List <span class="fs-12 font-regular">(Select any of 2 vehicle)</span></h5>
                    <div class="card shadow-none">
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0">SL.</th>
                                        <th class="border-0">VIN Number</th>
                                        <th class="border-0">License Number</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>12354687</td>
                                        <td>Dhk-Cha-12-2342</td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input class="form-check-input single-select m-auto position-relative"
                                                    type="checkbox" value="hourly" checked>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>12354687</td>
                                        <td>Dhk-Cha-12-2342</td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input class="form-check-input single-select m-auto position-relative"
                                                    type="checkbox" value="hourly">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>12354687</td>
                                        <td>Dhk-Cha-12-2342</td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input class="form-check-input single-select m-auto position-relative"
                                                    type="checkbox" value="hourly" checked>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>12354687</td>
                                        <td>Dhk-Cha-12-2342</td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input class="form-check-input single-select m-auto position-relative"
                                                    type="checkbox" value="hourly">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 flex-shrink-0">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--warning-light min-w-120px">{{ translate('messages.cancel') }}</button>
                        <button type="submit"
                            class="btn btn--primary min-w-120px">{{ translate('messages.add') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Show Provider location on map Modal -->
    <div class="modal fade" id="providerLocationModal" tabindex="-1" role="dialog"
        aria-labelledby="providerLocationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pt-4 px-4">
                    <h4 class="modal-title" id="providerLocationModalLabel">{{ translate('messages.Trip ID # 1000078') }}
                    </h4>
                    <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div class="initial--25 rounded-8 custom_map_canvas" id="provider_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Show Pickup/Destinaton route on map Modal -->
    <div class="modal fade" id="pickupDesModal" tabindex="-1" role="dialog" aria-labelledby="pickupDesModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pt-4 px-4">
                    <h4 class="modal-title" id="pickupDesModalLabel">{{ translate('messages.Trip ID # 1000078') }}</h4>
                    <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="pickup_location_map">
                                <div class="initial--25 rounded-8 custom_map_canvas" id="custom_route_line_map_canvas">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Show Edit trip Modal -->
    <div class="modal fade" id="editTripModal" tabindex="-1" role="dialog" aria-labelledby="editTripModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header pt-4 px-4">
                    <h4 class="modal-title" id="editTripModalLabel">{{ translate('messages.Trip ID # 1000078') }}</h4>
                    <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <form action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group text-title">
                                    <label class="input-label font-semibold" for="">Pickup Location</label>
                                    <div class="position-relative w-100 d-flex align-items-center">
                                        <input type="text" name="" id="" class="form-control pr-2"
                                            placeholder="Enter your pickup location"
                                            value="Home: Road 9/a, house - 666, Dhaka">
                                        <div class="input-icon fs-20 opacity-60">
                                            <i class="tio-poi"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-title">
                                    <label class="input-label font-semibold" for="">Destination</label>
                                    <div class="position-relative w-100 d-flex align-items-center">
                                        <input type="text" name="" id="" class="form-control pr-2"
                                            placeholder="Enter your destination location"
                                            value="50 lake circus, kolabagan, Dhanmondi">
                                        <div class="input-icon fs-20 opacity-60">
                                            <i class="tio-navigate-outlined rotate-45 d-block"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-title">
                                    <label class="input-label font-semibold" for="">Trip Type</label>
                                    <select name="" id="" class="form-control custom-select-arrow">
                                        <option value="hourly" selected>Hourly</option>
                                        <option value="day">Day</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-title">
                                    <label class="input-label font-semibold" for="trip-schedule">Trip Schedule</label>
                                    <div class="position-relative w-100 d-flex align-items-center">
                                        <input type="datetime-local" name="trip_schedule" id="trip-schedule"
                                            value="2022-08-14T12:45" class="form-control pr-2 opacity-lg"
                                            placeholder="Enter your destination location">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card p-0">
                            <div class="card-body p-0 bg--F6F6F6">
                                <!-- item cart -->
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                                        <thead class="bg--EDEDED text--title">
                                            <tr>
                                                <th class="border-0">#</th>
                                                <th class="border-0">Vehicle Details</th>
                                                <th class="border-0">Unite Fair</th>
                                                <th class="border-0">Quantity</th>
                                                <th class="text-right  border-0">Fare</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>

                                                    <div>
                                                        1
                                                    </div>

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
                                                                <div><span class="font-semibold mr-2">License No.
                                                                        :</span>Nator Kha
                                                                    21-3214</div>
                                                                <div><span class="font-semibold mr-2">Category :</span>SUV
                                                                </div>
                                                                <div><span class="font-semibold mr-2">Brand :</span>Toyota
                                                                </div>

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
                                                    <input type="number" class="form-control fs-14 text--title w--60px"
                                                        value="5" placeholder="EX:5">
                                                </td>
                                                <td class="text-right">
                                                    <input type="text"
                                                        class="form-control w--120px text-right fs-14 text--title"
                                                        value="$ 1,350.25" placeholder="fare">
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

                                            <dt class="col-6 font-regular text-uppercase">Vat/tax:</dt>
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
                        </div>
                        <div class="btn--container justify-content-end mt-4">
                            <button type="reset" id="reset_btn"
                                class="btn btn--warning-light min-w-120px">{{ translate('messages.cancel') }}</button>
                            <button type="submit"
                                class="btn btn--primary min-w-120px">{{ translate('messages.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection

@push('script_2')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&v=3.45.8">
    </script>
    <script>
        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function() {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    </script>
    <script>
        $(document).ready(function() {

            function providerLocationMap() {
                const grayStyle = [{
                        featureType: "all",
                        stylers: [{
                                saturation: -100
                            }, // Desaturate all colors
                            {
                                lightness: 20
                            }, // Increase lightness
                        ],
                    },
                    {
                        featureType: "road",
                        stylers: [{
                                visibility: "on"
                            },
                            {
                                lightness: 30
                            },
                        ],
                    },
                    {
                        featureType: "landscape",
                        stylers: [{
                                lightness: 10
                            },
                            {
                                saturation: -80
                            },
                        ],
                    },
                ];

                // Initialize the map centered on the provider's location
                const map = new google.maps.Map(
                    document.getElementById("provider_map_canvas"), {
                        center: {
                            lat: 23.837232,
                            lng: 90.373129
                        },
                        zoom: 14, // Adjusted for better overview
                        styles: grayStyle,
                    }
                );

                const infowindow = new google.maps.InfoWindow();

                // Provider location
                const providerLocation = {
                    lat: 23.837232,
                    lng: 90.373129
                };

                // Add a marker for the provider's location
                const providerMarker = new google.maps.Marker({
                    position: providerLocation,
                    map: map,
                    title: "Provider Location",
                    icon: "{{ asset('public/assets/admin/img/icons/pickup.svg') }}",
                });

                google.maps.event.addListener(providerMarker, "click", function() {
                    infowindow.setContent('<div class="fs-12 font-medium">Provider Location</div>');
                    infowindow.open(map, providerMarker);
                });
            }

            // Re-init map before show modal
            $('#providerLocationModal').on('shown.bs.modal', function(event) {
                providerLocationMap();
            });

            // ------- pickup destinaton map with route line starts

            // drawing a route (polyline) on the map between two locations
            function addPolylineToMap(map, pickupLocation, destinationLocation) {
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map: map,
                    suppressMarkers: true, // Suppress default markers
                    polylineOptions: {
                        strokeColor: '#4D4D4D', // Line color
                        strokeOpacity: 1.0, // Line opacity
                        strokeWeight: 3 // Line width
                    },
                });

                // Define request for the route
                const request = {
                    origin: pickupLocation,
                    destination: destinationLocation,
                    travelMode: google.maps.TravelMode.DRIVING, // Use DRIVING as travel mode.
                };

                // Calculate the route and render it
                directionsService.route(request, function(response, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(response);
                    } else {
                        console.error("Directions request failed due to " + status);
                    }
                });
            }


            function initializeCustomRouteLocationMap() {

                const grayStyle = [{
                        featureType: "all",
                        stylers: [{
                                saturation: -100
                            }, // Desaturate all colors
                            {
                                lightness: 20
                            }, // Increase lightness
                        ]
                    },
                    {
                        featureType: "road",
                        stylers: [{
                                visibility: "on"
                            },
                            {
                                lightness: 30
                            }
                        ]
                    },
                    {
                        featureType: "landscape",
                        stylers: [{
                                lightness: 10
                            },
                            {
                                saturation: -80
                            }
                        ]
                    }
                ];

                const map = new google.maps.Map(document.getElementById("custom_route_line_map_canvas"), {
                    center: {
                        lat: 23.766660,
                        lng: 90.424993
                    },
                    zoom: 14, // Adjusted for better overview
                    styles: grayStyle,
                });

                const infowindow = new google.maps.InfoWindow();

                // Define pickup and destination locations
                const pickupLocation = {
                    lat: 23.766660,
                    lng: 90.424993
                };
                const destinationLocation = {
                    lat: 23.837232,
                    lng: 90.373129
                };

                // get dynamic icon color
                function getDynamicMarkerSvg(dynamicColor) {
                    return `
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
                                        <g>
                                            <g clip-path="url(#clip0_5241_7679)">
                                                <path d="M14.7577 1C9.36898 1 5 5.3684 5 10.7577C5 14.0607 8.6658 19.7298 11.5035 23.6238C13.2959 26.0826 14.7577 27.8335 14.7577 27.8335C14.7621 27.8273 24.5154 16.1557 24.5154 10.7576C24.5154 5.3684 20.147 1 14.7577 1Z" fill="${dynamicColor}"/>
                                                <path d="M14.7575 3.43945C10.8843 3.43945 7.74414 6.57961 7.74414 10.4528C7.74414 12.4299 8.56258 14.2162 9.87865 15.4908H19.6363C20.953 14.2162 21.7708 12.4299 21.7708 10.4528C21.7708 6.57955 18.6313 3.43945 14.7575 3.43945Z" fill="white"/>
                                                <path d="M19.6366 15.0263V15.4904C16.917 18.1246 12.5984 18.1246 9.87891 15.4904V15.0263C9.87891 13.0052 11.517 11.3672 13.538 11.3672H15.9775C17.9985 11.3672 19.6366 13.0052 19.6366 15.0263Z" fill="white"/>
                                                <path d="M14.7578 11.3671C16.1051 11.3671 17.1972 10.275 17.1972 8.92772C17.1972 7.58045 16.1051 6.48828 14.7578 6.48828C13.4105 6.48828 12.3184 7.58045 12.3184 8.92772C12.3184 10.275 13.4105 11.3671 14.7578 11.3671Z" fill="white"/>
                                                <g clip-path="url(#clip1_5241_7679)">
                                                    <path d="M15.0563 14.9415C14.999 14.9415 14.941 14.9362 14.8826 14.9262C14.4166 14.8445 14.0913 14.4569 14.0913 13.9839V11.6079H11.715C11.242 11.6079 10.8546 11.2822 10.773 10.8165C10.6916 10.3515 10.944 9.91486 11.3866 9.75352L18.7673 6.93652L15.9446 14.3155C15.8056 14.6992 15.4533 14.9415 15.056 14.9415H15.0563Z" fill="#1E2124" fill-opacity="0.6"/>
                                                </g>
                                            </g>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_5241_7679">
                                                <rect width="30" height="30" fill="white"/>
                                            </clipPath>
                                            <clipPath id="clip1_5241_7679">
                                                <rect width="8" height="8" fill="white" transform="translate(10.7578 6.94141)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                    `;
                }

                function createMarkerIconFromCssVariable(variableName) {
                    const rootStyles = getComputedStyle(document.documentElement);
                    const dynamicColor = rootStyles.getPropertyValue(variableName).trim();

                    // Generate SVG with the dynamic color
                    const svg = getDynamicMarkerSvg(dynamicColor);

                    // Convert to Base64 for Google Maps marker
                    const base64Svg = `data:image/svg+xml;base64,${btoa(svg)}`;

                    return {
                        url: base64Svg,
                        scaledSize: new google.maps.Size(30, 30),
                    };
                }

                // Set icon color from a CSS variable
                const destinationMarkerIcon = createMarkerIconFromCssVariable("--primary-clr");

                // Add a marker for the pickup location
                const pickupMarker = new google.maps.Marker({
                    position: pickupLocation,
                    map: map,
                    title: "Pickup Location",
                    icon: "{{ asset('public/assets/admin/img/icons/pickup.svg') }}",
                });

                google.maps.event.addListener(pickupMarker, "click", function() {
                    infowindow.setContent('<div class="fs-12 font-medium">Pickup</div>');
                    infowindow.open(map, pickupMarker);
                });

                // Add a marker for the destination location
                const destinationMarker = new google.maps.Marker({
                    position: destinationLocation,
                    map: map,
                    title: "Destination Location",
                    icon: destinationMarkerIcon,
                });

                google.maps.event.addListener(destinationMarker, "click", function() {
                    infowindow.setContent('<div class="fs-12 font-medium">Destination</div>');
                    infowindow.open(map, destinationMarker);
                });

                // Add a routed polyline between the pickup and destination locations
                addPolylineToMap(map, pickupLocation, destinationLocation);
            }

            // Re-init map before showing modal
            $('#pickupDesModal').on('shown.bs.modal', function(event) {
                initializeCustomRouteLocationMap();
            });

            // ------- pickup destinaton map with route line ends

            // ------- select2 search placeholder add 
            $('.select2-search__field').attr("placeholder", '<i class="tio-search"></i> Search Vendor');
            // ------- select2 search placeholder add ends 
        })
    </script>
@endpush
