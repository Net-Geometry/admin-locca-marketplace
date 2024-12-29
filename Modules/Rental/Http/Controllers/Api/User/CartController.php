<?php

namespace Modules\Rental\Http\Controllers\Api\User;


use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Routing\Controller;
use Modules\Rental\Entities\Vehicle;
use Illuminate\Support\Facades\Validator;
use Modules\Rental\Entities\RentalCart;
use Modules\Rental\Entities\RentalCartUserData;

class CartController extends Controller
{
    public function __construct(private RentalCart $cart, private RentalCartUserData $user_data, private Helpers $helpers, private Vehicle $vehicle,)
    {
        $this->cart = $cart;
        $this->helpers = $helpers;
        $this->user_data = $user_data;
        $this->vehicle = $vehicle;
    }

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

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? [];
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle', 'provider:id,name'])->get();

        $data = [
            'carts' => $carts,
            'user_data' => $user_data,
        ];
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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $vehicle = $this->vehicle->where('id', $request->vehicle_id)->first();

        if ($this->cart->where('vehicle_id', $request->vehicle_id)->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->exists()) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.Item_already_exists')]
                ]
            ], 403);
        }
        if ($request->rental_type ==  'hourly' && $vehicle->trip_hourly != 1 ) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => $vehicle->name.' '.translate('messages.Does_Not_Support_Hourly_Trips') ]
                ]
            ], 403);
        }
        if ($request->rental_type ==  'distance_wise' && $vehicle->trip_distance != 1 ) {
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => $vehicle->name.' '.translate('messages.Does_Not_Support_Distance_Wise_Trips') ]
                ]
            ], 403);
        }


        $price =$this->getDiscount(price: $request->rental_type == 'hourly' ? $vehicle->hourly_price *  $request->estimated_hours : $vehicle->distance_price *  $request->distance, discount_type: $vehicle->discount_type, discount: $vehicle->discount_price);


        $carts = $this->cart;
        $carts->user_id = $user_id;
        $carts->is_guest = $is_guest;
        $carts->vehicle_id = $request->vehicle_id;
        $carts->provider_id = $vehicle->provider_id;
        $carts->quantity = $request->quantity ?? 1;
        $carts->module_id = $request->header('moduleId');
        $carts->price = $price * $carts->quantity;
        $carts->save();



        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle', 'provider:id,name'])->get();

        $total_cart_price = $carts->sum('price');

        $user_data= $this->setUserData($request,$user_id,$is_guest,$total_cart_price);

        $data = [
            'carts' => $carts,
            'user_data' => $user_data,
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

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? [];

        $price =$this->getDiscount(price: $user_data->rental_type == 'hourly' ? $cart->vehicle->hourly_price *  $user_data->estimated_hours : $cart->vehicle->distance_price *  $user_data->distance, discount_type: $cart->vehicle->discount_type, discount: $cart->vehicle->discount_price);

        $cart->user_id = $user_id;
        $cart->is_guest = $is_guest;
        $cart->quantity = $request->quantity ?? 1;
        $cart->price = $price * $cart->quantity;
        $cart->save();

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle', 'provider:id,name'])->get();

        $user_data->total_cart_price = $carts->sum('price');
        $user_data->save();
        $data = [
            'carts' => $carts,
            'user_data' => $user_data,
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

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle', 'provider:id,name'])->get();

        $user_data->total_cart_price=$carts->sum('price');
        $user_data->save();

        $data = [
            'carts' => $carts,
            'user_data' => $user_data,
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

        $this->updateCartPrice($request, $user_id, $is_guest);

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle', 'provider:id,name'])->get();

        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first() ?? [];


        $data = [
            'carts' => $carts,
            'user_data' =>$user_data,
        ];

        return response()->json($data, 200);
    }

    private function setUserData($request,$user_id,$is_guest,$total_cart_price){
        $user_data = $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->firstOrNew();
        $user_data->user_id = $user_id;
        $user_data->pickup_location = $request->pickup_location ? json_encode($request->pickup_location) : json_encode($user_data->pickup_location);
        $user_data->destination_location = $request->destination_location ? json_encode($request->destination_location) : json_encode($user_data->destination_location);
        $user_data->pickup_time = $request->pickup_time ? \Carbon\Carbon::parse($request->pickup_time) : $user_data?->pickup_time ?? now();
        $user_data->rental_type = $request->rental_type ?? $user_data?->rental_type ?? 'hourly';
        $user_data->estimated_hours = $user_data->rental_type == 'hourly' ? $request->estimated_hours ??  $user_data?->estimated_hours ?? 0 : 0;
        $user_data->distance = $user_data->rental_type != 'hourly' ? $request->distance ??  $user_data?->distance ?? 0 : 0;
        $user_data->destination_time = $user_data->rental_type != 'hourly' ? $request->destination_time ??  $user_data?->destination_time ?? 0 : 0;
        $user_data->is_guest = $is_guest;
        $user_data->total_cart_price = $total_cart_price;
        $user_data->save();

        return $user_data;

    }

    private function getDiscount($price, $discount_type, $discount=0)
    {
        if ($price > 0 &&  $discount > 0) {
            $discount =  $discount_type == 'percent' ? ($price * $discount) / 100 :  $discount;
        }
        return $price - $discount;
    }


    private function updateCartPrice($request, $user_id, $is_guest)
    {
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with(['vehicle'])->get();
        $total_cart_price=0;
            foreach($carts as $cart){

                if ($request->rental_type ==  'hourly' && $cart->vehicle->trip_hourly != 1 ) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'cart_item', 'message' => $cart->vehicle->name.' '.translate('messages.Does_Not_Support_Hourly_Trips') ]
                        ]
                    ], 403);
                }
                if ($request->rental_type ==  'distance_wise' && $cart->vehicle->trip_distance != 1 ) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'cart_item', 'message' => $cart->vehicle->name.' '.translate('messages.Does_Not_Support_Distance_Wise_Trips') ]
                        ]
                    ], 403);
                }

                $price =$this->getDiscount(price: $request->rental_type == 'hourly' ? $cart->vehicle->hourly_price *  $request->estimated_hours : $cart->vehicle->distance_price *  $request->distance, discount_type: $cart->vehicle->discount_type, discount: $cart->vehicle->discount_price);

                $cart->user_id = $user_id;
                $cart->is_guest = $is_guest;
                $cart->price = $price * $cart->quantity;
                $cart->save();

                $total_cart_price +=$cart->price;
            }
            $this->setUserData($request,$user_id,$is_guest,$total_cart_price);
    }


}
