<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use App\Traits\Processor;
use App\Models\PaymentRequest;

class ChipPaymentController extends Controller
{
    use Processor;

    private mixed $config_values;
    private PaymentRequest $payment;

    public function __construct(PaymentRequest $payment)
    {
        $config = $this->payment_config('chip', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values, true);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values, true);
        }
        $this->payment = $payment;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);
        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }
        $paymentData = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($paymentData)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
        $amount = intval(round($paymentData->payment_amount * 100));
        $reference = "REF-" . time();
        $payer = json_decode($paymentData->payer_information);
        $curl = curl_init();
        $data = [
            "client" => [
                "email" => $payer->email,
                "phone" => $payer->phone,
                "full_name" => $payer->name
            ],
            "purchase" => [
                "products" => [
                    [
                        "name" => "purchase",
                        "price" => $amount,
                        "quantity" => 1
                    ]
                ],
                "currency" => $paymentData->currency_code
            ],
            "brand_id" => $this->config_values['brand_id'],
            "send_receipt" => true,
            "reference" => $reference,
            "success_redirect" => route('chip.successUrl', ['payment_id' => $paymentData->id]),
            "failure_redirect" => route('chip.failureUrl', ['payment_id' => $paymentData->id]),
            "cancel_redirect" => route('chip.cancelUrl', ['payment_id' => $paymentData->id]),
            "success_callback" => route('chip.callback', ['payment_id' => $paymentData->id]),

        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://gate.chip-in.asia/api/v1/purchases/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->config_values['api_key'],
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        if (isset($result['checkout_url'])) {
            $checkout_url = $result['checkout_url'];
            return redirect()->to($checkout_url);
        }
        return $this->payment_response($paymentData, 'fail');
    }



    public function successUrl(Request $request)
    {
        $paymentData = $this->payment::where(['id' => $request['payment_id']])->first();
        return $this->payment_response($paymentData, 'success');
    }
    public function failureUrl(Request $request)
    {
        $paymentData = $this->payment::where(['id' => $request['payment_id']])->first();
        return $this->payment_response($paymentData, 'fail');
    }

    public function cancelUrl(Request $request)
    {
        $paymentData = $this->payment::where(['id' => $request['payment_id']])->first();
        return $this->payment_response($paymentData, 'fail');
    }

    public function callback(Request $request)
    {
        $purchaseId = $request->id; // Replace with actual purchase ID
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://gate.chip-in.asia/api/v1/purchases/$purchaseId/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer ". $this->config_values['api_key']

            ]
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response,true);
        $data = $this->payment::where(['id' => $request['payment_id']])->first();
        if($data->is_paid == 1){
            return $this->payment_response($data, 'success');

        }

        if (isset($result['status']) && $result['status'] == "paid" && $data->is_paid == 0) {
            $this->payment::where(['id' => $request['payment_id']])->update([
                'payment_method' => 'chip',
                'is_paid' => 1,
                'transaction_id' => $request->reference,
            ]);
            $data = $this->payment::where(['id' => $request['payment_id']])->first();
            if (isset($data) && function_exists($data->success_hook)) {
                call_user_func($data->success_hook, $data);
            }
            return $this->payment_response($data, 'success');
        }
        $data = $this->payment::where(['id' => $request['payment_id']])->first();
        return $this->payment_response($data, 'fail');
    }
}
