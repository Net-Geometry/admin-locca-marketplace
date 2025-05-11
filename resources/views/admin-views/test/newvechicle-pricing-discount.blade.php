@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">

      <!-- Add New Vechicle > Pricing & Discount Update -->
      <div class="card">
        <div class="card-header">
            <div>
                <h5 class="text-title mb-1">
                    {{ translate('messages.Pricing & Discounts') }}
                </h5>
                <p class="fs-12 mb-0">
                    {{ translate('messages.Insert_The_Pricing & Discount Informations') }}
                </p>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h6 class="fz--14px mb-1">
                    {{ translate('messages.Trip Type') }}
                </h6>
                <p class="fs-12 mb-0">
                    {{ translate('messages.Choose the trip type you prefer.') }}
                </p>
            </div>
            <div class="bg--secondary rounded p-20 mobile-space-0">
                <div class="bg-white rounded p-15 border">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-0">
                                <div class="p-0 resturant-type-group">
                                    <label class="d-flex mb-0 form-check item">
                                        <input class="form-check-input single-select" type="checkbox" name="trip_hourly"
                                                value="hourly" {{ old('trip_hourly') == 'hourly' ? 'checked' : '' }}>
                                        <span class="form-check-label ml-2 mt-1">
                                            <span class="title-clr d-block fz--14px">Hourly</span>
                                            <p class="fz-12px mb-0 text-wrap">Set your hourly rental price.</p>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-0">
                                <div class="p-0 resturant-type-group">
                                    <label class="d-flex mb-0 form-check item">
                                        <input class="form-check-input single-select" type="checkbox" name="trip_day" value="">
                                        <span class="form-check-label ml-2 mt-1">
                                            <span class="title-clr d-block fz--14px">Per Day</span>
                                            <p class="fz-12px mb-0 text-wrap">Set your Per Day rental price.</p>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-0">
                                <div class="p-0 resturant-type-group">
                                    <label class="d-flex mb-0 form-check item">
                                        <input class="form-check-input single-select" type="checkbox" name="trip_distance"
                                                value="distance_wise" {{ old('trip_distance') == 'distance_wise' ? 'checked' : '' }}>
                                        <span class="form-check-label ml-2 mt-1">
                                            <span class="title-clr d-block fz--14px">Distance Wise</span>
                                            <p class="fz-12px mb-0 text-wrap">Set your distance wise rental price.</p>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-lg-4">
                        <div class="form-group mb-0">
                            <label class="input-label" for="">{{ translate('messages.Hourly Wise Price ($/per hour)') }}<span class="text-danger">*</span></label>
                            <input type="number" name="hourly_price" class="form-control"
                                    placeholder="Ex: 35.25" min="0.01" step="0.01" value="{{ old('hourly_price') }}" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-0">
                            <label class="input-label" for="">Per Day Price ($)<span class="text-danger">*</span></label>
                            <input type="number" name="perday_price" class="form-control"
                                    placeholder="Ex: 35.25" min="0.01" step="0.01" value="" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-0">
                            <label class="input-label" for="">{{ translate('messages.Distance Wise Price ($/per km)') }}<span class="text-danger">*</span></label>
                            <input type="number" name="distance_price" class="form-control"
                                    placeholder="Ex: 35.25" min="0.01" step="0.01" value="{{ old('distance_price') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-20 bg--secondary rounded p-20 mobile-space-0">
                <div class="row g-3">
                    <div class="col-xxl-3 col-md-4">
                        <div class="mb-0">
                            <h6 class="fz--14px mb-1">
                                Give Discount
                            </h6>
                            <p class="fz-12px mb-0">
                                Set a discount that applies to all pricing types—hourly, daily, and distance-based
                            </p>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-md-8">
                        <div class="bg-white rounded p-20 mobile-space-0">
                            <div class="form-group mb-0">
                                <div class="custom-group-btn border">
                                    <div class="flex-sm-grow-1">
                                        <input id="discount_input" type="number" name="discount_price" class="form-control h--45px border-0 pl-unset"
                                                placeholder="Ex: 10" min="0" step="0.001" value="{{ old('discount_price') }}">
                                    </div>
                                    <div class="flex-shrink-0">
                                        <select name="discount_type" id="discount_type" class="custom-select ltr border-0">
                                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>%</option>
                                            <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>
                                                {{ \App\CentralLogics\Helpers::currency_symbol() }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
