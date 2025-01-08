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
                        <img class="invoice-logo" src="{{ asset('/public/assets/admin/img/car_icon.svg') }}"
                            alt="">
                        <div class="top-info">
                            <h2 class="store-name">
                                {{-- {{ $trip->provider->name }} --}}
                                ABC Car Rental
                            </h2>
                            <div>
                                <img src="{{ asset('/public/assets/admin/img/location_icon.svg') }}" alt="">
                                {{ $trip->provider->address }}
                            </div>
                            <div class="mt-1 d-flex justify-content-center">
                                <span><img src="{{ asset('/public/assets/admin/img/phone_icon.svg') }}" alt=""></span>&nbsp;
                                <span>{{ $trip->provider->phone }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="img-wrap">
                    <div class="top-info">
                        <img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100">
                        <div>{{ translate('messages.cash_receipt') }}</div>
                        <img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100">
                    </div>
                    <div class="order-info-id text-center">
                        <div class="d-flex justify-content-center mb-2 fs-12">
                            <span class="fw-medium">{{ translate('trip_Id') }}</span>
                            <span>:</span>
                            <span class="fw-medium">{{ $trip['id'] }}</span>
                        </div>
                        <div>
                            Wed, May 27, 2020 • 9:27:53 AM
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
                                <div class="mb-1">
                                    <span class="opacity-70">{{ translate('messages.customer_name') }}</span> <span>:</span>
                                    <span>Victor Shoaga</span>
                                </div>
                                <div class="mb-1">
                                    <span class="opacity-70">{{ translate('messages.phone') }}</span> <span>:</span>
                                    <span>+880154865474</span>
                                </div>
                                <div class="text-break mb-1">
                                    <span class="opacity-70">{{ translate('messages.address') }}</span> <span>:</span>
                                    <span>7953 Oakland St Honolulu, HI 96815</span>
                                </div>
                            </div>
                        </div>

                        <div><img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100"></div>

                        <div>
                            <table class="table invoice--table text-black mb-1">
                                <thead class="border-0">
                                    <tr class="border-0">
                                        <th>{{ translate('messages.Vehicle_List') }}</th>
                                        <th class="w-10p"></th>
                                        <th>{{ translate('messages.price') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <div>1.</div>
                                                <div class="opacity-70">
                                                    <strong class="d-block mb-1">Toyota Harrier 2006</strong>
                                                    <span class="fs-9">$25.00/hour, 1 Vehicle, 5 Hours</span><br>
                                                    <span class="fs-9">Vehicles: Dhk-ka-21-3254</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>$125.00</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <div>2.</div>
                                                <div class="opacity-70">
                                                    <strong class="d-block mb-1">Toyota HiAce 2015</strong>
                                                    <span class="fs-9">$25.00/hour, 1 Vehicle, 5 Hours</span><br>
                                                    <span class="fs-9">Vehicles: Dhk-ka-21-3254</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>$6,870.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div><img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100"></div>

                        <div class="checkout--info">
                            <dl class="row text-right">
                                <dt class="col-6 opacity-70">{{ translate('messages.Subtotal') }}:</dt>
                                <dd class="col-6"> $6,995.00</dd>

                                <dt class="col-6 opacity-70">{{ translate('messages.Discount') }}:</dt>
                                <dd class="col-6">  -$50.00</dd>

                                <dt class="col-6 opacity-70">{{ translate('messages.Coupon_Discount') }}:</dt>
                                <dd class="col-6"> -$10.00</dd>

                                <dt class="col-6 opacity-70">{{ translate('messages.tax') }}:</dt>
                                <dd class="col-6"> +$5.00</dd>

                                <dt class="col-6 total">{{ translate('messages.total') }}:</dt>
                                <dd class="col-6 total"> $6,940.00</dd>
                            </dl>
                        </div>
                    </div>

                    <div><img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100"></div>

                    <div class="checkout--info">
                        <dl class="row text-right">
                            <dt class="col-6 opacity-70">{{ translate('messages.Paid_By') }}:</dt>
                            <dd class="col-6"> Cash</dd>

                            <dt class="col-6 opacity-70">{{ translate('messages.Paid_Amount') }}:</dt>
                            <dd class="col-6">  $6,940.00</dd>

                            <dt class="col-6 opacity-70">{{ translate('messages.Change_Return') }}:</dt>
                            <dd class="col-6"> $0.00</dd>
                        </dl>
                    </div>

                    <div class="top-info">
                        <img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100">
                        <div>{{ translate('Thank You') }}</div>
                        <img src="{{ asset('/public/assets/admin/img/line_icon.svg') }}" alt="" class="w-100">

                        <div class="copyright">
                            &copy; {{ \App\Models\BusinessSetting::where(['key' => 'business_name'])->first()->value }}.
                            <span class="d-none d-sm-inline-block">{{ \App\Models\BusinessSetting::where(['key' => 'footer_text'])->first()->value }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
