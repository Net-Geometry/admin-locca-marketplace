@extends('layouts.vendor.app')

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
                                               value=""
                                               placeholder="{{ translate('messages.Type your first name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="">
                                            {{ translate('messages.last_name') }}
                                        </label>
                                        <input type="text" name="last_name" id=""
                                               class="form-control"
                                               value=""
                                               placeholder="{{ translate('messages.Type your last name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                               for="">
                                            {{ translate('messages.email') }}
                                        </label>
                                        <input type="email" name="email" id=""
                                               class="form-control"
                                               value=""
                                               placeholder="{{ translate('messages.Type your email address') }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="phone">{{ translate('messages.phone') }}</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                               placeholder="{{ translate('messages.Ex:') }} 017********"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="text-center">
                                        <label class="text--title fs-16 font-semibold mb-1">
                                            {{ translate('Profile_Image') }}
                                        </label>
                                        <div class="mb-20">
                                            <p class="fs-12">
                                                {{ translate('JPG, JPEG, PNG Less Than 1MB') }} <strong class="font-semibold">({{ translate('Ratio 1:1') }})</strong>
                                            </p>
                                        </div>
                                        <div class="upload-file image-general d-inline-block w-auto">
                                            <a href="javascript:void(0);" class="remove-btn opacity-0 z-index-99">
                                                <i class="tio-clear"></i>
                                            </a>
                                            <input type="file" name="image" class="upload-file__input single_file_input"
                                                accept=".webp, .jpg, .jpeg, .png" data-max-size="1" required>
                                            <label
                                                class="upload-file-wrapper w--180px">
                                                <div class="upload-file-textbox text-center">
                                                    <img width="34" height="34" src="{{ asset('public/assets/admin/img/document-upload.svg') }}" alt="">
                                                    <h6 class="mt-2 font-semibold text-center">
                                                        <span>{{ translate('Click to upload') }}</span>
                                                        <br>
                                                        {{ translate('or drag and drop') }}
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" height="180" width="180" loading="lazy" style="display: none;" src="" alt="">
                                            </label>
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
                                        <label class="input-label"
                                               for="">{{ translate('messages.Identity_Type') }}</label>
                                        <select name="identity_type" class="form-control js-select2-custom" required>
                                            <option value="" readonly="true" hidden="true"  > {{ translate('messages.select_identity_type') }}</option>
                                            <option value="passport">{{ translate('messages.passport') }}</option>
                                            <option value="driving_license">{{ translate('messages.driving_license') }} </option>
                                            <option value="nid">{{ translate('messages.nid') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="">{{ translate('messages.Identity_Number') }}</label>
                                        <input type="text" id="" name="identity_number" class="form-control"
                                               placeholder="Ex: 123654789512364" value=""
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div>
                                        <label class="form-label font-semibold mb-1">
                                            {{ translate('Identity Image') }}
                                        </label>
                                        <p class="fs-12 mb-0">
                                            JPG, JPEG, PNG Less Than 1MB
                                            <strong class="font-semibold">(Ratio 2:1)</strong>
                                        </p>
                                    </div>
                                    <div class="d-flex pt-20 pb-2 overflow-x-auto">
                                        {{-- <div>
                                            <div class="row" id="multiImg"></div>
                                        </div> --}}
                                        {{-- @php
                                            $uploadedImages = json_decode($request->input('uploaded_images'), true);
                                        @endphp --}}
                                       <div class="d-flex gap-3 flex-shrink-0" id="image_container">
                                           <div class="upload-file text-wrapper h--100px w--200px flex-shrink-0"
                                                id="image_upload_wrapper">
                                               <input type="file" name="identity_image[]"
                                                      class="upload-file__input multiple_image_input" accept=".webp, .jpg,.jpeg,.png" multiple required>
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
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        // $(function() {
        //     $("#multiImg").spartanMultiImagePicker({
        //         fieldName: 'identity_image[]',
        //         maxCount: 5,
        //         rowHeight: '120px',
        //         groupClassName: 'col-6 spartan_item_wrapper size--md',
        //         maxFileSize: '',
        //         placeholderImage: {
        //             image: '{{ asset('public/assets/admin/img/document-upload.png') }}',
        //             width: '100%'
        //         },
        //         dropFileLabel: "Drop Here",
        //         onAddRow: function(index, file) {

        //         },
        //         onRenderedPreview: function(index) {

        //         },
        //         onRemoveRow: function(index) {

        //         },
        //         onExtensionErr: function(index, file) {
        //             toastr.error(
        //                 '{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
        //                     CloseButton: true,
        //                     ProgressBar: true
        //                 });
        //         },
        //         onSizeErr: function(index, file) {
        //             toastr.error('{{ translate('messages.file_size_too_big') }}', {
        //                 CloseButton: true,
        //                 ProgressBar: true
        //             });
        //         }
        //     });
        // });

        // ---- single image upload starts
        $(document).ready(function () {
            const MAX_FILE_SIZE_MB = 1; // Maximum file size in MB
            const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

            // Handle file input change
            $('.single_file_input').on('change', function (event) {
                var file = event.target.files[0];

                // Validate file type
                if (!ALLOWED_FILE_TYPES.includes(file.type)) {
                    toastr.error('{{ translate('please_only_input_png_or_jpg_type_file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $(this).val(''); // Clear the input
                    return;
                }

                // Validate file size
                if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                    toastr.error('{{ translate('file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $(this).val(''); // Clear the input
                    return;
                }

                // Continue with existing file preview logic
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var $card = $(event.target).closest('.upload-file');
                        $card.find('.upload-file-textbox').hide();
                        $card.find('.upload-file-img').attr('src', e.target.result).show();
                        $card.find('.remove-btn').css('opacity', 1);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle remove button click
            $('.remove-btn').click(function () {
                var $card = $(this).closest('.upload-file');
                $card.find('.single_file_input').val('');
                $card.find('.upload-file-textbox').show();
                $card.find('.upload-file-img').hide().attr('src', '');
                $(this).css('opacity', 0);
            });

            // Handle reset button click
            $('#reset_btn').click(function () {
                var $cards = $('.upload-file');
                $cards.each(function () {
                    $(this).find('.single_file_input').val('');
                    $(this).find('.upload-file-textbox').show();
                    $(this).find('.upload-file-img').hide().attr('src', '');
                    $(this).find('.remove-btn').css('opacity', 0);
                });
            });
        });
         // ---- single image upload ends

        // ----- mutiple image upload
        $(document).ready(function () {
            const MAX_FILE_SIZE_MB = 1; // Maximum file size in MB
            const MAX_FILES = 5;
            const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
            const imageContainer = document.getElementById("image_container");
            const uploadWrapper = document.getElementById("image_upload_wrapper");
            const inputElement = document.querySelector('.multiple_image_input');
            const fileSet = new Set(); // To keep track of files

            inputElement.addEventListener('change', function (event) {
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
                        toastr.error('{{ translate('please_only_input_png_or_jpg_type_file') }}', {
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

                    // Add to the file set and create preview
                    if (!fileSet.has(file.name)) {
                        fileSet.add(file.name);

                        const fileURL = URL.createObjectURL(file);
                        const imageSingle = document.createElement("div");
                        imageSingle.className = "image-single h-100 max-w-200px p-0";
                        imageSingle.innerHTML = `
                            <a href="javascript:void(0);" class="remove-btn" onclick="removeImage(event, this, '${file.name}')">
                                <i class="tio-clear"></i>
                            </a>
                            <img class="img--vertical-2 rounded-10" width="200" height="100" loading="lazy" src="${fileURL}" alt="">
                        `;
                        imageContainer.appendChild(imageSingle);
                    }
                });

                toggleUploadWrapper();
            });

            window.removeImage = function (event, element, fileName) {
                event.stopPropagation();
                const imageSingle = element.closest(".image-single");
                imageSingle.remove();
                fileSet.delete(fileName); // Remove the file from the set
                toggleUploadWrapper();
            };

            function toggleUploadWrapper() {
                const currentFiles = imageContainer.querySelectorAll(".image-single").length;
                uploadWrapper.style.display = currentFiles >= 5 ? "none" : "block";
            }
           // Handle reset button click
           $('#reset_btn').click(function () {
                // Select and remove only the uploaded image elements
                const uploadedImages = imageContainer.querySelectorAll(".image-single");
                uploadedImages.forEach(image => image.remove());

                // Clear the file set
                fileSet.clear();

                // Ensure the upload wrapper is visible
                uploadWrapper.style.display = "block";
            });

        });
        // ----- mutiple image upload ends

    </script>
@endpush
