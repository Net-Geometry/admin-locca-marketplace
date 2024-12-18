<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\CentralLogics\Helpers;
use App\Models\Store;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\VehicleBrand;
use Modules\Rental\Entities\VehicleCategory;
use Modules\Rental\Entities\VehicleIdentity;
use Modules\Rental\Exports\VehicleExport;

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
     * @return Renderable
     */
    public function index(Request $request)
    {
        $vehicles = $this->vehicle
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->paginate(config('default_pagination'));
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());

        return view('rental::admin.vehicle.list', compact('vehicles', 'language', 'defaultLang'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(): Renderable
    {
        $providers = $this->store->with('vendor','module')->whereHas('vendor', function($query){
            return $query->ofStatus(1);
        })
        ->module(Config::get('module.current_module_id'))
        ->with('vendor','module')->latest()->get();
        $categories = $this->vehicleCategory->ofStatus(1)->latest()->get();
        $brands = $this->vehicleBrand->ofStatus(1)->latest()->get();

        return view('rental::admin.vehicle.create', compact('providers', 'categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
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

        $providerZoneId = $this->store->where('id', $request->provider_id)->value('zone_id') ?? 0;
        $vehicles = $request->input('vehicle');
        $vinNumbers = $vehicles['vin_number'];
        $licensePlateNumbers = $vehicles['license_plate_number'];

        $vehicle = $this->vehicle;
        $vehicle->name = $request->name[array_search('default', $request->lang)];
        $vehicle->description = $request->description[array_search('default', $request->lang)];
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

        Toastr::success(translate('messages.vehicle_added_successfully'));
        return back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('rental::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $vehicle = $this->vehicle->findOrFail($id);
        $providers = $this->store->with('vendor','module')->whereHas('vendor', function($query){
            return $query->ofStatus(1);
        })
        ->module(Config::get('module.current_module_id'))
        ->with('vendor','module')->latest()->get();
        $categories = $this->vehicleCategory->ofStatus(1)->latest()->get();
        $brands = $this->vehicleBrand->ofStatus(1)->latest()->get();

        return view('rental::admin.vehicle.edit', compact('vehicle', 'providers', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $vehicle = $this->vehicle->findOrFail($id);
        if (!$vehicle) {
            Toastr::success(translate('messages.vehicle_not_found_successfully'));
            return back();
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

        $vehicleDocuments = !empty($vehicle->documents) ? json_decode($vehicle->documents, true) : []; // Decode JSON to array
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

        $vehicle->name = $request->name[array_search('default', $request->lang)];
        $vehicle->description = $request->description[array_search('default', $request->lang)];
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

        Toastr::success(translate('messages.vehicle_updated_successfully'));
        return back();
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function details(int $id): Renderable
    {
        $vehicle = $this->vehicle->findOrFail($id);


        $language = getWebConfig('language') ?? [];
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('rental::admin.vehicle.details', compact('vehicle', 'language', 'defaultLang'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function status(Request $request, $id): RedirectResponse
    {
        $vehicle = $this->vehicle->find($id);

        if (!$vehicle) {
            Toastr::error(translate('messages.vehicle_not_found'));
            return back();
        }

        $vehicle->update(['status' => !$vehicle->status]);

        Toastr::success(translate('messages.vehicle_status_updated_successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function newTag(Request $request, $id): RedirectResponse
    {
        $vehicle = $this->vehicle->find($id);

        if (!$vehicle) {
            Toastr::error(translate('messages.vehicle_not_found'));
            return back();
        }

        $vehicle->update(['new_tag' => !$vehicle->new_tag]);

        Toastr::success(translate('messages.vehicle_new_tag_updated_successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $vehicle = $this->vehicle->find($id);

        if (!$vehicle) {
            Toastr::error(translate('messages.failed_to_delete_vehicle'));
            return back();
        }

        if ($vehicle->thumbnail) {
            $this->helpers->check_and_delete('vehicle/' , $vehicle->thumbnail);
        }

        if ($vehicle->images) {
            $value = is_array($vehicle->images)
                ? $vehicle->images
                : ($vehicle->images && is_string($vehicle->images)
                    ? json_decode($vehicle->images, true)
                    : []);
            if ($value){
                foreach ($value as $item){
                    $item = is_array($item)?$item:(is_object($item) && get_class($item) == 'stdClass' ? json_decode(json_encode($item), true):['img' => $item]);
                    $this->helpers->check_and_delete('vehicle/' , $item['img']);
                }
            }
        }

        if ($vehicle->documents) {
            $value = is_array($vehicle->documents)
                ? $vehicle->documents
                : ($vehicle->documents && is_string($vehicle->documents)
                    ? json_decode($vehicle->documents, true)
                    : []);
            if ($value){
                foreach ($value as $item){
                    $item = is_array($item)?$item:(is_object($item) && get_class($item) == 'stdClass' ? json_decode(json_encode($item), true):['img' => $item]);
                    $this->helpers->check_and_delete('vehicle/' , $item['img']);
                }
            }
        }

        $vehicle->vehicleIdentities()->delete();
        $vehicle->translations()->delete();
        $vehicle->delete();

        Toastr::success(translate('messages.vehicle_deleted_successfully'));

        if ($request->vehicle_list){
            return to_route('admin.rental.provider.vehicle.list');
        }elseIf($request->provider_vehicle_list){
            return to_route('admin.rental.provider.details',['id' => $request->provider_id, 'tab' => 'vehicle']);
        }

        return back();
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $vehicles = $this->vehicle
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->get();

        $data = [
            'data' => $vehicles,
            'search' => $request['search'] ?? null,
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new VehicleExport($data), 'Vehicles.csv');
        }
        return Excel::download(new VehicleExport($data), 'Vehicles.xlsx');
    }

}
