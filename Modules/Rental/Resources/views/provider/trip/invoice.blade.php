@extends('layouts.vendor.app')

@section($trip?->trip_status)
active
@endsection
@section('title',translate('Invoice'))


@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }

    </style>
@endpush


@section('content')

@include('rental::admin.trip.partials._invoice')

@endsection

@push('script')
    <script>
        function printDiv(divName) {
            window.open('{{route("vendor.trip.print-invoice",["id" => $trip->id])}}', '_blank');
        }

    </script>
@endpush
