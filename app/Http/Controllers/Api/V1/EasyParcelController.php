<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EasyParcelCountry;
use App\Models\EasyParcelState;
use App\Traits\EasyPercelEngineTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EasyParcelController extends Controller
{
    use EasyPercelEngineTrait;
    public function countryList(Request $request)
    {
        $countries = EasyParcelCountry::Active()->latest()->get();
        return response()->json($countries, 200);
    }
    public function stateList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'easy_parcel_country_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $states = EasyParcelState::where('easy_parcel_country_id', $request->easy_parcel_country_id)->Active()->latest()->get();
        return response()->json($states, 200);
    }

    public function rateCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pick_code' => 'required',
            'pick_state' => 'required',
            'pick_country' => 'required',
            'send_code' => 'required',
            'send_state' => 'required',
            'send_country' => 'required',
            'weight' => 'required|numeric|min:0.1',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'pick_code' => $request['pick_code'],
            'pick_state' => $request['pick_state'],
            'pick_country' => $request['pick_country'],
            'send_code' => $request['send_code'],
            'send_state' => $request['send_state'],
            'send_country' => $request['send_country'],
            'weight' => (float)$request['weight']
        ];


        return response()->json($this->rateCheckEngine($data), 200);
    }

    public function submitOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|min:0.1',
            'content' => 'required|string',
            'value' => 'required|numeric|min:1',
            'service_id' => 'required|string',

            'pick_name' => 'required|string',
            'pick_contact' => 'required|string',
            'pick_addr1' => 'required|string',
            'pick_city' => 'required|string',
            'pick_state' => 'required|string',
            'pick_code' => 'required|string',
            'pick_country' => 'required|string',

            'send_name' => 'required|string',
            'send_contact' => 'required|string',
            'send_addr1' => 'required|string',
            'send_city' => 'required|string',
            'send_state' => 'required|string',
            'send_code' => 'required|string',
            'send_country' => 'required|string',

            'collect_date' => 'required|date|after_or_equal:today',
            'send_email' => 'nullable|email',
            'sms' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $orderData = $request->only([
            'weight',
            'content',
            'value',
            'service_id',
            'pick_name',
            'pick_contact',
            'pick_addr1',
            'pick_city',
            'pick_state',
            'pick_code',
            'pick_country',
            'send_name',
            'send_contact',
            'send_addr1',
            'send_city',
            'send_state',
            'send_code',
            'send_country',
            'collect_date',
            'send_email',
            'sms'
        ]);

        $orderData['weight'] = (float) $orderData['weight'];
        $orderData['value'] = (float) $orderData['value'];
        $orderData['sms'] = filter_var($orderData['sms'], FILTER_VALIDATE_BOOLEAN);

        $response = $this->orderSubmitEngine($orderData);

        return response()->json($response, $response['success'] ? 200 : 500);
    }

    public function payOrder(Request $request)
    { // This function is only for the sandbox environment
        $validator = Validator::make($request->all(), [
            'order_no' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $payload = [
            'order_no' => $request->order_no,
        ];

        $response = $this->payEngine($payload);

        return response()->json($response, $response['success'] ? 200 : 500);
    }


    public function orderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_no' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $payload = [
            'order_no' => $request->order_no,
        ];

        $response = $this->orderStatusEngine($payload);

        return response()->json($response, $response['success'] ? 200 : 500);
    }

    public function trackParcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'awb_no' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $payload = [
            'awb_no' => $request->awb_no,
        ];

        $response = $this->trackingEngine($payload);

        return response()->json($response, $response['success'] ? 200 : 500);
    }
}
