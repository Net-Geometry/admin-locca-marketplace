<?php

namespace Modules\Rental\Http\Controllers\Api\Public;


use App\Models\Zone;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\Vehicle;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;

class VehicleController extends Controller
{



    public function __construct(private Vehicle $vehicle, private Helpers $helpers)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
    }

    public function topRatedVehicleList(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_id = $request->header('zoneId');
        $zone_id = json_decode($zone_id, true);

        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;

        $vehicles = $this->vehicle->whereIn('zone_id', $zone_id)->with('provider:id,name,address,tax','provider.discount')->withcount('vehicleIdentities as total_vehicle_count')
            ->orderBy('total_trip', 'desc')
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);

        $data = $this->helpers->preparePaginatedResponse(pagination: $vehicles, limit: $limit, offset: $offset, key: 'vehicles', extraData: []);
        return response()->json($data, 200);
    }

    public function getSearchedVehicles(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;
        $pickup_location = json_decode($request->pickup_location, true) ?? [];

        $pick_up_lat = data_get($pickup_location, 'lat') ?? null;
        $pick_up_lng = data_get($pickup_location, 'lng') ?? null;

        if($pick_up_lat && $pick_up_lng){
            $zones = Zone::whereContains('coordinates', new Point($pick_up_lat, $pick_up_lng, POINT_SRID))->pluck('id')->toArray();
        }


        if($pick_up_lat && $pick_up_lng && count($zones) == 0){
            $errors = [];
            array_push($errors, ['code' => 'zone', 'message' => translate('messages.Out_of_pick_up_zone')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }


        $vehicles = $this->getVelicleListData($request,$zones??[],$pick_up_lat,$pick_up_lng)->paginate($limit, ['*'], 'page', $offset);
        $data = $this->helpers->preparePaginatedResponse(pagination: $vehicles, limit: $limit, offset: $offset, key: 'vehicles', extraData: []);
        return response()->json($data, 200);
    }


    public function getSearchedVehiclesSuggestion(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_id = $request->header('zoneId');
        $zone_id = json_decode($zone_id, true);

        $vehicles = $this->vehicle->whereIn('zone_id', $zone_id)->with('brand:id,name')
            ->when($request->filled('name'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('name'));
                $query->where(function ($query) use ($keys) {
                    foreach ($keys as $value) {
                        $query->orWhere('name', 'LIKE', '%' . $value . '%')->orWhere('tag', 'LIKE', '%' . $value . '%');
                    }
                    $relationships = [
                        'translations' => 'name',
                        'category' => 'name',
                        'brand' => 'name',
                    ];
                    $query->applyRelationShipSearch(relationships: $relationships, searchParameter: $keys);
                });
            })
            ->latest()
            ->take(10)
            ->get(['id', 'brand_id', 'name']);
        $vehicles = $vehicles->map(function ($vehicle) {
            if ($vehicle?->brand?->name) {
                return $vehicle?->brand?->name . ' - ' . $vehicle->name;
            } else {
                return  $vehicle->name;
            }
        });
        return response()->json($vehicles, 200);
    }

    public function getProviderWiseVehicles(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;
        $vehicles = $this->getVelicleListData($request)->paginate($limit, ['*'], 'page', $offset);
        $data = $this->helpers->preparePaginatedResponse(pagination: $vehicles, limit: $limit, offset: $offset, key: 'vehicles', extraData: []);
        return response()->json($data, 200);
    }

    public function getVehicleDetails(Vehicle $vehicle)
    {
        $vehicle = $vehicle->load('brand:id,name,image', 'provider:id,name,logo,cover_photo,rating')->loadCount('vehicleIdentities');
        $ratings = StoreLogic::calculate_store_rating($vehicle['provider']['rating']);
        $vehicle['provider']['avg_rating'] = $ratings['rating'];
        $vehicle['provider']['rating_count'] = $ratings['total'];
        return response()->json($vehicle, 200);
    }

    private function getVelicleListData($request,$zones=[],$pick_up_lat=null,$pick_up_lng=null)
    {
        $zone_id = $request->header('zoneId');
        $zone_id = json_decode($zone_id, true);


        $brand_ids = json_decode($request->brand_ids, true) ?? null;
        $category_ids = json_decode($request->category_ids, true) ?? null;
        $seating_capacity = json_decode($request->seating_capacity, true) ?? null;

        $vehicles = $this->vehicle
            ->when($pick_up_lat &&  $pick_up_lng && count($zones) > 0, function ($query) use ($zones) {
                $query->whereHas('provider', function ($query) use ($zones) {
                    $query->where(function ($query) use ($zones) {
                        $query->whereJsonContains('pickup_zone_id', (string) $zones[0]);
                        for ($i = 1; $i < count($zones); $i++) {
                            $query->orWhereJsonContains('pickup_zone_id', (string) $zones[$i]);
                        }
                        return $query;
                    });
                });
            })
            ->with('provider:id,name,address,tax','provider.discount');
            if($request?->date){
                $vehicles = $vehicles->withCount([
                    'vehicleIdentities as total_vehicle_count' => function ($query) use($request) {
                        $query->where(function ($query) use($request) {
                            $query->whereHas('vehicle_trip_details', function ($subQuery) use($request) {
                                $subQuery->where('estimated_trip_end_time', '<', $request?->date ?? now());
                            })
                            ->orWhereDoesntHave('vehicle_trip_details');
                        });
                    },
                ])->having('total_vehicle_count' ,'>', 0);

            } else{
                $vehicles = $vehicles->withcount('vehicleIdentities as total_vehicle_count');
            }

            $vehicles = $vehicles->when($request->provider_id, function ($query) use ($request) {
                $query->where('provider_id', $request->provider_id);
            })
            ->when($request->trip_type == 'hourly', function ($query) {
                $query->where('trip_hourly', 1);
            })
            ->when($request->trip_type == 'distance_wise', function ($query) {
                $query->where('trip_distance', 1);
            })
            ->when($request->trip_type == 'distance_wise'  && $request->min_price > 0 && $request->max_price > 0, function ($query) use ($request) {
                $query->wherebetween('distance_price', [$request->min_price, $request->max_price]);
            })
            ->when($request->trip_type == 'hourly'  && $request->min_price > 0 && $request->max_price > 0, function ($query) use ($request) {
                $query->wherebetween('hourly_price', [$request->min_price, $request->max_price]);
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('name'));
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%')->orWhere('tag', 'LIKE', '%' . $key . '%');
                }
            })
            ->when($brand_ids, function ($query) use ($brand_ids) {
                $query->whereIn('brand_id', $brand_ids);
            })
            ->when($category_ids, function ($query) use ($category_ids) {
                $query->whereIn('category_id', $category_ids);
            })
            ->when($seating_capacity, function ($query) use ($seating_capacity) {
                $query->whereIn('seating_capacity', $seating_capacity);
            })
            ->when($request->air_condition, function ($query) {
                $query->where('air_condition', 1);
            })
            ->when($request->no_air_condition, function ($query) {
                $query->where('air_condition', 0);
            })
            ->when($request->transmission_type, function ($query) use ($request) {
                $query->where('transmission_type', $request->transmission_type);
            })
            ->when($request->fuel_type, function ($query) use ($request) {
                $query->where('fuel_type', $request->fuel_type);
            })
            ->when($request->top_rated == 1, function ($query){
                $query->orderBy('total_trip', 'desc');
            })
            ->when(in_array($request->sortby_price,['asc','desc']), function ($query) use ($request){
                $query->orderBy('hourly_price', $request->sortby_price)->orderBy('distance_price', $request->sortby_price);
            })

            ->latest();



        return $vehicles;
    }
}
