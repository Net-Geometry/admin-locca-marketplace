@extends('layouts.admin.app')

@section('title', translate('messages.Update Driver'))



@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/car-logo.png') }}" alt="">
                        </span>
                        <span>{{ translate('messages.Update Driver') }}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <form action="" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-title mb-1">
                                {{ translate('messages.User_Info') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="">
                                            {{ translate('messages.first_name') }}
                                        </label>
                                        <input type="text" name="first_name" id=""
                                               class="form-control"
                                               value="{{ $driver->first_name }}"
                                               placeholder="{{ translate('messages.Type your first name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="">
                                            {{ translate('messages.last_name') }}
                                        </label>
                                        <input type="text" name="last_name" id=""
                                               class="form-control"
                                               value="{{ $driver->last_name }}"
                                               placeholder="{{ translate('messages.Type your last name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="">
                                            {{ translate('messages.email') }}
                                        </label>
                                        <input type="email" name="email" id=""
                                               class="form-control"
                                               value="{{ $driver->email }}"
                                               placeholder="{{ translate('messages.Type your email address') }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="phone">{{ translate('messages.phone') }}</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                               placeholder="{{ translate('messages.Ex:') }} 017********" value="{{ $driver->phone }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column h-100">
                                        <label>{{translate('messages.deliveryman_image')}} <small class="text-danger">* ( {{translate('messages.ratio')}} 1:1 )</small></label>
                                        <div class="text-center py-3 my-auto">
                                            <img class="img--100 rounded onerror-image" id="viewer"
                                                 src="{{$driver['image_full_url'] }}"
                                                 data-onerror-image="{{asset('/public/assets/admin/img/admin.png')}}"
                                                 alt="delivery-man image"/>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose_file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-title mb-1">
                                {{ translate('messages.Identity_Info') }}
                            </h5>
                        </div>
                        <div class="card-body pb-2">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="">{{ translate('messages.Identity_Type') }}</label>
                                        <select name="identity_type" class="form-control js-select2-custom" required>
                                            <option value="" readonly="true" hidden="true"  > {{ translate('messages.select_identity_type') }}</option>
                                            <option value="passport" {{ $driver->identity_type == 'passport' ? 'selected' : '' }}>{{ translate('messages.passport') }}</option>
                                            <option value="driving_license" {{ $driver->identity_type == 'driving_license' ? 'selected' : '' }}>{{ translate('messages.driving_license') }} </option>
                                            <option value="nid" {{ $driver->identity_type == 'nid' ? 'selected' : '' }}>{{ translate('messages.nid') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="">{{ translate('messages.Identity_Number') }}</label>
                                        <input type="number" id="" name="identity_number" class="form-control"
                                               placeholder="Ex: 123654789512364" value="{{ $driver->identity_number }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row g-3">
                                        <div class="col-md-6 pb-0">
                                            <div class="row g-2">
                                                <div class="col-12 pb-0">
                                                    <div class="form-group mb-0">
                                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.identity_images')}}
                                                    </div>
                                                </div>
                                                @foreach($driver['identity_image_full_url'] as $img)
                                                    <div class="col-6 spartan_item_wrapper size--sm">
                                                        <img class="rounded border" src="{{ $img }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.update_identity_image')}}</label>
                                            <div>
                                                <div class="row g-2 mt-0" id="coba"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn"
                                class="btn btn--reset min-w-120px shadow-none">{{ translate('messages.reset') }}</button>
                        <button type="submit"
                                class="btn btn--primary min-w-120px shadow-none">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>


    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '100px',
                groupClassName: 'col-6 spartan_item_wrapper size--sm',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/document-upload.png')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        $(document).ready(function() {
            function handleImageUpload(inputSelector, imgViewerSelector, textBoxSelector) {
                const inputElement = $(inputSelector);

                inputElement.on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                        };
                        reader.readAsDataURL(file);
                    }
                });

                const dropZone = inputElement.closest('.image--border');

                dropZone.on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });

                dropZone.on('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });

                dropZone.on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const file = e.originalEvent.dataTransfer.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            handleImageUpload(
                '#coverImageUpload',
                '#coverImageViewer',
                '#coverImageViewer ~ .upload-file__textbox'
            );

            handleImageUpload(
                '#customFileEg1',
                '#logoImageViewer',
                '#logoImageViewer ~ .upload-file__textbox'
            );
        });

    </script>
@endpush
