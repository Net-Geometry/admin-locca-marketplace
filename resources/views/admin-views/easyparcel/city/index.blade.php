@extends('layouts.admin.app')

@section('title', translate('messages.Add new easy_percel_cites'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/category.png') }}" class="w--20" alt="">
                </span>
                <span>
                    {{ translate('easy_percel_cites') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form
                    action="{{ isset($city) ? route('admin.business-settings.easy-parcel.city.update', [$city['id']]) : route('admin.business-settings.easy-parcel.city.store') }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($language)
                        <ul class="nav nav-tabs mb-4 border-0">
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
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            @if ($language)
                                <div class="form-group lang_form" id="default-form">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.name') }}
                                        ({{ translate('messages.default') }})
                                        <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}"> *
                                        </span>

                                    </label>
                                    <input type="text" name="name[]" value="{{ old('name.0') }}" class="form-control"
                                        placeholder="{{ translate('messages.new_city') }}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach ($language as $key => $lang)
                                    <div class="form-group d-none lang_form" id="{{ $lang }}-form">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.name') }}
                                            ({{ strtoupper($lang) }})</label>
                                        <input type="text" name="name[]" value="{{ old('name.' . $key + 1) }}"
                                            class="form-control" placeholder="{{ translate('messages.new_city') }}"
                                            maxlength="191">
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                @endforeach
                            @else
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.name') }}</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ translate('messages.new_city') }}" value="{{ old('name') }}"
                                        maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            @endif
                        

                        
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                                  <label class="input-label" for="countryCodeSelect">
                                      {{ translate('messages.country_code') }}
                                      <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}"> *
                                      </span>
                                  </label>
                                  
                                  <select name="country_code" id="countryCodeSelect" class="form-control js-select2-custom">
                                      <option value="">{{ translate('messages.select_country_code') }}</option>
                                      @foreach(EASY_PARCEL_COUNTRY_CODE as $country)
                                          <option value="{{ $country['code'] }}" 
                                                  @if(old('country_code') == $country['code']) selected @endif>
                                              {{ $country['name'] }} ({{ $country['code'] }})
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit"
                            class="btn btn--primary">{{ isset($city) ? translate('messages.update') : translate('messages.add') }}</button>
                    </div>

                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{ translate('messages.city_list') }}<span
                            class="badge badge-soft-dark ml-2" id="itemCount">{{ $easyPercelCites->total() }}</span></h5>

                    <form class="search-form">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input type="search" name="search" value="{{ request()?->search ?? null }}"
                                class="form-control min-height-45"
                                placeholder="{{ translate('messages.search_cities') }}"
                                aria-label="{{ translate('messages.ex_:_cities') }}">
                            <input type="hidden" name="position" value="0">
                            <button type="submit" class="btn btn--secondary min-height-45"><i
                                    class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    @if (request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-category"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                    @endif

                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-align-middle"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('sl') }}</th>
                                <th class="border-0">{{ translate('messages.id') }}</th>
                                <th class="border-0 w--1">{{ translate('messages.name') }}</th>
                                <th class="border-0 w--1">{{ translate('messages.country_code') }}</th>
                                <th class="border-0 text-center">{{ translate('messages.status') }}</th>
                                <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                            @foreach ($easyPercelCites as $key => $city)
                                <tr>
                                    <td>{{ $key + $easyPercelCites->firstItem() }}</td>
                                    <td>{{ $city->id }}</td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{ Str::limit($city['name'], 20, '...') }}
                                        </span>
                                    </td>
                                      <td>{{ $city->country_code }}</td>
                                      <td>
                                        <label class="toggle-switch toggle-switch-sm"
                                            for="stocksCheckbox{{ $city->id }}">
                                            <input type="checkbox"
                                                data-url="{{ route('admin.business-settings.easy-parcel.city.status', [$city['id'], $city->status ? 0 : 1]) }}"
                                                class="toggle-switch-input redirect-url"
                                                id="stocksCheckbox{{ $city->id }}"
                                                {{ $city->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary"
                                                href="{{ route('admin.business-settings.easy-parcel.city.edit', [$city['id']]) }}"
                                                title="{{ translate('messages.edit_category') }}"><i
                                                    class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                href="javascript:" data-id="category-{{ $city['id'] }}"
                                                data-message="{{ translate('Want to delete this city') }}"
                                                title="{{ translate('messages.delete_city') }}"><i
                                                    class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('admin.business-settings.easy-parcel.city.delete', [$city['id']]) }}"
                                                method="post" id="category-{{ $city['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if (count($easyPercelCites) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $easyPercelCites->appends($_GET)->links() !!}
            </div>
            @if (count($easyPercelCites) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/js/view-pages/category-index.js"></script>
    <script>
        "use strict";
        $('.location-reload-to-category').on('click', function() {
            const url = $(this).data('url');
            let nurl = new URL(url);
            nurl.searchParams.delete('search');
            location.href = nurl;
        });

        $("#customFileEg1").change(function() {
            readURL(this);
            $('#viewer').show(1000)
        });
        $('#reset_btn').click(function() {
            $('#exampleFormControlSelect1').val(null).trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
        })
    </script>
@endpush
