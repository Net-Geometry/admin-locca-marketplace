<div class="content container-fluid invoice-page initial-38">
    <div id="printableArea">
        <div>
            <div class="text-center">
                <input type="button" class="btn btn-primary mt-3 non-printable" onclick="printDiv('printableArea')"
                    value="{{ translate('Proceed,_If_thermal_printer_is_ready.') }}" />
                <a href="{{ url()->previous() }}"
                    class="btn btn-danger non-printable mt-3">{{ translate('messages.back') }}</a>
            </div>

            <hr class="non-printable">
            <div class="print--invoice initial-38-1">
                @if ($trip->provider)
                    <div class="text-center pt-4 mb-3">
                        <img class="invoice-logo" src="{{ asset('/public/assets/admin/img/invoice-logo.png') }}"
                            alt="">
                        <div class="top-info">
                            <h2 class="store-name">{{ $trip->provider->name }}</h2>
                            <div>
                                {{ $trip->provider->address }}
                            </div>
                            <div class="mt-1 d-flex justify-content-center">
                                <span>{{ translate('messages.phone') }}</span> <span>:</span> <span>{{ $trip->provider->phone }}</span>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="top-info">
                    <img src="{{ asset('/public/assets/admin/img/invoice-star.png') }}" alt="" class="w-100">
                    <div class="text-uppercase text-center">{{ translate('messages.cash_receipt') }}</div>
                    <img src="{{ asset('/public/assets/admin/img/invoice-star.png') }}" alt="" class="w-100">
                </div>
                <div class="order-info-id text-center">
                    <h5 class="d-flex justify-content-center"><span>{{ translate('order_id') }}</span> <span>:</span> <span>{{ $trip['id'] }}</span></h5>
                    <div>
                        {{ date('d/M/Y ' . config('timeformat'), strtotime($trip['created_at'])) }}
                    </div>
                    <div>
                        @if ($trip->provider?->gst_status)
                            <span>{{ translate('Gst No') }}</span> <span>:</span> <span>{{ $trip->provider->gst_code }}</span>
                    @endif
                    </div>
                </div>
                <div class="order-info-details">
                    <div class="row mt-3">
                        @if ($trip->order_type == 'parcel')
                            <div class="col-12">
                                @php($address = json_decode($trip->delivery_address, true))
                                <h5>{{ translate('messages.sender_info') }}</h5>
                                <div class="d-flex">
                                    <span>{{ translate('messages.sender_name') }}</span> <span>:</span>
                                    <span>{{ isset($address) ? $address['contact_person_name'] : $trip->address['f_name'] . ' ' . $trip->customer['l_name'] }}</span>
                                </div>
                                <div class="d-flex">
                                    <span>{{ translate('messages.phone') }}</span> <span>:</span>
                                    <span>{{ isset($address) ? $address['contact_person_number'] : $trip->customer['phone'] }}</span>
                                </div>
                                <div class="text-break d-flex">
                                    <span class="word-nobreak">{{ translate('messages.address') }}</span> <span>:</span>
                                    <span>{{ isset($address) ? $address['address'] : '' }}</span>
                                </div>
                                @php($address = $trip->receiver_details)
                                <h5><u>{{ translate('messages.receiver_info') }}</u></h5>
                                <div class="d-flex">
                                    <span>{{ translate('messages.receiver_name') }}</span> <span>:</span>
                                    <span>{{ isset($address) ? $address['contact_person_name'] : $trip->address['f_name'] . ' ' . $trip->customer['l_name'] }}</span>
                                </div>
                                <div class="d-flex">
                                    <span>{{ translate('messages.phone') }}</span> <span>:</span>
                                    <span>{{ isset($address) ? $address['contact_person_number'] : $trip->customer['phone'] }}</span>
                                </div>
                                <div class="text-break d-flex">
                                    <span class="word-nobreak">{{ translate('messages.address') }}</span> <span>:</span>
                                    <span>{{ isset($address) ? $address['address'] : '' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                @php($address = json_decode($trip->delivery_address, true))
                                @if (!empty($address))
                                    <h5 class="d-flex">
                                        <span>{{ translate('messages.contact_name') }}</span> <span>:</span>
                                        <span>{{ isset($address['contact_person_name']) ? $address['contact_person_name'] : '' }}</span>
                                    </h5>
                                    <h5 class="d-flex">
                                        <span>{{ translate('messages.phone') }}</span> <span>:</span>
                                        <span>{{ isset($address['contact_person_number']) ? $address['contact_person_number'] : '' }}</span>
                                    </h5>
                                    <h5 class="text-break d-flex">
                                        <span class="word-nobreak">{{ translate('messages.address') }}</span> <span>:</span>
                                        <span>{{ isset($address['address']) ? $address['address'] : '' }}</span>
                                    </h5>
                                    @elseif ($trip->customer)
                                    <h5 class="d-flex">
                                        <span>{{ translate('messages.contact_name') }}</span> <span>:</span>
                                        <span>{{ $trip->customer?->f_name .' '.$trip->customer?->l_name }}</span>
                                    </h5>
                                    <h5 class="d-flex">
                                        <span>{{ translate('messages.phone') }}</span> <span>:</span>
                                        <span>{{ $trip->customer?->phone}}</span>
                                    </h5>
                                @endif
                            </div>
                        @endif
                    </div>
                    <table class="table invoice--table text-black mt-3">
                        <thead class="border-0">
                            <tr class="border-0">
                                <th>{{ translate('messages.desc') }}</th>
                                <th class="w-10p"></th>
                                <th>{{ translate('messages.price') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($trip->order_type == 'parcel')
                                <tr>
                                    <td>{{ translate('messages.delivery_charge') }}</td>
                                    <td class="text-center">1</td>
                                    <td>{{ \App\CentralLogics\Helpers::format_currency($trip->delivery_charge) }}</td>
                                </tr>
                            @else
                                @php($sub_total = 0)
                                <?php
                                if ($trip->prescription_order == 1) {
                                    $sub_total = $trip['order_amount'] - $trip['delivery_charge'] - $trip['total_tax_amount'] - $trip['dm_tips'] + $trip['store_discount_amount'];
                                }
                                ?>
                                @php($total_tax = 0)
                                @php($total_dis_on_pro = 0)
                                @php($add_ons_cost = 0)
                                @foreach ($trip->trip_details as $detail)
                                @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                            @endforeach
                        @endif

                    </tbody>
                </table>
                <div class="checkout--info">
                    <dl class="row text-right">
                        @if ($trip->order_type != 'parcel')
                            <dt class="col-6">{{ translate('messages.subtotal') }}
                                @if ($trip->tax_status == 'included' )
                                    ({{ translate('messages.TAX_Included') }})
                                    @endif
                                :</dt>
                            <dd class="col-6">
                                {{ \App\CentralLogics\Helpers::format_currency($sub_total + $add_ons_cost) }}</dd>
                            <dt class="col-6">{{ translate('messages.discount') }}:</dt>
                            <dd class="col-6">
                                -
                                {{ \App\CentralLogics\Helpers::format_currency($trip['store_discount_amount'] + $trip['flash_admin_discount_amount']  + $trip['flash_store_discount_amount']) }}
                            </dd>


                            <dt class="col-6">{{ translate('messages.coupon_discount') }}:</dt>
                            <dd class="col-6">
                                -
                                {{ \App\CentralLogics\Helpers::format_currency($trip['coupon_discount_amount']) }}
                            </dd>
                            @if ($trip['ref_bonus_amount'] > 0)
                            <dt class="col-6">{{ translate('messages.Referral_Discount') }}:</dt>
                            <dd class="col-6">
                                -
                                {{ \App\CentralLogics\Helpers::format_currency($trip['ref_bonus_amount']) }}
                            </dd>
                            @endif
                            @if ($trip->tax_status == 'excluded' || $trip->tax_status == null  )
                            <dt class="col-6">{{ translate('messages.vat/tax') }}:</dt>
                            <dd class="col-6">+
                                {{ \App\CentralLogics\Helpers::format_currency($trip['total_tax_amount']) }}</dd>
                            @endif
                            <dt class="col-6">{{ translate('messages.delivery_man_tips') }}:</dt>
                            <dd class="col-6">
                                @php($delivery_man_tips = $trip['dm_tips'])
                                + {{ \App\CentralLogics\Helpers::format_currency($delivery_man_tips) }}
                            </dd>
                            <dt class="col-6">{{ translate('messages.delivery_charge') }}:</dt>
                            <dd class="col-6">
                                @php($del_c = $trip['delivery_charge'])
                                {{ \App\CentralLogics\Helpers::format_currency($del_c) }}
                            </dd>
                        @else
                            <dt class="col-6">{{ translate('messages.delivery_man_tips') }}:</dt>
                            <dd class="col-6">
                                @php($delivery_man_tips = $trip['dm_tips'])
                                + {{ \App\CentralLogics\Helpers::format_currency($delivery_man_tips) }}
                            </dd>
                            @endif
                            <dt class="col-6">{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}:</dt>
                            <dd class="col-6">
                                @php($additional_charge = $trip['additional_charge'])
                                + {{ \App\CentralLogics\Helpers::format_currency($additional_charge) }}
                            </dd>

                            @if ($trip['extra_packaging_amount'] > 0)
                            <dt class="col-6">{{ translate('messages.Extra_Packaging_Amount') }}:</dt>
                            <dd class="col-6">
                                +
                                {{ \App\CentralLogics\Helpers::format_currency($trip['extra_packaging_amount']) }}
                            </dd>
                            @endif
                        <dt class="col-6 total">{{ translate('messages.total') }}:</dt>
                        <dd class="col-6 total">
                            {{ \App\CentralLogics\Helpers::format_currency($trip->order_amount) }}</dd>
                            @if ($trip?->payments)
                                @foreach ($trip?->payments as $payment)
                                    @if ($payment->payment_status == 'paid')
                                        @if ( $payment->payment_method == 'cash_on_delivery')

                                        <dt class="col-6 text-left">{{ translate('messages.Paid_with_Cash') }} ({{  translate('COD')}}) :</dt>
                                        @else

                                        <dt class="col-6 text-left">{{ translate('messages.Paid_by') }} {{  translate($payment->payment_method)}} :</dt>
                                        @endif
                                    @else

                                    <dt class="col-6 text-left">{{ translate('Due_Amount') }} ({{  $payment->payment_method == 'cash_on_delivery' ?  translate('messages.COD') : translate($payment->payment_method) }}) :</dt>
                                    @endif
                                <dd class="col-6 ">
                                    {{ \App\CentralLogics\Helpers::format_currency($payment->amount) }}
                                </dd>
                                @endforeach
                            @endif

                    </dl>
                    @if ($trip->payment_method != 'cash_on_delivery')
                        <div class="d-flex flex-row justify-content-between border-top">
                            <span class="d-flex">
                                <span>{{ translate('messages.Paid by') }}</span> <span>:</span>
                                <span>{{ translate('messages.' . $trip->payment_method) }}</span> </span>
                            <span> <span>{{ translate('messages.amount') }}</span> <span>:</span>
                                <span>{{ $trip->adjusment + $trip->order_amount }}</span> </span>
                            <span> <span>{{ translate('messages.change') }}</span> <span>:</span> <span>{{ abs($trip->adjusment) }}</span> </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="top-info mt-2">
                <img src="{{ asset('/public/assets/admin/img/invoice-star.png') }}" alt="" class="w-100">
                <div class="text-uppercase text-center">{{ translate('THANK YOU') }}</div>
                <img src="{{ asset('/public/assets/admin/img/invoice-star.png') }}" alt="" class="w-100">
                <div class="copyright">
                    &copy; {{ \App\Models\BusinessSetting::where(['key' => 'business_name'])->first()->value }}.
                    <span
                        class="d-none d-sm-inline-block">{{ \App\Models\BusinessSetting::where(['key' => 'footer_text'])->first()->value }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
