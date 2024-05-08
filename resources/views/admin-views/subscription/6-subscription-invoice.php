@extends('layouts.admin.app')

@section('title',translate('messages.disbursement'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid initial-38">
        <div class="row justify-content-center" id="printableArea">
            <div class="col-md-12">
                <!-- <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                        value="Proceed  If printer is ready.">
                    <a href=""
                        class="btn btn-danger non-printable">Back</a>
                </center>
                <hr class="non-printable"> -->
                <div class="__trx-print mx-auto">
                    <div class="pt-3 text-center">
                        <img src=""
                            alt="6amMart" class="initial-38-2">
                    </div>
                    <div class="pt-3 text-center mb-3 pb-1">
                        <img src="{{asset('/public/assets/admin/img/transaction.png')}}" alt="">
                    </div>
                    <div class="text-center pt-2 mb-3">
                        <h1 class="initial-38-3">Transaction Sucessfull</h1>
                        <div class="initial-38-4" style="margin-bottom: 20px">For Standard Package</div>
                        <h4> <span class="text--base">Purches Status: </span> Subscribed.</h4>
                        <h3 class="initial-38-3 name my-3">Green Mart</h3>
                        <h5 class="pb-4 pt-2 mv-2">
                            Thank You for transcation with &nbsp; <span class="text--base">6amMart</span> &nbsp; In
                            Pro Package
                        </h5>
                    </div>
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
                    <div class="text-center my-5 py-4">
                        If you require any assistance or have feedback or suggestions about our site you can email us at
                        <a href="mailto:admin@gmail.com" style="text-decoration: none; color: inherit;">Email:
                            a**********@gmail.com</a>
                    </div>

                    <div style="font-weight: 400;font-size: 11px;line-height: 22px;color: #242A30;display:flex;justify-content:center">
                        <span style="margin-inline-end:5px;">
                            <a href="tel:01700000000" style="text-decoration: none; color: inherit;">Privacy Policy</a>
                        </span>
                        <span>
                            <a href="mailto:admin@gmail.com" style="text-decoration: none; color: inherit;">Contact Us</a>
                        </span>
                    </div>
                    <div class="d-block text-center mt-3">
                        <div style="display: inline-block;padding-left: 15px;padding-right: 15px">
                            <a href="https://www.instagram.com/?hl=en" target="”_blank”">
                                <img src="{{asset('public/assets/admin/img/instagram.png')}}"
                                    alt="" style="height: 14px; width:14px;object-fit:contain">
                            </a>
                        </div>
                        <div style="display: inline-block;padding-left: 15px;padding-right: 15px">
                            <a href="https://www.facebook.com/" target="”_blank”">
                                <img src="{{asset('public/assets/admin/img/facebook.png')}}"
                                    alt="" style="height: 14px; width:14px;object-fit:contain">
                            </a>
                        </div>
                        <div style="display: inline-block;padding-left: 15px;padding-right: 15px">
                            <a href="https://twitter.com/?lang=en" target="”_blank”">
                                <img src="{{asset('public/assets/admin/img/twitter.png')}}"
                                    alt="" style="height: 14px; width:14px;object-fit:contain">
                            </a>
                        </div>
                        <div style="display: inline-block;padding-left: 15px;padding-right: 15px">
                            <a href="https://bd.linkedin.com/" target="”_blank”">
                                <img src="{{asset('public/assets/admin/img/linkedin.png')}}"
                                    alt="" style="height: 14px; width:14px;object-fit:contain">
                            </a>
                        </div>
                        <div style="display: inline-block;padding-left: 15px;padding-right: 15px">
                            <a href="https://www.pinterest.com/" target="”_blank”">
                                <img src="{{asset('public/assets/admin/img/pinterest.png')}}"
                                    alt="" style="height: 14px; width:14px;object-fit:contain">
                            </a>
                        </div>
                        <div style="font-weight: 400;font-size: 10px;line-height: 22px;color: #242A30;padding-top:25px">
                            All copy right reserved
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

@endpush

