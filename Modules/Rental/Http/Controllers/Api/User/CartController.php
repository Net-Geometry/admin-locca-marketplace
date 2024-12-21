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
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with('vehicles')->get();

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

        $user_data = $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->firstOrNew();
        $user_data->user_id = $user_id;
        $user_data->pickup_location = $request->pickup_location ?? $user_data?->pickup_location;
        $user_data->destination_location = $request->destination_location ?? $user_data?->destination_location;
        $user_data->pickup_time = $request->pickup_time ?? $user_data?->pickup_time ?? now();
        $user_data->rental_type = $request->rental_type ?? $user_data?->rental_type ?? 'hourly';
        $user_data->estimated_hours = $user_data->rental_type == 'hourly' ? $request->estimated_hours ??  $user_data?->estimated_hours ?? 0 : 0;
        $user_data->is_guest = $is_guest;
        $user_data->save();

        $carts = $this->cart;
        $carts->user_id = $user_id;
        $carts->is_guest = $is_guest;
        $carts->vehicle_id = $request->vehicle_id;
        $carts->provider_id = $vehicle->provider_id;
        $carts->quantity = $request->quantity ?? 1;
        $carts->module_id = $request->header('moduleId');
        $carts->save();

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with('vehicles')->get();

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

        $cart = $this->cart->where('id', $request->cart_id)->first();
        if(!$cart){
            return response()->json(['errors' => translate('cart_not_found')], 404);
        }
        $cart->user_id = $user_id;
        $cart->is_guest = $is_guest;
        $cart->quantity = $request->quantity ?? 1;
        $cart->save();
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with('vehicles')->get();

        $user_data = $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->firstOrNew();
        $user_data->pickup_location = $request->pickup_location ?? $user_data?->pickup_location;
        $user_data->destination_location = $request->destination_location ?? $user_data?->destination_location;
        $user_data->pickup_time = $request->pickup_time ?? $user_data?->pickup_time ?? now();
        $user_data->rental_type = $request->rental_type ?? $user_data?->rental_type;
        $user_data->estimated_hours = $user_data->rental_type == 'hourly' ? $request->estimated_hours ??  $user_data?->estimated_hours ?? 0 : 0;
        $user_data->is_guest = $is_guest;
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
        $user_data =   $this->user_data->where('user_id', $user_id)->where('is_guest', $is_guest)->first()?? [];

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest', $is_guest)->where('module_id', $request->header('moduleId'))->with('vehicles')->get();

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
}
