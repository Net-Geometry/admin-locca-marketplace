<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EasyParcelCountry;
use App\Models\EasyParcelState;
use App\Traits\EasyPercelEngineTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EasyParcelController extends Controller{
    use EasyPercelEngineTrait;
    public function countryList(Request $request)
    {
        $countries = EasyParcelCountry::Active()->latest()->get();
        return response()->json($countries, 200);
    }
    public function stateList(Request $request)
    {
     

        $states = EasyParcelState::Active()->latest()->get();
        return response()->json($states, 200);
    }

    public function rateCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pick_code' => 'required',
            'pick_state' => 'required',
            'pick_country' => 'required',
            'send_code' => 'required',
            'send_state' => 'required',
            'send_country' => 'required',
            'weight' => 'required|numeric|min:0.1',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'pick_code' => $request['pick_code'],
            'pick_state' => $request['pick_state'],
            'pick_country' => $request['pick_country'],
            'send_code' => $request['send_code'],
            'send_state' => $request['send_state'],
            'send_country' => $request['send_country'],
            'weight' => (float)$request['weight']
        ];
        

        return response()->json($this->rateCheckEngine($data), 200);
    }

   
}
