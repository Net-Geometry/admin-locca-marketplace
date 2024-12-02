@extends('layouts.admin.app')

@section('title', translate('messages.Business_Setup'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ translate('messages.Business_Setup') }}
                    </h1></span>
                    </h1>
                </div>
            </div>

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title"
                            href="javascript:">{{ translate('messages.Business Information') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title"
                            href="javascript:">{{ translate('messages.Orders') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title"
                            href="javascript:">{{ translate('messages.Refund Settings') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title active"
                            href="javascript:">{{ translate('messages.Vendors') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title"
                            href="javascript:">{{ translate('messages.Delivery Man') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title"
                            href="javascript:">{{ translate('messages.Customers') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize text-title"
                            href="javascript:">{{ translate('messages.Language') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <div class="card mb-20">
            <div class="card-header">
                <div>
                    <h5 class="opacity-70 fs-14 mb-0">
                        <i class="tio-notebook-bookmarked"></i> {{ translate('messages.Vendor Settings') }}
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label class="input-label font-medium" for="">
                                    {{ translate('messages.Vendor Can Cancel Order?') }}
                                </label>
                                <div class="resturant-type-group border">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" value="yes"
                                            name="cancel_confirmation_model" id="cancel_confirmation_model"
                                            checked="">
                                        <span class="form-check-label">
                                            {{ translate('messages.yes') }}
                                        </span>
                                    </label>
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" value="no"
                                            name="cancel_confirmation_model" id="cancel_confirmation_model2">
                                        <span class="form-check-label">
                                            {{ translate('messages.no') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title">
                                        <span class="line--limit-1">
                                            {{ translate('messages.add_product_from_galley') }}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.add_product_from_galley') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="add_product_from_galley_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_add_product_from_galley?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_add_product_from_galley?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_add_product_from_galley_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_add_product_from_galley_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="add_product_from_galley_status" id="add_product_from_galley_status"
                                        checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title">
                                        <span class="line--limit-1">
                                            {{ translate('messages.vendor_self_registration') }}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.vendor_self_registration') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="vendor_self_registration_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_vendor_self_registration?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_vendor_self_registration?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_vendor_self_registration_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_vendor_self_registration_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="vendor_self_registration_status" id="vendor_self_registration_status"
                                        checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title">
                                        <span class="line--limit-1">
                                            {{ translate('messages.access_all_products') }}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.access_all_products') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="access_all_products_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_access_all_products?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_access_all_products?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_access_all_products_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_access_all_products_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="access_all_products_status" id="access_all_products_status"
                                        checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title">
                                        <span class="line--limit-1">
                                            {{ translate('messages.need_approval_for_products') }}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.need_approval_for_products') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="need_approval_for_products_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_ao_enabl__ne_p_Approval for Products?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_ao_disab_e_n_pd_Approval for Products?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_needaApprova_ fo_pProducts_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_needaApprova_ fo_pProducts_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="need_approval_for_products_status" id="need_approval_for_products_status"
                                        checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label text--title">
                                        <span class="line--limit-1">
                                            {{ translate('messages.vendor_can_reply_review') }}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.vendor_can_reply_review') }}">
                                                <i class="tio-info-outined text--title"></i>
                                            </span>
                                        </span>
                                    </span>
                                    <input type="checkbox" data-id="vendor_can_reply_review_status" data-type="toggle"
                                        data-image-on="{{ asset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                        data-image-off="{{ asset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                        data-title-on="<strong>{{ translate('messages.Want_to_enable_vendor_can_reply_review?') }}</strong>"
                                        data-title-off="<strong>{{ translate('messages.Want_to_disable_vendor_can_reply_review?') }}</strong>"
                                        data-text-on="<p>{{ translate('messages.If_you_enable_this,_vendor_can_reply_review_will_be_enabled.') }}</p>"
                                        data-text-off="<p>{{ translate('messages.If_you_disable_this,_vendor_can_reply_review_will_be_disabled.') }}</p>"
                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                        name="vendor_can_reply_review_status" id="vendor_can_reply_review_status"
                                        checked>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-0">
                                <label class="input-label font-medium" for="">
                                    {{ translate('messages.Need Approval When') }}
                                </label>
                                <div class="border resturant-type-group gap-30px px-3 py-2">
                                    <label class="align-items-center d-flex form-check">
                                        <input class="form-check-input single-select" type="checkbox"
                                            value="Add new product" checked="">
                                        <span class="form-check-label ml-2 mt-1">
                                            {{ translate('messages.Add new product') }}
                                        </span>
                                    </label>
                                    <label class="align-items-center d-flex form-check">
                                        <input class="form-check-input single-select" type="checkbox"
                                            value="Update product price" checked="">
                                        <span class="form-check-label ml-2 mt-1">
                                            {{ translate('messages.Update product price') }}
                                        </span>
                                    </label>
                                    <label class="align-items-center d-flex form-check">
                                        <input class="form-check-input single-select" type="checkbox"
                                            value="Update product variation">
                                        <span class="form-check-label ml-2 mt-1">
                                            {{ translate('messages.Update product variation') }}
                                        </span>
                                    </label>
                                    <label class="align-items-center d-flex form-check">
                                        <input class="form-check-input single-select" type="checkbox"
                                            value="Update anything in product details">
                                        <span class="form-check-label ml-2 mt-1">
                                            {{ translate('messages.Update anything in product details') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="__bg-FAFAFA card shadow-none">
            <div class="card-header bg-transparent">
                <div>
                    <h5 class="text-title mb-1">
                        {{ translate('messages.Vendor Cancelation Rate Setup') }}
                    </h5>
                    <p class="fs-12 mb-0">
                        {{ translate('messages.This section will be applicable for vendors of car rental module') }}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-0">
                            <label class="input-label font-medium" for="">
                                {{ translate('messages.Cancelation Rate Limit') }} (%)
                                <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Cancelation Rate Limit') }}">
                                    <i class="tio-info-outined text--title"></i>
                                </span>
                            </label>
                            <input type="number" name="" class="form-control" placeholder="Ex: 25"
                                value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-0">
                            <label class="input-label font-medium" for="">
                                {{ translate('messages.Cancelation Rate Warning') }} (%)
                                <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Cancelation Rate Warning') }}">
                                    <i class="tio-info-outined text--title"></i>
                                </span>
                            </label>
                            <input type="number" name="" class="form-control" placeholder="Ex: 20"
                                value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn--container justify-content-end mt-3">
            <button type="reset" id="reset_btn"
                class="btn btn--reset min-w-120px">{{ translate('messages.reset') }}</button>
            <button type="submit"
                class="btn btn--primary min-w-120px">{{ translate('messages.Save_Information') }}</button>
        </div>
    </div>

@endsection

@push('script_2')
@endpush
