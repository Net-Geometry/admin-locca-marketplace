<?php

namespace Modules\Rental\Http\Controllers\Api\Public;


use App\Models\Store;
use App\Models\Review;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Rental\Entities\Vehicle;


class ProviderController extends Controller
{



    public function __construct(private Vehicle $vehicle, private Helpers $helpers)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
    }


    public function getProvidereDetails(Store $provider){

        $provider->loadCount([
            'vehicle_identity as total_vehicle_count',
            'vehicles as brand_count' => function ($query) {
                $query->select(DB::raw('COUNT(DISTINCT(brand_id))'));
            },
        ]);
        return response()->json($this->helpers->store_data_formatting($provider), 200);
    }

    public function getProvidereReviews(Store $provider){

        // Review

        // $data=[
        //     'store' => $this->helpers->store_data_formatting($provider),
        //     'store' => $this->helpers->store_data_formatting($provider),
        // ];
        return response()->json($data, 200);
    }


}
