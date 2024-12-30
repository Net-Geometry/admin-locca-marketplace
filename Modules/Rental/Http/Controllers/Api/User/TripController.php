<?php

namespace Modules\Rental\Http\Controllers\Api\User;


use App\Models\User;
use App\Models\Zone;
use App\Models\Store;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;

use App\Models\BusinessSetting;
use App\CentralLogics\CouponLogic;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\Trips;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\RentalCart;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Modules\Rental\Entities\RentalCartUserData;
use Modules\Rental\Entities\TripDetails;
use App\CentralLogics\StoreLogic;


class TripController extends Controller
{
    public function __construct(
        private RentalCart $cart,
        private Trips $trips,
        private RentalCartUserData $user_data,
        private Helpers $helpers,
        private Vehicle $vehicle,
    ) {
        $this->cart = $cart;
        $this->$trips = $trips;
        $this->user_data = $user_data;
        $this->helpers = $helpers;
        $this->vehicle = $vehicle;
    }

    public function tripBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_amount' => 'required|numeric',
            'trip_type' => 'required|in:hourly,distance_wise',
            'provider_id' => 'required|numeric',
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;


        $schedule_at = $request->schedule_at ? \Carbon\Carbon::parse($request->schedule_at) : now();

        $user_data =  $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? null;



        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with('vehicle')->get();

