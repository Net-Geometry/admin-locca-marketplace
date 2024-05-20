@extends('layouts.vendor.app')

@section('title',translate('messages.Subscription_Invoice'))

@push('css_or_js')

    <style>
        .trx-invoice table {
            width: 100%
        }
        .trx-invoice {
            font-size: 0.75rem;
            font-family: "Inter", sans-serif;
        }
        .trx-invoice * {
            margin: 0;
            padding: 0;
            line-height: 1.6;
            font-family: "Inter", sans-serif;
            color: #6a707c;
        }
        .trx-invoice .ltr {
            direction: ltr;
        }
        .trx-invoice .rtl {
            direction: rtl;
        }
        .trx-invoice .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
        }
        .trx-invoice img {
            max-width: 100%;
        }
        .trx-invoice .customers {
            border-collapse: collapse;
            width: 100%;
        }
        .trx-invoice table {
            width: 100%;
        }
        .trx-invoice table thead th {
            padding: 8px;
            font-size: 9px;
        }
        .trx-invoice table tbody th, .trx-invoice table tbody td {
            padding: 8px;
            color: #6a707c;
        }
        .trx-invoice table.fz-12 thead th {
            font-size: 12px;
        }
        .trx-invoice table.fz-12 tbody th, .trx-invoice table.fz-12 tbody td {
            font-size: 12px;
        }
        .trx-invoice table.fz-10 thead th {
            font-size: 10px;
        }
        .trx-invoice table.fz-10 tbody th, .trx-invoice table.fz-10 tbody td {
            font-size: 10px;
        }
        .trx-invoice table.customers thead th {
            background-color: #f5fbff;
            color: #222;
            border-top: 1px solid #d6ebff;
            border-bottom: 1px solid #d6ebff;
            padding-top: 10px;
        }
        .trx-invoice table.customers tbody th {
            background-color: #fafcff;
        }
        .trx-invoice table.customers tbody td {
            padding-block: 10px;
            border-bottom: 1px solid #d7dae0;
        }
        .trx-invoice .calc-table * {
            color: #222;
        }
        .trx-invoice .calc-table td {
            padding-inline: 0 !important;
        }
        .trx-invoice .calc-table {
            padding: 0 !important;
        }
        .trx-invoice .text-left {
            text-align: left !important;
        }
        .trx-invoice .pb-2 {
            padding-bottom: 8px !important;
        }
        .trx-invoice .pb-3 {
            padding-bottom: 16px !important;
        }
        .trx-invoice .text-right {
            text-align: right !important;
        }
        .trx-invoice table th.text-right {
            text-align: right !important;
        }
        .fz-10 {
            font-size: 10px;
        }
        .fz-17 {
            font-size: 17px;
            font-weight: 700
        }
        .fz-12 {
            font-size: 12px;
        }
    </style>
@endpush

@section('content')


