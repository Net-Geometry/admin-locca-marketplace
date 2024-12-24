@extends('layouts.vendor.app')

@section('title', translate('messages.banner'))

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
                        <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label font-semibold" for="">Title
                                            (Default)
                                        </label>
                                        <input type="text" name="title" id="" class="form-control"
                                               value="{{ $banner->title }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label font-semibold" for="">
                                            Redirection URL / Link
                                        </label>
                                        <input type="text" name="default_link" id="" class="form-control"
                                               value="{{ $banner->default_link }}">
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
                                                    <img class="upload-file-img" loading="lazy" style="" src="{{ $banner['image_full_url'] }}"
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
        </div>
    </div>

@endsection

@push('script_2')
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
        });
    </script>
@endpush
