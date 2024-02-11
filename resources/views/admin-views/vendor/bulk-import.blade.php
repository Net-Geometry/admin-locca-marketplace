@extends('layouts.admin.app')

@section('title',translate('Store Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/resturant.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('messages.stores_bulk_import')}}
                </span>
            </h1>
        </div>
        <!-- Content Row -->
        <div class="card">
            <div class="card-body">
                <div class="export-steps-2">
                    <div class="row g-4">
                        <div class="col-sm-6 col-lg-4">
                            <div class="export-steps-item-2 h-100">
                                <div class="top">
                                    <div>
                                        <h3 class="font-regular">{{translate('Step 1')}}</h3>
                                        <div>
                                            {{translate('Download_Excel_File')}}
                                        </div>
                                    </div>
                                    <img src="{{asset('/public/assets/admin/img/bulk-import-1.png')}}" alt="">
                                </div>
                                <h4>{{ translate('Instruction') }}</h4>
                                <ul class="m-0 pl-4">
                                    <li>
                                        {{ translate('Download_the_format_file_and_fill_it_with_proper_data.') }}
                                    </li>
                                    <li>
                                        {{ translate('You_can_download_the_example_file_to_understand_how_the_data_must_be_filled.') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="export-steps-item-2 h-100">
                                <div class="top">
                                    <div>
                                        <h3 class="font-regular">{{translate('Step 2')}}</h3>
                                        <div>
                                            {{translate('Match_Spread_sheet_data_according_to_instruction')}}
                                        </div>
                                    </div>
                                    <img src="{{asset('/public/assets/admin/img/bulk-import-2.png')}}" alt="">
                                </div>
                                <h4>{{ translate('Instruction') }}</h4>
                                <ul class="m-0 pl-4">
                                    <li>
                                        {{ translate('Once_you_have_downloaded_and_filled_the_format_file,_upload_it_in_the_form_below_and_submit.Make_sure_the_phone_numbers_and_email_addresses_are_unique') }}
                                    </li>
                                    <li>
                                        {{ translate('You can get module id and  zone id from their list, please input the right ids.')}}
                                    </li>
                                    <li>
                                        {{ translate('For delivery time the format is "from-to type" for example: "30-40 min". Also you can use days or hours as type. Please be carefull about this format or leave this field empty.') }}
                                    </li>
                                    <li>
                                        {{ translate('Latitude_must_be_a_number_between_-90_to_90_and_Longitude_must_a_number_between_-180_to_180._Otherwise_it_will_create_server_error') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="export-steps-item-2 h-100">
                                <div class="top">
                                    <div>
                                        <h3 class="font-regular">{{translate('Step 3')}}</h3>
                                        <div>
                                            {{translate('Validate data and complete import')}}
                                        </div>
                                    </div>
                                    <img src="{{asset('/public/assets/admin/img/bulk-import-3.png')}}" alt="">
                                </div>
                                  <h4>{{ translate('Instruction') }}</h4>
                                <ul class="m-0 pl-4">
                                    <li>
                                        {{ translate('Have_to_upload_excel_file') }}
                                    </li>
                                    <li>
                                       {{ translate('After_uploading_stores_you_need_to_edit_them_and_set_stores`s_logo_and_cover.`s_path')}}
                                    </li>
                                    <li>
                                       {{ translate('You_can_upload_your_store_images_in_store_folder_from_gallery,_and_copy_image`s_path') }}
                                    </li>
                                    <li>
                                       {{ translate('Default_password_for_store_is_12345678.') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center pb-4">
                    <h3 class="mb-3 export--template-title font-regular">{{translate('download_spreadsheet_template')}}</h3>
                    <div class="btn--container justify-content-center export--template-btns">

                        <a href="{{asset('public/assets/stores_bulk_format.xlsx')}}" download="" class="btn btn--primary btn-outline-primary">{{ translate('Template with Existing Data') }}</a>
                        <a href="{{asset('public/assets/stores_bulk_format_nodata.xlsx')}}" download="" class="btn btn--primary">{{ translate('Template without Data') }}</a>

                    </div>
                </div>
            </div>
        </div>



        <form class="product-form" id="import_form" action="{{route('admin.store.bulk-import')}}" method="POST"
        enctype="multipart/form-data">
            @csrf

        <input type="hidden" name="button" id="btn_value">
        <div class="card mt-2 rest-part">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <h5 class="text-capitalize mb-3">{{ translate('Select_Data_Upload_type') }}</h5>
                        <div class="module-radio-group border rounded">
                            <label class="form-check form--check">
                                <input class="form-check-input "   value="import" type="radio" name="upload_type" checked>
                                <span class="form-check-label py-20">
                                    {{ translate('Upload_New_Data') }}
                                </span>
                            </label>
                            <label class="form-check form--check">
                                <input class="form-check-input " value="update" type="radio" name="upload_type">
                                <span class="form-check-label py-20">
                                    {{ translate('Update_Existing_Data') }}
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="text-capitalize mb-3">{{ translate('Import_Stores_file') }}</h5>
                        <div class="uploadDnD">
                            <div class="form-group inputDnD input_image input_image_edit position-relative">
                                <div class="upload-text">
                                    <div>
                                        <img src="{{asset('/public/assets/admin/img/bulk-import-3.png')}}" alt="">
                                    </div>
                                    <div class="filename">{{translate('Must_be_Excel_files_using_our_Excel_template_above')}}</div>
                                </div>
                                <input type="file" name="products_file" class="form-control-file text--primary font-weight-bold action-upload-section-dot-area" id="products_file">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="button" class="btn btn--primary update_or_import">{{translate('messages.Upload')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>


    </div>
@endsection

@push('script_2')
    <script>
        $('#reset_btn').click(function(){
            $('#bulk__import').val(null);
        })
    </script>
        <script>


    $(document).on("click", ".update_or_import", function(e){
    e.preventDefault();
    let upload_type = $('input[name="upload_type"]:checked').val();
    myFunction(upload_type)
});

$(".action-upload-section-dot-area").on("change", function () {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = () => {
                let imgName = this.files[0].name;
                $(this).closest(".uploadDnD").find('.filename').text(imgName);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    function myFunction(data) {
        Swal.fire({
        title: '{{ translate('Are you sure?') }}' ,
        text: "{{ translate('You_want_to_') }}" +data,
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#FC6A57',
        cancelButtonText: '{{translate('messages.no')}}',
        confirmButtonText: '{{translate('messages.yes')}}',
        reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#btn_value').val(data);
                $("#import_form").submit();
            }
        })
    }
        </script>
@endpush
