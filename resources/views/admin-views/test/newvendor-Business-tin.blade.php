@extends('layouts.admin.app')

@section('title',translate('messages.new_page'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">

    <!--- Add New Vendor > Add New Providers, Add New Store --->
    <div class="my-5">
        <div class="card p-20">
           <div class="mb-20">
                <h3 class="mb-1">Business TIN</h3>
                <p class="fz-12px mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
           </div>
           <div class="row g-3">
                <div class="col-md-8 col-xxl-9">
                    <div class="bg--secondary rounded p-20 h-100">
                        <div class="form-group">
                            <label class="input-label mb-2 d-block title-clr fw-normal" for="exampleFormControlInput1">Taxpayer Identification Number(TIN) <span class="text-danger">*</span></label>
                            <input type="number" name="identification_number" placeholder="Type your user name" value="" class="form-control" id="" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="input-label mb-2 d-block title-clr fw-normal" for="exampleFormControlInput1">Expire Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" value="" class="form-control" id="date_from" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xxl-3">
                    <div class="bg--secondary rounded p-20 __custom-upload-img">
                        <div class="mb-20">
                            <h4 class="mb-1 fz--14px">TIN Certificate</h4>
                            <p class="fz-12px mb-0">pdf, doc, jpg. File size : max 2 MB</p>
                        </div>
                        <label class="position-relative mb-0 d-inline-block image--border cursor-pointer w-100 h-100px max-width-300px">
                            <img class="h-165 aspect-ratio-1 rounded-10 display-none" id="logoImageViewer"
                                data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                alt="logo image"/>
                            <div class="upload-file__textbox p-2 h-100">
                                <img width="34" height="34" src="{{ asset('public/assets/admin/img/upload-cloud.png') }}" alt="" class="svg">
                                <span class="mt-2 text-center fw-normal fs-12">
                                    Select a file or <span class="fw-medium title-clr">Drag & Drop</span> here
                                </span>
                            </div>
                            <div class="icon-file-group outside">
                                <input type="file" name="logo" id="customFileEg1" class="custom-file-input" accept=".webp, .jpg, .png, .jpeg|image/*">
                            </div>
                        </label>
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>




@endsection

@push('script_2')

<script>
    // ---- file upload with textbox
    $(document).ready(function() {
            function handleImageUpload(inputSelector, imgViewerSelector, textBoxSelector, iconSelector) {
                const inputElement = $(inputSelector);

                // Handle input change for file selection
                inputElement.on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgViewerSelector).attr('src', e.target.result).show();
                            $(textBoxSelector).hide();
                            $(iconSelector).remove();
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
                            $(iconSelector).remove();
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Apply functionality to each upload element
            handleImageUpload(
                '#coverImageUpload',
                '#coverImageViewer',
                '#coverImageViewer ~ .upload-file__textbox',
                '#coverEditIcon'
            );

            handleImageUpload(
                '#customFileEg1',
                '#logoImageViewer',
                '#logoImageViewer ~ .upload-file__textbox',
                '#logoEditIcon'
            );
        });
</script>

@endpush
