<?php

namespace Modules\Rental\Http\Controllers\Api\Public;


use App\Models\Store;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;
use App\Models\Review;
use Illuminate\Routing\Controller;
use Modules\Rental\Entities\Vehicle;


class ProviderController extends Controller
{



    public function __construct(private Vehicle $vehicle, private Helpers $helpers)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
    }


    public function getProvidereDetails(Store $provider){
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
