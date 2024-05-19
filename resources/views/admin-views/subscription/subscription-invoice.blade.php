@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

    <style>
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
        @media print {
            .trx-invoice table th.text-right {
                text-align: right !important;
            }
        }
        .trx-invoice .content-position {
            padding: 30px 20px 10px;
        }
        .trx-invoice .content-position-y {
            padding: 0 40px;
        }
        .trx-invoice .text-white {
            color: white !important;
        }
        .trx-invoice .bs-0 {
            border-spacing: 0;
        }
        .trx-invoice .mb-1 {
            margin-bottom: 4px !important;
        }
        .trx-invoice .mb-2 {
            margin-bottom: 8px !important;
        }
        .trx-invoice .mb-4 {
            margin-bottom: 24px !important;
        }
        .trx-invoice .mb-30 {
            margin-bottom: 30px !important;
        }
        .trx-invoice .px-10 {
            padding-inline-start: 10px;
            padding-inline-end: 10px;
        }
        .trx-invoice .fz-14 {
            font-size: 14px;
        }
        .trx-invoice .fz-12 {
            font-size: 12px;
        }
        .trx-invoice .fz-10 {
            font-size: 10px;
        }
        .trx-invoice .font-normal {
            font-weight: 400;
        }
        .trx-invoice .font-weight-normal {
            font-weight: normal;
        }
        .trx-invoice .border-dashed-top {
            border-top: 1px dashed #ddd;
        }
        .trx-invoice .font-weight-bold {
            font-weight: 700;
        }
        .trx-invoice .bg-light {
            background-color: #f7f7f7;
        }
        .trx-invoice .py-30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .trx-invoice .py-4 {
            padding-top: 24px;
            padding-bottom: 24px;
        }
        .trx-invoice .d-flex {
            display: flex;
            gap: 3px;
        }
        .trx-invoice .align-items-center {
            align-items: center;
        }
        .trx-invoice .gap-2 {
            gap: 8px;
        }
        .trx-invoice .flex-wrap {
            flex-wrap: wrap;
        }
        .trx-invoice .align-items-center {
            align-items: center;
        }
        .trx-invoice .justify-content-center {
            justify-content: center;
        }
        .trx-invoice a {
            color: rgba(0, 128, 245, 1);
        }
        .trx-invoice .p-1 {
            padding: 4px !important;
        }
        .trx-invoice .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0;
            margin-inline-end: 0;
            font-weight: bold;
            color: #222;
        }
        .trx-invoice .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0;
            margin-inline-end: 0;
            font-weight: bold;
            color: #222;
        }
        .trx-invoice .m-0 {
            margin: 0;
        }
        .trx-invoice .my-0 {
            margin-top: 0;
            margin-bottom: 0;
        }
        .trx-invoice .mb-0 {
            margin-bottom: 0;
        }
        .trx-invoice .mt-6px {
            margin-top: 6px;
        }
        .trx-invoice .font-size-26px {
            font-size: 26px;
        }
        .trx-invoice .w-100 {
            width: 100%;
        }
        .trx-invoice .width-60 {
            width: 60%;
        }
        .trx-invoice .fz-17 {
            font-size: 17px;
            font-weight: 700;
        }
        .trx-invoice .text-primary {
            color: #0177cd;
        }
        .trx-invoice .border {
            border: 1px solid #d7dae0;
        }
        .trx-invoice .border-bottom {
            border-bottom: 1px solid #d7dae0;
        }
        .trx-invoice .border-left {
            border-left: 1px solid #d7dae0;
        }
        .trx-invoice .font-bold {
            font-weight: bold;
            color: #222;
        }
        .trx-invoice .vertical-align-top {
            vertical-align: top;
        }
        .trx-invoice .font-semibold {
            font-weight: 600;
            color: #222;
        }
        .trx-invoice .fz-11 {
            font-size: 11px;
        }
        .trx-invoice .fz-14 {
            font-size: 14px !important;
        }
        .trx-invoice .h-100 {
            height: 100%;
        }
        .trx-invoice .font-medium {
            font-weight: 600;
            color: #222;
        }
        .trx-invoice .text-capitalize {
            text-transform: capitalize;
        }
        .trx-invoice .text-dark, .trx-invoice strong {
            color: #222;
        }
        .trx-invoice .text-uppercase {
            text-transform: uppercase;
        }
        .trx-invoice .pt-0 {
            padding-top: 0 !important;
        }
        .trx-invoice .pb-0 {
            padding-bottom: 0 !important;
        }
        .__subscribe-table tr th {
            background: #00555512;
            color: #000000
        }
        .trx-invoice .text-dark,
        .__subscribe-table tr th span {
            color: #000000
        }
        .trx-invoice .bg-white {
            background: #ffffff
        }
        .trx-invoice .p-3 {
            padding: 16px;
        }
        .trx-invoice .rounded-md {
            border-radius: 0.375rem
        }
        body {
            background: #f9f9f9;
        }
    </style>
