<?php

namespace Modules\Rental\Http\Controllers\Api\Provider;

use App\CentralLogics\Helpers;
use App\Models\Store;
use App\Traits\FileManagerTrait;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\VehicleBrand;
use Modules\Rental\Entities\VehicleCategory;
use Modules\Rental\Entities\VehicleIdentity;

class VehicleController extends Controller
{
    use FileManagerTrait;
    private Vehicle $vehicle;
    private VehicleCategory $vehicleCategory;
    private VehicleBrand $vehicleBrand;
    private VehicleIdentity $vehicleIdentity;
    private Helpers $helpers;
    private Store $store;

    public function __construct(Vehicle $vehicle, VehicleCategory $vehicleCategory, VehicleBrand $vehicleBrand, Helpers $helpers, Store $store, VehicleIdentity $vehicleIdentity)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
        $this->store = $store;
        $this->vehicleCategory = $vehicleCategory;
        $this->vehicleBrand = $vehicleBrand;
        $this->vehicleIdentity = $vehicleIdentity;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|integer|min:1',
            'offset' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $vehicles = $this->vehicle
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
            ->latest()
            ->paginate($request->input('limit'), ['*'], 'page', $request->input('offset'));

        return response()->json($vehicles, 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('rental::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->has('thumbnail')) {
            $thumbnailName = $this->upload('vehicle/', 'png', $request->file('thumbnail'));
        } else {
            $thumbnailName = 'def.png';
        }

        $imagesNames = [];
        if (!empty($request->file('images'))) {
            foreach ($request->images as $img) {
                $image = $this->upload('vehicle/', 'png', $img);
                $imagesNames[] = ['img' => $image, 'storage' => $this->helpers->getDisk()];
            }
            $image = json_encode($imagesNames);
        } else {
            $image = json_encode([]);
        }

        $vehicleDocuments = [];
        if (!empty($request->file('documents'))) {
            foreach ($request->documents as $img) {
                $documents = $this->upload('vehicle/', 'png', $img);
                $vehicleDocuments[] = ['img' => $documents, 'storage' => $this->helpers->getDisk()];
            }
            $documents = json_encode($vehicleDocuments);
        } else {
            $documents = json_encode([]);
        }

        $vehicles = $request->input('vehicle');
        $vinNumbers = $vehicles['vin_number'];
        $licensePlateNumbers = $vehicles['license_plate_number'];

        try {
            DB::beginTransaction();

            $vehicle = $this->vehicle;
            $vehicle->name = $request->name[array_search('default', $request->lang)];
            $vehicle->description = $request->description[array_search('default', $request->lang)];
            $vehicle->provider_id = $request->provider_id;
            $vehicle->brand_id = $request->brand_id;
            $vehicle->category_id = $request->category_id;
            $vehicle->model = $request->model;
            $vehicle->type = $request->type;
            $vehicle->engine_capacity = $request->engine_capacity;
            $vehicle->engine_power = $request->engine_power;
            $vehicle->seating_capacity = $request->seating_capacity;
            $vehicle->air_condition = $request->air_condition ? 1 : 0;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->transmission_type = $request->transmission_type;
            $vehicle->trip_hourly = $request->trip_hourly ? 1 : 0;
            $vehicle->trip_distance = $request->trip_distance ? 1 : 0;
            $vehicle->hourly_price = $request->hourly_price;
            $vehicle->discount_price = $request->discount_price;
            $vehicle->discount_type = $request->discount_type;
            $vehicle->tag = json_encode($request->tag);
            $vehicle->thumbnail = $thumbnailName;
            $vehicle->images = $image;
            $vehicle->documents = $documents;
            $vehicle->save();

            foreach ($vinNumbers as $index => $vin) {
                $licensePlate = $licensePlateNumbers[$index];

                $this->vehicleIdentity->create([
                    'vehicle_id' => $vehicle->id,
                    'provider_id' => $vehicle->provider_id,
                    'vin_number' => $vin,
                    'license_plate_number' => $licensePlate,
                ]);
            }

            $this->helpers->add_or_update_translations(request: $request, key_data: 'name', name_field: 'name', model_name: 'Vehicle', data_id: $vehicle->id, data_value: $vehicle->name);
            $this->helpers->add_or_update_translations(request: $request, key_data: 'description', name_field: 'description', model_name: 'Vehicle', data_id: $vehicle->id, data_value: $vehicle->description);

            DB::commit();
            return response()->json(['message' => translate('messages.vehicle_created_successfully.')], 200);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => translate('messages.some_thing_wrong.')], 400);

        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $vehicle = $this->vehicle->findOrFail($id);
        if (!$vehicle) {
            return response()->json(['message' => translate('messages.vehicle_not_found.')], 400);
        }

        if ($request->has('thumbnail')) {
            $thumbnailName = $this->updateAndUpload('vehicle/', $vehicle->thumbnail, 'png', $request->file('thumbnail'));
        } else {
            $thumbnailName = $vehicle->thumbnail;
        }