<div class="content container-fluid">
    <div id="printableArea2">
        <div class="first content-position trx-invoice" style="width:732px;margin: 0 auto;">
            <div class="bg-white p-3 rounded-md">
                <table class="fz-10">
                    <tr>
                        <td style="padding:0;text-align:left">
                            <div class="text-dark" style="text-transform:uppercase; font-size:22px;margin-bottom:5px">
                                {{ translate('Invoice')}}
                            </div>
                            <div class="font-normal">
                                <span class="text-dark">{{ translate('Transaction ID')}}</span> : #{{ $transaction->id }}
                            </div>
                            <div class="font-normal">
                                <span class="text-dark">{{ translate('invoice_Date')}}</span> : {{ App\CentralLogics\Helpers::date_format($transaction->created_at) }}
                            </div>
                        </td>
                        <td style="padding:0;text-align:right">
                            <img width="60" height="40"  alt="6amMart"
                            src="{{ \App\CentralLogics\Helpers::get_image_helper($logo,'value', asset('storage/app/public/business/').'/'.$logo->value??'', asset('public/assets/admin/img/upload-img.png'),'business/') }}"
                            style="margin-bottom:5px">
                            <div class="font-normal">
                                {{ $BusinessData['address'] }}
                            </div>
                            {{-- <div>
                                TAX ID 00XXXXX1234X0XX
                            </div> --}}
                        </td>
                    </tr>
                </table>
                <br>
                <table class="border bs-0" style="border-radius:12px;">
                    <tr>
                        <td class="text-left" style="padding:21px 16px">
                            <div class="fz-11">{{ translate('Store Owner')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">{{ $transaction?->store?->vendor?->f_name. ' '.$transaction?->store?->vendor?->l_name }}</span></div>
                        </td>
                        <td class="text-left" style="padding:21px 8px">
                            <div class="fz-11">{{ translate('Phone')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">{{ $transaction?->store?->vendor?->phone }}</span></div>
                        </td>
                        <td class="text-left" style="padding:21px 8px">
                            <div class="fz-11">{{ translate('Email')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">{{ $transaction?->store?->vendor?->email }}</span></div>
                        </td>
                        <td colspan="3" class="text-right" style="padding:21px 16px">
                            <div class="mb-1 fz-10">
                                <span class="text-dark">{{translate('invoice_of')}}</span> <span class="font-normal">({{  App\CentralLogics\Helpers::currency_symbol() }})</span>
                            </div>
                            <div class="fz-17 text-primary text-right">{{  App\CentralLogics\Helpers::format_currency($transaction->paid_amount)  }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="border-bottom" style="padding: 0"></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="height: 10px;padding: 0 !important;line-height:10px"></td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <table>
                                <tr>
                                    <td class="vertical-align-top" style="padding:8px 16px; width:25%">
                                        <div class="fz-11">{{ translate('payment')}}</div>
                                        <div class="font-medium fz-10 mb-2 text-capitalize">
                                        <span class="text-dark">{{ translate($transaction->payment_method) }}</span></div>
                                    </td>
                                    <td class="fz-10 border-left vertical-align-top" style="padding:8px 16px; width:34%">
                                        <div>{{ translate('Purchased') }}</div>
                                        <div class="font-bold fz-11">{{ $transaction->package->package_name}}</div>
                                    </td>
                                    <td class="fz-10 border-left vertical-align-top" style="padding:8px 16px; width:34%">
                                        <div>{{translate('Duration')}}</div>
                                        <div class="font-bold fz-11"> {{ $transaction->validity }} {{translate('Days')}} </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="height: 10px;padding: 0 !important;line-height:10px"></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="height: 20px;padding: 0 !important;line-height:20px">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">

                            <table class="table __subscribe-table table-borderless mt-3" style="color: rgb(105, 101, 101)">
                                <thead>
                                    <tr>
                                        <th>
                                            <span>{{ translate('Transaction ID') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Package Name') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Transaction Time') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Validity Time') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Amount') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>{{ $transaction->id}}</span>
                                        </td>
                                        <td>
                                            <span>{{ $transaction->package->package_name}}</span>
                                        </td>
                                        <td>
                                            <span>{{ App\CentralLogics\Helpers::date_format($transaction->created_at) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $transaction->validity }} {{translate('Days')}}</span>
                                        </td>
                                        <td>
                                            <span class="__txt-nowrap">
                                                {{  App\CentralLogics\Helpers::format_currency($transaction->paid_amount) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="font-semibold fz-12 pt-0" style="text-align: center;padding-bottom: 14px">
                            {{translate('Thanks for the Subscription')}}
                        </td>
                    </tr>
                </table>
            </div>
            <table class="border-0" style="text-align:center; background-color: #f1f1f1">
                <tr>
                    <td>
                        {{url('/') }}
                    </td>
                    <td>
                        {{ $BusinessData['phone'] }}
                    </td>
                    <td>
                        {{ $BusinessData['email_address'] }}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
@endsection

@push('script_2')
<script>
      let printContents = document.getElementById("printableArea2").innerHTML;
    let originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = printContents;
</script>
@endpush
