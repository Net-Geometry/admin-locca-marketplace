@extends('layouts.admin.app')

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
                                {{ translate('messages.Update_Banner') }}
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.rental.banner.update', [$banner['id']])}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="__bg-FAFAFA p-4 radius-10 mb-4">
                                        @if ($language)
                                            <ul class="nav nav-tabs mb-3 border-0">
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link active" href="#"
                                                        id="default-link">{{ translate('messages.default') }}</a>
                                                </li>
                                                @foreach ($language as $lang)
                                                    <li class="nav-item">
                                                        <a class="nav-link lang_link" href="#"
                                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="lang_form" id="default-form">
                                                <div class="form-group mb-0">
                                                    <label class="input-label"
                                                        for="default_title">{{ translate('messages.title') }}
                                                        ({{ translate('Default') }})
                                                    </label>
                                                    <input type="text" name="title[]" id="default_title"
                                                        class="form-control" value="{{$banner?->getRawOriginal('title')}}"
                                                        placeholder="{{ translate('messages.new_banner') }}">
                                                </div>
                                                <input type="hidden" name="lang[]" value="default">
                                            </div>
                                            @foreach ($language as $lang)

                                            <?php
                                            if(count($banner['translations'])){
                                                $translate = [];
                                                foreach($banner['translations'] as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=="title"){
                                                        $translate[$lang]['title'] = $t->value;
                                                    }
                                                }
                                            }
                                        ?>



                                                <div class="d-none lang_form" id="{{ $lang }}-form">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label"
                                                            for="{{ $lang }}_title">{{ translate('messages.title') }}
                                                            ({{ strtoupper($lang) }})
                                                        </label>
                                                        <input type="text" name="title[]" id="{{ $lang }}_title"
                                                            class="form-control" value="{{$translate[$lang]['title']??''}}"
                                                            placeholder="{{ translate('messages.new_banner') }}">
                                                    </div>
                                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>

                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('Banner_Type') }}</label>
                                        <select name="banner_type" id="banner_type" class="custom-select js-select2-custom">
                                            <option  {{$banner->type == 'store_wise'? 'selected':'' }}  value="store_wise">{{ translate('Provider_Wise') }}</option>
                                            <option {{$banner->type == 'default'? 'selected':'' }}   value="default">{{ translate('messages.default') }}</option>
                                        </select>
                                    </div>


                                    <div class="form-group mb-0  {{$banner->type == 'store_wise'? '':'d-none' }}" id="store_wise">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{ translate('messages.provider') }}</label>
                                        <select name="store_id" id="store_id" class="js-data-example-ajax form-control"
                                            title="{{ translate('messages.Select_Provider') }}">
                                            @if($banner->type=='store_wise')
                                        @php($store = \App\Models\Store::where('id', $banner->data)->first(['id','name']))
                                            @if($store)
                                            <option value="{{$store->id}}" selected>{{$store->name}}</option>
                                            @endif
                                        @endif
                                        </select>
                                    </div>

                                    <div class="form-group mb-0 {{$banner->type !== 'store_wise'? '':'d-none' }}" id="default">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.default_link') }}({{ translate('messages.optional') }})</label>
                                        <input type="url" name="default_link" class="form-control" value="{{ $banner->default_link }}"
                                            placeholder="{{ translate('messages.default_link') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="h-100 d-flex flex-column justify-content-between">
                                        <div class="form-group">
                                            <label
                                                class="fs-16 text-title font-semibold  mb-0">{{ translate('messages.Banner_Image') }}</label>
                                            <p class="mb-20">{{ translate('JPG, JPEG, PNG Less Than 2MB') }} <span
                                                    class="font-weight-bold">({{ translate('Ratio 3:1') }})</span>
                                            </p>
                                            <div class="upload-file image-general">
                                                <a href="javascript:void(0);" class="remove-btn opacity-0 z-index-99">
                                                    <i class="tio-clear"></i>
                                                </a>
                                                <input type="file" name="image" class="upload-file__input single_file_input" value="{{ $banner['image_full_url'] }}" accept=".jpg, .jpeg, .png" title="" />
                                                <label class="upload-file-wrapper fullwidth">
                                                    <div class="upload-file-textbox text-center">
                                                        <img width="34" height="34" src="{{ asset('public/assets/admin/img/document-upload.svg') }}" alt="">
                                                        <h6 class="mt-2 font-semibold text-center">
                                                            <span>{{ translate('Click to upload') }}</span>
                                                            <br>
                                                            {{ translate('or drag and drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy" style="display: none;" src="{{ $banner['image_full_url'] }}" alt="">
                                                </label>
                                            </div>

                                        </div>
                                        <div class="btn--container justify-content-end">
                                            <button type="reset" id="reset_btn"
                                                class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                            <button type="submit"
                                                class="btn btn--primary">{{ translate('messages.submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";

        $(document).ready(function () {
            $('.single_file_input').on('change', function (event) {
                var file = event.target.files[0];
                var $card = $(event.target).closest('.upload-file');
                var $textbox = $card.find('.upload-file-textbox');
                var $imgElement = $card.find('.upload-file-img');
                var $removeBtn = $card.find('.remove-btn');

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $textbox.hide();
                        $imgElement.attr('src', e.target.result).show();
                        $removeBtn.css('opacity', 1);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('.upload-file').each(function () {
                var $card = $(this);
                var $textbox = $card.find('.upload-file-textbox');
                var $imgElement = $card.find('.upload-file-img');
                var $removeBtn = $card.find('.remove-btn');
                if ($imgElement.attr('src') && $imgElement.attr('src') !== window.location.href) {
                    $textbox.hide();
                    $imgElement.show();
                }
            });

           $('.remove-btn').click(function () {
                var $card = $(this).closest('.upload-file');
                $card.find('.single_file_input').val('');
                $card.find('.upload-file-img').attr('src', '{{ $banner['image_full_url'] }}');
                $(this).css('opacity', 0);
            });

            $('#reset_btn').click(function () {
                banner_type_change('{{$banner->type}}')
                @isset($store)
                $('#store_id').val('{{ $store?->id }}').trigger('change')
                @endisset
                $('#banner_type').val('{{ $banner?->type }}').trigger('change');
                var $cards = $('.upload-file');
                $cards.each(function () {
                    $(this).find('.single_file_input').val('');
                    $(this).find('.upload-file-img').attr('src', '{{ $banner['image_full_url'] }}');
                    $(this).find('.remove-btn').css('opacity', 0);
                });
            });
        });



        var module_id = {{ Config::get('module.current_module_id') }};

        $(document).on('ready', function() {
            banner_type_change('{{$banner->type}}');

            module_id = {{ Config::get('module.current_module_id') }};
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/store/get-stores',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            module_id: module_id
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);
                        $request.then(success);
                        $request.fail(failure);
                        return $request;
                    }
                }
            });
        });
        $("#banner_type").on("change", function () {
            let order_type = $(this).val();
            banner_type_change(order_type);
        }).trigger('change');
        function banner_type_change(order_type) {
            if (order_type == "item_wise") {
                $("#store_wise").hide();
                $("#item_wise").show();
                $("#default").hide();
            } else if (order_type == "store_wise") {
                $("#store_wise").removeClass("d-none").show();
                $("#item_wise").hide();
                $("#default").hide();
            } else if (order_type == "default") {
                $("#default").removeClass("d-none").show();
                $("#store_wise").hide();
                $("#item_wise").hide();
            } else {
                $("#item_wise").hide();
                $("#store_wise").hide();
                $("#default").hide();
            }
        }

    </script>
@endpush