        $imagesNames = !empty($vehicle->images) ? json_decode($vehicle->images, true) : [];
        if (!empty($request->file('images'))) {
            foreach ($request->images as $img) {
                $image = $this->updateAndUpload('vehicle/', $vehicle->images, 'png', $img);
                $imagesNames[] = ['img' => $image, 'storage' => $this->helpers->getDisk()];
            }
        }
        $image = json_encode($imagesNames);

        $vehicleDocuments = !empty($vehicle->documents) ? json_decode($vehicle->documents, true) : [];
        if (!empty($request->file('documents'))) {
            foreach ($request->documents as $doc) {
                $document = $this->updateAndUpload('vehicle/', $vehicle->documents, 'png', $doc);
                $vehicleDocuments[] = ['img' => $document, 'storage' => $this->helpers->getDisk()];
            }
        }
        $documents = json_encode($vehicleDocuments);

        $vehicles = $request->input('vehicle');
        $vinNumbers = $vehicles['vin_number'];
        $licensePlateNumbers = $vehicles['license_plate_number'];

        try {
            DB::beginTransaction();
            $vehicle->name = $request->name[array_search('default', $request->lang)];
            $vehicle->description = $request->description[array_search('default', $request->lang)];
            $vehicle->provider_id = $request->provider_id;
            $vehicle->brand_id = $request->brand_id;
            $vehicle->category_id = $request->category_id;
            $vehicle->model = $request->model;
            $vehicle->type = $request->type;
            $vehicle->engine_capacity = $request->engine_capacity;
            $vehicle->engine_power = $request->engine_power;
            $vehicle->seating_capacity = $request->seating_capacity;
            $vehicle->air_condition = $request->air_condition ? 1 : 0;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->transmission_type = $request->transmission_type;
            $vehicle->trip_hourly = $request->trip_hourly ? 1 : 0;
            $vehicle->trip_distance = $request->trip_distance ? 1 : 0;
            $vehicle->hourly_price = $request->hourly_price;
            $vehicle->discount_price = $request->discount_price;
            $vehicle->discount_type = $request->discount_type;
            $vehicle->tag = json_encode($request->tag);
            $vehicle->thumbnail = $thumbnailName;
            $vehicle->images = $image;
            $vehicle->documents = $documents;
            $vehicle->update();

            $requestVinNumbers = $vinNumbers;
            foreach ($vinNumbers as $index => $vin) {
                $licensePlate = $licensePlateNumbers[$index];

                $this->vehicleIdentity->updateOrCreate(
                    [
                        'vehicle_id' => $vehicle->id,
                        'vin_number' => $vin,
                    ],
                    [
                        'provider_id' => $vehicle->provider_id,
                        'license_plate_number' => $licensePlate,
                    ]
                );
            }

            $this->vehicleIdentity->where('vehicle_id', $vehicle->id)->where('provider_id', $vehicle->provider_id)
                ->whereNotIn('vin_number', $requestVinNumbers)
                ->delete();

            $this->helpers->add_or_update_translations(request: $request, key_data: 'name', name_field: 'name', model_name: 'Vehicle', data_id: $vehicle->id, data_value: $vehicle->name);
            $this->helpers->add_or_update_translations(request: $request, key_data: 'description', name_field: 'description', model_name: 'Vehicle', data_id: $vehicle->id, data_value: $vehicle->description);

            DB::commit();
            return response()->json(['message' => translate('messages.vehicle_updated_successfully.')], 200);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => translate('messages.some_thing_wrong.')], 400);

        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function details($id): JsonResponse
    {
        $vehicle = $this->vehicle->with('provider', 'category', 'brand', 'vehicleIdentities')->findOrFail($id);

        if (isset($vehicle)) {
            return response()->json($vehicle, 200);
        }

        return response()->json(['message' => translate('messages.vehicle_not_found.')], 400);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status(Request $request, $id): JsonResponse
    {
        $vehicle = $this->vehicle->find($id);

        if ($vehicle) {
            $vehicle->update(['status' => !$vehicle->status]);
            return response()->json(['message' => translate('messages.vehicle_status_updated.')], 200);
        }

        return response()->json(['message' => translate('messages.vehicle_not_found.')], 400);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function newTag(Request $request, $id): JsonResponse
    {
        $vehicle = $this->vehicle->find($id);

        if ($vehicle) {
            $vehicle->update(['new_tag' => !$vehicle->new_tag]);
            return response()->json(['message' => translate('messages.vehicle_new_tag_updated.')], 200);
        }

        return response()->json(['message' => translate('messages.vehicle_not_found.')], 400);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $vehicle = $this->vehicle->find($id);

        if ($vehicle) {
            if ($vehicle->image) {
                $this->helpers->check_and_delete('vehicle/', $vehicle->image);
            }

            $vehicle->translations()->delete();
            $vehicle->delete();

            return response()->json(['message' => translate('messages.vehicle_deleted_successfully.')], 200);
        }

        return response()->json(['message' => translate('messages.failed_to_delete_vehicle.')], 400);
    }
}
