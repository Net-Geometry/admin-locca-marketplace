@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <h3 class="mb-20">All Taxes</h3>
    <div class="bg--F6F6F6 tax-error__table w-100 h-100-vh py-5">
        <div class="max-349 text-center mx-auto my-5">
            <img src="{{asset('/public/assets/admin/img/tax-error.png')}}" alt="img" class="mb-20">
            <h4 class="mb-2">Currently you don’t have any Tax</h4>
            <p class="mb-20">In this page you see all the Tax you added. Please create new tax to collect tax.</p>
            <div class="d-flex align-items-center justify-content-center gap-md-3 gap-2">
                <button type="button" class="btn btn--primary btn-outline-primary">Import</button>
                <button type="button" class="btn btn--primary" data-bs-toggle="offcanvas" data-bs-target="#tax-management__edit">Create Tax</button>
            </div>
        </div>
    </div>
    <!--- Tax List Create Here -->
    <div class="mt-5">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-20">
            <h4 class="mb-0">List of Taxes</h4>
            <div class="search--button-wrapper justify-content-end">
                <form class="search-form min--260">
                    <div class="input-group input--group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                placeholder="{{ translate('messages.Ex:') }} 10010" value="{{ request()?->search ?? null}}" aria-label="{{translate('messages.search')}}">
                                
                                <input type="hidden" name="parcel_order" value="">
                                
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                </form>
                @if(request()->get('search'))
                    <button type="reset" class="btn btn--primary ml-2 location-reload-to-base" data-url="{{url()->full()}}">{{translate('messages.reset')}}</button>
                @endif
                <!-- Datatable Info -->
                <div id="datatableCounterInfo" class="mr-2 mb-2 mb-sm-0 initial-hidden">
                    <div class="d-flex align-items-center">
                        <span class="font-size-sm mr-3">
                        <span id="datatableCounter">0</span>
                        {{translate('messages.selected')}}
                        </span>
                    </div>
                </div>
                <div class="hs-unfold mr-2">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px" href="javascript:;"
                        data-hs-unfold-options='{
                            "target": "#usersExportDropdown", "type": "css-animation" }'>
                        <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                    </a>
                    <div id="usersExportDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                        <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                        <a id="export-excel" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a id="export-csv" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                            .{{translate('messages.csv')}}
                        </a>
                    </div>
                </div>
                <button type="button" class="btn btn-white border h--40px">Import</button>
                <button type="button" class="btn btn--primary h--40px">Create Tax</button>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table fz--14px">
                <thead class="thead-light">
                <tr>
                    <th class="border-0">sl</th>
                    <th class="border-0">Tax Name</th>
                    <th class="border-0">Tax Rate</th>
                    <th class="border-0 text-end">Status</th>
                    <th class="border-0 text-end">Action</th>
                </tr>
                </thead>

                <tbody id="set-rows">
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            VAT
                        </td>
                        <td>
                            5%
                        </td>
                        <td>
                            <label class="toggle-switch ml-auto justify-content-end toggle-switch-sm" for="status">
                                <input type="checkbox" class="toggle-switch-input" id="status" checked>
                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm ml-auto text-end action-btn info--outline text--info info-hover" href="#0">
                                <i class="tio-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            GST
                        </td>
                        <td>
                            7%
                        </td>
                        <td>
                            <label class="toggle-switch ml-auto justify-content-end toggle-switch-sm" for="status2">
                                <input type="checkbox" class="toggle-switch-input" id="status2" checked>
                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm ml-auto text-end action-btn info--outline text--info info-hover" href="#0">
                                <i class="tio-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3
                        </td>
                        <td>
                            Income Tax
                        </td>
                        <td>
                            15%
                        </td>
                        <td>
                            <label class="toggle-switch ml-auto justify-content-end toggle-switch-sm" for="status3">
                                <input type="checkbox" class="toggle-switch-input" id="status3" checked>
                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm ml-auto text-end action-btn info--outline text--info info-hover" href="#0">
                                <i class="tio-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4
                        </td>
                        <td>
                            Service Tax
                        </td>
                        <td>
                            5%
                        </td>
                        <td>
                            <label class="toggle-switch ml-auto justify-content-end toggle-switch-sm" for="status4">
                                <input type="checkbox" class="toggle-switch-input" id="status4" checked>
                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm ml-auto text-end action-btn info--outline text--info info-hover" href="#0">
                                <i class="tio-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5
                        </td>
                        <td>
                            Sales Tax
                        </td>
                        <td>
                            5%
                        </td>
                        <td>
                            <label class="toggle-switch ml-auto justify-content-end toggle-switch-sm" for="status5">
                                <input type="checkbox" class="toggle-switch-input" id="status5" checked>
                                    <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm ml-auto text-end action-btn info--outline text--info info-hover" href="#0">
                                <i class="tio-edit"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- End Table -->
    </div>
</div>




<!--  Offcanvas -->
<form action="" method="post" id="">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="tax-management__edit" aria-labelledby="tax-management__editLabel">
        <div class="offcanvas-header ">
            <h2 class="mb-0">Create Tax</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, neque! Voluptatibus facere enim obcaecati, quos dolorum blanditiis voluptatum fugiat reiciendis.</p>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex justify-content-center gap-2 px-3 py-sm-3 py-2">
                <button type="button" class="btn btn--primary w-100 btn-outline-primary">Reset</button>
                <button type="button" class="btn btn--primary w-100">Submit</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('script_2')
@endpush
