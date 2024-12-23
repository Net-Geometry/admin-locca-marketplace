<?php

namespace Modules\Rental\Http\Controllers\Api\User;


use App\Models\Zone;
use App\Models\Store;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\RentalCart;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Modules\Rental\Entities\RentalCartUserData;
use App\CentralLogics\CouponLogic;
use Modules\Rental\Entities\Trips;

class TripController extends Controller
{
    public function __construct(private RentalCart $cart, private RentalCartUserData $user_data, private Helpers $helpers, private Vehicle $vehicle,)
    {
        $this->cart = $cart;
        $this->user_data = $user_data;
        $this->helpers = $helpers;
        $this->vehicle = $vehicle;
    }

    public function TripBooking(Request $request)
    {

            $validator = Validator::make($request->all(), [
                'trip_amount' => 'required|numeric',
                'trip_type' => 'required|in:hourly,distance_wise',
                'provider_id' => 'required|numeric',
                'longitude' => 'required|numeric',
                'latitude' => 'required|numeric',
                'distance' => 'required_if:trip_type,distance_wise',
                'estimated_hours' => 'required_if:trip_type,hourly',
                'guest_id' => $request->user ? 'nullable' : 'required',
            ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;


        $schedule_at = $request->schedule_at?\Carbon\Carbon::parse($request->schedule_at):now();

        $estimated_trip_end_time = $schedule_at->copy()->addHours($request->time?? 1);

        $user_data =  $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? [];
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with('vehicles')->get();

        $trip_validation_check =  $this->trip_validation_check($request,$schedule_at);

        if(data_get($trip_validation_check,'status_code') === 403 ){
            return response()->json([
                        'errors' => [
                            ['code' => data_get($trip_validation_check,'code'), 'message' => data_get($trip_validation_check,'message')]
                        ]
                    ], data_get($trip_validation_check,'status_code'));
        } else{
            $provider = $trip_validation_check;
        }


        DB::beginTransaction();

        if ($request['coupon_code']) {
            $coupon_check =  $this->coupon_check($request);
            if(data_get($coupon_check,'code') === 'coupon' ){
                return response()->json([
                            'errors' => [
                                ['code' => data_get($coupon_check,'code'), 'message' => data_get($coupon_check,'message')]
                            ]
                        ], data_get($coupon_check,'status_code'));
            } else{
                $coupon = data_get($coupon_check,'coupon');
                $coupon_created_by = data_get($coupon_check,'coupon_created_by');
            }
        }

        $tax_included = BusinessSetting::where(['key' => 'tax_included'])->first()?->value ?? 0;

        $details_data =  $this->trip_details(request:$request,user_data:$user_data,carts:$carts,schedule_at:$schedule_at,estimated_trip_end_time: $estimated_trip_end_time,tax: $provider->tax,is_include :$tax_included,provider : $provider);

        if(data_get($details_data,'code') === 'details_data' ){
            return response()->json([
                        'errors' => [
                            ['code' => data_get($details_data,'code'), 'message' => data_get($details_data,'message')]
                        ]
                    ], data_get($details_data,'status_code'));
        } else{
            $details_data = data_get($details_data,'details_data');
            $price = data_get($details_data,'price');
            $discount_on_trip = data_get($details_data,'discount_on_trip');
            $tax_amount = data_get($details_data,'tax_amount');
            $discount_on_trip_by = data_get($details_data,'discount_on_trip_by');
        }



        $make_trip_data=[
            'user_id' => $user_id,
            'is_guest' => $is_guest,
            'schedule_at' => $schedule_at,
            'estimated_trip_end_time' => $estimated_trip_end_time,
            'user_data' => $user_data,
            'provider' => $provider,
            'coupon' => $coupon ?? null,
            'coupon_created_by' => $coupon_created_by ?? null,
            'tax_included' => $tax_included,
            'discount_on_trip' => $discount_on_trip ?? 0,
            'tax_amount' => $tax_amount ?? 0,
            'trip_amount' => $price ?? 0,
            'cash_back_id' => $cash_back_id ?? null,
            'discount_on_trip_by' => $discount_on_trip_by ?? 'none',

        ];



        return response()->json($data, 200);
    }



    private function makeTrip($request,$make_trip_data){
        $trip = new Trips();
        $trip->user_id = $make_trip_data['user_id'];
        $trip->cash_back_id = $make_trip_data['cash_back_id'];
        $trip->provider_id = $make_trip_data['provider']['id'];
        $trip->zone_id = $make_trip_data['provider']['zone_id'];
        $trip->module_id =  $make_trip_data['provider']['module_id'];
        $trip->trip_amount = $make_trip_data['trip_amount'];
        $trip->discount_on_trip = $make_trip_data['discount_on_trip'];




      }



    private function trip_validation_check($request,$schedule_at){

        // $settings_key = ['dine_in_order_option'];

        // $settings =  array_column(BusinessSetting::whereIn('key', $settings_key)->get()->toArray(), 'value', 'key');


        $store = Store::with(['discount', 'store_sub'])->selectRaw('*, IF(((select count(*) from `store_schedule` where `stores`.`id` = `store_schedule`.`store_id` and `store_schedule`.`day` = ' . $schedule_at->format('w') . ' and `store_schedule`.`opening_time` < "' . $schedule_at->format('H:i:s') . '" and `store_schedule`.`closing_time` >"' . $schedule_at->format('H:i:s') . '") > 0), true, false) as open')->where('id', $request->provider_id)->first();


        $zone_id = isset($store) ? [$store->zone_id] : json_decode($request->header('zoneId'), true);
        $zone = Zone::where('id', $zone_id)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();



        $response = match (true) {
            !$store => [
                'code' => 'provider',
                'message' =>  'provider_not_found',
                'status' => 403
            ],
            !$zone  => [
                'code' => 'Zone',
                'message' =>  'out_of_zone',
                'status' => 403
            ],
            in_array($store->store_business_model,['unsubscribed','none']) || ( in_array($store->store_business_model,['subscription']) && $store?->store_sub == null) || (in_array($store->store_business_model,['subscription']) && $store?->store_sub?->max_order != "unlimited" && $store?->store_sub?->max_order <= 0 ) => [
                'code' => 'provider',
                'message' =>  'Sorry_the_provider_is_unable_to_take_any_trip',
                'status' => 403
            ],
            // $request->schedule_at  && !$store->schedule_order => [
            //     'code' => 'schedule_at',
            //     'message' => 'schedule_trip_not_available',
            //     'status' => 403
            // ],
            // $store->open == false => [
            //     'code' => 'schedule_at',
            //     'message' => 'provider_is_closed_at_trip_time',
            //     'status' => 403
            // ],
            // $request->schedule_at && $schedule_at < now() => [
            //     'code' => 'trip_time',
            //     'message' =>  'you_can_not_schedule_a_trip_in_past',
            //     'status' => 403
            // ],
            default => null
        };

        if ($response) {
            return ['code' => $response['code'],'message' => translate($response['message']),'status_code'=> $response['status']];
        }

        return $store;
    }

    private function  coupon_check($request){

        $coupon = Coupon::active()->where(['code' => $request['coupon_code']])->first();
        if (isset($coupon)) {


            if($request->is_guest){
                $staus = CouponLogic::is_valid_for_guest($coupon, $request['store_id']);
            }else{
                $staus = CouponLogic::is_valide($coupon, $request->user->id, $request['store_id']);
            }

            $message= match($staus){
                407 => translate('messages.coupon_expire'),
                408 => translate('messages.You_are_not_eligible_for_this_coupon'),
                406 => translate('messages.coupon_usage_limit_over'),
                404 => translate('messages.not_found'),
                default => null ,
            };
            if ($message != null) {
                return ['code' => 'coupon','message' => $message,'status_code'=> $staus];
            }
            if($coupon->coupon_type == 'free_delivery'){
                return ['code' => 'coupon','message' => translate('messages.invalid_coupon'),'status_code'=>403];
            }

            $coupon->increment('total_uses');
            $coupon_created_by =$coupon->created_by;

            return ['coupon' => $coupon ,'coupon_created_by' => $coupon_created_by];
        } else {
            return ['code' => 'coupon','message' => translate('messages.not_found'),'status_code'=> 404];
        }
    }
    private function trip_details($request,$user_data,$carts,$schedule_at, $estimated_trip_end_time,$tax,$is_include,$provider){
        $price=0;
        $discount_on_trip=0;
        $tax_amount=0;
        $details_data=[];
        foreach($carts as $cart){


            if(!$cart->vehicles){
                return ['code' => 'details_data','message' => translate('messages.Vehicle_not_found'),'status_code'=> 404];
            }

           $discount_data= $this->getDiscount(price: $user_data->rental_type == 'hourly' ? $cart->vehicles->hourly_price *  $cart->estimated_hours : $cart->vehicles->distance_price *  $cart->distance, discount_type: $cart->vehicles->discount_type, discount:$cart->vehicles->discount_price);

            $trip_details_data=[
                'vehicle_id' =>$cart->vehicle_id,
                'quantity' =>$cart->quantity,
                'tax_percentage' =>$tax,
                'discount_on_trip_by' =>'vendor',
                'discount_percentage' =>$cart->vehicles->discount_type == 'amount' ? 0 : $cart->vehicles->discount_price,
                'price' => round($discount_data['price'] ,config('round_up_to_digit') ),
                'discount_on_trip' =>round( $discount_data['discount'] ,config('round_up_to_digit')) ,
                'discount_type' => $cart->vehicles->discount_type,
                'tax_amount' => round($this->helpers->product_tax($discount_data['price'],$tax,$is_include) ,config('round_up_to_digit')),
                'tax_status' =>$is_include == 1 ? 'included' : 'excluded',
                'vehicle_details' =>json_encode($cart->vehicles),
                'rental_type' =>$user_data->rental_type,
                'estimated_hours' =>$user_data->estimated_hours,
                'distance' =>$user_data->distance,
                'scheduled' =>$request->scheduled,
                'schedule_at' =>$schedule_at,
                'estimated_trip_end_time' =>$estimated_trip_end_time,
            ];
            $cart->vehicles->increment('total_trip',$cart->quantity);
            $details_data[]=$trip_details_data;

            $price += $trip_details_data['price'] * $cart->quantity ;
            $discount_on_trip += $trip_details_data['discount_on_trip'] * $cart->quantity;
            $tax_amount += $trip_details_data['tax_amount'] * $cart->quantity;

        }



        $provider_discount = $this->helpers->get_store_discount($provider);
        if (isset($provider_discount)) {


            $discount = $this->checkAdminDiscount(price: $price,discount:$provider_discount['discount'],max_discount:$provider_discount['max_discount'] ,min_purchase:$provider_discount['min_purchase'] );

            $discount_on_trip_by = 'admin';


            foreach ($trip_details_data as $key => $trip_data) {
                $discount_on_trip_amount= ($trip_data->price / 100) * $provider_discount['discount'];


                $trip_data->discount_on_trip_by = $discount_on_trip_by;
                $trip_data->discount_type = 'precentage';
                $trip_data->discount_percentage = $provider_discount['discount'];
                $trip_data->discount_on_trip = $discount_on_trip_amount;
            }



        } else{
            $discount = $discount_on_trip;
            $discount_on_trip_by = 'vendor';
        }




        if(count($details_data) > 0){
            return ['details_data'=> $details_data, 'price'=> $price,  'discount'=> $discount,'tax_amount'=> $tax_amount ,'discount_on_trip_by' => $discount_on_trip_by];
        }
        return ['code' => 'details_data','message' => translate('messages.details_data_not_found'),'status_code'=> 404];
    }


    private function getDiscount($price , $discount_type, $discount){
        if($price > 0 &&  $discount > 0){
          $discount=  $discount_type == 'percent' ? ($price * $discount ) / 100 :  $discount;
        }
        return ['price' => $price , 'discount' => $discount ?? 0 ];
    }
    private function checkAdminDiscount($price, $discount,$max_discount,$min_purchase){
        if($price > 0 &&  $discount > 0){
            $discount = ($price / 100) * $discount;
            $discount = $discount > $max_discount ? $max_discount : $discount ;
            $discount = $price >= $min_purchase ? $discount : 0;
        }
        return $discount ?? 0;

        if(true){

        }
    }



}
