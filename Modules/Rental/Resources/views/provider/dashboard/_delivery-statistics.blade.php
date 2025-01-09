<div class="row g-4" id="order_stats">
    <div class="col-sm-6 col-lg-3">
        <!-- Card -->
        <a class="resturant-card dashboard--card card--bg-1" href="{{ route('vendor.order.list', ['confirmed']) }}">
            <h4 class="title">{{ $confirmedCount }}</h4>
            <span class="subtitle">{{ translate('messages.confirmed') }}</span>
            <img src="{{ asset('public/assets/admin/img/rental/1.png') }}" alt="img" class="resturant-icon">
        </a>
        <!-- End Card -->
    </div>

    <div class="col-sm-6 col-lg-3">
        <!-- Card -->
        <a class="resturant-card dashboard--card card--bg-2" href="{{ route('vendor.order.list', ['cooking']) }}">
            <h4 class="title">{{ $ongoingCount }}</h4>
            <span class="subtitle">{{ translate('messages.Ongoing_Trip') }}</span>
            <img src="{{ asset('public/assets/admin/img/rental/2.png') }}" alt="img" class="resturant-icon">
        </a>
        <!-- End Card -->
    </div>

    <div class="col-sm-6 col-lg-3">
        <!-- Card -->
        <a class="resturant-card dashboard--card card--bg-3"
            href="{{ route('vendor.order.list', ['ready_for_delivery']) }}">
            <h4 class="title">{{ $completedCount }}</h4>
            <span class="subtitle">{{ translate('messages.completed') }}</span>
            <img src="{{ asset('public/assets/admin/img/rental/3.png') }}" alt="img" class="resturant-icon">
        </a>
        <!-- End Card -->
    </div>

    <div class="col-sm-6 col-lg-3">
        <!-- Card -->
        <a class="resturant-card dashboard--card card--bg-4"
            href="{{ route('vendor.order.list', ['item_on_the_way']) }}">
            <h4 class="title">{{ $canceledCount }}</h4>
            <span class="subtitle">{{ translate('messages.canceled') }}</span>
            <img src="{{ asset('public/assets/admin/img/rental/4.png') }}" alt="img" class="resturant-icon">
        </a>
        <!-- End Card -->
    </div>


    <div class="col-12">
        <div class="row g-2">
            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="{{ route('vendor.order.list', ['delivered']) }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            {{-- <img src="{{ asset('/public/assets/admin/img/dashboard/statistics/1.png') }}"
                                alt="dashboard" class="oder--card-icon"> --}}
                            <span>{{ translate('messages.All') }}</span>
                        </h6>
                        <span class="card-title text-success">
                            {{ $totalCount }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="{{ route('vendor.order.list', ['refunded']) }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            {{-- <img src="{{ asset('/public/assets/admin/img/dashboard/statistics/2.png') }}"
                                alt="dashboard" class="oder--card-icon"> --}}
                            <span>{{ translate('messages.pending') }}</span>
                        </h6>
                        <span class="card-title text-danger">
                            {{ $pendingCount }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="{{ route('vendor.order.list', ['scheduled']) }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            {{-- <img src="{{ asset('/public/assets/admin/img/dashboard/statistics/3.png') }}"
                                alt="dashboard" class="oder--card-icon"> --}}
                            <span>{{ translate('messages.scheduled') }}</span>
                        </h6>
                        <span class="card-title text-primary">
                            {{ $scheduledCount }}
                        </span>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-3">
                <a class="order--card h-100" href="{{ route('vendor.order.list', ['all']) }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                            {{-- <img src="{{ asset('/public/assets/admin/img/dashboard/statistics/4.png') }}"
                                alt="dashboard" class="oder--card-icon"> --}}
                            <span>{{ translate('Instant_Booking') }}</span>
                        </h6>
                        <span class="card-title text-info">
                            {{ $instantCount }}
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>































</div>
