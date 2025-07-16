@extends('layouts.admin.app')

@section('title', translate('messages.Add new easy_percel_states'))

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
                    {{ translate('easy_percel_states') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form
                    action="{{ isset($state) ? route('admin.business-settings.easy-parcel.state.update', [$state['id']]) : route('admin.business-settings.easy-parcel.state.store') }}"
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
                    <div class="col-md-4">
                            @if($language)
                                <div class="form-group lang_form" id="default-form">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{ translate('messages.default') }}) <span class="form-label-secondary text-danger"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Required.')}}"> *
                                        </span>
                                    </label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_city')}}" maxlength="191" value="{{$state?->getRawOriginal('name')}}"  >
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                @foreach($language as $lang)
                                    <?php
                                        if(count($state['translations'])){
                                            $translate = [];
                                            foreach($state['translations'] as $t)
                                            {
                                                if($t->locale == $lang && $t->key=="name"){
                                                    $translate[$lang]['name'] = $t->value;
                                                }
                                            }
                                        }
                                    ?>
                                    <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_city')}}" maxlength="191" value="{{$translate[$lang]['name']??''}}"  >
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                @endforeach
                            @else
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{translate('messages.new_city')}}" value="{{$state['name']}}" maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endif
                        
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                              <label class="input-label" for="country">
                                  {{ translate('messages.country') }}
                                  <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                  </span>
                              </label>
                                            <select name="country_id_display" id="countrySelect" class="form-control js-select2-custom" disabled>
                                                <option value="">{{ translate('messages.select_country_code') }}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['id'] }}" 
                                                            @if('MY' == $country['country_code']) selected @endif>
                                                        {{ $country['name'] }} ({{ $country['country_code'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                    
                                            <!-- Hidden input that submits the real value -->
                                            <input type="hidden" name="country_id" value="{{ $countries->where('country_code', 'MY')?->first()?->id ?? '' }}">
                                        </div>
                                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                                  <label class="input-label" for="stateCodeSelect">
                                      {{ translate('messages.state_code') }}
                                      <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="{{ translate('messages.Required.') }}"> *
                                      </span>
                                  </label>
                                  
                                  <select name="state_code" id="stateCodeSelect" class="form-control js-select2-custom">
                                      <option value="">{{ translate('messages.select_state_code') }}</option>
                                      @foreach(EASY_PARCEL_STATE_CODE as $current_state)
                                          <option value="{{ $current_state['code'] }}" 
                                                  @if($state['state_code'] == $current_state['code']) selected @endif>
                                              {{ $current_state['name'] }} ({{ $current_state['code'] }})
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
                            class="btn btn--primary">{{ translate('messages.update') }}</button>
                    </div>

                </form>
            </div>
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
