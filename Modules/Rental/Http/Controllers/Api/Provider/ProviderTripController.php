<?php

namespace Modules\Rental\Http\Controllers\Api\Provider;

use App\CentralLogics\Helpers;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Rental\Entities\Trips;
use Modules\Rental\Entities\TripVehicleDetails;
use Modules\Rental\Entities\Vehicle;

class ProviderTripController extends Controller
{

    public function __construct(private Trips $trips, private Helpers $helpers)
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


    public function updateTripStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
            'cancellation_reason' => 'nullable|max:255',
            'trip_status' => 'required|in:confirmed,ongoing,completed,canceled',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $trip = $this->trips->where(['provider_id' => $request->vendor->id,'id' => $request->trip_id])->first();

        if(!$trip){
            return response()->json(['errors' => translate('trip_data_not_found')], 404);
        }

        if( in_array($request->trip_status,['completed','canceled'])){
            return response()->json(['errors' => translate('You_can_not_change_this_trip_status')], 403);
        }


        $trip->trip_status = $request->trip_status;
            if($request->trip_status == 'canceled'){
                $trip->canceled_by = 'vendor';
                $trip->cancellation_reason = $request?->cancellation_reason;
                $trip->canceled = now();
            }else{
                $trip[$request->trip_status]= now();
            }
        $trip->save();
        return response()->json(['message' => translate('Trip_successfully_canceled')], 200);
    }

    public function updateTripPaymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
            'payment_method' => 'nullable|max:100',
            'transaction_reference' => 'nullable|max:100',
            'payment_status' => 'required|in:paid,unpaid',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $trip = $this->trips->where(['provider_id' => $request->vendor->id,'id' => $request->trip_id])->first();

        if(!$trip){
            return response()->json(['errors' => translate('trip_data_not_found')], 404);
        }

        $trip->payment_status = $request->payment_status;
        $trip->payment_method = $request?->payment_method ??  $trip->payment_method ;
        $trip->transaction_reference = $request?->transaction_reference ??  $trip->transaction_reference ;

        $trip->save();
        return response()->json(['message' => translate('Trip_payment_status_updated')], 200);
    }


    public function assignDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
            'driver_ids'=>'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $trip = $this->trips->where(['provider_id' => $request->vendor->id,'id' => $request->trip_id])->first();

        if(!$trip){
            return response()->json(['errors' => translate('trip_data_not_found')], 404);
        }

        $driver_ids= json_decode($request->driver_ids, true);

        $vehicle_datas=[];
        foreach($driver_ids as $key => $driver_id){
            $vehicle_data= TripVehicleDetails::where('id',$key)->first();
            if(!$vehicle_data){
                return response()->json(['errors' => translate('vehicle_information_not_found')], 404);
            }
            $vehicle_data->vehicle_driver_id=$driver_id;
            $vehicle_data->save();
            $vehicle_datas[]=$vehicle_data;
        }

        return response()->json($vehicle_datas, 200);
    }


    public function assignVehicle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
            'vehicle_identity_ids'=>'required',
            'vehicle_id'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $trip = $this->trips->where(['provider_id' => $request->vendor->id,'id' => $request->trip_id])->first();

        if(!$trip){
            return response()->json(['errors' => translate('trip_data_not_found')], 404);
        }

        if(Vehicle::where(['id'=>$request->vehicle_id, 'provider_id' =>$request->vendor->id])->doesntExist()){
            return response()->json(['errors' => translate('vehicle_not_found')], 404);
        }

        $vehicle_identity_ids= json_decode($request->vehicle_identity_ids, true);
        $vehicle_datas=[];
        foreach($vehicle_identity_ids as  $identity_id){
            $vehicle_data= TripVehicleDetails::where(['trip_id'=> $request->trip_id ,'vehicle_id' =>$request->vehicle_id ,'vehicle_identity_id' =>$identity_id])->firstOrNew();
            $vehicle_data->trip_id = $request->trip_id;
            $vehicle_data->vehicle_id = $request->vehicle_id;
            $vehicle_data->vehicle_identity_id = $identity_id;
            $vehicle_data->estimated_trip_end_time = $trip->estimated_trip_end_time;
            $vehicle_data->save();
            $vehicle_datas[]=$vehicle_data;
        }

        TripVehicleDetails::where(['trip_id'=> $request->trip_id ,'vehicle_id' =>$request->vehicle_id])->whereNotIn('vehicle_identity_id', $vehicle_identity_ids)->delete();
        return response()->json($vehicle_datas, 200);
    }



    public function getTripDetails(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $trip= $this->trips->where('provider_id',$request->vendor->id)->where('id',$request->trip_id)
        ->with(['customer:id,f_name,l_name,phone,email,image','trip_details',
        'vehicle_identity.driver_data:id,first_name,last_name,email,phone,image',
        'vehicle_identity.vehicle_identity_data:id,vin_number,license_plate_number'])
        ->first();

        if(!$trip){
            return response()->json(['errors' => translate('Trip_not_found')], 404);
        }

        if ($trip->customer) {
            $trip->customer->trips_count = $trip->customer->trips()->count();
        }

        return response()->json($trip, 200);
    }


}
