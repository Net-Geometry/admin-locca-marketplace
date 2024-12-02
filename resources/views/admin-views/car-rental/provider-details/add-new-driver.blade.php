@extends('layouts.admin.app')

@section('title', translate('messages.Add New Driver'))



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
                        <span>{{ translate('messages.Add New Driver') }}
                    </h1></span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <form action="{{ route('admin.store.store') }}" method="post" enctype="multipart/form-data" class="js-validate"
            id="vendor_form">
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
                                        <input type="text" name="" id=""
                                            class="form-control"
                                            value=""
                                            placeholder="{{ translate('messages.Type your first name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">
                                            {{ translate('messages.last_name') }}
                                        </label>
                                        <input type="text" name="" id=""
                                            class="form-control"
                                            value=""
                                            placeholder="{{ translate('messages.Type your last name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">
                                            {{ translate('messages.email') }}
                                        </label>
                                        <input type="email" name="" id=""
                                            class="form-control"
                                            value=""
                                            placeholder="{{ translate('messages.Type your email address') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Vendor') }}</label>
                                            <select name="" id="" required
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.Select Vendor') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.Select Vendor') }}</option>
                                                <option value="test">test</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="phone">{{ translate('messages.phone') }}</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            placeholder="{{ translate('messages.Ex:') }} 017********" value="123456789"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="__custom-upload-img text-center">
                                        <label class="form-label font-semibold mb-1">
                                            {{ translate('Profile Image') }}
                                        </label>
                                        <p class="fs-12 mb-20">
                                            JPG, JPEG, PNG Less Than 1MB 
                                            <strong class="font-semibold">(Ratio 1:1)</strong>
                                        </p>
                                        <label
                                            class="position-relative d-inline-block image--border cursor-pointer w-100 h-180 max-w-180">
                                            <img  class="h-180 aspect-ratio-1 radius-10" id="logoImageViewer"
                                                data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                                src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                alt="logo image" style="display: none" />
                                            <div class="upload-file__textbox p-2 h-100">
                                                <img width="34" height="34"
                                                    src="{{ asset('public/assets/admin/img/document-upload.png') }}"
                                                    alt="" class="svg">
                                                <h6 class="mt-2 text-center font-semibold fs-12">
                                                    <span
                                                        class="text-info">{{ translate('messages.Click to upload') }}</span>
                                                    <br>
                                                    {{ translate('messages.or drag and drop') }}
                                                </h6>
                                            </div>
                                            <div class="icon-file-group">
                                                <input type="file" name="logo" id="customFileEg1"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            </div>
                                        </label>
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
                                        <label class="input-label"
                                            for="">{{ translate('messages.Identity_Type') }}</label>
                                            <select name="" id="" required
                                            class="form-control js-select2-custom"
                                            data-placeholder="{{ translate('messages.Select Driver Identity type') }}">
                                            <option value="" selected disabled>
                                                {{ translate('messages.Select Driver Identity type') }}</option>
                                                <option value="test">test</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="">{{ translate('messages.Identity_Number') }}</label>
                                        <input type="number" id="" name="" class="form-control"
                                            placeholder="Ex: 123654789512364" value=""
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div>
                                        <label class="form-label font-semibold mb-1">
                                            {{ translate('Profile Image') }}
                                        </label>
                                        <p class="fs-12 mb-0">
                                            JPG, JPEG, PNG Less Than 1MB 
                                            <strong class="font-semibold">(Ratio 1:1)</strong>
                                        </p>
                                    </div>
                                    <div class="d-flex pt-20 pb-2 overflow-x-auto">
                                        <div class="d-flex gap-3 flex-shrink-0" id="image_container">
                                            <div class="upload-file text-wrapper h--100px w--200px flex-shrink-0"
                                                id="image_upload_wrapper">
                                                <input type="file" name="files[]"
                                                    class="upload-file__input multiple_image_input" accept=".jpg,.jpeg,.png"
                                                    required multiple>
                                                <div
                                                    class="upload-file__img d-flex gap-0 justify-content-center align-items-center h-100 max-w-300px p-0">
                                                    <div class="upload-file__textbox">
                                                        <img width="34" height="34"
                                                            src="{{ asset('public/assets/admin/img/document-upload.png') }}"
                                                            alt="" class="svg">
                                                        <h6 class="mt-2 font-semibold">
                                                            <span class="text-info">{{ translate('Click to upload') }}</span><br>
                                                            {{ translate('or drag and drop') }}
                                                        </h6>
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
    <script>
        // ---- image upload with textbox 
        $(document).ready(function() {
            function handleImageUpload(inputSelector, imgViewerSelector, textBoxSelector) {
                const inputElement = $(inputSelector);

                // Handle input change for file selection
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

                // Handle drag-and-drop functionality
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

            // Apply functionality to each upload element
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
        // ---- image upload with textbox ends

        // ----- mutiple image upload 
        document.addEventListener("DOMContentLoaded", function() {
            const MAX_FILES = 5;
            const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png"];
            const MAX_FILE_SIZE_MB = 1; // Set maximum file size in MB
            const imageContainer = document.getElementById("image_container");
            const uploadWrapper = document.getElementById("image_upload_wrapper");

            document.querySelector('.multiple_image_input').addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                const currentFiles = imageContainer.querySelectorAll(".image-single").length;

                if (currentFiles + files.length > MAX_FILES) {
                    toastr.error('{{ translate('You can upload a maximum of') }} ' + MAX_FILES +
                        ' {{ translate('files.') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    return;
                }

                files.forEach(file => {
                    // Validate file type
                    if (!ALLOWED_FILE_TYPES.includes(file.type)) {
                        toastr.error(
                            '{{ translate('please_only_input_png_or_jpg_type_file') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        return;
                    }

                    // Validate file size
                    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                        toastr.error('{{ translate('file_size_too_big') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        return;
                    }

                    // Create file preview
                    const fileURL = URL.createObjectURL(file);

                    const imageSingle = document.createElement("div");
                    imageSingle.className = "image-single h-100 max-w-200px p-0";
                    imageSingle.innerHTML = `
                        <a href="javascript:void(0);" class="remove-btn" onclick="removeImage(event, this)">
                            <i class="tio-clear"></i>
                        </a>
                        <img class="img--vertical-2 rounded-10" width="200" height="100" loading="lazy" src="${fileURL}" alt="">
                    `;

                    imageContainer.appendChild(imageSingle);

                    // Success notification
                    toastr.success('{{ translate('image_added') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                });

                toggleUploadWrapper();

                // Clear file input after upload
                event.target.value = "";
            });

            window.removeImage = function(event, element) {
                event.stopPropagation();
                const imageSingle = element.closest(".image-single");
                imageSingle.remove();
                toggleUploadWrapper();
            };

            function toggleUploadWrapper() {
                const currentFiles = imageContainer.querySelectorAll(".image-single").length;
                uploadWrapper.style.display = currentFiles >= MAX_FILES ? "none" : "block";
            }
        });
        // ----- mutiple image upload ends

    </script>
@endpush
