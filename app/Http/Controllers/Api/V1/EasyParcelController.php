<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\EasyParcelCountry;
use App\Models\EasyParcelState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EasyParcelController extends Controller{
    public function countryList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $countries = EasyParcelCountry::Active()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        return response()->json($countries, 200);
    }
    public function stateList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $states = EasyParcelState::Active()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        return response()->json($states, 200);
    }
   
}
