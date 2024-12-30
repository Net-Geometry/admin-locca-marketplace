<?php

namespace Modules\Rental\Http\Controllers\Api\Provider;

use App\CentralLogics\Helpers;
use App\Models\Banner;
use App\Models\Store;
use App\Traits\FileManagerTrait;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Rental\Entities\Trips;

class ProviderTripController extends Controller
{



    public function __construct(private Trips $trips, private Helpers $helpers, Store $store)
    {
        $this->trips = $trips;
        $this->helpers = $helpers;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function tripList(Request $request,$trip_status): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'trip_status' => 'nullable|in:pending,confirmed,ongoing,completed,canceled,payment_failed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }
        $limit = $request['limit']?? 25;
        $offset = $request['offset'] ??1;

        $providerId = $request->vendor->id;
        // $moduleId = $request->vendor->stores[0]->module_id;


        $trips= $this->trips->where('provider_id',$providerId)
        ->when(in_array($trip_status,['pending','confirmed','ongoing','completed','canceled','payment_failed']), function ($query) use ($trip_status) {
            $query->where('trip_status', $trip_status);
        })
        ->latest()
        ->paginate($limit, ['*'], 'page', $offset);


        $data = $this->helpers->preparePaginatedResponse(pagination:$trips, limit:$limit, offset:$offset, key:'trips', extraData:[]);

        return response()->json($data, 200);
    }


    public function getTripDetails(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $providerId = $request->vendor->id;
        // $moduleId = $request->vendor->stores[0]->module_id;


        $trip= $this->trips->where('provider_id',$providerId)->where('id',$request->trip_id)
        // ->withcount('customer.trips')
        ->with(['customer:id,f_name,l_name,phone,email,image','trip_details','vehicle_identity.vehicle_identity_data'])
        ->first();

        if(!$trip){
            return response()->json(['errors' => translate('Trip_not_found')], 404);
        }



        foreach($trip->vehicle_identity as $r){

            dd($r->vehicle_identity_data);
        }

        if ($trip && $trip->customer) {
            $trip->customer->trips_count = $trip->customer->trips()->count();
        }

        return response()->json($trip, 200);
    }


}
