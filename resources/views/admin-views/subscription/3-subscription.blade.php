@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-start">
                        <img src="{{asset('/public/assets/admin/img/create-package-icon.png')}}" width="24" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title">{{translate('Subscription Package')}}</h1>
                            <div class="page-header-text">Create Subscriptions Packages for Subscription Business Model</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-20">
            <div class="card-header">
                <div class="w-100 d-flex flex-wrap align-items-start gap-2">
                    <img src="{{asset('/public/assets/admin/img/material-symbols_featured-play-list.png')}}" width="18" alt="img" class="mt-1">
                    <div class="w-0 flex-grow">
                        <h5 class="text--title card-title">Package Information</h5>
                        <div class="fz-12px">Give Subscriptions Package Information</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link lang_link active" href="#" id="en-link">English(EN)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic - العربية(AR)</a>
                    </li>
                </ul>
                <div class="row g-3">
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="input-label">Package Name</label>
                            <input type="text" class="form-control" placeholder="Ex: Basic Plan">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="input-label">Package Price ($)</label>
                            <input type="number" class="form-control" placeholder="Ex: 300">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="input-label">Package Validity Days</label>
                            <input type="number" class="form-control" placeholder="Ex: Basic Plan">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group m-0">
                            <label class="input-label">Package Info</label>
                            <textarea type="number" class="form-control" placeholder="Ex: Value for money" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-20">
            <div class="card-header">
                <div class="w-100 d-flex flex-wrap align-items-start gap-2">
                    <img src="{{asset('/public/assets/admin/img/material-symbols_featured-play-list-2.png')}}" alt="img" class="mt-1">
                    <div class="w-0 flex-grow">
                        <h5 class="text--title card-title d-flex gap-3 flex-wrap mb-1">
                            <div>
                                Package Available Features
                            </div>
                            <label class="form-group form-check form--check">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                <span class="form-check-label text-dark font-regular text-14">Select Alll</span>
                            </label>
                        </h5>
                        <div class="fz-12px">Mark the feature you want to give in this package</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="check--item-wrapper check--item-wrapper-2 mt-0">
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">POS System</span>
                        </label>
                    </div>
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">Self Delivery</span>
                        </label>
                    </div>
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">Mobile App</span>
                        </label>
                    </div>
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">Review</span>
                        </label>
                    </div>
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">Chat</span>
                        </label>
                    </div>
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">Table Booking System</span>
                        </label>
                    </div>
                    <div class="check-item">
                        <label class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input" name="package-available-feature">
                            <span class="form-check-label text-dark">Kitchen App</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="w-100 d-flex flex-wrap align-items-start gap-2">
                    <img src="{{asset('/public/assets/admin/img/bx_category.png')}}" alt="img" class="mt-1">
                    <div class="w-0 flex-grow">
                        <h5 class="text--title card-title d-flex gap-3 flex-wrap mb-1">
                            <div>
                                Set limit
                            </div>
                        </h5>
                        <div class="fz-12px">Set maximum order & product limit for this package</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <div class="__bg-F8F9FC-card p-0">
                        <div class="card-body">
                            <div class="limit-item-card">
                                <div class="form-group mb-0">
                                    <label class="form-label text-capitalize">Maximum Order Limit</label>
                                    <div class="d-flex flex-wrap items-center gap-2">
                                        <div class="resturant-type-group p-0">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input limit-input" type="radio" checked name="minimum-order-limit" value="unlimited">
                                                <span class="form-check-label">
                                                    Unlimited (Default)
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input limit-input" type="radio" name="minimum-order-limit" value="Use Limit">
                                                <span class="form-check-label">
                                                    Use Limit
                                                </span>
                                            </label>
                                        </div>
                                        <div class="custom-limit-box">
                                            <input type="number" class="form-control" placeholder="Ex: 1000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="__bg-F8F9FC-card p-0">
                        <div class="card-body">
                            <div class="limit-item-card">
                                <div class="form-group mb-0">
                                    <label class="form-label text-capitalize">Maximum Order Limit</label>
                                    <div class="d-flex flex-wrap items-center gap-2">
                                        <div class="resturant-type-group p-0">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input limit-input" type="radio" name="maximum-order-limit" value="unlimited">
                                                <span class="form-check-label">
                                                    Unlimited (Default)
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input limit-input" type="radio" name="maximum-order-limit" value="Use Limit" checked>
                                                <span class="form-check-label">
                                                    Use Limit
                                                </span>
                                            </label>
                                        </div>
                                        <div class="custom-limit-box">
                                            <input type="number" class="form-control" placeholder="Ex: 1000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn--container justify-content-end mt-3">
            <button type="reset" class="btn btn--reset">Reset</button>
            <button type="submit" class="btn btn--primary">Submit</button>
        </div>
        <div class="modal fade show" id="initial-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header pt-4">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true" class="tio-clear"></span>
                        </button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div>
                            <div>
                                <div class="text-center">
                                    <h2 class="modal-title">Subscription Packages</h2>
                                </div>
                                <div class="text-center text-14 mb-4">
                                    Here you can view all the data placements in a package card in the subscription UI in the user app and website
                                </div>
                                <div class="text-center pt-2">
                                    <img class="mb-20" src="{{asset('public/assets/admin/img/plan/plan-details.svg')}}" alt="">
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--primary" data-dismiss="modal">
                                    Okay
                                </button>
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

    $('#select-all').on('change', function(){
        if($(this).is(':checked')){
            $('[name="package-available-feature"]').prop('checked', true);
        }else{
            $('[name="package-available-feature"]').prop('checked', false);
        }
    })
    $('[name="package-available-feature"]').on('change', function(){
        if($(this).is(':checked')){
            if($('[name="package-available-feature"]').length == $('[name="package-available-feature"]:checked').length){
                $('#select-all').prop('checked', true);
            }
        }else{
            $('#select-all').prop('checked', false);
        }
    })

    $('.limit-input').on('change', function(){
        if($(this).is(':checked')){
            if($(this).val() == 'Use Limit'){
                $(this).closest('.limit-item-card').find('.custom-limit-box').show();
            }else{
                $(this).closest('.limit-item-card').find('.custom-limit-box').hide();
            }
        }
    })
    $(window).on('load', function(){
        // Limit Input Field Show Hide
        $('.limit-input').each(function(){
            if($(this).is(':checked')){
                if($(this).val() == 'Use Limit'){
                    $(this).closest('.limit-item-card').find('.custom-limit-box').show();
                }else{
                    $(this).closest('.limit-item-card').find('.custom-limit-box').hide();
                }
            }
        })
        // Modal Show
        $('#initial-modal').modal('show');
    })

</script>

@endpush

