<?php

namespace Modules\Rental\Http\Controllers\Api\Public;

use App\Models\User;
use App\Models\Zone;
use App\Models\Store;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\Rental\Entities\Vehicle;

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
        $zone_id=$request->header('zoneId');
        $zone_id =json_decode($zone_id, true);

        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;

        $vehicles = $this->vehicle->whereIn('zone_id',$zone_id)
            ->when($request->filled('search'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('search'));
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->input('category_id'));
            })
            ->when($request->filled('brand_id'), function ($query) use ($request) {
                $query->where('brand_id', $request->input('brand_id'));
            })
            ->when($request->filled('seating_capacity'), function ($query) use ($request) {
                $query->where('seating_capacity', $request->input('seating_capacity'));
            })
            ->when($request->filled('air_condition'), function ($query) use ($request) {
                $query->where('air_condition', $request->input('air_condition'));
            })
            ->when($request->filled('transmission_type'), function ($query) use ($request) {
                $query->where('transmission_type', $request->input('transmission_type'));
            })
            ->when($request->filled('fuel_type'), function ($query) use ($request) {
                $query->where('fuel_type', $request->input('fuel_type'));
            })
            ->orderBy('total_trip','desc')
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);

        $data = $this->helpers->preparePaginatedResponse(pagination:$vehicles, limit:$limit, offset:$offset, key:'vehicles', extraData:[]);
        return response()->json($data, 200);
    }

}
