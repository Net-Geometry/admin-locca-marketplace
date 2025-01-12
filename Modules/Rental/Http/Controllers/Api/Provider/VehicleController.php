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
use Illuminate\Validation\Rule;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\VehicleBrand;
use Modules\Rental\Entities\VehicleCategory;
use Modules\Rental\Entities\VehicleIdentity;
use Modules\Rental\Entities\VehicleReview;

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

        $limit = $request['limit'];
        $offset = $request['offset'];

        $vehicles = $this->vehicle->with('vehicleIdentities','provider', 'category', 'brand', 'translations')
            ->when($request->filled('search'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('search'));
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->where('provider_id', $request['vendor']->store->id)
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
            ->when($request->filled('vehicle_type'), function ($query) use ($request) {
                $query->where('type', $request->input('vehicle_type'));
            })
            ->when($request->filled('fuel_type'), function ($query) use ($request) {
                $query->where('fuel_type', $request->input('fuel_type'));
            })
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);

        $vehicles->transform(function ($vehicle) {
            $vehicle->tag = json_decode($vehicle->tag, true);
            return $vehicle;
        });

        $data = $this->helpers->preparePaginatedResponse(pagination:$vehicles, limit:$limit, offset:$offset, key:'vehicles', extraData:[]);


        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'brand_id' => 'required',
            'translations'=>'required',
        ], [
            'category_id.required' => translate('messages.category_required'),
            'brand_id.required' => translate('messages.brand_required'),
        ]);

        $data = json_decode($request->translations, true) ?? [];

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Name and description in english is required'));
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 402);
        }

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

        $providerZoneId = $this->store->where('id', $request->provider_id)->value('zone_id') ?? 0;

        $vehicles = $request->input('vehicle');
        $vinNumbers = $vehicles['vin_number'];
        $licensePlateNumbers = $vehicles['license_plate_number'];

        try {
            DB::beginTransaction();

            $vehicle = $this->vehicle;
            $vehicle->name = $data[0]['value'];
            $vehicle->description = $data[1]['value'];
            $vehicle->zone_id = $providerZoneId;
            $vehicle->provider_id = $request->provider_id;
            $vehicle->brand_id = $request->brand_id;
            $vehicle->category_id = $request->category_id;
            $vehicle->model = $request->model;
            $vehicle->type = $request->type;
            $vehicle->engine_capacity = $request->engine_capacity;
            $vehicle->engine_power = $request->engine_power;
            $vehicle->seating_capacity = $request->seating_capacity;
            $vehicle->air_condition = $request->air_condition ? 1 : 0;
            $vehicle->multiple_vehicles = $request->multiple_vehicles ? 1 : 0;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->transmission_type = $request->transmission_type;
            $vehicle->trip_hourly = $request->trip_hourly ? 1 : 0;
            $vehicle->trip_distance = $request->trip_distance ? 1 : 0;
            $vehicle->hourly_price = $request->hourly_price ?? 0.00;
            $vehicle->discount_price = $request->discount_price ?? 0.00;
            $vehicle->distance_price = $request->distance_price ?? 0.00;
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
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'brand_id' => 'required',
            'translations'=>'required',
        ], [
            'category_id.required' => translate('messages.category_required'),
            'brand_id.required' => translate('messages.brand_required'),
        ]);

        $data = json_decode($request->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Name and description in english is required'));
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 402);
        }

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
        $providerZoneId = $this->store->where('id', $request->provider_id)->value('zone_id') ?? 0;
        $vehicles = $request->input('vehicle');
        $vinNumbers = $vehicles['vin_number'];
        $licensePlateNumbers = $vehicles['license_plate_number'];

        try {
            DB::beginTransaction();

            $vehicle->name = $data[0]['value'];
            $vehicle->description = $data[1]['value'];
            $vehicle->zone_id = $providerZoneId;
            $vehicle->provider_id = $request->provider_id;
            $vehicle->brand_id = $request->brand_id;
            $vehicle->category_id = $request->category_id;
            $vehicle->model = $request->model;
            $vehicle->type = $request->type;
            $vehicle->engine_capacity = $request->engine_capacity;
            $vehicle->engine_power = $request->engine_power;
            $vehicle->seating_capacity = $request->seating_capacity;
            $vehicle->air_condition = $request->air_condition ? 1 : 0;
            $vehicle->multiple_vehicles = $request->multiple_vehicles ? 1 : 0;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->transmission_type = $request->transmission_type;
            $vehicle->trip_hourly = $request->trip_hourly ? 1 : 0;
            $vehicle->trip_distance = $request->trip_distance ? 1 : 0;
            $vehicle->hourly_price = $request->hourly_price ?? 0.00;
            $vehicle->discount_price = $request->discount_price ?? 0.00;
            $vehicle->distance_price = $request->distance_price ?? 0.00;
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

    public function reviews(Request $request)
    {

        $limit = $request['limit']?? 25;
        $offset = $request['offset'] ?? 1;


        $id = $request['vendor']->stores[0]->id;
        $key = explode(' ', $request['search']);

        $reviews = VehicleReview::with(['customer', 'vehicle'])->where('provider_id' ,$id)

        ->when(isset($key), function ($query) use ($key,$request) {
            $query->where(function($query) use($key,$request) {
                $query->whereHas('vehicle', function ($query) use ($key) {
                    foreach ($key as $value) {
                        $query->where('name', 'like', "%{$value}%");
                    }
                })->orWhereHas('customer', function ($query) use ($key){
                    foreach ($key as $value) {
                        $query->where('f_name', 'like', "%{$value}%")->orwhere('l_name', 'like', "%{$value}%");
                    }
                })->orwhere('rating', $request['search'])->orwhere('review_id', $request['search']);
            });
        })
        ->latest()
        ->paginate($limit, ['*'], 'page', $offset);

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $item['vehicle_name'] = null;
            $item['vehicle_image'] = null;
            $item['customer_name'] = null;
            $item['customer_phone'] = null;
            if($item->vehicle)
            {
                $item['vehicle_name'] = $item->vehicle->name;
                $item['vehicle_image'] = $item->vehicle->image;
                $item['vehicle_image_full_url'] = $item->vehicle->image_full_url;
            }

            if($item->customer)
            {
                $item['customer_name'] = $item->customer->f_name.' '.$item->customer->l_name;
                $item['customer_phone'] = $item->customer->phone;
            }

            unset($item['vehicle']);
            unset($item['customer']);
            array_push($storage, $item);
        }

        $data = [
            'total_size' => (int) $reviews->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'reviews' => $storage,
        ];

        return response()->json($data, 200);
    }
    public function updateReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'reply' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $review = VehicleReview::findOrFail($request->id);
        $review->reply = $request->reply;
        $review->replied_at = now();
        $review->provider_id = $request['vendor']?->stores[0]?->id;
        $review->save();

        return response()->json(['message'=>translate('messages.review_reply_updated_successfully')], 200);
    }

}
