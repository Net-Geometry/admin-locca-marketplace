@extends('layouts.admin.app')

@section('title', translate('Easy_parcel'))

@push('css_or_js')
<link rel="stylesheet" href="{{asset('public/assets/admin/css/owl.min.css')}}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/business.png') }}" class="w--26" alt="">
                </span>
                <span>
                    {{ translate('messages.business_setup') }}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>
        <!-- End Page Header -->

        <div class="card mb-3 mt-0">
             <div class="card-body mb-3">
                 <form action="{{ route('admin.business-settings.update-setup') }}" method="POST">
                    @csrf
                     <div class="mb-3">
                     @php($easy_parcel_api_url = \App\Models\BusinessSetting::where('key', 'easy_parcel_api_url')?->first()?->value)
                         <label for="api_endpoint" class="form-label">{{ translate("API Endpoint URL") }}</label>
                         <input type="url" class="form-control" id="api_endpoint" name="easy_parcel_api_url" placeholder="https://example.com/api" value={{ $easy_parcel_api_url ?? '' }} >
                     </div>
                     <div class="mb-3">
                     @php($easy_parcel_api_key = \App\Models\BusinessSetting::where('key', 'easy_parcel_api_key')?->first()?->value)
                         <label for="api_key" class="form-label">{{ translate("API Key") }}</label>
                         <input type="text" class="form-control" id="easy_parcel_api_key" name="easy_parcel_api_key" placeholder="Enter your API key" value={{ $easy_parcel_api_key ?? '' }}>
                     </div>
                     <button type="submit" class="btn btn-primary">Save</button>
                 </form>
             </div>
         </div>


    </div>
    </div>


@endsection
@push('script_2')
    <script src="{{ asset('public/assets/admin/js/view-pages/business-settings-refund-reasons-page.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/owl.min.js') }}"></script>
 
@endpush
