<?php

namespace Modules\Rental\Http\Controllers\Api\User;


use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Routing\Controller;
use Modules\Rental\Entities\Vehicle;
use Illuminate\Support\Facades\Validator;
use Modules\Rental\Entities\RentalCart;

class CartController extends Controller
{
    public function __construct(private RentalCart $cart, private Helpers $helpers,private Vehicle $vehicle,)
    {
        $this->cart = $cart;
        $this->helpers = $helpers;
        $this->vehicle = $vehicle;
    }

    public function getCartList(Request $request){
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest',$is_guest)->where('module_id',$request->header('moduleId'))->with('vehicles')->get();
        return response()->json($carts, 200);
    }

    public function addToCart(Request $request){
        $validator = Validator::make($request->all(), [
            'guest_id' => $request->user ? 'nullable' : 'required',
            'vehicle_id' =>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $user_id = $request->user ? $request->user->id : $request['guest_id'];
        $is_guest = $request->user ? 0 : 1;

        $vehicle=$this->vehicle->where('id',$request->vehicle_id)->first();

        if($this->cart->where('vehicle_id',$request->vehicle_id)->where('user_id', $user_id)->where('is_guest',$is_guest)->where('module_id',$request->header('moduleId'))->exists()){
            return response()->json([
                'errors' => [
                    ['code' => 'cart_item', 'message' => translate('messages.Item_already_exists')]
                ]
            ], 403);
        }

        $carts = $this->cart;
        $carts->user_id = $user_id;
        $carts->is_guest = $is_guest;
        $carts->vehicle_id = $request->vehicle_id;
        $carts->provider_id = $vehicle->provider_id;
        $carts->quantity = $request->quantity ?? 1;
        $carts->module_id = $request->header('moduleId');
        $carts->save();

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest',$is_guest)->where('module_id',$request->header('moduleId'))->with('vehicles')->get();

        return response()->json($carts, 200);
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

        $cart = $this->cart->where('id',$request->cart_id)->first();
        $cart->user_id = $user_id;
        $cart->is_guest = $is_guest;
        $cart->quantity = $request->quantity ?? 1;
        $cart->save();
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest',$is_guest)->where('module_id',$request->header('moduleId'))->with('vehicles')->get();

        return response()->json($carts, 200);
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

        $this->cart->where('id',$request->cart_id)->where('user_id', $user_id)->where('is_guest',$is_guest)->delete();

        $carts = $this->cart->where('user_id', $user_id)->where('is_guest',$is_guest)->where('module_id',$request->header('moduleId'))->with('vehicles')->get();

        return response()->json($carts, 200);
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
        $this->cart->where('user_id', $user_id)->where('is_guest',$is_guest)->delete();
        $carts = $this->cart->where('user_id', $user_id)->where('is_guest',$is_guest)->where('module_id',$request->header('moduleId'))->with('vehicles')->get();

        return response()->json($carts, 200);
    }

}
