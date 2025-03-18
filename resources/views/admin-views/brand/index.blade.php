@extends('layouts.admin.app')

@section('title',translate('messages.add_new_brand'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/category.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('messages.Brand_Setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.brand.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-12">
                            @if($language)
                                <ul class="nav nav-tabs mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active"
                                           href="#"
                                           id="default-link">{{translate('messages.default')}}</a>
                                    </li>
                                    @foreach ($language as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link"
                                               href="#"
                                               id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="col-6">
                            @if($language)
                                <div class="form-group lang_form" id="default-form">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{ translate('messages.default') }})</label>
                                    <input type="text" name="name[]" value="{{ old('name.0') }}"  class="form-control" placeholder="{{translate('messages.new_brand')}}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach($language as $key => $lang)
                                    <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="name[]" value="{{ old('name.'.$key+1) }}"  class="form-control" placeholder="{{translate('messages.new_brand')}}" maxlength="191">
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                @endforeach
                            @else
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{translate('messages.new_brand')}}" value="{{old('name')}}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            @endif
                        </div>
                        <div class="col-6">
                            <div class="h-100 d-flex align-items-center flex-column">
                                <label class="mb-3 text-center">{{translate('messages.image')}} <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1)</small></label>
                                <label class="text-center my-auto position-relative d-inline-block">
                                    <img class="img--176 border" id="viewer"
                                         @if(isset($category))
                                             src="{{asset('storage/app/public/category')}}/{{$category['image']}}"
                                         @else
                                             src="{{asset('public/assets/admin/img/upload-img.png')}}"
                                         @endif
                                         alt="image"/>
                                    <div class="icon-file-group">
                                        <div class="icon-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input read-url"
                                                   accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                            <i class="tio-edit"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{isset($brand)?translate('messages.update'):translate('messages.add')}}</button>
                    </div>

                </form>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{translate('messages.All_Brand_List')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$brands->total()}}</span></h5>
                    <form  class="search-form">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch" name="search" value="{{ request()?->search ?? null }}"  type="search" class="form-control" placeholder="{{translate('messages.search_by_name')}}" aria-label="{{translate('messages.Brands')}}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "search": "#datatableSearch",
                            "entries": "#datatableEntries",
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{translate('sl')}}</th>
                                <th class="border-0 w--1">{{translate('messages.Brand_Info')}}</th>
                                <th class="border-0 text-center">{{translate('messages.Total_Products')}}</th>
                                <th class="border-0 text-center">{{translate('messages.status')}}</th>
                                <th class="border-0 text-center">{{translate('messages.action')}}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                        @foreach($brands as $key=>$brand)
                            <tr>
                                <td>{{$key+$brands->firstItem()}}</td>


                                <td>
                                    <div class="media align-items-center">
                                        <img class="avatar avatar-lg mr-3 onerror-image"
                                        src="{{$brand['image_full_url'] ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"  alt="{{$brand->name}} image">
                                        <div  class="media-body">
                                            <h5   class="text-hover-primary mb-0">{{Str::limit($brand['name'],20,'...')}}
                                                @if($brand->module_id == null)
                                                    <span class="ml-2 badge badge-soft-success">{{translate('messages.All_module')}}</span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="d-block font-size-sm text-body">
                                        {{ $brand->items->count()}}
                                    </span>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$brand->id}}">
                                    <input type="checkbox" data-url="{{route('admin.brand.status',[$brand['id'],$brand->status?0:1])}}" class="toggle-switch-input redirect-url" id="stocksCheckbox{{$brand->id}}" {{$brand->status?'checked':''}}>
                                        <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        @if ($brand->module_id == null)
                                        <button  title="{{translate('Module_Assign')}}" class="btn action-btn btn--primary btn-outline-primary  withdraw-info-show" type="button" data-brand_id="{{ $brand['id'] }}"
                                        data-image_src="{{ $brand['image_full_url'] }}"
                                        data-name="{{ $brand['name'] }}"
                                            ><i class="tio-apps"></i>
                                        </button>
                                        @endif
                                        <a class="btn action-btn btn--primary btn-outline-primary"
                                        href="{{route('admin.brand.edit',[$brand['id']])}}" title="{{translate('messages.edit_brand')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:" data-id="brand-{{$brand['id']}}" data-message="{{ translate('messages.Want to delete this brand') }}"  title="{{translate('messages.delete_brand')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('admin.brand.delete',[$brand['id']])}}" method="post" id="brand-{{$brand['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if(count($brands) !== 0)
            <hr>
            @endif
            <div class="page-area">
                {!! $brands->links() !!}
            </div>
            @if(count($brands) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
        </div>
    </div>


    <div class="modal fade" id="module-change-modal">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">{{ translate('Update_Module') }}</h3>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <h5 class="modal-title"> </h5>
                            </div>

                        </div>
                        <div class="btn--container justify-content-center">
                            <button type="button" class="btn btn-outline-info min-w-120" data-toggle="modal" data-target="#Keep_only_this_module_confirmation" data-dismiss="modal" >{{translate('Keep_only_this_module')}}</button>
                            <button type="button" class="btn btn-outline-warning min-w-120" data-toggle="modal"  data-target="#make_a_new_brand_confirmation"  data-dismiss="modal">
                                {{translate("Make it a new Brand")}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="withdraw-info-sidebar-wrap">
        <div class="withdraw-info-sidebar-overlay"></div>
        <div class="withdraw-info-sidebar">
            <div class="d-flex p-3 justify-content-between">
                <h3 class="mb-3">{{translate('Module Assign')}}</h3>
                <span class="circle bg-light withdraw-info-hide cursor-pointer">
                    <i class="tio-clear"></i>
                </span>
            </div>


            <div class="card mb-3">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="d-flex justify-content-center mb-3">
                            <img src="brand-logo.png" id="brand_img_src" alt="Brand Logo" width="90">
                            <h5 id="brand_name"  class="mt-2 ml-2"></h5>
                        </div>
                            <div class="alert fs-13 alert-primary-light text-dark mb-0  mt-md-0 add_text_mute text-muted mt-2"  role="alert">
                                <img src="{{ asset('/public/assets/admin/img/lnfo_light.png') }}" alt="">
                                {{translate('Currently, this brand is active in all modules of the')}} <strong>{{ Config::get('module.current_module_name') }}</strong> {{ translate('Module_Type') }}
                            </div>

                    </div>
                </div>
            </div>
            <form action="{{ route('admin.brand.moduleUpadte') }}" method="post">
                @csrf
                <input type="text" hidden  name="brand_id"  id="brand_id">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title mb-0 font-medium">{{translate('Assign Brand')}}</h4>
                        <small class="card-text">{{ translate('Select your preferred assign option for this brand') }}</small>

                        <div class=" mt-4 mb-3">

                            <div class="radio-card selected mb-2" data-value="module-only">
                                <input type="radio" name="type" value="only_this_module" checked>
                                    <strong>{{ translate('Use this Brand only for this module’s product') }}</strong>
                                    <br>
                                    <small class="text-muted mt-1 mb-0">
                                    {{ translate(' This brand will only use for') }} <strong>{{ Config::get('module.current_module_name') }}</strong> {{ translate('Module and will be removed from other module’s product.') }}
                                    </small>
                            </div>

                                <div class="radio-card mt-2"  data-value="all-modules">
                                    <input type="radio" name="type" value="copy_this_brand">
                                    <strong>{{ translate('Create the same brand for other modules also') }}</strong>
                                    <br>
                                    <small class="text-muted mt-1 mb-0">
                                        {{ translate('This brand will be created automatically for every module. And the products in each module will automatically be assigned to that brand.') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center gap-3">
                        <button  type="reset" class="btn btn-outline-secondary min-w-120 withdraw-info-hide">
                            {{translate("Cancel")}}
                            <button type="submit" class="btn btn-outline-primary min-w-120" >{{translate('Transfer')}}</button>
                        </button>
                    </div>

                </div>
            </form>


        </div>

@endsection

@push('script_2')
    <script src="{{asset('public/assets/admin')}}/js/view-pages/brand-index.js"></script>
    <script>
        "use strict";
        $('.withdraw-info-hide, .withdraw-info-sidebar-overlay').on('click', function () {
            $('.withdraw-info-sidebar, .withdraw-info-sidebar-overlay').removeClass('show');
        });

        $(document).on('click', '.withdraw-info-show', function () {
            $('#brand_img_src').attr('src', $(this).data('image_src'));
            $('#brand_name').text($(this).data('name'));
            $('#brand_id').val($(this).data('brand_id'));
            $('.withdraw-info-sidebar, .withdraw-info-sidebar-overlay').addClass('show');
            });
        $(document).on('submit', '.withdraw_status_form', function (event) {
    $(this).find('button[type="submit"]').attr('disabled', true);
});

        $('#reset_btn').click(function(){
            $('#viewer').attr('src', "{{asset('public/assets/admin/img/upload-img.png')}}");
        })
    </script>
@endpush
