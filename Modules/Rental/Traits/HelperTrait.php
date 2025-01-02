<?php

namespace Modules\Rental\Traits;

use App\Models\User;
use App\Models\Admin;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Expense;
use App\Models\AdminWallet;
use App\Models\StoreWallet;
use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\CentralLogics\OrderLogic;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\PartialPayment;
use Modules\Rental\Entities\TripTransaction;

trait HelperTrait
{



    public static function create_transaction($trip, $received_by=false, $status = null){
        $amount_admin = 0;
        $store_d_amount = 0;
        $admin_coupon_discount_subsidy =0;
        $store_coupon_discount_subsidy =0;
        $discount_on_trip=0;
        $comission_on_store_amount=0;
        $ref_bonus_amount=0;


        $provider= $trip?->provider;
        $store_sub = $provider?->store_sub;

        // coupon discount by Admin
        if($trip->coupon_created_by == 'admin')
        {
            $admin_coupon_discount_subsidy = $trip->coupon_discount_amount;
            self::expenseCreate(amount:$admin_coupon_discount_subsidy,type:'coupon_discount',datetime:now(),created_by:$trip->coupon_created_by,trip_id:$trip->id);
        }
        // 1st order discount by Admin
        if($trip->ref_bonus_amount > 0)
        {
            $ref_bonus_amount = $trip->ref_bonus_amount;
            self::expenseCreate(amount:$ref_bonus_amount,type:'referral_discount',datetime:now(),created_by:'admin',trip_id:$trip->id);
        }
        // coupon discount by store
        if($trip->coupon_created_by == 'vendor')
        {
            $store_coupon_discount_subsidy = $trip->coupon_discount_amount;
            self::expenseCreate(amount:$store_coupon_discount_subsidy,type:'coupon_discount',datetime:now(),created_by:$trip->coupon_created_by, trip_id:$trip->id,store_id:$provider->id);
        }

        if($trip?->cashback_history){
            self::cashbackToWallet($trip);
        }

            $comission =   $provider?->comission ??  BusinessSetting::where('key','admin_commission')->first()->value;


            if($trip->discount_on_trip > 0  && $trip->discount_on_trip_by == 'vendor')
            {
                if($provider->store_business_model == 'subscription' && isset($store_sub)){
                    $store_d_amount=  $trip->discount_on_trip;
                    self::expenseCreate(amount:$store_d_amount,type:'discount_on_trip',datetime:now(),created_by:'vendor',trip_id:$trip->id,store_id:$trip->store->id);
                }
                else{
                    $amount_admin = $comission?($trip->discount_on_trip/ 100) * $comission:0;
                    $store_d_amount=  $trip->discount_on_trip- $amount_admin;
                    self::expenseCreate(amount:$store_d_amount,type:'discount_on_trip',datetime:now(),created_by:'vendor',trip_id:$trip->id,store_id:$trip->store->id);
                    self::expenseCreate(amount:$amount_admin,type:'discount_on_trip',datetime:now(),created_by:'admin',trip_id:$trip->id);
                }
            }

            if($trip->discount_on_trip > 0  && $trip->discount_on_trip_by == 'admin')
            {
                $discount_on_trip=$trip->discount_on_trip;
                self::expenseCreate(amount:$discount_on_trip,type:'discount_on_trip',datetime:now(),created_by:'admin',trip_id:$trip->id);
            }



            $trip_amount = $trip->trip_amount - $trip->additional_charge  -  $trip->tax_amount   + $trip->coupon_discount_amount + $discount_on_trip  + $ref_bonus_amount;


            //final comission
            if($provider->store_business_model == 'subscription' && isset($store_sub)){
                $comission_on_store_amount =0;
                $subscription_mode= 1;
                $commission_percentage= 0;
            } else{
                $comission_on_store_amount = ($comission?($trip_amount/ 100) * $comission:0);
                $subscription_mode= 0;
                $commission_percentage= $comission;
            }

            $comission_amount = $comission_on_store_amount ;

        $store_amount = $trip_amount + $trip->tax_amount  - $comission_on_store_amount - $store_coupon_discount_subsidy;
        try{
            TripTransaction::insert([
                'vendor_id' =>$provider->vendor->id,
                'provider_id' =>$provider->id,
                'trip_id' =>$trip->id,
                'trip_amount'=>$trip->trip_amount,
                'store_amount'=>$store_amount,
                'admin_commission'=>$comission_amount + $trip->additional_charge - $admin_coupon_discount_subsidy - $ref_bonus_amount,
                'tax'=>$trip->tax_amount,
                'received_by'=> $received_by?$received_by:'admin',
                'zone_id'=>$trip->zone_id,
                'module_id'=>$trip->module_id,
                'admin_expense'=> $admin_coupon_discount_subsidy + $discount_on_trip + $amount_admin + $ref_bonus_amount,
                'store_expense'=>  $store_coupon_discount_subsidy + $store_d_amount,
                'status'=> $status,
                'created_at' => now(),
                'updated_at' => now(),
                'discount_amount_by_store' => $store_coupon_discount_subsidy + $store_d_amount ,
                'additional_charge' => $trip->additional_charge,
                'ref_bonus_amount' => $trip->ref_bonus_amount,
                 // for store business model
                'is_subscribed'=> $subscription_mode,
                'commission_percentage'=> $commission_percentage,
            ]);
            $adminWallet = AdminWallet::firstOrNew(
                ['admin_id' => Admin::where('role_id', 1)->first()->id]
            );

            $adminWallet->total_commission_earning = $adminWallet->total_commission_earning + $comission_amount + $trip->additional_charge - $admin_coupon_discount_subsidy -$discount_on_trip  - $ref_bonus_amount;

            $vendorWallet = StoreWallet::firstOrNew(
                ['vendor_id' => $provider->vendor->id]
            );
            $vendorWallet->total_earning = $vendorWallet->total_earning+$store_amount;


            try
            {
                DB::beginTransaction();
                $unpaid_payment = PartialPayment::where('payment_status','unpaid')->where('trip_id',$trip->id)->first()?->payment_method;
                $unpaid_pay_method = 'digital_payment';
                if($unpaid_payment){
                    $unpaid_pay_method = $unpaid_payment;
                }

                if($received_by=='admin')
                {
                    $adminWallet->digital_received = $adminWallet->digital_received+($trip->trip_amount-$trip->partially_paid_amount);
                }
                else if($received_by=='store' &&  ($trip->payment_method == "cash_payment" || $unpaid_pay_method == 'cash_payment'))
                {
                    $store_over_flow =  true ;
                    $vendorWallet->collected_cash = $vendorWallet->collected_cash+($trip->trip_amount-$trip->partially_paid_amount);
                }
                else if($received_by==false)
                {
                    $adminWallet->manual_received = $adminWallet->manual_received+($trip->trip_amount-$trip->partially_paid_amount);
                }


                $adminWallet->save();
                $vendorWallet->save();


                if(isset($store_over_flow) ){
                    self::create_account_transaction_for_collect_cash(old_collected_cash:$vendorWallet->collected_cash , from_type:'store' , from_id: $provider->vendor->id , amount: $trip->trip_amount - $trip->partially_paid_amount ,trip_id: $trip->id);
                }


                OrderLogic::update_unpaid_trip_payment(trip_id:$trip->id, payment_method:$trip->payment_method);

                DB::commit();

                if($trip->is_guest  == 0){
                    $ref_status = BusinessSetting::where('key','ref_earning_status')->first()->value;
                    if(isset($trip->customer->ref_by) && $trip->customer->order_count == 0  && $ref_status == 1){
                        $ref_code_exchange_amt = BusinessSetting::where('key','ref_earning_exchange_rate')->first()->value;
                        $referar_user=User::where('id',$trip->customer->ref_by)->first();
                        $refer_wallet_transaction = CustomerLogic::create_wallet_transaction($referar_user->id, $ref_code_exchange_amt, 'referrer',$trip->customer->phone);

                        $notification_data = [
                            'title' => translate('messages.Congratulation'),
                            'description' => translate('You have received').' '.Helpers::format_currency($ref_code_exchange_amt).' '.translate('in your wallet as').' '.$trip?->customer?->f_name.' '.$trip?->customer?->l_name.' '.translate('you referred completed thier first order') ,
                            'trip_id' => 1,
                            'image' => '',
                            'type' => 'referral_code',
                        ];

                        // if(Helpers::getNotificationStatusData('customer','customer_referral_bonus_earning','push_notification_status') && $referar_user?->cm_firebase_token){
                        //     Helpers::send_push_notif_to_device($referar_user?->cm_firebase_token, $notification_data);
                        //     DB::table('user_notifications')->insert([
                        //         'data' => json_encode($notification_data),
                        //         'user_id' => $referar_user?->id,
                        //         'created_at' => now(),
                        //         'updated_at' => now()
                        //     ]);
                        // }


                        try{
                            // Helpers::add_fund_push_notification($referar_user->id);
                            // if(config('mail.status') && Helpers::get_mail_status('add_fund_mail_status_user') == '1' && Helpers::getNotificationStatusData('customer','customer_add_fund_to_wallet','mail_status') ) {
                            //     Mail::to($referar_user->email)->send(new \App\Mail\AddFundToWallet($refer_wallet_transaction));
                            // }
                        } catch(\Exception $ex){
                            info($ex->getMessage());
                        }
                    }

                    $create_loyalty_point_transaction= CustomerLogic::create_loyalty_point_transaction($trip->user_id, $trip->id, $trip->trip_amount, 'trip_booking');
                    if($create_loyalty_point_transaction > 0) {
                        $notification_data = [
                            'title' => translate('messages.Congratulation'),
                            'description' => translate('You_have_received').' '.$create_loyalty_point_transaction.' '.translate('points_as_loyalty_point'),
                            'trip_id' => $trip->id,
                            'image' => '',
                            'type' => 'loyalty_point',
                        ];

                        // if(Helpers::getNotificationStatusData('customer','customer_loyalty_point_earning','push_notification_status') && $trip->customer?->cm_firebase_token){
                        //     Helpers::send_push_notif_to_device($trip->customer?->cm_firebase_token, $notification_data);
                        //     DB::table('user_notifications')->insert([
                        //         'data' => json_encode($notification_data),
                        //         'user_id' => $trip->user_id,
                        //         'created_at' => now(),
                        //         'updated_at' => now()
                        //     ]);
                        // }

                    }
                }


            }
            catch(\Exception $e)
            {
                DB::rollBack();
                info($e->getMessage());
                return false;
            }
        }
        catch(\Exception $e){
            info($e->getMessage());
            return false;
        }

        return true;
    }



