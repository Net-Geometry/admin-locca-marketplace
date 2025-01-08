<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Rental\Entities\TripDetails;
use Modules\Rental\Entities\Trips;
use Modules\Rental\Entities\TripVehicleDetails;
use Modules\Rental\Entities\Vehicle;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Rental\Exports\TripExport;
use Modules\Rental\Traits\TripLogicTrait;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{

    use TripLogicTrait;
    private Trips $trips;
    private TripDetails $tripDetails;
    private TripVehicleDetails $tripVehicleDetails;

    public function __construct( Trips $trips, TripDetails $tripDetails, TripVehicleDetails $tripVehicleDetails)
    {
        $this->trips = $trips;
        $this->tripDetails = $tripDetails;
        $this->tripVehicleDetails = $tripVehicleDetails;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function list(Request $request): Renderable
    {
        $key = explode(' ', $request['search']);
        $status = $request['status'];

        $this->trips->where(['checked' => 0])->update(['checked' => 1]);

        $trips = $this->trips->with(['customer', 'provider'])
            ->when($status == 'scheduled', function ($query) {
                return $query->scheduled();
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'confirmed', function ($query) {
                return $query->Confirmed();
            })
            ->when($status == 'ongoing', function ($query) {
                return $query->Ongoing();
            })
            ->when($status == 'completed', function ($query) {
                return $query->Completed();
            })
            ->when($status == 'canceled', function ($query) {
                return $query->Canceled();
            })
            ->when($status == 'payment_failed', function ($query) {
                return $query->PaymentFailed();
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('provider', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($request->from_date) && isset($request->to_date) && $request->from_date != null && $request->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhereHas('customer', function ($q) use ($value) {
                                $q->where('f_name', 'like', "%{$value}%")
                                    ->orWhere('l_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            });
                    }
                });
            })
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'));

        $total = $trips->total();

        return view('rental::admin.trip.list', compact('trips', 'status', 'total'));

    }

    /**
     * Show the form for creating a new resource.
     * @param $id
     * @return Renderable
     */
    public function details($id): Renderable
    {
        $trip = $this->trips->findOrFail($id);
        session()->forget('vehicleQuantities');
        session()->forget('modifiedPrices');
        return view('rental::admin.trip.details', compact('trip'));
    }

    /**
     * @param $id
     * @param $status
     * @return RedirectResponse
     */

    public function status($id, $status): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $trip = $this->trips->findOrFail($id);

            if (!$trip) {
                Toastr::success(translate('messages.trip_not_found'));
                return back();
            }

            if ($trip->trip_status != 'pending' && $status == 'pending') {
                $trip->vehicle_identity()->delete();
            }

            $trip->trip_status = $status;
            $trip->save();

            if ($status == 'completed' && $trip->payment_status == 'paid' && !$trip->trip_transaction) {
                if ($this->create_transaction($trip, 'vendor') === false) {
                    DB::rollBack();

                    Toastr::error(translate('messages.Failed_to_create_Transaction'));
                    return back();
                }
            }

            DB::commit();

            Toastr::success(translate('messages.trip_status_updated_successfully'));
            return back();
        } catch (\Exception $e) {
            DB::rollBack();

            Toastr::error(translate('messages.something worng'));
            return back();
        }
    }

    /**
     * @param $id
     * @param $status
     * @return RedirectResponse
     */
    public function paymentStatus($id, $status): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $trip = $this->trips->findOrFail($id);

            if (!$trip) {
                Toastr::success(translate('messages.trip_not_found'));
                return back();
            }

            $trip->payment_status = $status;
            $trip->save();

            if ($trip->trip_status == 'completed' && $trip->payment_status == 'paid' && !$trip->trip_transaction) {
                if ($this->create_transaction($trip, 'vendor') === false) {
                    DB::rollBack();

                    Toastr::error(translate('messages.Failed_to_create_Transaction'));
                    return back();
                }
            }

            DB::commit();

            Toastr::success(translate('messages.trip_payment_status_updated_successfully'));
            return back();
        } catch (\Exception $e) {
            DB::rollBack();

            Toastr::error(translate('messages.something worng'));
            return back();
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function assignVehicle(Request $request): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $request->validate([
            'trip_id' => 'required',
            'vehicle_identity_ids'=>'required',
            'vehicle_id'=>'required',
            'details_id'=>'required',
        ]);

        $trip = $this->trips->find($request->trip_id);

        if(!$trip){
            Toastr::success(translate('messages.trip_data_not_found'));
            return back();
        }

        if(Vehicle::where(['id'=> $request->vehicle_id])->doesntExist()){
            Toastr::success(translate('messages.vehicle_not_found'));
            return back();
        }

        $vehicle_identity_ids = $request->vehicle_identity_ids;

        foreach($vehicle_identity_ids as  $identity_id){
            $vehicle_data = TripVehicleDetails::where(['trip_id' => $request->trip_id ,'vehicle_id' => $request->vehicle_id ,'vehicle_identity_id' => $identity_id])->firstOrNew();
            $vehicle_data->trip_id = $request->trip_id;
            $vehicle_data->vehicle_id = $request->vehicle_id;
            $vehicle_data->trip_details_id = $request->details_id;
            $vehicle_data->vehicle_identity_id = $identity_id;
            $vehicle_data->estimated_trip_end_time = $trip->estimated_trip_end_time;
            $vehicle_data->save();
        }

        TripVehicleDetails::where(['trip_id'=> $request->trip_id ,'vehicle_id' => $request->vehicle_id])->whereNotIn('vehicle_identity_id', $vehicle_identity_ids)->delete();

        Toastr::success(translate('messages.trip_vehicle_assigned_successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function assignDriver(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'trip_id' => 'required',
            'driver_ids'=>'required',
        ]);

        $trip = $this->trips->find($request->trip_id);
        if(!$trip){
            Toastr::success(translate('messages.trip_data_not_found'));
            return back();
        }

        $driver_ids = $request->driver_ids;

        foreach($driver_ids as $key => $driver_id){
            $vehicle_data = TripVehicleDetails::where('id', $key)->first();

            if(!$vehicle_data){
                return response()->json(['errors' => translate('vehicle_information_not_found')], 404);
            }

            $vehicle_data->vehicle_driver_id = $driver_id;
            $vehicle_data->save();
        }

        Toastr::success(translate('messages.trip_driver_assigned_successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request): mixed
    {
        $key = explode(' ', $request['search']);
        $status = $request['status'];

        $this->trips->where(['checked' => 0])->update(['checked' => 1]);

        $trips = $this->trips->with(['customer', 'provider'])
            ->when($status == 'scheduled', function ($query) {
                return $query->scheduled();
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'confirmed', function ($query) {
                return $query->Confirmed();
            })
            ->when($status == 'ongoing', function ($query) {
                return $query->Ongoing();
            })
            ->when($status == 'completed', function ($query) {
                return $query->Completed();
            })
            ->when($status == 'canceled', function ($query) {
                return $query->Canceled();
            })
            ->when($status == 'payment_failed', function ($query) {
                return $query->PaymentFailed();
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('provider', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($request->from_date) && isset($request->to_date) && $request->from_date != null && $request->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhereHas('customer', function ($q) use ($value) {
                                $q->where('f_name', 'like', "%{$value}%")
                                    ->orWhere('l_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%");
                            });
                    }
                });
            })
            ->orderBy('schedule_at', 'desc')->get();

        $data = [
            'data' => $trips,
            'search' => $request['search'] ?? null,
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new TripExport($data), 'Trips.csv');
        }
        return Excel::download(new TripExport($data), 'Trips.xlsx');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCalculation(Request $request): JsonResponse
    {
        $id = $request->id;

        $tripDetail = $this->tripDetails->findOrFail($id);
        $trip = $this->trips->findOrFail($tripDetail->trip_id);

        $distance = $request->distance ?? $trip->distance;
        $modifiedPrices = $request->modified_prices;
        $processedValue = preg_replace('/[^\d.]/', '', $modifiedPrices);
        $processedDistanceValue = preg_replace('/[^\d.]/', '', $distance);
        $vehicleId = $request->vehicle_id;
        $quantity = (int)$request->quantity;
        $estimatedHours = $request->estimated_hours ?? $trip->estimated_hours;

        if ($quantity) {
            $vehicleQuantities = session()->get('vehicleQuantities', []);
            $vehicleQuantities[$vehicleId] = $quantity;
            session()->put('vehicleQuantities', $vehicleQuantities);
            session()->save();
            $vehicleQuantities = session()->get('vehicleQuantities');
        }

        if ($modifiedPrices) {
            $modifiedPrices = session()->get('modifiedPrices', []);

            $modifiedPrices[$vehicleId] = $processedValue;

            session()->put('modifiedPrices', $modifiedPrices);
            session()->save();
            $modifiedPrices = session()->get('modifiedPrices');
        }

        info($vehicleQuantities);
        info($modifiedPrices);
        info($request->modified_prices);


        $data = [
            'distance' => $processedDistanceValue,
            'vehicleQuantities' => $vehicleQuantities ?? [],
            'modifiedPrices' => $modifiedPrices ?? [],
            'taxPercentage' => $trip?->provider?->tax,
            'estimatedHours' => $estimatedHours,
        ];

        $providerTax = $request->vendor ? $request?->vendor?->stores[0]->tax : $trip?->provider?->tax;

        $calculationSingleData = $this->calculateTripDetailPricing(
            $tripDetail,
            $data['vehicleQuantities'],
            $data['modifiedPrices'],
            $data['estimatedHours'],
            $data['distance'],
            $trip->trip_type,
            $providerTax
        );

        $calculationData = $this->getUpdatedTrip($request, $trip, $data, false);

        if ($calculationData) {
            return response()->json([
                'success' => true,
                'originalPrice' => round($calculationSingleData['originalPrice'], 2),
                'calculationSingleData' => round($calculationSingleData['price'], 2),
                'quantity' => $calculationSingleData['quantity'],
                'subTotal' => round($calculationData['subTotal'], 2),
                'grandTotal' => round($calculationData['tripAmount'], 2),
                'discount' => round($calculationData['couponDiscount'], 2),
                'couponDiscount' => round($calculationData['taxAmount'], 2),
                'taxAmount' => round($calculationData['taxAmount'], 2),
            ]);
        }

        return response()->json(['success' => false], 400);
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'trip_id' => 'required',
        ]);

        $trip = $this->trips->findOrFail($request->trip_id);

        if (!$trip) {
            Toastr::error(translate('messages.Trip_not_found'));
            return back();
        }

        if (in_array($trip->trip_status, ['completed', 'canceled'])) {
            Toastr::error(translate('messages.You_can_not_edit_this'));
            return back();
        }

        $pickup = [
            'lat' => $request->pickup_lat,
            'lng' => $request->pickup_lng,
            'location_name' => $request->pickup_location,
        ];

        $destination = [
            'lat' => $request->destination_lat,
            'lng' => $request->destination_lng,
            'location_name' => $request->destination_location,
        ];

        $destinationLocation = $request->destination_location ? json_encode($pickup) :json_encode( $trip->destination_location);
        $pickupLocation = $request->pickup_location  ? json_encode($destination)  : json_encode($trip->pickup_location);
        $scheduleAt = $request->schedule_at ? Carbon::parse($request->schedule_at) : Carbon::parse($trip->schedule_at);

        $estimatedHours = $request->estimated_hours ?? $trip->estimated_hours;
        $distance = $request->distance ?? $trip->distance;
        $scheduled = $request->scheduled ?? $trip->scheduled;

        $estimatedTripEndTime = $scheduleAt->copy()->addHours(
            $trip->rental_type === 'hourly' ? $estimatedHours : ($request->destination_time ?? $trip->destination_time)
        );

        $vehicleQuantities = $request->update_quantity ?? [];
        $modifiedPrices = $request->update_price ?? [];

        foreach ($vehicleQuantities as $vehicle_id => $quantity) {
            if (isset($modifiedPrices[$vehicle_id])) {
                $cleanPrice = (float) str_replace([',', '$'], '', $modifiedPrices[$vehicle_id]);

                $modifiedPrices[$vehicle_id] = $cleanPrice;
            }
        }

        $data = [
            'destinationLocation' => $destinationLocation,
            'pickupLocation' => $pickupLocation,
            'scheduleAt' => $scheduleAt,
            'estimatedHours' => $estimatedHours,
            'distance' => $distance,
            'scheduled' => $scheduled,
            'estimatedTripEndTime' => $estimatedTripEndTime,
            'vehicleQuantities' => $vehicleQuantities,
            'modifiedPrices' => $modifiedPrices,
            'taxPercentage' => $trip?->provider?->tax,
        ];


        $this->getUpdatedTrip($request, $trip, $data);

        Toastr::success(translate('messages.updated successfully'));
        return back();
    }

    public function generateInvoice($id)
    {
        $trip = $this->trips->findOrFail($id);
        return view('rental::admin.trip.invoice', compact('trip'));
    }
    public function printInvoice($id)
    {
        $trip = $this->trips->findOrFail($id);
        return view('rental::admin.trip.invoice-print', compact('trip'))->render();
    }

}
