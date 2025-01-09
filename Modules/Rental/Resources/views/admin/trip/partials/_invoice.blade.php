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
                                    <span>{{ $trip->customer?->fullName }}</span>
                                </h5>
                                <h5 class="d-flex">
                                    <span>{{ translate('messages.phone') }}</span> <span>:</span>
                                    <span>{{ $trip->customer?->phone}}</span>
                                </h5>
                            @endif
                        </div>
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
                            @php($sub_total = 0)
                            @php($total_tax = 0)
                            @php($total_dis_on_pro = 0)
                            @php($add_ons_cost = 0)

                            @foreach($trip->trip_details as $details)
                                <tr>
                                    <td class="text-break">{{ $details?->vehicle?->name }} <br> {{ \App\CentralLogics\Helpers::format_currency($details['price']) }}</td>
                                    <td class="text-center">
                                        {{ $details['quantity'] }}
                                    </td>
                                    <td class="w-28p">
                                        @php($amount = $details['price'] * $details['quantity'])
                                        {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                    </td>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $details['tax_amount'] * $details['quantity'])
                                </tr>
                            @endforeach

                    </tbody>
                </table>
                <div class="checkout--info">
                    <dl class="row text-right">
                        <dt class="col-6">{{ translate('messages.subtotal') }}
                            @if ($trip->tax_status == 'included' )
                                ({{ translate('messages.TAX_Included') }})
                            @endif
                            :
                        </dt>
                        <dd class="col-6"> {{ \App\CentralLogics\Helpers::format_currency($sub_total + $add_ons_cost) }}</dd>

                        <dt class="col-6">{{ translate('messages.discount') }}:</dt>
                        <dd class="col-6">
                            -
                            {{ \App\CentralLogics\Helpers::format_currency($trip['discount_on_trip'])}}
                        </dd>


                        <dt class="col-6">{{ translate('messages.coupon_discount') }}:</dt>
                        <dd class="col-6">
                            -
                            {{ \App\CentralLogics\Helpers::format_currency($trip['coupon_discount_amount']) }}
                        </dd>
                        @if ($trip->tax_status == 'excluded' || $trip->tax_status == null  )
                        <dt class="col-6">{{ translate('messages.vat/tax') }}:</dt>
                        <dd class="col-6">+
                            {{ \App\CentralLogics\Helpers::format_currency($trip['tax_amount']) }}</dd>
                        @endif
                        <dt class="col-6 total">{{ translate('messages.total') }}:</dt>
                        <dd class="col-6 total">{{ \App\CentralLogics\Helpers::format_currency($trip->trip_amount) }}</dd>
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