    public static function expenseCreate($amount,$type,$datetime,$created_by,$trip_id=null,$store_id=null,$description='',$delivery_man_id=null,$user_id=null)
    {
        $expense = new Expense();
        $expense->amount = $amount;
        $expense->type = $type;
        $expense->trip_id = $trip_id;
        $expense->created_by = $created_by;
        $expense->store_id = $store_id;
        $expense->delivery_man_id = $delivery_man_id;
        $expense->user_id = $user_id;
        $expense->description = $description;
        $expense->created_at = now();
        $expense->updated_at = now();
        return $expense->save();
    }

    public static function create_account_transaction_for_collect_cash($old_collected_cash, $from_type ,$from_id ,$amount, $trip_id){
        $account_transaction = new AccountTransaction();
        $account_transaction->from_type =$from_type;
        $account_transaction->from_id = $from_id;
        $account_transaction->created_by = $from_type;
        $account_transaction->method = 'cash_collection';
        $account_transaction->ref = $trip_id;
        $account_transaction->amount = $amount ?? 0;
        $account_transaction->current_balance = $old_collected_cash ?? 0;
        $account_transaction->type = 'cash_in';
        $account_transaction->save();

        if($from_type  ==  'store'){
            $vendor= Vendor::find($from_id);
            $Payable_Balance = $vendor?->wallet?->collected_cash   > 0 ? 1: 0;
            $cash_in_hand_overflow= BusinessSetting::where('key' ,'cash_in_hand_overflow_store')->first()?->value;
            $cash_in_hand_overflow_store_amount = BusinessSetting::where('key' ,'cash_in_hand_overflow_store_amount')->first()?->value;

            if ($Payable_Balance == 1 &&  $cash_in_hand_overflow && $vendor?->wallet?->balance<0 &&  $cash_in_hand_overflow_store_amount <= abs($vendor?->wallet?->collected_cash)){
                $rest= Store::where('vendor_id', $vendor->id)->first();
                $rest->status = 0 ;
                $rest->save();
            }
        }
        return true;
    }

