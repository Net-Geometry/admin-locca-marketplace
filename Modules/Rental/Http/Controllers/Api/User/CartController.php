<?php

namespace Modules\Rental\Http\Controllers\Api\User;


use App\Models\Zone;
use App\Models\Store;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Routing\Controller;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\RentalCart;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Modules\Rental\Entities\RentalCartUserData;

class CartController extends Controller
{
    public function __construct(
        private RentalCart $cart,
        private RentalCartUserData $user_data,
        private Helpers $helpers,
        private Vehicle $vehicle
    ) {}

    public function getCartList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first();

        if($user_data){
            $updated_cart_data= $this->updateCartPrice($request, $user_id, $is_guest,$user_data);
            $data = [
                'carts' => $updated_cart_data['carts'],
                'user_data' =>  $updated_cart_data['user_data'],
            ];
        } else{
            $data = [
                'carts' => [],
                'user_data' => $user_data ?? [],
            ];
        }
        return response()->json($data, 200);
    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'vehicle_id' => 'required',
            'rental_type' => 'required|in:hourly,distance_wise',
            'estimated_hours' => 'required_if:rental_type,hourly',
            'distance' => 'required_if:rental_type,distance_wise',
            'destination_time' => 'required_if:rental_type,distance_wise',
        ],[
            'destination_time.required_if' => translate('destination_address_is_required_when_rental_type_is_distance_wise')
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first();
        $pickup_time = $request->pickup_time
        ? \Carbon\Carbon::parse($request->pickup_time)
        : ($user_data?->pickup_time ? \Carbon\Carbon::parse($user_data->pickup_time) : now());
        // dd($pickup_time );
        $vehicle = $this->vehicle->where('id', $request->vehicle_id)->active()
        ->withCount([
            'vehicleIdentities as total_vehicle_count' => function ($query) use ($pickup_time) {
                $query->where(function ($query) use ($pickup_time) {
                    $query->whereDoesntHave('vehicle_trip_details')
                        ->orWhere(function ($query) use ($pickup_time) {
                            $query->whereNotExists(function ($subQuery) use ($pickup_time) {
                                $subQuery->from('trip_vehicle_details')
                                    ->whereColumn('trip_vehicle_details.vehicle_identity_id', 'vehicle_identities.id')
                                    ->where('estimated_trip_end_time', '>', $pickup_time);
                            });
                        });
                });
            },
        ])
        ->first();

        if (!$vehicle) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.vehicle_not_found')]
                ]
            ], 403);
        }

        if ($this->cart->where('vehicle_id', $request->vehicle_id)->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->exists()) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.Item_already_exists')]
                ]
            ], 403);
        }
        if($vehicle->total_vehicle_count <= 0){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.This_Vehicle_is_not_available_on_this_pickup_time')]
                ]
            ], 403);
        }

        if($vehicle->total_vehicle_count < $request->quantity){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.max_vehicle_available_quantity_is') .' '.$vehicle->total_vehicle_count]
                ]
            ], 403);
        }
        $provider_id= $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->first()?->provider_id;

        if ($provider_id  && $provider_id != $vehicle->provider_id ) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.You_can_not_add_different_provider_vehicles')]
                ]
            ], 403);
        }


        $store = Store::selectRaw('*, IF(((select count(*) from `store_schedule` where `stores`.`id` = `store_schedule`.`store_id` and `store_schedule`.`day` = ' . $pickup_time->format('w') . ' and `store_schedule`.`opening_time` < "' . $pickup_time->format('H:i:s') . '" and `store_schedule`.`closing_time` >"' . $pickup_time->format('H:i:s') . '") > 0), true, false) as open')->where('id', $vehicle->provider_id)->first();

        if($store->open == false){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.provider_is_closed_at_trip_time')]
                ]
            ], 403);
        }

        if ($request->rental_type ==  'hourly' && $vehicle->trip_hourly != 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => $vehicle->name . ' ' . translate('messages.Does_Not_Support_Hourly_Trips')]
                ]
            ], 403);
        }
        if ($request->rental_type ==  'distance_wise' && $vehicle->trip_distance != 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => $vehicle->name . ' ' . translate('messages.Does_Not_Support_Distance_Wise_Trips')]
                ]
            ], 403);
        }

        if($provider_id && $user_data?->rental_type && $user_data?->rental_type !=$request->rental_type ){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => $vehicle->name . ' ' . translate('messages.You_can_not_add_different_rental_type_vehicles')]
                ]
            ], 403);
        }

        $price = $this->getDiscount(price: $request->rental_type == 'hourly' ? $vehicle->hourly_price *  $request->estimated_hours : $vehicle->distance_price *  $request->distance, discount_type: $vehicle->discount_type, discount: $vehicle->discount_price);

        $carts = $this->cart;
        $carts->user_id = $user_id;
        $carts->is_guest = $is_guest;
        $carts->vehicle_id = $request->vehicle_id;
        $carts->provider_id = $vehicle->provider_id;
        $carts->quantity = $request->quantity ?? 1;
        $carts->module_id = $request->header('moduleId');
        $carts->price = $price * $carts->quantity;
        $carts->save();

        $updated_cart_data= $this->updateCartPrice($request, $user_id, $is_guest);

        $data = [
            'carts' => $updated_cart_data['carts'],
            'user_data' =>  $updated_cart_data['user_data'],
        ];
        return response()->json($data, 200);
    }


    public function updateCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'guest_id' => $request->user ? 'nullable' : 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $cart = $this->cart->where('id', $request->cart_id)->with('vehicle')->first();
        if (!$cart) {
            return response()->json(['errors' => translate('cart_not_found')], 404);
        }


        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first();
        $pickup_time = $request->pickup_time
        ? \Carbon\Carbon::parse($request->pickup_time)
        : ($user_data?->pickup_time ? \Carbon\Carbon::parse($user_data->pickup_time) : now());

        $vehicle = $this->vehicle->where('id', $cart->vehicle_id)->active()
        ->withCount([
            'vehicleIdentities as total_vehicle_count' => function ($query) use ($pickup_time) {
                $query->where(function ($query) use ($pickup_time) {
                    $query->whereDoesntHave('vehicle_trip_details')
                        ->orWhere(function ($query) use ($pickup_time) {
                            $query->whereNotExists(function ($subQuery) use ($pickup_time) {
                                $subQuery->from('trip_vehicle_details')
                                    ->whereColumn('trip_vehicle_details.vehicle_identity_id', 'vehicle_identities.id')
                                    ->where('estimated_trip_end_time', '>', $pickup_time);
                            });
                        });
                });
            },
        ])
        ->first();
        if (!$vehicle) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.vehicle_not_found')]
                ]
            ], 403);
        }
        if($vehicle->total_vehicle_count <= 0){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.This_Vehicle_is_not_available_on_this_pickup_time')]
                ]
            ], 403);
        }

        if($vehicle->total_vehicle_count < $request->quantity){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.max_vehicle_available_quantity_is') .' '.$vehicle->total_vehicle_count]
                ]
            ], 403);
        }
        $price = $this->getDiscount(price: $user_data->rental_type == 'hourly' ? $cart->vehicle->hourly_price *  $user_data->estimated_hours : $cart->vehicle->distance_price *  $user_data->distance, discount_type: $cart->vehicle->discount_type, discount: $cart->vehicle->discount_price);
        $cart->user_id = $user_id;
        $cart->is_guest = $is_guest;
        $cart->quantity = $request->quantity ?? 1;
        $cart->price = $price * $cart->quantity;
        $cart->save();

        $updated_cart_data= $this->updateCartPrice($request, $user_id, $is_guest,$user_data);

        $data = [
            'carts' => $updated_cart_data['carts'],
            'user_data' =>  $updated_cart_data['user_data'],
        ];
        return response()->json($data, 200);
    }


    public function removeVehicle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $this->cart->where('id', $request->cart_id)->where('user_id', $user_id)->where('is_guest', $is_guest)->delete();

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? [];
        $updated_cart_data= $this->updateCartPrice($request, $user_id, $is_guest,$user_data);

        $data = [
            'carts' => $updated_cart_data['carts'],
            'user_data' =>  $updated_cart_data['user_data'],
        ];
        return response()->json($data, 200);
    }
    public function removeMultipleVehicles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'cart_ids' =>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $this->cart->whereIn('id', json_decode($request->cart_ids,true))->where('user_id', $user_id)->where('is_guest', $is_guest)->delete();

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? [];
        $updated_cart_data= $this->updateCartPrice($request, $user_id, $is_guest,$user_data);

        $data = [
            'carts' => $updated_cart_data['carts'],
            'user_data' =>  $updated_cart_data['user_data'],
        ];
        return response()->json($data, 200);
    }

    public function removeCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->delete();
        $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->delete();

        $data = [
            'carts' => [],
            'user_data' => [],
        ];
        return response()->json($data, 200);
    }



    public function updateUserData(RentalCartUserData $user_data, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'rental_type' => 'required|in:hourly,distance_wise',
            'estimated_hours' => 'required_if:rental_type,hourly',
            'distance' => 'required_if:rental_type,distance_wise',
            'destination_time' => 'required_if:rental_type,distance_wise',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $pickup_time = $request->pickup_time
        ? \Carbon\Carbon::parse($request->pickup_time)
        : ($user_data?->pickup_time ? \Carbon\Carbon::parse($user_data->pickup_time) : now());

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle'])->get();
        $unsupported_vehicle_ids=[];
        foreach($carts as $cart){
            $store = Store::selectRaw('*, IF(((select count(*) from `store_schedule` where `stores`.`id` = `store_schedule`.`store_id` and `store_schedule`.`day` = ' . $pickup_time->format('w') . ' and `store_schedule`.`opening_time` < "' . $pickup_time->format('H:i:s') . '" and `store_schedule`.`closing_time` >"' . $pickup_time->format('H:i:s') . '") > 0), true, false) as open')->where('id', $cart->provider_id)->first();

            if($store->open == false){
                return response()->json([
                    'errors' => [
                        ['code' => 'cart_item', 'message' => translate('messages.provider_is_closed_at_trip_time')]
                    ]
                ], 403);
            }


                if($user_data->rental_type != $request->rental_type){
                    if ($request->rental_type ==  'hourly' && $cart?->vehicle->trip_hourly != 1) {
                        $unsupported_vehicle_ids[]= $cart?->id;
                    }

                    if ($request->rental_type ==  'distance_wise' && $cart?->vehicle->trip_distance != 1) {
                        $unsupported_vehicle_ids[]= $cart?->id;
                    }

                    if(count($unsupported_vehicle_ids) > 0 ){
                        return response()->json($unsupported_vehicle_ids, 403);
                    }
            }
        }

        $updated_cart_data= $this->updateCartPrice($request, $user_id, $is_guest);

        $data = [
            'carts' => $updated_cart_data['carts'],
            'user_data' =>  $updated_cart_data['user_data'],
        ];

        return response()->json($data, 200);
    }

    private function setUserData($request, $user_id, $is_guest, $total_cart_price)
    {
        $user_data = $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->firstOrNew();
        $user_data->user_id = $user_id;
        $user_data->pickup_location = $request->pickup_location ? json_encode($request->pickup_location) : json_encode($user_data->pickup_location);
        $user_data->destination_location = $request->destination_location ? json_encode($request->destination_location) : json_encode($user_data->destination_location);
        $user_data->pickup_time = $request->pickup_time ? \Carbon\Carbon::parse($request->pickup_time) : $user_data?->pickup_time ?? now();
        $user_data->rental_type = $request->rental_type ?? $user_data?->rental_type ?? 'hourly';
        $user_data->estimated_hours =$request->estimated_hours ??  $user_data?->estimated_hours ?? 0;
        $user_data->distance = $request->distance ??  $user_data?->distance ?? 0;
        $user_data->destination_time =$request->destination_time ??  $user_data?->destination_time?? 0;
        $user_data->is_guest = $is_guest;
        $user_data->total_cart_price = $total_cart_price;
        $user_data->save();

        return $user_data;
    }

    private function getDiscount($price, $discount_type, $discount = 0)
    {
        if ($price > 0 &&  $discount > 0) {
            $discount =  $discount_type == 'percent' ? ($price * $discount) / 100 :  $discount;
        }
        return $price - $discount;
    }


    private function updateCartPrice($request, $user_id, $is_guest,$user_data=null)
    {
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))
        ->with(['vehicle' => function($query) {
            $query->withCount('vehicleIdentities as total_vehicle_count');
        }, 'provider:id,name,address,tax', 'provider.discount'])
        ->get();

        $user_data=   $this->setUserData($request, $user_id, $is_guest, 0);

        if (data_get($user_data,'pickup_location.lat')  && data_get($user_data ,'pickup_location.lng') ) {
            $zones = Zone::whereContains('coordinates', new Point(data_get($user_data ,'pickup_location.lat'), data_get($user_data ,'pickup_location.lng'), POINT_SRID))->pluck('id')->toArray();
        }

        $total_cart_price = 0;
        foreach ($carts as  $cart) {
            if($cart->vehicle &&  $cart->vehicle->status == 1){
                $price = $this->getDiscount(price: ($request->rental_type ?? $user_data?->rental_type) == 'hourly' ? $cart->vehicle->hourly_price *   ($request->estimated_hours ?? $user_data?->estimated_hours) : $cart->vehicle?->distance_price *  ($request->distance ?? $user_data?->distance), discount_type: $cart->vehicle->discount_type, discount: $cart->vehicle->discount_price);
                $cart->user_id = $user_id;
                $cart->is_guest = $is_guest;
                $cart->price = $price * $cart->quantity;
                $cart->save();
                $total_cart_price += $cart->price;
            } else{
                $cart->delete();
            }

            if(!$cart->vehicle()->when(count($zones) > 0, function ($query) use ($zones) {
                $query->whereHas('provider', function ($query) use ($zones) {
                    $query->active()->where(function ($query) use ($zones) {
                        $query->whereJsonContains('pickup_zone_id', (string) $zones[0]);
                        for ($i = 1; $i < count($zones); $i++) {
                            $query->orWhereJsonContains('pickup_zone_id', (string) $zones[$i]);
                        }
                        return $query;
                    });
                });
            })->exists()){
                $cart->delete();
            }
        };

        return [
            'carts' => $carts,
            'user_data' => $this->setUserData($request, $user_id, $is_guest, $total_cart_price)
        ];
    }

}
