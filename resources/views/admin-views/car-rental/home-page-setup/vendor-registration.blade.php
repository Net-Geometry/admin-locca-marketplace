@extends('layouts.admin.app')

@section('title', translate('messages.Home_Page_Setup'))

@section('content')
<div class="content container-fluid">
     <!-- Page Header -->
     <div class="page-header">
        <div class="d-flex justify-content-between flex-wrap gap-3 mb-3">
            <div>
                <h1 class="page-header-title text-break">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                    </span>
                    <span>{{ translate('messages.Home_Page_Setup') }}
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
                        href="javascript:">{{ translate('messages.Download App') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-capitalize text-title active"
                        href="javascript:">{{ translate('messages.Vendors Registration') }}</a>
                </li>
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
    <!-- End Page Header -->

    <form action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card mb-20">
            <div class="card-body">
                @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                @php($language = $language->value ?? null)
                @php($defaultLang = 'en')
                <div class="row gy-3">
                    <div class="col-lg-6">
                        @if ($language)
                        <ul class="nav nav-tabs border-0 mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link active" href="#"
                                    id="default-link">{{ translate('Default') }}</a>
                            </li>
                            @foreach (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link" href="#"
                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                        @if ($language)
                            <div class="lang_form" id="default-form">
                                <div class="form-group mb-20">
                                    <label class="input-label font-semibold"
                                        for="default_name">{{ translate('messages.title') }}
                                        ({{ translate('messages.Default') }})
                                    </label>
                                        <div class="character-count">
                                            <input type="text" name="name[]" id="default_name"
                                            class="form-control character-count-field h--45px"
                                            value="{{ translate('messages.Its much easier From Apps') }}"
                                            placeholder="{{ translate('messages.type_title') }}" maxlength="30"
                                            data-max-character="30" required>
                                            <span class="d-flex justify-content-end">{{ translate('26/30') }}</span>
                                        </div>
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                <div class="form-group mb-20">
                                    <label class="input-label font-semibold"
                                        for="exampleFormControlInput1">{{ translate('messages.subtitle') }}
                                        ({{ translate('messages.default') }})</label>
                                    <div class="character-count">
                                        <textarea type="text" name="subtitle[]" placeholder="{{ translate('messages.type_subtitle') }}"
                                        class="form-control character-count-field" maxlength="110"
                                        data-max-character="110">Enjoy Your Ride! & travel your destination everyday.</textarea>
                                        <span class="d-flex justify-content-end">{{ translate('52/110') }}</span>
                                    </div>
                                    
                                </div>
                                <div class="form-group mb-0">
                                    <label class="input-label font-semibold"
                                        for="exampleFormControlInput1">{{ translate('messages.button_title') }}
                                        ({{ translate('messages.default') }})
                                    </label>
                                        <div class="character-count">
                                            <textarea type="text" name="button_title[]" placeholder="{{ translate('messages.type_button_title') }}"
                                            class="form-control character-count-field h--45px" maxlength="20"
                                            data-max-character="20">Register as Vendor</textarea>
                                            <span class="d-flex justify-content-end">{{ translate('18/20') }}</span>
                                        </div>
                                </div>
                            </div>
                            @foreach (json_decode($language) as $lang)
                                <div class="d-none lang_form" id="{{ $lang }}-form">
                                    <div class="form-group mb-20">
                                        <label class="input-label font-semibold"
                                            for="{{ $lang }}_name">{{ translate('messages.title') }}
                                            ({{ strtoupper($lang) }})
                                        </label>
                                        <div class="character-count">
                                            <input type="text" name="name[]" id="{{ $lang }}_name"
                                                class="form-control character-count-field h--45px"
                                                maxlength="30"
                                                data-max-character="30"
                                                placeholder="{{ translate('messages.type_title') }}">
                                                <span class="d-flex justify-content-end">{{ translate('26/30') }}</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                    <div class="form-group mb-20">
                                        <label class="input-label font-semibold"
                                            for="exampleFormControlInput1">{{ translate('messages.subtitle') }}
                                            ({{ strtoupper($lang) }})</label>
                                            <div class="character-count">
                                                <textarea type="text" name="subtitle[]" placeholder="{{ translate('messages.type_subtitle') }}"
                                                    class="form-control character-count-field" maxlength="110"
                                                    data-max-character="110"></textarea>
                                                <span class="d-flex justify-content-end">{{ translate('52/110') }}</span>
                                            </div>
                                        
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="input-label font-semibold"
                                            for="exampleFormControlInput1">{{ translate('messages.button_title') }}
                                            ({{ strtoupper($lang) }})</label>
                                            <div class="character-count">
                                                <textarea type="text" name="button_title[]" placeholder="{{ translate('messages.type_button_title') }}"
                                                class="form-control character-count-field h--45px" maxlength="20"
                                                data-max-character="20"></textarea>
                                                <span class="d-flex justify-content-end">{{ translate('18/20') }}</span>
                                            </div>
                                        
                                    </div>
                                </div>
                            @endforeach
                        @else
                        <div id="default-form">
                            <div class="form-group mb-20">
                                <label class="input-label font-semibold"
                                    for="exampleFormControlInput1">{{ translate('messages.title') }}
                                    ({{ translate('messages.default') }})</label>
                                
                                    <div class="character-count">
                                        <input type="text" name="name[]" 
                                        class="form-control character-count-field h--45px"
                                        maxlength="30"
                                        data-max-character="30"
                                        placeholder="{{ translate('messages.type_title') }}" required>
                                        <span class="d-flex justify-content-end">{{ translate('26/30') }}</span>
                                    </div>
                            </div>
                            <input type="hidden" name="lang[]" value="default">
                            <div class="form-group mb-0">
                                <label class="input-label font-semibold"
                                    for="exampleFormControlInput1">{{ translate('messages.subtitle') }}
                                </label>
                                <div class="character-count">
                                    <textarea type="text" name="subtitle[]" placeholder="{{ translate('messages.type_subtitle') }}"
                                        class="form-control character-count-field" maxlength="110"
                                        data-max-character="110"></textarea>
                                    <span class="d-flex justify-content-end">{{ translate('52/110') }}</span>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label class="input-label font-semibold"
                                    for="exampleFormControlInput1">{{ translate('messages.subtitle') }}
                                </label>
                                <div class="character-count">
                                    <textarea type="text" name="button_title[]" placeholder="{{ translate('messages.type_button_title') }}"
                                    class="form-control character-count-field h--45px" maxlength="20"
                                    data-max-character="20"></textarea>
                                    <span class="d-flex justify-content-end">{{ translate('18/20') }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div class="text-center">
                                <label class="text--title fs-16 font-semibold mb-1">
                                    {{ translate('Image') }}
                                </label>
                                <div class="mb-20">
                                    <p class="fs-12">
                                        JPG, JPEG, PNG Less Than 1MB <strong class="font-semibold">(Ratio 3:2)</strong>
                                    </p>
                                </div>
                                <div class="upload-file text-wrapper">
                                    <input type="file" name=""
                                        class="upload-file__input single_file_input" accept=".jpg, .jpeg, .png"
                                        required>
                                    <div
                                        class="upload-file__img d-flex justify-content-center align-items-center height-200px max-w-300px m-auto p-0">
                                        <div class="upload-file__textbox text-center">
                                            <img width="34" height="34"
                                                src="{{ asset('public/assets/admin/img/document-upload.png') }}"
                                                alt="" class="svg">
                                            <h6 class="mt-2 font-semibold">
                                                <span class="text-info">{{ translate('Click to upload') }}</span>
                                                <br>
                                                {{ translate('or drag and drop') }}
                                            </h6>
                                        </div>
                                        <img class="upload-file__img__img border--dashed aspect-3-2" height="200"
                                            loading="lazy" style="display: none;" alt="">
                                    </div>
                                </div>
    
                            </div>
                            <div class="btn--container justify-content-end mt-5">
                                <button type="reset" id="reset_btn"
                                    class="btn btn--reset min-w-120px">{{ translate('messages.reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary min-w-120px">{{ translate('messages.Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script_2')
<script>
    // Get all upload-file input elements
    document.querySelectorAll('.single_file_input').forEach(function(input) {
        input.addEventListener('change', function(event) {
            var file = event.target.files[0];
            var card = event.target.closest('.upload-file');
            var textbox = card.querySelector('.upload-file__textbox');
            var imgElement = card.querySelector('.upload-file__img__img');

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    textbox.style.display = 'none';
                    imgElement.src = e.target.result;
                    imgElement.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
