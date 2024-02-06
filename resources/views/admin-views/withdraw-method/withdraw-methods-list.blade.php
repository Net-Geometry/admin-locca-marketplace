@extends('layouts.admin.app')

@section('title', translate('messages.disbursement_method_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/admin/img/icons/withdraw.png')}}" alt="">
                    {{ translate('messages.withdraw_method_list')}}
                </h2>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 pt-3 pb-1">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <form  class="search-form theme-style">
                                    <!-- Search -->
                                    <div class="input-group input--group">
                                        <input id="datatableSearch" name="search" type="search" value="{{ $search }}"class="form-control h--40px" placeholder="{{ translate('messages.Search_by_ID_or_name')}}" aria-label="{{translate('messages.search_here')}}">
                                        <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            <div class="col-auto">
                                <a href="{{route('admin.transactions.withdraw-method.create')}}" class="btn btn--primary">
                                    <i class="tio-add"></i>
                                    {{ translate('messages.add_new_method')}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('messages.SL')}}</th>
                                <th>{{ translate('messages.method_name')}}</th>
                                <th>{{  translate('messages.method_fields') }}</th>
                                <th>{{ translate('messages.active_status')}}</th>
                                <th >{{ translate('messages.default_method')}}</th>
                                <th class="text-center">{{ translate('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdrawal_methods as $key=>$withdrawal_method)
                                <tr>
                                    <td>{{$withdrawal_methods->firstitem()+$key}}</td>
                                    <td>{{$withdrawal_method['method_name']}}</td>
                                    <td>
                                        <div class="max-text-2-line" style="--line-count: 4">
                                            @foreach($withdrawal_method['method_fields'] as $key=>$method_field)
                                                <b>{{ translate('messages.Name')}}:</b> {{ translate($method_field['input_name'])}} <br/>
                                                <b>{{ translate('messages.Type')}}:</b> {{ translate($method_field['input_type']) }} <br/>
                                                <b>{{ translate('messages.Placeholder')}}:</b> {{ $method_field['placeholder'] }} <br/>
                                                {{ $method_field['is_required'] ? translate('messages.Required') :  translate('messages.Optional') }}
                                                <br/>
                                            @endforeach
                                        </div>
                                        <a href="#" class="font-semibold d-flex gap-2 align-items-center text-capitalize mt-1" data-toggle="modal" data-target="#withdrawMethodList">
                                            {{ translate('messages.see_all')}}
                                            <i class="tio-arrow-forward"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input class="toggle-switch-input status featured-status"
                                                   data-id="{{$withdrawal_method->id}}"
                                                   type="checkbox" {{$withdrawal_method->is_active?'checked':''}}>
                                                   <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox" class="default-method toggle-switch-input"
                                            id="{{$withdrawal_method->id}}" {{$withdrawal_method->is_default == 1?'checked':''}}>
                                                   <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                        </label>
                                    </td>



                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a href="{{route('admin.transactions.withdraw-method.edit',[$withdrawal_method->id])}}"
                                               class="btn btn-sm btn--primary btn-outline-primary action-btn">
                                                <i class="tio-edit"></i>
                                            </a>

                                            @if(!$withdrawal_method->is_default)
                                                <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:"
                                                   title="{{ translate('messages.Delete')}}" data-id="delete-{{$withdrawal_method->id}}" data-message="{{ translate('Want to delete this item ?') }}">
                                                    <i class="tio-delete-outlined"></i>
                                                </a>
                                                <form action="{{route('admin.transactions.withdraw-method.delete',[$withdrawal_method->id])}}"
                                                      method="post" id="delete-{{$withdrawal_method->id}}">
                                                    @csrf @method('delete')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($withdrawal_methods)==0)
                            <div class="empty--data">
                                <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                            </div>
                       @endif
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            <!-- Pagination -->
                            {{$withdrawal_methods->links()}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Withdraw Method List Modal -->
    <div class="modal fade" id="withdrawMethodList" tabindex="-1" role="dialog" aria-labelledby="withdrawMethodListLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0 pb-2">
                    <h4 class="text-center mb-1">{{translate('withdraw_Method_List')}}</h4>
                    <div class="d-flex justify-content-center  align-items-center gap-2">
                        <span>{{translate('method_Name')}}</span>
                        :
                        <span class="font-semibold text-dark">Card</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-align-middle text-dark">
                        <tbody>
                            @foreach($withdrawal_method['method_fields'] as $key=>$method_field)
                            <tr>
                                <td class="px-4 {{$withdrawal_methods->firstitem()+$key === 1 ? "border-top-0" : ""}}">{{$withdrawal_methods->firstitem()+$key}}</td>
                                <td class="{{$withdrawal_methods->firstitem()+$key === 1 ? "border-top-0" : ""}}">
                                    <div>
                                        <div>{{ translate('messages.Name')}}: {{ translate($method_field['input_name'])}}</div>
                                        <div>{{ translate('messages.Type')}}: {{ translate($method_field['input_type']) }}</div>
                                        <div>{{ translate('messages.Placeholder')}}: {{ $method_field['placeholder'] }}</div>
                                    </div>
                                </td>
                                <td class="{{$withdrawal_methods->firstitem()+$key === 1 ? "border-top-0" : ""}}">
                                    <div class="d-flex gap-3 align-items-center">
                                        {!! $method_field['is_required'] ? 
                                            '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                                <path d="M3.43848 8.76026C3.11543 8.76116 2.79924 8.85351 2.52649 9.02662C2.25374 9.19973 2.03558 9.44652 1.89724 9.73845C1.7589 10.0304 1.70603 10.3555 1.74476 10.6762C1.78349 10.9969 1.91224 11.3001 2.1161 11.5507L6.46189 16.8743C6.61683 17.0667 6.81545 17.2194 7.04124 17.3196C7.26704 17.4198 7.51348 17.4647 7.76011 17.4506C8.2876 17.4222 8.76383 17.1401 9.06745 16.6761L18.0948 2.13765C18.0962 2.13524 18.0978 2.13283 18.0994 2.13045C18.1841 2.0004 18.1566 1.74267 17.9818 1.58076C17.9337 1.5363 17.8771 1.50214 17.8154 1.48038C17.7537 1.45863 17.6881 1.44975 17.6228 1.45427C17.5576 1.4588 17.4939 1.47665 17.4357 1.50672C17.3776 1.53678 17.3263 1.57843 17.2848 1.6291C17.2816 1.63309 17.2782 1.63701 17.2748 1.64087L8.17065 11.9272C8.13601 11.9664 8.09393 11.9982 8.04687 12.021C7.9998 12.0437 7.94869 12.0569 7.8965 12.0597C7.8443 12.0625 7.79207 12.055 7.74282 12.0374C7.69358 12.0199 7.64831 11.9927 7.60965 11.9576L4.58815 9.20797C4.27434 8.92031 3.86419 8.76058 3.43848 8.76026Z" fill="#10DC7C"/>
                                            </svg>' :  
                                            '' 
                                        !!}
                                    

                                          {{ $method_field['is_required'] ? translate('messages.Required') :  translate('messages.Optional') }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script_2')
  <script>
      "use strict";
      $(document).on('change', '.default-method', function () {
          let id = $(this).attr("id");
          let status = $(this).prop("checked") === true ? 1:0;

          $.ajaxSetup({
              headers: {
                //   'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

              }
          });
          $.ajax({
              url: "{{route('admin.transactions.withdraw-method.default-status-update')}}",
              method: 'POST',
              data: {
                  id: id,
                  status: status
              },
              success: function (data) {
                  if(data.success == true) {
                      toastr.success('{{ translate('messages.Default_Method_updated_successfully')}}');
                      setTimeout(function(){
                          location.reload();
                      }, 1000);
                  }
                  else if(data.success == false) {
                      toastr.error('{{ translate('messages.Default_Method_updated_failed.')}}');
                      setTimeout(function(){
                          location.reload();
                      }, 1000);
                  }
              }
          });
      });

      $('.featured-status').on('change', function () {
          let id = $(this).data('id');
          $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                //   'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              }
          });
          $.ajax({
              url: "{{route('admin.transactions.withdraw-method.status-update')}}",
              method: 'POST',
              data: {
                  id: id
              },
              success: function (data) {
                  toastr.success('{{ translate('messages.status_updated_successfully')}}');
              }
          });
      })
  </script>
@endpush