@endpush

@section('content')
<div class="content container-fluid">
    <div class="trx-invoice">

        <div class="first content-position" style="width:732px;margin: 0 auto;">
            <div class="bg-white p-3 rounded-md">
                <table class="fz-10">
                    <tr>
                        <td style="padding:0;text-align:left">
                            <div class="text-dark" style="text-transform:uppercase; font-size:22px;margin-bottom:5px">
                                {{ translate('Invoice')}}
                            </div>
                            <div class="font-normal">
                                <span class="text-dark">{{ translate('Transaction ID')}}</span> : #0100082
                            </div>
                            <div class="font-normal">
                                <span class="text-dark">{{ translate('invoice_Date')}}</span> : June 3, 2020
                            </div>
                        </td>
                        <td style="padding:0;text-align:right">
                            <img width="60" height="40" src="" alt="6amMart" style="margin-bottom:5px">
                            <div class="font-normal">
                                Business address City, State, IN - 000 000
                            </div>
                            <div>
                                TAX ID 00XXXXX1234X0XX
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <table class="border bs-0" style="border-radius:12px;">
                    <tr>
                        <td class="text-left" style="padding:21px 16px">
                            <div class="fz-11">{{ translate('Store Owner')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">Jhone Doe</span></div>
                        </td>
                        <td class="text-left" style="padding:21px 8px">
                            <div class="fz-11">{{ translate('Phone')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">+9154983134435</span></div>
                        </td>
                        <td class="text-left" style="padding:21px 8px">
                            <div class="fz-11">{{ translate('Email')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">jhone@example.com</span></div>
                        </td>
                        <td colspan="3" class="text-right" style="padding:21px 16px">
                            <div class="mb-1 fz-10">
                                <span class="text-dark">{{translate('invoice_of')}}</span> <span class="font-normal">$</span>
                            </div>
                            <div class="fz-17 text-primary text-right">$1000.00</div>
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
                                        <span class="text-dark">Stripe</span></div>
                                    </td>
                                    <td class="fz-10 border-left vertical-align-top" style="padding:8px 16px; width:34%">
                                        <div>Purchased</div>
                                        <div class="font-bold fz-11">{{ translate('Standard Package')}}</div>
                                    </td>
                                    <td class="fz-10 border-left vertical-align-top" style="padding:8px 16px; width:34%">
                                        <div>365 Days</div>
                                        <div class="font-bold fz-11">{{translate('365 Days')}} </div>
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
                                            <span>Transaction ID</span>
                                        </th>
                                        <th>
                                            <span>Package Name</span>
                                        </th>
                                        <th>
                                            <span>Transaction Time</span>
                                        </th>
                                        <th>
                                            <span>Validity Time</span>
                                        </th>
                                        <th>
                                            <span>Amount</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>2398435734</span>
                                        </td>
                                        <td>
                                            <span>Pro</span>
                                        </td>
                                        <td>
                                            <span>24 Nov 2022</span>
                                        </td>
                                        <td>
                                            <span>365 Days</span>
                                        </td>
                                        <td>
                                            <span class="__txt-nowrap">
                                                $ 1,199.00
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
            <table class="border-0" style="text-align:center; background: #f1f1f1">
                <tr>
                    <td>
                        www.6ammart.inc
                    </td>
                    <td>
                        +91 00000 00000
                    </td>
                    <td>
                        6ammart@email.com
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
@endsection

@push('script_2')

@endpush

