<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\CentralLogics\Helpers;
use App\Models\Store;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\VehicleBrand;
use Modules\Rental\Entities\VehicleCategory;

class VehicleController extends Controller
{
    use FileManagerTrait;
    private Vehicle $vehicle;
    private VehicleCategory $vehicleCategory;
    private VehicleBrand $vehicleBrand;
    private Helpers $helpers;
    private Store $store;

    public function __construct(Vehicle $vehicle, VehicleCategory $vehicleCategory, VehicleBrand $vehicleBrand, Helpers $helpers, Store $store)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
        $this->store = $store;
        $this->vehicleCategory = $vehicleCategory;
        $this->vehicleBrand = $vehicleBrand;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('rental::index');
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
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

        $vehicle = $this->vehicle;
        $vehicle->name = $request->name;
        $vehicle->description = $request->description;
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
        $vehicle->trip_type = $request->trip_type;
        $vehicle->hourly_price = $request->hourly_price;
        $vehicle->distance_price = $request->distance_price;
        $vehicle->discount_type = $request->discount_type;
        $vehicle->tag = $request->tag;
        $vehicle->thumbnail = $thumbnailName;
        $vehicle->images = $image;
        $vehicle->documents = $documents;
        $vehicle->save();

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
        return view('rental::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
