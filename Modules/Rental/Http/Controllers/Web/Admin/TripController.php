<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
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

        if (session()->has('zone_filter') == false) {
            session()->put('zone_filter', 0);
        }

        if (session()->has('order_filter')) {
            $request = json_decode(session('order_filter'));
        }

        $this->trips->where(['checked' => 0])->update(['checked' => 1]);

        $trips = $this->trips->with(['customer', 'provider'])
            ->when($status == 'scheduled', function ($query) {
                return $query->scheduled();
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'accepted', function ($query) {
                return $query->Accepted();
            })
            ->when($status == 'processing', function ($query) {
                return $query->Processing();
            })
//            ->when($status == 'item_on_the_way', function ($query) {
//                return $query->ItemOnTheWay();
//            })
//            ->when($status == 'delivered', function ($query) {
//                return $query->Delivered();
//            })
//            ->when($status == 'canceled', function ($query) {
//                return $query->Canceled();
//            })
//            ->when($status == 'failed', function ($query) {
//                return $query->failed();
//            })
//            ->when($status == 'refunded', function ($query) {
//                return $query->Refunded();
//            })
//            ->when($status == 'requested', function ($query) {
//                return $query->Refund_requested();
//            })
//            ->when($status == 'rejected', function ($query) {
//                return $query->Refund_request_canceled();
//            })
//            ->when($status == 'scheduled', function ($query) {
//                return $query->Scheduled();
//            })
//            ->when($status == 'on_going', function ($query) {
//                return $query->Ongoing();
//            })
//            ->when(($status != 'all' && $status != 'scheduled' && $status != 'canceled' && $status != 'rejected' && $status != 'requested' && $status != 'refunded' && $status != 'delivered' && $status != 'failed'), function ($query) {
//                return $query->OrderScheduledIn(30);
//            })
//            ->when(isset($request->vendor), function ($query) use ($request) {
//                return $query->whereHas('store', function ($query) use ($request) {
//                    return $query->whereIn('id', $request->vendor);
//                });
//            })
//            ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
//                return $query->whereIn('order_status', $request->orderStatus);
//            })
//            ->when(isset($request->order_type), function ($query) use ($request) {
//                return $query->where('order_type', $request->order_type);
//            })
//            ->when(isset($request->from_date) && isset($request->to_date) && $request->from_date != null && $request->to_date != null, function ($query) use ($request) {
//                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
//            })
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
//            ->StoreOrder()
//            ->module(Config::get('module.current_module_id'))
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
     * @return Renderable
     */
    public function create()
    {
        return view('rental::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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
