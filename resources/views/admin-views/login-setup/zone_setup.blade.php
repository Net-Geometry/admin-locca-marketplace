@extends('layouts.admin.app')

@section('title',translate('available_zone_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap align-items-center justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/app.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('available_zone_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        @include('admin-views.business-settings.partials.nav-menu')
        <form id="zone-setup-form">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            To view a list of all active zones on your <a href="" class="text-primary">Admin Landing</a> Page, <br class="d-none d-md-inline-block"> Enable the 'Available Zones' feature
                        </div>
                        <div class="col-sm-6">
                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                <span class="pr-1 d-flex align-items-center switch--label">
                                    <span class="line--limit-1 text-primary">
                                        Available Zone
                                    </span>
                                </span>
                                <input type="checkbox" class="status toggle-switch-input" checked>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs border-0 mb-3">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active" href="#" id="default-link">Default</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                                </li>
                            </ul>
                            <div class="lang_form" id="default-form">
                                <div class="form-group">
                                    <label class="input-label" for="default_name">Name
                                        ( Default) <span class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right" data-original-title="Required."> *
                                        </span>

                                    </label>
                                    <input type="text" name="name[]" id="default_name" class="form-control" placeholder="New item">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">Short description (Default)<span class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right" data-original-title="Required."> *
                                        </span></label>
                                    <textarea type="text" name="description[]" class="form-control min-h-90px ckeditor"></textarea>
                                </div>
                            </div>
                            <div class="lang_form d-none" id="en-form">
                                <div class="form-group">
                                    <label class="input-label" for="default_name">Name
                                        (En) <span class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right" data-original-title="Required."> *
                                        </span>

                                    </label>
                                    <input type="text" name="name[]" id="default_name" class="form-control" placeholder="New item">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                <div class="form-group mb-0">
                                    <label class="input-label" for="exampleFormControlInput1">Short description (En)<span class="form-label-secondary text-danger" data-toggle="tooltip" data-placement="right" data-original-title="Required."> *
                                        </span></label>
                                    <textarea type="text" name="description[]" class="form-control min-h-90px ckeditor"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div>
                                <div class="d-flex justify-content-center">
                                    <label class="text-dark d-block mb-4">
                                            Related Image
                                        <small class="text-danger">* ( Ratio 1:1 )</small>
                                    </label>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <label class="text-center position-relative">
                                        <img class="img--110 min-height-170px min-width-170px onerror-image image--border" id="viewer"
                                        data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                            src="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                            alt="logo image" />
                                        <div class="icon-file-group">
                                            <div class="icon-file">
                                                <i class="tio-edit"></i>
                                                <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card shadow-none border-0 bg-soft-danger">
                        <div class="card-body d-flex">
                            <i class="tio-info-outined text-danger mr-1 mt-1"></i>
                            <p class="fs-15 text-dark m-0">
                                <strong>Note:</strong> Customize the section by adding a title, short description, and images in the <a href="" class="text-primary">Zone Setup</a> section. All created zones will be automatically displayed on the <a href="" class="text-primary">Admin Landing</a> Page. The zones will be based on the Zone Display Name.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <button class="btn btn--reset" type="reset">{{translate('reset')}}</button>
                            <button class="btn btn--primary" type="submit">{{translate('Save Information')}}</button>
                        </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
<script>
    // Form on reset
    const prevImage = $('#viewer').attr('src');
    $('#zone-setup-form').on('reset', function(){
        $('#customFileEg1').val(null);
        $('#viewer').attr('src', prevImage);
    })

    function readURL(input, viewer) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function (e) {
                $('#'+viewer).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function () {
        readURL(this, 'viewer');
    });
</script>
@endpush
