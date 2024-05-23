<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Store;
use App\Models\StoreWallet;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Models\StoreSubscription;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Schema;
use App\Models\SubscriptionTransaction;
use App\Models\SubscriptionBillingAndRefundHistory;

class SubscriptionController extends Controller
{
    public function subscriberDetail(){
        $store= Store::where('id',Helpers::get_store_id())->with([
            'store_sub_update_application.package','vendor','store_sub_update_application.last_transcations'
        ])
        ->first();
        $packages = SubscriptionPackage::where('status',1)->latest()->get();
        $admin_commission=BusinessSetting::where('key', 'admin_commission')->first()?->value ;
        $business_name=BusinessSetting::where('key', 'business_name')->first()?->value ;

        return view('vendor-views.subscription.subscriber.vendor-subscription',compact('store','packages','business_name','admin_commission'));
    }

    public function cancelSubscription(Request $request, $id){

        StoreSubscription::where(['store_id' => $id, 'id'=>$request->subscription_id])->update([
            'is_canceled' => 1,
            'canceled_by' => 'vendor',
        ]);
        return response()->json(200);

    }
    public function switchToCommission($id){

        StoreSubscription::where(['store_id' => $id])->update([
            'status' => 0,
        ]);
        Store::where('id',$id)->update([
            'store_business_model' => 'commission',
        ]);
        return response()->json(200);

    }
    public function packageView($id,$store_id){
        $store_subscription= StoreSubscription::where('store_id', $store_id)->with(['package'])->latest()->first();
        $package = SubscriptionPackage::where('status',1)->where('id',$id)->first();

        $store= Store::Where('id',$store_id)->first(['id','vendor_id']);
        $pending_bill= SubscriptionBillingAndRefundHistory::where(['store_id'=>$store->id,
        'transaction_type'=>'pending_bill', 'is_success' =>0])?->sum('amount') ?? 0;

        $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? StoreWallet::where('vendor_id',$store->vendor_id)->first()?->balance ?? 0 : 0;
        $payment_methods = $this->getDefaultPaymentMethods();
        return response()->json([
            'view' => view('vendor-views.subscription.subscriber.partials._package_selected', compact('store_subscription','package','store_id','balance','payment_methods','pending_bill'))->render()
        ]);

    }
    public function packageBuy(Request $request){

        if( Helpers::subscriptionConditionsCheck(store_id:$request->store_id,package_id:$request->package_id) == 'downgrade_error'){
            Toastr::error( translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits'));
            return back();
        }

        $request->validate([
            'package_id' => 'required',
            'store_id' => 'required',
            'payment_gateway' => 'required'
        ]);
        $store= Store::Where('id',$request->store_id)->first(['id','vendor_id']);
        $package = SubscriptionPackage::withoutGlobalScope('translate')->find($request->package_id);
        $pending_bill= SubscriptionBillingAndRefundHistory::where(['store_id'=>$store->id,
        'transaction_type'=>'pending_bill', 'is_success' =>0])?->sum('amount') ?? 0;

        if(!in_array($request->payment_gateway,['wallet'])){
            $url= route('vendor.subscriptionackage.subscriberDetail',$store->id);
            return redirect()->away(Helpers::subscriptionPayment(store_id:$store->id,package_id:$package->id,payment_gateway:$request->payment_gateway,payment_platform:'web',url:$url,pending_bill:$pending_bill,type: $request?->type));
        }

        if($request->payment_gateway == 'wallet'){
        $wallet= StoreWallet::firstOrNew(['vendor_id'=> $store->vendor_id]);
        $balance = BusinessSetting::where('key', 'wallet_status')->first()?->value == 1 ? $wallet?->balance ?? 0 : 0;

            if($balance > ($package?->price + $pending_bill)){
                $reference= 'wallet_payment_by_vendor';
                $plan_data=   Helpers::subscription_plan_chosen(store_id:$store->id,package_id:$package->id,payment_method:$reference,discount:0,pending_bill:$pending_bill,reference:$reference,type: $request?->type);
                if($plan_data != false){
                    $wallet->total_withdrawn= $wallet?->total_withdrawn + $package->price +$pending_bill;
                    $wallet?->save();
                }
            }
            else{
                Toastr::error( translate('messages.Insufficient_balance_in_wallet'));
                return to_route('vendor.subscriptionackage.subscriberDetail',$store->id);

            }
        }

        $plan_data != false ?  Toastr::success( translate('Successfully_Subscribed.')) : Toastr::error( translate('Something_went_wrong!.'));
        return to_route('vendor.subscriptionackage.subscriberDetail',$store->id);

    }



    public function subscriberTransactions($id,Request $request){
        $filter= $request['filter'];
        $plan_type= $request['plan_type'];
        $from =$request['start_date'] ?? Carbon::now()->format('Y-m-d');
        $to =$request['expire_date'] ?? Carbon::now()->format('Y-m-d');
        $store= Store::where('id',$id)->with([
            'store_sub_update_application.package'
        ])
        ->first();

        $key = explode(' ', $request['search']);
        $transactions= SubscriptionTransaction::where('store_id',$id)
        ->when(isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
            });
        })
        ->when($filter == 'this_year' , function($query){
            $query->whereYear('created_at', Carbon::now()->year );
        })
        ->when($filter == 'this_month' , function($query){
            $query->whereMonth('created_at', Carbon::now()->month );
        })
        ->when($filter == 'this_week' , function($query){
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()] );
        })
        ->when($filter == 'custom' , function($query) use($from,$to) {
            $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })

        ->when( in_array( $plan_type,['renew','new_plan','first_purchased','free_trial'])  , function($query) use($plan_type){
            $query->where('plan_type', $plan_type );
        })

        ->latest()->paginate(config('default_pagination'));
            $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;
        return view('vendor-views.subscription.subscriber.transaction',compact('store','transactions','id','filter','subscription_deadline_warning_days'));

    }
    public function invoice($id){
        $BusinessData= ['admin_commission' ,'business_name','address','phone','logo','email_address'];
        $transaction= SubscriptionTransaction::with(['store.vendor','package:id,package_name,price'])->find($id);
        $BusinessData=BusinessSetting::whereIn('key', $BusinessData)->pluck('value' ,'key') ;
        $logo=BusinessSetting::where('key', "logo")->first() ;
        return view('vendor-views.subscription.subscription-invoice',compact('transaction','BusinessData','logo'))->render();
    }
    private function getDefaultPaymentMethods()
    {
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('is_active',1)->whereIn('settings_type', ['payment_config'])->whereIn('key_name', ['ssl_commerz','paypal','stripe','razor_pay','senang_pay','paytabs','paystack','paymob_accept','paytm','flutterwave','liqpay','bkash','mercadopago'])->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }
        return $data;
    }

}