        if (count($carts) == 0) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart', 'message' => translate('Your_cart_is_empty')]
                ]
            ], 403);
        }
        if (!$user_data) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart', 'message' => translate('Your_location_is_empty')]
                ]
            ], 403);
        }
        $estimated_trip_end_time = $schedule_at->copy()->addHours($user_data->rental_type == 'hourly' ? $user_data->estimated_hours ?? 1 : $user_data->destination_time ?? 1);

        $trip_validation_check =  $this->tripValidationCheck($request, $schedule_at);

        if (data_get($trip_validation_check, 'status_code') === 403) {

            return response()->json([
                'errors' => [
                    ['code' => data_get($trip_validation_check, 'code'), 'message' => data_get($trip_validation_check, 'message')]
                ]
            ], data_get($trip_validation_check, 'status_code'));
        } else {
            $provider = $trip_validation_check;
        }

        DB::beginTransaction();

        if ($request['coupon_code']) {
            $coupon_check =  $this->couponCheck($request);
            if (data_get($coupon_check, 'code') === 'coupon') {
                DB::rollBack();
                return response()->json([
                    'errors' => [
                        ['code' => data_get($coupon_check, 'code'), 'message' => data_get($coupon_check, 'message')]
                    ]
                ], data_get($coupon_check, 'status_code'));
            } else {
                $coupon = data_get($coupon_check, 'coupon');
                $coupon_created_by = data_get($coupon_check, 'coupon_created_by');
            }
        }

        $tax_included = BusinessSetting::where(['key' => 'tax_included'])->first()?->value ?? 0;

        $details_data =  $this->tripDetails(request: $request, user_data: $user_data, carts: $carts, schedule_at: $schedule_at, estimated_trip_end_time: $estimated_trip_end_time, tax: $provider->tax, is_include: $tax_included, provider: $provider);

        if (data_get($details_data, 'code') === 'details_data') {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    ['code' => data_get($details_data, 'code'), 'message' => data_get($details_data, 'message')]
                ]
            ], data_get($details_data, 'status_code'));
        } else {
            $price = data_get($details_data, 'price');
            $discount_on_trip = data_get($details_data, 'discount_on_trip');
            $quantity = data_get($details_data, 'quantity');
            $discount_on_trip_by = data_get($details_data, 'discount_on_trip_by');
            $details_data = data_get($details_data, 'details_data');
        }


        $price = $price - $discount_on_trip;
        $coupon_discount_amount = isset($coupon) ? CouponLogic::get_discount($coupon, $price) : 0;
        $price = $price - $coupon_discount_amount;

        if ($is_guest == 0 && $user_id) {
            $user = User::withcount('trips')->find($user_id);
            $discount_data = $this->helpers->getCusromerFirstOrderDiscount(order_count: $user->trips_count, user_creation_date: $user->created_at, refby: $user->ref_by, price: $price);
            if (data_get($discount_data, 'is_valid') == true &&  data_get($discount_data, 'calculated_amount') > 0) {
                $price = $price - data_get($discount_data, 'calculated_amount');
                $ref_bonus_amount = data_get($discount_data, 'calculated_amount');
            }
        }
        $tax_status = 'excluded';
        if ($tax_included  == 1) {
            $tax_status = 'included';
        }
        $orignal_tax_amount = $this->helpers->product_tax($price, $provider->tax, $tax_status == 'included');
        $tax_amount = $tax_status == 'included' ? 0 : $orignal_tax_amount;

        $additional_charge =  0;
        if (BusinessSetting::where('key', 'additional_charge_status')->first()?->value == 1) {
            $additional_charge = BusinessSetting::where('key', 'additional_charge')->first()?->value ?? 0;
        }

        if ($price < 0) {
            $price = 0;
        }
        $make_trip_data = [
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
            'coupon_discount_amount' => $coupon_discount_amount ?? 0,
            'coupon_discount_by' => $coupon_discount_by ?? 'none',
            'coupon_code' => $coupon?->code ?? null,
            'tax_amount' => $tax_amount ?? 0,
            'tax_status' => $tax_status,
            'trip_amount' => $price + $additional_charge ?? 0,
            'discount_on_trip_by' => $discount_on_trip_by ?? 'none',
            'additional_charge' => $additional_charge ?? 0,
            'distance' => $user_data->distance ?? 0,
            'estimated_hours' => $user_data->estimated_hours ?? 0,
            'ref_bonus_amount' => $ref_bonus_amount ?? 0,
            'cash_back_id' => $cash_back_id ?? null,
            'quantity' => $quantity ?? 1,
            'pickup_location' => json_encode($user_data->pickup_location),
            'destination_location' => json_encode($user_data->destination_location),
        ];


        $trip = $this->makeTrip($request, $make_trip_data);

        foreach ($details_data as $key => $item) {
            $details_data[$key]['trip_id'] = $trip->id;

            // if($store_discount_amount <= 0 ){
            //     $order_details[$key]['discount_on_item'] = 0;
            // }
        }
        TripDetails::insert($details_data);


        $carts->each(function ($cart) {
            $cart->delete();
        });

        $user_data->delete();


        DB::commit();
        return response()->json($trip->id, 200);
    }



    private function makeTrip($request, $make_trip_data)
    {
        $trip = $this->trips;
        $trip->user_id = $make_trip_data['user_id'];
        $trip->provider_id = $make_trip_data['provider']['id'];
        $trip->zone_id = $make_trip_data['provider']['zone_id'];
        $trip->module_id =  $make_trip_data['provider']['module_id'];
        $trip->cash_back_id = $make_trip_data['cash_back_id'];
        $trip->trip_amount = $make_trip_data['trip_amount'];
        $trip->discount_on_trip = $make_trip_data['discount_on_trip'];
        $trip->discount_on_trip_by = $make_trip_data['discount_on_trip_by'];
        $trip->coupon_discount_amount = $make_trip_data['coupon_discount_amount'];
        $trip->coupon_discount_by = $make_trip_data['coupon_discount_by'];
        $trip->coupon_code = $make_trip_data['coupon_code'];
        $trip->trip_status = 'pending';
        $trip->payment_status = 'unpaid';
        $trip->tax_amount = $make_trip_data['tax_amount'];
        $trip->tax_status = $make_trip_data['tax_status'];
        $trip->tax_percentage = $make_trip_data['provider']['tax'];
        $trip->trip_type = $request->trip_type;
        $trip->additional_charge = $make_trip_data['additional_charge'];
        $trip->distance = $make_trip_data['distance'];
        $trip->estimated_hours = $make_trip_data['estimated_hours'];
        $trip->ref_bonus_amount = $make_trip_data['ref_bonus_amount'];
        $trip->trip_note = $request->trip_note;
        $trip->otp = rand(1000, 9999);
        $trip->is_guest = $make_trip_data['is_guest'];
        $trip->scheduled = $request->scheduled ?? 0;
        $trip->schedule_at = $make_trip_data['schedule_at'];
        $trip->quantity = $make_trip_data['quantity'];
        $trip->estimated_trip_end_time = $make_trip_data['estimated_trip_end_time'];
        $trip->destination_location = $make_trip_data['destination_location'];
        $trip->pickup_location = $make_trip_data['pickup_location'];
        $trip->pending = now();
        $trip->save();
        return $trip;
    }



    private function tripValidationCheck($request, $schedule_at)
    {

        // $settings_key = ['dine_in_order_option'];

        // $settings =  array_column(BusinessSetting::whereIn('key', $settings_key)->get()->toArray(), 'value', 'key');

        $longitude= $request->header('longitude')?? 0;
        $latitude= $request->header('latitude')?? 0;

        $store = Store::with(['discount', 'store_sub'])->selectRaw('*, IF(((select count(*) from `store_schedule` where `stores`.`id` = `store_schedule`.`store_id` and `store_schedule`.`day` = ' . $schedule_at->format('w') . ' and `store_schedule`.`opening_time` < "' . $schedule_at->format('H:i:s') . '" and `store_schedule`.`closing_time` >"' . $schedule_at->format('H:i:s') . '") > 0), true, false) as open')->where('id', $request->provider_id)->first();


        $zone_id = isset($store) ? [$store->zone_id] : json_decode($request->header('zoneId'), true);
        $zone = Zone::where('id', $zone_id)->whereContains('coordinates', new Point($latitude, $longitude, POINT_SRID))->first();



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
            in_array($store->store_business_model, ['unsubscribed', 'none']) || (in_array($store->store_business_model, ['subscription']) && $store?->store_sub == null) || (in_array($store->store_business_model, ['subscription']) && $store?->store_sub?->max_order != "unlimited" && $store?->store_sub?->max_order <= 0) => [
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
            return ['code' => $response['code'], 'message' => translate($response['message']), 'status_code' => $response['status']];
        }

        return $store;
    }

    private function  couponCheck($request)
    {

        $coupon = Coupon::active()->where(['code' => $request['coupon_code']])->first();
        if (isset($coupon)) {


            if ($request->is_guest) {
                $staus = CouponLogic::is_valid_for_guest($coupon, $request['store_id']);
            } else {
                $staus = CouponLogic::is_valide($coupon, $request->user->id, $request['store_id']);
            }

            $message = match ($staus) {
                407 => translate('messages.coupon_expire'),
                408 => translate('messages.You_are_not_eligible_for_this_coupon'),
                406 => translate('messages.coupon_usage_limit_over'),
                404 => translate('messages.not_found'),
                default => null,
            };
            if ($message != null) {
                return ['code' => 'coupon', 'message' => $message, 'status_code' => $staus];
            }
            if ($coupon->coupon_type == 'free_delivery') {
                return ['code' => 'coupon', 'message' => translate('messages.invalid_coupon'), 'status_code' => 403];
            }

            $coupon->increment('total_uses');
            $coupon_created_by = $coupon->created_by;

            return ['coupon' => $coupon, 'coupon_created_by' => $coupon_created_by];
        } else {
            return ['code' => 'coupon', 'message' => translate('messages.not_found'), 'status_code' => 404];
        }
    }
    private function tripDetails($request, $user_data, $carts, $schedule_at, $estimated_trip_end_time, $tax, $is_include, $provider)
    {
        $price = 0;
        $discount_on_trip = 0;
        $quantity = 0;
        $details_data = [];
        foreach ($carts as $cart) {

            if (!$cart->vehicle) {
                return ['code' => 'details_data', 'message' => translate('messages.Vehicle_not_found'), 'status_code' => 404];
            }

            $discount_data = $this->getDiscount(price: $user_data->rental_type == 'hourly' ? $cart->vehicle->hourly_price *  $user_data->estimated_hours : $cart->vehicle->distance_price *  $user_data->distance, discount_type: $cart->vehicle->discount_type, discount: $cart->vehicle->discount_price);

            $trip_details_data = [
                'vehicle_id' => $cart->vehicle_id,
                'quantity' => $cart->quantity,
                'tax_percentage' => $tax,
                'discount_on_trip_by' => 'vendor',
                'discount_percentage' => $cart->vehicle->discount_type == 'amount' ? 0 : $cart->vehicle->discount_price,
                'price' => round($discount_data['price'], config('round_up_to_digit')),
                'discount_on_trip' => round($discount_data['discount'], config('round_up_to_digit')),
                'discount_type' => $cart->vehicle->discount_type,
                'tax_amount' => round($this->helpers->product_tax($discount_data['price'] - round($discount_data['discount'], config('round_up_to_digit')), $tax, $is_include), config('round_up_to_digit')),
                'tax_status' => $is_include == 1 ? 'included' : 'excluded',
                'vehicle_details' => json_encode($cart->vehicle),
                'rental_type' => $user_data->rental_type,
                'estimated_hours' => $user_data->estimated_hours,
                'distance' => $user_data->distance,
                'scheduled' => $request->scheduled ?? 0,
                'schedule_at' => $schedule_at,
                'estimated_trip_end_time' => $estimated_trip_end_time,

            ];
            $cart->vehicle->increment('total_trip', $cart->quantity);
            $details_data[] = $trip_details_data;

            $price += $trip_details_data['price'] * $cart->quantity;
            $discount_on_trip += $trip_details_data['discount_on_trip'] * $cart->quantity;
            $quantity += $cart->quantity;
        }

        $provider_discount = $this->helpers->get_store_discount($provider);
        if (isset($provider_discount)) {
            $discount = $this->checkAdminDiscount(price: $price, discount: $provider_discount['discount'], max_discount: $provider_discount['max_discount'], min_purchase: $provider_discount['min_purchase']);

            $discount_on_trip_by = 'admin';

            foreach ($trip_details_data as $key => $trip_data) {
                $trip_data->discount_on_trip_by = $discount_on_trip_by;
                $trip_data->discount_type = 'precentage';
                $trip_data->discount_percentage = $provider_discount['discount'];
                $trip_data->discount_on_trip =  $this->checkAdminDiscount(price: $price, discount: $provider_discount['discount'], max_discount: $provider_discount['max_discount'], min_purchase: $provider_discount['min_purchase'], vehicle_wise_price: $trip_data->price);
                $trip_data->tax_amount = round($this->helpers->product_tax($trip_data->price - $trip_data->discount_on_trip, config('round_up_to_digit')), $tax, $is_include);
            }
        } else {
            $discount = $discount_on_trip;
            $discount_on_trip_by = 'vendor';
        }




        if (count($details_data) > 0) {
            return ['details_data' => $details_data, 'price' => $price,  'discount' => $discount, 'quantity' => $quantity, 'discount_on_trip_by' => $discount_on_trip_by];
        }
        return ['code' => 'details_data', 'message' => translate('messages.details_data_not_found'), 'status_code' => 404];
    }

    private function getDiscount($price, $discount_type, $discount)
    {
        if ($price > 0 &&  $discount > 0) {
            $discount =  $discount_type == 'percent' ? ($price * $discount) / 100 :  $discount;
        }
        return ['price' => $price, 'discount' => $discount ?? 0];
    }
    private function checkAdminDiscount($price, $discount, $max_discount, $min_purchase, $vehicle_wise_price = null)
    {
        if ($price > 0 &&  $discount > 0) {
            $discount = ($price  * $discount) / 100;
            $discount = $discount > $max_discount ? $max_discount : $discount;
            $discount = $price >= $min_purchase ? $discount : 0;
        }

        if ($discount > 0 && $vehicle_wise_price > 0) {
            $discount = ($vehicle_wise_price / $price) * $discount;
        }

        return $discount ?? 0;
    }



    public function getTripList(Request $request,$type)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'trip_status' => 'nullable|in:pending,confirmed,ongoing,completed,canceled,payment_failed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;
        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;
        $trips = $this->trips->where(['user_id' => $user_id, 'is_guest' => $is_guest])

        ->with('provider:id,name,logo,cover_photo,phone')
            ->when($request->search, function ($query) use ($request) {
                $keys = explode(' ', $request->search);
                $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->trip_status, function ($query) use ($request) {
                $query->where('trip_status', $request->trip_status);
            })
            ->when($type == 'completed', function ($query) {
                $query->whereIn('trip_status', ['completed','canceled']);
            })
            ->when($type == 'running', function ($query) {
                $query->whereIn('trip_status', ['pending','confirmed','ongoing']);
            })
            ->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = $this->helpers->preparePaginatedResponse(pagination: $trips, limit: $limit, offset: $offset, key: 'trips', extraData: []);
        return response()->json($data, 200);
    }
    public function getTripDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $trip = $this->trips->where(['user_id' => $user_id, 'is_guest' => $is_guest, 'id' => $request->trip_id])
            ->with([
                'trip_details:id,trip_id,quantity,vehicle_details',
                'provider' => function ($query) {
                    $query->select('id', 'name', 'logo', 'cover_photo', 'rating', 'phone')
                        ->withCount('vehicle_identity as total_vehicles');
                }
            ])
            ->first();

            $ratings = StoreLogic::calculate_store_rating($trip['provider']['rating']);
            $trip['provider']['avg_rating'] =$ratings['rating'];
            $trip['provider']['rating_count'] =$ratings['total'];


        return response()->json($trip, 200);
    }


    public function cancelTrip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'trip_id' => 'required',
            'cancellation_reason' => 'nullable|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $trip = $this->trips->where(['user_id' => $user_id, 'is_guest' => $is_guest, 'id' => $request->trip_id])->with('trip_details.vehicle')->first();

        if(!$trip){
            return response()->json(['errors' => translate('trip_data_not_found')], 404);
        }

        if($trip->trip_status !== 'pending'){
            return response()->json(['errors' => translate('You_can_not_cancal_this_trip')], 403);
        }

        $trip->trip_status = 'canceled';
        $trip->canceled_by = 'user';
        $trip->cancellation_reason = $request->cancellation_reason;
        $trip->canceled = now();
        $trip->save();
        foreach($trip->trip_details as $detail){
            $detail?->vehicle?->total_trip > 0 ? $detail?->vehicle?->decrement('total_trip',$detail->quantity) : '';
        }


        return response()->json(['message' => translate('Trip_successfully_canceled')], 200);

    }
    public function makePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'trip_id' => 'required',
            'paymet_method' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $trip = $this->trips->where(['user_id' => $user_id, 'is_guest' => $is_guest, 'id' => $request->trip_id])->first();

        if(!$trip){
            return response()->json(['errors' => translate('trip_data_not_found')], 404);
        }


        if($request->paymet_method == 'cash_payment' ){
            $trip->payment_status = 'paid';
            $trip->payment_method = 'cash_payment';
        }

        if(!$is_guest){
            $user=  User::whereId($user_id)->first();

        }

        $trip->save();
        return response()->json(['message' => translate('Trip_successfully_canceled')], 200);

    }




}