    public static function cashbackToWallet($trip){

        $refer_wallet_transaction = CustomerLogic::create_wallet_transaction($trip?->cashback_history?->user_id, $trip?->cashback_history?->calculated_amount, 'CashBack',$trip->id);
        if($refer_wallet_transaction != false){
            self::expenseCreate(amount:$trip?->cashback_history?->calculated_amount,type:'CashBack',datetime:now(),created_by:'admin', trip_id:$trip->id);
            $trip?->cashback_history?->cashBack?->increment('total_used');

            // $notification_data = [
            //     'title' => translate('messages.Congratulation_you_have_received').' '.$trip?->cashback_history?->calculated_amount.' '.translate('cashback'),
            //     'description' => translate('The_cashback_amount_successfully_added_to_your_wallet') ,
            //     'trip_id' => $trip->id,
            //     'image' => '',
            //     'type' => 'cashback',
            // ];

            // if($trip->customer?->cm_firebase_token && Helpers::getNotificationStatusData('customer','customer_cashback','push_notification_status')){
            //     Helpers::send_push_notif_to_device($trip->customer?->cm_firebase_token, $notification_data);
            //     DB::table('user_notifications')->insert([
            //         'data' => json_encode($notification_data),
            //         'user_id' => $trip->customer?->id,
            //         'created_at' => now(),
            //         'updated_at' => now()
            //     ]);
            // }

        }

        return true;
    }
}
