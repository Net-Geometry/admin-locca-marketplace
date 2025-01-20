@extends('layouts.vendor.app')

@section('title', translate('messages.banner'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/banner.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.Banners') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h5 class="text-title mb-1">
                                {{ translate('messages.Add_New_Banner') }}
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="banner_form" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label font-semibold" for="">Title
                                            (Default)
                                        </label>
                                        <input type="text" name="title" id="" class="form-control"
                                               placeholder="Auto Focus Car Service" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label font-semibold" for="">
                                            Redirection URL / Link
                                        </label>
                                        <input type="text" name="default_link" id="" class="form-control"
                                               placeholder="Enter URL">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="h-100 d-flex flex-column justify-content-between">
                                        <div class="form-group">
                                            <label
                                                class="fs-16 text-title font-semibold  mb-0">{{ translate('messages.Banner_Image') }}</label>
                                            <p class="mb-20">JPG, JPEG, PNG Less Than 1MB <span
                                                    class="font-weight-bold">(Ratio
                                                    3:1)</span>
                                            </p>

                                            <div class="upload-file">
                                                <input type="file" name="image" class="upload-file__input"
                                                       accept=".jpg, .jpeg, .png">
                                                <label class="upload-file-wrapper three-one">
                                                    <div class="upload-file-textbox text-center">
                                                        <img width="34" height="34"
                                                             src="{{ asset('public/assets/admin/img/document-upload.svg') }}"
                                                             alt="">
                                                        <h6 class="mt-2 font-semibold  text-center">
                                                            <span>{{ translate('Click to upload') }}</span>
                                                            <br class="d-block d-sm-none">
                                                            {{ translate('or drag and drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy" style="display: none;"
                                                         alt="">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="btn--container justify-content-end">
                                            <button type="reset" id="reset_btn" class="btn btn--reset">Reset</button>
                                            <button type="submit" class="btn btn--primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header py-2">
                        <div class="search--button-wrapper gap-20px">
                            <h5 class="card-title text--title flex-grow-1">{{ translate('messages.Banner_List') }}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$banners->count()}}</span></h5>

                            <form class="search-form m-0 flex-grow-1 max-w-353px">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}"
                                        name="search" class="form-control"
                                        placeholder="{{ translate('Search by banner title...') }}"
                                        aria-label="{{ translate('messages.Search by banner title...') }}">
                                    <button type="submit" class="btn btn--secondary bg--primary"><i
                                            class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>
                            @if (request()->get('search'))
                                <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                    data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                            @endif
                            <!-- Unfold -->
                            <div class="hs-unfold m-0">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40 font-semibold"
                                    href="javascript:;"
                                    data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                                    <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                    <a id="export-excel" class="dropdown-item"
                                        href="{{ route('vendor.rental_banner.export', ['type' => 'excel', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item"
                                        href="{{ route('vendor.rental_banner.export', ['type' => 'csv', request()->getQueryString()]) }}">
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
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{ translate('messages.SL') }}</th>
                                    <th class="border-0">{{ translate('messages.Banner_Info') }}</th>
                                    <th class="border-0">{{ translate('messages.banner_type') }}</th>
                                    <th class="border-0 text-center">{{translate('messages.featured')}} <span class="input-label-secondary"
                                        data-toggle="tooltip" data-placement="right" data-original-title="{{translate('if_you_turn/off_on_this_featured,_it_will_effect_on_website_&_user_app')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}"
                                            alt="public/img"></span></th>
                                    <th class="border-0 text-center">{{ translate('messages.status') }}</th>
                                    <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach($banners as $key=>$banner)
                                <tr>
                                    <td>{{$key+$banners->firstItem()}}</td>
                                    <td>
                                        <span class="media align-items-center">
                                            <img class="img--ratio-3 w-auto h--50px rounded mr-2 onerror-image" src="{{ $banner['image_full_url'] }}"
                                                data-onerror-image="{{asset('/public/assets/admin/img/900x400/img1.jpg')}}" alt="{{$banner->name}} image">
                                            <div class="media-body">
                                                <h5 title="{{ $banner['title'] }}" class="text-hover-primary mb-0">{{Str::limit($banner['title'], 25, '...')}}</h5>
                                            </div>
                                        </span>
                                    <span class="d-block font-size-sm text-body">

                                    </span>
                                    </td>
                                    <td>{{ $banner['type'] == 'store_wise' ?  translate('provider_wise') : translate($banner['type']) }}</td>

                                    <td  >
                                        <div class="d-flex justify-content-center">
                                            <label class="toggle-switch toggle-switch-sm" for="featuredCheckbox{{$banner->id}}">
                                            <input type="checkbox"
                                            data-id="featuredCheckbox{{$banner->id}}"
                                            data-type="status"
                                            data-image-on="{{ asset('/public/assets/admin/img/modal/basic_campaign_on.png') }}"
                                            data-image-off="{{ asset('/public/assets/admin/img/modal/basic_campaign_off.png') }}"
                                            data-title-on="{{ translate('By_Turning_ON_As_Featured!') }}"
                                            data-title-off="{{ translate('By_Turning_OFF_As_Featured!') }}"
                                            data-text-on="<p>{{ translate('If_you_turn_on_this_featured,_then_promotional_banner_will_show_on_website_and_user_app_with_store_or_item.') }}</p>"
                                            data-text-off="<p>{{ translate('If_you_turn_off_this_featured,_then_promotional_banner_won’t_show_on_website_and_user_app') }}</p>"
                                            class="toggle-switch-input  dynamic-checkbox" id="featuredCheckbox{{$banner->id}}" {{$banner->featured?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        </div>
                                    </td>
                                    <form action="{{route('vendor.rental_banner.featured',[$banner['id'],$banner->featured?0:1])}}"
                                        method="get" id="featuredCheckbox{{$banner->id}}_form">
                                        </form>

                                    <td  >
                                        <div class="d-flex justify-content-center">
                                            <label class="toggle-switch toggle-switch-sm" for="statusCheckbox{{$banner->id}}">
                                            <input type="checkbox"
                                            data-id="statusCheckbox{{$banner->id}}"
                                            data-type="status"
                                            data-image-on="{{ asset('/public/assets/admin/img/modal/basic_campaign_on.png') }}"
                                            data-image-off="{{ asset('/public/assets/admin/img/modal/basic_campaign_off.png') }}"
                                            data-title-on="{{ translate('By_Turning_ON_Banner!') }}"
                                            data-title-off="{{ translate('By_Turning_OFF_Banner!') }}"
                                            data-text-on="<p>{{ translate('If_you_turn_on_this_status,_it_will_show_on_user_website_and_app.') }}</p>"
                                            data-text-off="<p>{{ translate('If_you_turn_off_this_status,_it_won’t_show_on_user_website_and_app') }}</p>"
                                            class="toggle-switch-input  dynamic-checkbox" id="statusCheckbox{{$banner->id}}" {{$banner->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        </div>
                                    </td>

                                    <form action="{{route('vendor.rental_banner.status',[$banner['id'],$banner->status?0:1])}}"
                                        method="get" id="statusCheckbox{{$banner->id}}_form">
                                        </form>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary" href="{{route('vendor.rental_banner.edit',[$banner['id']])}}" title="{{translate('messages.edit_banner')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:" data-id="banner-{{$banner['id']}}" data-message="{{ translate('Want to delete this banner ?') }}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('vendor.rental_banner.delete',[$banner['id']])}}"
                                                        method="post" id="banner-{{$banner['id']}}">
                                                    @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                    </div>
                    @if(count($banners) !== 0)
                    <hr>
                    @endif
                    <div class="page-area">
                        {!! $banners->links() !!}
                    </div>
                    @if(count($banners) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/view-pages/banner-index.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function() {
            $('.upload-file__input').on('change', function(event) {
                var file = event.target.files[0];
                var $card = $(event.target).closest('.upload-file');
                var $textbox = $card.find('.upload-file-textbox');
                var $imgElement = $card.find('.upload-file-img');

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $textbox.hide();
                        $imgElement.attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#reset_btn').on('click', function() {
                var $uploadFile = $('.upload-file');
                var $textbox = $uploadFile.find('.upload-file-textbox');
                var $imgElement = $uploadFile.find('.upload-file-img');
                var $fileInput = $uploadFile.find('.upload-file__input');

                $textbox.show();
                $imgElement.attr('src', '').hide();
                $fileInput.val('');
            });
        });
    </script>
@endpush
