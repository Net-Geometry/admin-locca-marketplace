<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Store;
use App\Library\Payer;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Validator;
use App\Library\Payment as PaymentInfo;
use App\Traits\Payment;
use App\Library\Receiver;

class SubscriptionController extends Controller
{
    public function package_view(){
        $packages= SubscriptionPackage::where('status',1)->latest()->get();
        return response()->json(['packages'=> $packages], 200);
    }
    public function business_plan(Request $request){

        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
            'payment' => 'nullable',
            'business_plan' => 'required|in:subscription,commission',
            'package_id' => 'nullable|required_if:business_plan,subscription',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $store=Store::findOrFail($request->store_id);

        if($request->business_plan == 'subscription' && $request->package_id != null ) {
            $store_id=$store->id;
            $package_id=$request->package_id;
            $payment_method=$request->payment_method ?? 'free_trial';
            $reference=$request->reference ?? null;
            $discount=$request->discount ?? 0;
            $store=Store::findOrFail($store_id);
            $type=$request->type ?? 'new_join';

            if($request->payment == 'free_trial' ){
                $status=Helpers::subscription_plan_chosen(store_id:$store_id , package_id:$package_id,payment_method: $payment_method ,discount:$discount, reference:$reference ,type: $type);

                if($status === 'downgrade_error'){
                    return response()->json([
                        'errors' => ['message' => translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits')]
                    ], 403);
                }
            }
            elseif($request->payment == 'paying_now'){
                $digital_payment = Helpers::get_business_settings('digital_payment');
                if( $digital_payment['status'] != 1){
                    return response()->json([
                        'errors' => ['message' => translate('messages.Digital_Payment_is_disable')]
                    ], 403);
                }

                $status= Helpers::subscription_plan_chosen(store_id:$store_id , package_id:$package_id,payment_method: 'pay_now' ,discount:$discount, reference:$reference ,type: $type);
                if($status === 'downgrade_error'){
                    return response()->json([
                        'errors' => ['message' => translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits')]
                    ], 403);
                }
                return response()->json(['id'=>$status],200);
            }
            $data=[
            'store_business_model' => 'subscription',
            'logo'=> $store->logo,
            'message' => translate('messages.application_placed_successfully')
            ];
            return response()->json($data,200);
        }

        elseif($request->business_plan == 'commission' ){
            $store->store_business_model = 'commission';
            $store->save();

        $data=['store_business_model' => 'commission',
        'logo'=> $store->logo,
        'message' => translate('messages.application_placed_successfully')
        ];
        return response()->json($data,200);
        }
    }


    public function subscription_payment_api(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'callback' => 'nullable',
            'payment_gateway' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $subscription = SubscriptionTransaction::with('store')->where('transaction_status',0)->findOrFail($request->id);
        $payer = new Payer(
            $subscription->store->name ,
            $subscription->store->email,
            $subscription->store->phone,
            ''
        );
        $additional_data = [
            'business_name' => BusinessSetting::where(['key'=>'business_name'])->first()?->value,
            'business_logo' => asset('storage/app/public/business') . '/' .BusinessSetting::where(['key' => 'logo'])->first()?->value
        ];
        $payment_info = new PaymentInfo(
            success_hook: 'sub_success',
            failure_hook: 'sub_fail',
            currency_code: Helpers::currency_code(),
            payment_method: $request->payment_gateway,
            payment_platform: 'web',
            payer_id: $subscription->store_id,
            receiver_id: '100',
            additional_data:  $additional_data,
            payment_amount: $subscription->paid_amount ,
            external_redirect_link: $request->has('callback')?$request['callback']:session('callback'),
            attribute: 'store_subscription_payments',
            attribute_id: $subscription->id,
        );

        $receiver_info = new Receiver('Admin','example.png');
        $redirect_link = Payment::generate_link($payer, $payment_info, $receiver_info);
        $data = [
            'redirect_link' => $redirect_link,
            // 'type'=> 'subscription'
        ];
        return response()->json($data, 200);
    }


    public function transaction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        $key = explode(' ', $request['search']);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limite']??25;
        $offset = $request['offset']??1;
        $from = $request->from;
        $to = $request->to;
        $store_id = $request->vendor->stores[0]->id;

        $transactions=  SubscriptionTransaction::where('store_id', $store_id)->latest()
        ->with('store:id,name','package:id,package_name')
        ->when(isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
                $q->orWhereHas('store' , function ($q) use ($key) {
                    foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
                });
            });
        })
        ->when(isset($from) &&  isset($to) ,function($query) use($from,$to){
            $query->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:29']);
        })

        ->paginate($limit, ['*'], 'page', $offset);

            $data = [
                'total_size' => $transactions->total(),
                'limit' => $limit,
                'offset' => $offset,
                'transactions' => $transactions->items()
            ];
            return response()->json($data,200);
    }
}
