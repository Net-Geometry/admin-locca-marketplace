@extends('layouts.admin.app')

@section('title', translate('messages.update Provider'))



@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-20">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="page-header-title text-break">
                        <span class="page-header-icon">
                            <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--22" alt="">
                        </span>
                        <span>{{ $store->name }}
                    </h1></span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <form action="" method="post" enctype="multipart/form-data" id="providerFormSubmit">
            @csrf
            <div id="businessPlan">
                <div class="custom-timeline d-flex flex-wrap gap-40px text-title mb-2">
                    <h4 class="single text-primary checked"><span class="count-checked">1</span>{{ translate('messages.Business Basic Setup') }}</h4>
                    <h4 class="single font-semibold"><span class="count btn-primary">2</span>{{ translate('messages.Business Plan Setup') }}</h4>
                </div>
                <div class="row g-2">
                    <div class="col-lg-12">
                        <div class="card mt-3">
                            <div class="card-header">
                                <div>
                                    <h5 class="text-title mb-1">
                                        {{ translate('messages.Update Business Plan') }}
                                    </h5>
                                    {{-- <p class="fs-12 mb-0">
                                        {{ translate('messages.Provider Logo & Covers') }}
                                    </p> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <label class="business-plan-card-wrapper">
                                            <input type="radio" name="business_plan" class="business-plan-radio" value="commission-base" {{ $store->store_business_model == 'commission' ? 'checked' : ''}}/>
                                            <div class="business-plan-card">
                                                <h4 class="fs-16 title text-title mb-10px opacity-70">
                                                    {{ translate('messages.Commission Base') }}
                                                </h4>
                                                <p class="fs-14 text-title opacity-70 mb-0">
                                                    {{ translate('messages.You have to give a certain percentage of commission to admin for every Trip request.') }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="business-plan-card-wrapper">
                                            <input type="radio" name="business_plan" class="business-plan-radio" value="subscription-base" {{ $store->store_business_model == 'subscription' ? 'checked' : ''}}/>
                                            <div class="business-plan-card">
                                                <h4 class="fs-16 title text-title mb-10px opacity-70">
                                                    {{ translate('messages.Subscription Base') }}
                                                </h4>
                                                <p class="fs-14 text-title opacity-70 mb-0">
                                                    {{ translate('messages.You have to pay certain amount in every month/year to admin as subscription fee.') }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-lg-12 mt-20 d-none" id="subscription-plan">
                                        <div>
                                            <div class="text-center mb-20">
                                                <h3 class="modal-title fs-16 opacity-lg font-bold">
                                                    {{ translate('Choose Subscription Package') }}</h3>
                                            </div>
                                            <div class="plan-slider owl-theme owl-carousel owl-refresh">
                                                @forelse ($packages as $key=> $package)
                                                    <label class="__plan-item d-block hover {{ $package->id == $store->store_sub?->package_id ? 'active' : '' }}">
                                                        <input type="radio" name="package_id" id="package_id"
                                                               value="{{ $package->id }}" class="d-none">
                                                        <div class="inner-div">
                                                            <div class="text-center">
                                                                <h3 class="title">{{ $package->package_name }}</h3>
                                                                <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($package->price) }}</h2>
                                                                <div class="day-count">{{ $package->validity }}
                                                                    {{ translate('messages.days') }}</div>
                                                            </div>
                                                            <ul class="info">
                                                                @if ($package->pos)
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.POS') }}</span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->mobile_app)
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.mobile_app') }}</span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->chat)
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.chatting_options') }}</span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->review)
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.review_section') }}</span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->self_delivery)
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.self_delivery') }}</span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->max_order == 'unlimited')
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.Unlimited_Orders') }}</span>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ $package->max_order }} {{ translate('messages.Orders') }} </span>
                                                                    </li>
                                                                @endif
                                                                @if ($package->max_product == 'unlimited')
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ translate('messages.Unlimited_uploads') }}</span>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <i class="tio-checkmark-circle"></i>
                                                                        <span>{{ $package->max_product }} {{ translate('messages.uploads') }}</span>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </label>
                                                @empty
                                                    <div class="text-center">
                                                        {{translate('No Package Found')}}
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="btn--container justify-content-end mt-3">
                            <a href="{{ route('admin.rental.provider.edit-basic-setup', $store->id ) }}" class="btn btn--reset min-w-100px justify-content-center">{{ translate('messages.back') }}</a>
                            <div id="subscriptionBtn">
                                <button data-id="{{ $store->store_business_model == 'commission' ? 0 : $store?->package?->id }}"
                                    data-target="#package_detail" id="package_detail" type="button" class="btn btn--primary shift-btn package_detail">{{ translate('messages.update') }}</button>
                            </div>
                            <?php
                                $cash_backs= \App\CentralLogics\Helpers::calculateSubscriptionRefundAmount(store:$store ,return_data:true);
                            ?>
                            <div id="commissionBtn">
                                <button type="button" data-url="{{route('admin.business-settings.subscriptionackage.switchToCommission',$store->id)}}" data-message="{{translate('You_Want_To_Migrate_To_Commission.')}} {{ data_get($cash_backs,'back_amount') > 0  ?  translate('You will get').' '. \App\CentralLogics\Helpers::format_currency(data_get($cash_backs,'back_amount')) .' '.translate('to_your_wallet_for_remaining') .' '.data_get($cash_backs,'days').' '.translate('messages.days_subscription_plan') : '' }}"  class="btn btn--primary shift_to_commission">{{ translate('Update') }}</button>
                            </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade __modal" id="subscription-renew-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body px-4 pt-0">
                    <div class="data_package" id="data_package">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script>
        // ---- file upload with textbox
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

        $(document).on('ready', function() {
            $('.plan-slider').owlCarousel({
                loop: false,
                margin: 30,
                responsiveClass: true,
                nav: false,
                dots: false,
                items: 3,
                center: true,
                startPosition: 1,

                responsive: {
                    0: {
                        items: 1.1,
                        margin: 10,
                    },
                    375: {
                        items: 1.3,
                        margin: 30,
                    },
                    576: {
                        items: 1.7,
                    },
                    768: {
                        items: 2.2,
                        margin: 40,
                    },
                    992: {
                        items: 3,
                        margin: 40,
                    },
                    1200: {
                        items: 4,
                        margin: 40,
                    }
                }
            })

            $('#nextStep').on('click', function () {
                $('#businessSetup').removeClass('d-block').addClass('d-none');
                $('#businessPlan').removeClass('d-none').addClass('d-block');
            });

            $('#backBusinessSetup').on('click', function () {
                $('#businessSetup').removeClass('d-none').addClass('d-block');
                $('#businessPlan').removeClass('d-block').addClass('d-none');
            });
        });

        $(document).ready(function () {
            $('input[name="business_plan"]:checked').each(function () {
                if ($(this).val() == 'subscription-base') {
                    $('#subscription-plan').removeClass('d-none');
                    $('#subscription-plan').addClass('d-block');
                    $('#commissionBtn').hide();
                    $('#subscriptionBtn').show();
                } else {
                    $('#subscription-plan').addClass('d-none');
                    $('#commissionBtn').show();
                    $('#subscriptionBtn').hide();
                }
            });

            $('input[name="package_id"]:checked').each(function () {
                $(this).closest('.__plan-item').addClass('active');
            });

            $('input[name="business_plan"]').on('change', function () {
                if ($(this).val() == 'subscription-base') {
                    $('#subscription-plan').removeClass('d-none');
                    $('#subscription-plan').addClass('d-block');
                    $('#commissionBtn').hide();
                    $('#subscriptionBtn').show();
                } else {
                    $('#subscription-plan').addClass('d-none');
                    $('#subscription-plan').removeClass('d-block');
                    $('#commissionBtn').show();
                    $('#subscriptionBtn').hide();
                }
            });

            $('input[name="package_id"]').on('change', function () {
                $('input[name="package_id"]').each(function () {
                    $(this).closest('.__plan-item').removeClass('active');
                });
                $(this).closest('.__plan-item').addClass('active');
            });
        });


        $('#reset-btn').on('click', function() {
            location.reload()
        })

        $('.shift_to_commission').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            let storeBusinessModel = '{{ $store->store_business_model }}' == 'commission';
            if(storeBusinessModel){
                $('#loading').hide();
                toastr.success('{{ translate('Business Plan updated successfully') }}!');
                window.location.href = '{{ route("admin.rental.provider.list") }}';
                return;
            }
            shift_to_commission(url, message, event)
        })

        function shift_to_commission(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are_you_sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: url,
                        data: {
                            id: '{{ $store->id }}',
                        },
                        beforeSend: function () {
                            $('#loading').show()
                        },
                        success: function (data) {
                            toastr.success('{{ translate('Successfully_Switched_To_Commission') }}!');
                        },
                        complete: function () {
                            $('#loading').hide();
                            location.reload();
                        }
                    });
                }
            })
        }

        $(document).on('click', '.package_detail', function () {
            var oldPackage = $(this).data('id');
            var activePackage = $('.__plan-item.active input[name="package_id"]');

            if(oldPackage == activePackage.val()){
                $('#loading').hide();
                toastr.success('{{ translate('Business Plan updated successfully') }}');
                window.location.href = '{{ route("admin.rental.provider.list") }}';
                return;
            }

            if (activePackage.length) {
                var packageId = activePackage.val();
                var url = `{{ route('admin.business-settings.subscriptionackage.packageView', ['package_id', $store->id]) }}`.replace('package_id', packageId);
            }
            else{
                $('#loading').hide();
                toastr.warning('{{ translate('Please select a subscription package.') }}');
                return;
            }

            $.ajax({
                url: url,
                method: 'get',
                beforeSend: function() {
                    $('#loading').show();
                    $('#plan-modal').modal('hide')
                },
                success: function(data){
                    $('#data_package').html(data.view);
                    if(data.disable_item_count !== null && data.disable_item_count > 0){
                        $('#product_warning').modal('show')
                        $('#disable_item_count').text(data.disable_item_count)
                    } else{
                        $('#subscription-renew-modal').modal('show')
                    }
                },
                complete: function() {
                    $('#loading').hide();
                },

            });
        });
    </script>
@endpush
