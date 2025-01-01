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
use Modules\Rental\Entities\VehicleReview;


class ProviderController extends Controller
{



    public function __construct(private Vehicle $vehicle, private VehicleReview $review, private Helpers $helpers)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
        $this->review = $review;
    }


    public function getProvidereDetails(Store $provider)
    {

        $provider->loadCount([
            'vehicle_identity as total_vehicle_count',
            'vehicles as brand_count' => function ($query) {
                $query->select(DB::raw('COUNT(DISTINCT(brand_id))'));
            },
        ]);
        return response()->json($this->helpers->store_data_formatting($provider), 200);
    }

    public function getProvidereReviews(Store $provider, Request $request)
    {
        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;
        $key = explode(' ', $request['search']);
        $provider->select(['id','name']);

        $reviews = $this->review->with(['customer', 'vehicle'])->where('provider_id', $provider->id)
            ->when(isset($key), function ($query) use ($key, $request) {
                $query->where(function ($query) use ($key, $request) {
                    $query->whereHas('vehicle', function ($query) use ($key) {
                        foreach ($key as $value) {
                            $query->where('name', 'like', "%{$value}%");
                        }
                    })->orWhereHas('customer', function ($query) use ($key) {
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
            if ($item->vehicle) {
                $item['vehicle_name'] = $item->vehicle->name;
                $item['vehicle_image'] = $item->vehicle->image;
                $item['vehicle_image_full_url'] = $item->vehicle->image_full_url;
            }

            if ($item->customer) {
                $item['customer_name'] = $item->customer->f_name . ' ' . $item->customer->l_name;
            }

            unset($item['vehicle']);
            unset($item['customer']);
            array_push($storage, $item);
        }

        $data = [
            'total_size' => (int) $reviews->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'provider'=> $provider,
            'reviews' => $storage,
        ];


        return response()->json($data, 200);
    }
}
