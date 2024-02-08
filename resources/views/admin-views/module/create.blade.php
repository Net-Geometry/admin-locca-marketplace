@extends('layouts.admin.app')

@section('title',translate('messages.business_modules'))

@push('css_or_js')
<link rel="stylesheet" href="{{asset('public/assets/admin/css/radio-image.css')}}">

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('/public/assets/admin/img/module.png')}}" alt="">
            </span>
            <span>
                {{translate('Add_New_Business_Module')}}
            </span>
        </h1>
        <div class="alert alert-soft-primary alert-dismissible fade show d-flex" role="alert">
            <div>
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3345_9659)">
                    <path d="M9 2.10938C9.29127 2.10938 9.52734 1.87327 9.52734 1.58203V0.527344C9.52734 0.236109 9.29123 0 9 0C8.70877 0 8.47266 0.236109 8.47266 0.527344V1.58203C8.47266 1.87327 8.70877 2.10938 9 2.10938Z" fill="#015FA4"/>
                    <path d="M4.40189 4.01322C4.60784 3.80728 4.60784 3.4734 4.40189 3.26745L3.65609 2.52165C3.45014 2.3157 3.11626 2.3157 2.91032 2.52165C2.70437 2.72759 2.70437 3.06147 2.91032 3.26742L3.65612 4.01322C3.86203 4.21913 4.19595 4.21913 4.40189 4.01322Z" fill="#015FA4"/>
                    <path d="M1.96875 8.08594H0.914062C0.622828 8.08594 0.386719 8.32205 0.386719 8.61328C0.386719 8.90452 0.622828 9.14062 0.914062 9.14062H1.96875C2.25998 9.14062 2.49609 8.90452 2.49609 8.61328C2.49609 8.32205 2.25998 8.08594 1.96875 8.08594Z" fill="#015FA4"/>
                    <path d="M17.0859 8.08594H16.0312C15.74 8.08594 15.5039 8.32205 15.5039 8.61328C15.5039 8.90452 15.74 9.14062 16.0312 9.14062H17.0859C17.3772 9.14062 17.6133 8.90452 17.6133 8.61328C17.6133 8.32205 17.3772 8.08594 17.0859 8.08594Z" fill="#015FA4"/>
                    <path d="M14.3456 2.52165L13.5998 3.26745C13.3938 3.4734 13.3938 3.80728 13.5998 4.01322C13.8057 4.21917 14.1396 4.21917 14.3455 4.01322L15.0913 3.26742C15.2973 3.06147 15.2973 2.72759 15.0913 2.52165C14.8854 2.3157 14.5515 2.3157 14.3456 2.52165Z" fill="#015FA4"/>
                    <path d="M9 3.16406C5.99527 3.16406 3.55078 5.60855 3.55078 8.61328C3.55078 10.1134 4.18078 11.5622 5.27924 12.588C5.74474 13.0228 6.01172 13.6326 6.01172 14.2612V16.418C6.01172 17.2903 6.72142 18 7.59375 18H10.4062C11.2786 18 11.9883 17.2903 11.9883 16.418V14.2612C11.9883 13.6326 12.2553 13.0227 12.7208 12.588C13.8192 11.5622 14.4492 10.1134 14.4492 8.61328C14.4492 5.60855 12.0047 3.16406 9 3.16406ZM10.9336 16.418C10.9336 16.7087 10.697 16.9453 10.4062 16.9453H7.59375C7.30297 16.9453 7.06641 16.7087 7.06641 16.418V15.1172H10.9336V16.418ZM12.0009 11.8172C11.3716 12.4049 10.9912 13.2141 10.9396 14.0625H7.06036C7.00871 13.2141 6.62839 12.4049 5.99906 11.8172C5.1004 10.9779 4.60547 9.84009 4.60547 8.61328C4.60547 6.19014 6.57686 4.21875 9 4.21875C11.4231 4.21875 13.3945 6.19014 13.3945 8.61328C13.3945 9.84009 12.8996 10.9779 12.0009 11.8172Z" fill="#015FA4"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_3345_9659">
                    <rect width="18" height="18" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="w-0 flex-grow-1 pl-3">
                <strong>Holy guacamole!</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- <div class="mt-2 d-flex">
            <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1 p--10">
                <div class="blinkings active">
                    <i class="tio-info-outined"></i>
                    <div class="business-notes">
                        <h6><img src="{{asset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                        <div>
                        {{translate('messages.Don’t_forget_to_click_the_‘Add_Module’_button_below_to_save_the_new_business_module.')}}
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    <!-- End Page Header -->

    <h5 class="mb-3">{{translate('basic_setup')}}</h5>
    <form action="{{route('admin.business-settings.module.store')}}" method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body pb-0">
                @csrf
                @if($language)
                <ul class="nav nav-tabs mb-4 border-0">
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
                @if ($language)
                <div class="lang_form p-1 mb-2" id="default-form">
                    <div class="form-group">
                        <label class="input-label text-capitalize d-flex" for="exampleFormControlInput1">{{translate('Business_Module_name')}} ({{ translate('messages.default') }})</label>
                        <input type="text" name="module_name[]" class="form-control" maxlength="191" placeholder="{{ translate('messages.Ex:_Grocery,eCommerce,Pharmacy,etc.') }}">
                    </div>
                    <div class="form-group">
                        <label class="input-label d-flex">{{ translate('Business_Module_description')}} ({{ translate('messages.default') }})<span class="form-label-secondary text-danger d-flex"
                            data-toggle="tooltip" data-placement="right"
                            data-original-title="{{ translate('messages.Write_a_short_description_of_your_new_business_module_within_100_words_(550_characters)') }}"><img
                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                alt="{{ translate('messages.veg_non_veg') }}"></span></label>
                        <textarea class="ckeditor form-control" name="description[]"></textarea>
                    </div>
                </div>

                <input type="hidden" name="lang[]" value="default">
                @foreach($language as $lang)
                <div class="d-none lang_form p-1 mb-2" id="{{$lang}}-form">
                    <div class="form-group">
                        <label class="input-label text-capitalize d-flex" for="exampleFormControlInput1">{{translate('Business_Module_name')}} ({{strtoupper($lang)}})</label>
                        <input type="text" name="module_name[]" class="form-control" maxlength="191" placeholder="{{ translate('messages.Ex:_Grocery,eCommerce,Pharmacy,etc.') }}">
                    </div>
                    <div class="form-group">
                        <label class="input-label d-flex">{{ translate('Business_Module_description')}} ({{strtoupper($lang)}})<span class="form-label-secondary text-danger d-flex"
                            data-toggle="tooltip" data-placement="right"
                            data-original-title="{{ translate('messages.Write_a_short_description_of_your_new_business_module_within_100_words_(550_characters)')}}"><img
                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                alt="{{ translate('messages.veg_non_veg') }}"></span></label>
                        <textarea class="ckeditor form-control" name="description[]"></textarea>
                    </div>
                </div>

                <input type="hidden" name="lang[]" value="{{$lang}}">
                @endforeach
                @else
                <div class="form-group">
                    <label class="input-label" for="exampleFormControlInput1">{{translate('Business_Module_name')}}</label>
                    <input type="text" name="module_name" class="form-control" value="{{old('name')}}" maxlength="191"  placeholder="{{ translate('messages.Ex:_business_Module Name') }}">
                </div>
                <div class="form-group">
                    <label class="input-label">{{ translate('Business_Module_description')}}</label>
                    <textarea class="ckeditor form-control" name="description"></textarea>
                </div>
                <input type="hidden" name="lang[]" value="default">
                @endif
                {{--<div class="row mt-2">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="input-label" for="module_type">{{translate('messages.business_module_type')}}</label>
                            <select name="module_type" id="module_type" class="form-control text-capitalize module-change">
                                <option disabled selected>{{translate('messages.select_business_module_type')}}</option>
                                @foreach (config('module.module_type') as $key)
                                <option class="" value="{{$key}}">{{translate($key)}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger">{{translate('messages.business_module_type_change_warning')}}</small>
                            <div class="card mt-1 initial-hidden" id="module_des_card">
                                <div class="card-body" id="module_description"></div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <br>
        <h5 class="mb-3">{{translate('module_setup')}}</h5>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                    <h6 class="mb-3">{{translate('select_business_module_type')}}</h6>
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="module-radio-group">
                                @foreach (config('module.module_type') as $key)
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" value="deliveryman" name="module_type" value="{{$key}}">
                                    <span class="form-check-label">
                                        {{translate($key)}}
                                    </span>
                                </label>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h6 class="mb-3">{{translate('Chose related images')}}</h6>
                        <div class="card module-logo-card mb-3">
                            <div class="card-body">
                                <div class="row h-100">
                                    <div class="col-sm-6 mb-4 mb-sm-0">
                                        <div class="form-group m-0 h-100 d-flex align-items-center flex-column justify-content-center">
                                            <label class="form-label mb-0">
                                                {{translate('messages.icon')}}
                                                <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1)</small>
                                            </label>
                                            <div class="text-center my-auto position-relative">
                                                <img class="img--176 h-unset aspect-ratio-1 image--border" id="viewer" src="{{asset('public/assets/admin/img/upload-img.png')}}" alt="image" />
                                                <div class="icon-file-group">
                                                    <label class="icon-file">
                                                        <input type="file" name="icon" id="customFileEg1" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                        <i class="tio-edit"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-0 h-100 d-flex flex-column justify-content-center align-items-center">
                                            <label class="form-label mb-4">
                                                {{translate('messages.thumbnail')}}
                                                <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1)</small>
                                            </label>
                                            <div class="text-center my-auto position-relative">
                                                <img class="img--176 h-unset aspect-ratio-1 image--border" id="viewer2" src="{{asset('public/assets/admin/img/upload-img.png')}}" alt="image" />
                                                <div class="icon-file-group">
                                                    <label class="icon-file">
                                                        <input type="file" name="thumbnail" id="customFileEg2" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                        <i class="tio-edit"></i>
                                                    </label>
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
        </div>
        <div class="btn--container justify-content-end mt-4">
            <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
            <button type="submit" class="btn btn--primary">{{translate('messages.Add_Module')}}</button>
        </div>
    </form>

</div>

@endsection

@push('script_2')
    <script src="{{asset('public/assets/admin/ckeditor/ckeditor.js')}}"></script>
    <script>
        "use strict";
    $('.module-change').on('click', function (){
        let id = $(this).val();
        modulChange(id)
    })
    function modulChange(id) {
        $.get({
            url: "{{url('/')}}/admin/module/type/?module_type=" + id,
            dataType: 'json',
            success: function(data) {
                if(data.data.description.length)
                {
                    $('#module_des_card').show();
                    $('#module_description').html(data.data.description);
                }
                else
                {
                    $('#module_des_card').hide();
                }
                if(id=='parcel')
                {
                    $('#module_theme').hide();
                    $('#zone_check').hide();
                }
                else{
                    $('#module_theme').show();
                    $('#zone_check').show();
                }
            },
        });
    }

    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#' + id).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function() {
        readURL(this, 'viewer');
    });

    $("#customFileEg2").change(function() {
        readURL(this, 'viewer2');
    });

    $(".lang_link").click(function(e) {
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');

        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);
        console.log(lang);
        $("#" + lang + "-form").removeClass('d-none');
    });

    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });

        $('#reset_btn').click(function(){
            $('#viewer').attr('src','{{asset('public/assets/admin/img/400x400/img2.jpg')}}');
            $('#viewer2').attr('src','{{asset('public/assets/admin/img/400x400/img2.jpg')}}');
        })
</script>
@endpush
