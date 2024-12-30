<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rental\Entities\TripDetails;
use Modules\Rental\Entities\Trips;
use Modules\Rental\Entities\TripVehicleDetails;

class TripController extends Controller
{
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
        $status = explode(' ', $request['status']);

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

        $orderstatus = isset($request->orderStatus) ? $request->orderStatus : [];
        $scheduled = isset($request->scheduled) ? $request->scheduled : 0;
        $vendor_ids = isset($request->vendor) ? $request->vendor : [];
        $zone_ids = isset($request->zone) ? $request->zone : [];
        $from_date = isset($request->from_date) ? $request->from_date : null;
        $to_date = isset($request->to_date) ? $request->to_date : null;
        $order_type = isset($request->order_type) ? $request->order_type : null;
        $total = $trips->total();


        return view('rental::admin.trip.list', compact('trips', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total', 'order_type'));

    }

    /**
     * Show the form for creating a new resource.
     * @param $id
     * @return Renderable
     */
    public function details($id): Renderable
    {
        $trip = $this->trips->findOrFail($id);
        return view('rental::admin.trip.details', compact('trip'));
    }

    /**
     * @param $id
     * @param $status
     * @return RedirectResponse
     */
    public function status($id, $status): RedirectResponse
    {
        $trip = $this->trips->findOrFail($id);
        if (!$trip){
            Toastr::success(translate('messages.trip_not_found'));
            return back();
        }

        $trip->trip_status = $status;
        $trip->save();

        Toastr::success(translate('messages.trip_status_updated_successfully'));
        return back();
    }

    /**
     * @param $id
     * @param $status
     * @return RedirectResponse
     */
    public function paymentStatus($id, $status): RedirectResponse
    {
        $trip = $this->trips->findOrFail($id);
        if (!$trip){
            Toastr::success(translate('messages.trip_not_found'));
            return back();
        }

        $trip->payment_status = $status;
        $trip->save();

        Toastr::success(translate('messages.trip_payment_status_updated_successfully'));
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
