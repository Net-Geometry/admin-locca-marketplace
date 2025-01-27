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



    public function __construct(private Store $provider, private VehicleReview $review, private Helpers $helpers)
    {
        $this->provider = $provider;
        $this->helpers = $helpers;
        $this->review = $review;
    }


    public function getProvidereDetails($id)
    {
        if (!$id) {
            return response()->json(['errors' => 'Id_or_Slug_is_required'], 404);
        }
        $provider =  $this->provider->where(function ($query) use ($id) {
            $query->where('id', $id)->orWhere('slug', $id);
        })
            ->withCount([
                'vehicle_identity as total_vehicle_count',
                'vehicles as brand_count' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT(brand_id))'));
                },
            ])->first();
        if (!$provider) {
            return response()->json(['error' => 'provider_not_found'], 404);
        }
        return response()->json($this->helpers->store_data_formatting($provider), 200);
    }

    public function getProvidereReviews($id, Request $request)
    {
        if (!$id) {
            return response()->json(['errors' => 'Id_or_Slug_is_required'], 404);
        }
        $limit = $request['limit'] ?? 25;
        $offset = $request['offset'] ?? 1;
        $key = explode(' ', $request['search']);

        $provider =  $this->provider->where(function ($query) use ($id) {
            $query->where('id', $id)->orWhere('slug', $id);
        })->first();
        if (!$provider) {
            return response()->json(['error' => 'provider_not_found'], 404);
        }

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
                $item['vehicle_image'] = $item->vehicle->thumbnail;
                $item['vehicle_image_full_url'] = $item->vehicle->thumbnail_full_url;
            }

            if ($item->customer) {
                $item['customer_name'] = $item->customer->f_name . ' ' . $item->customer->l_name;
            }

            unset($item['vehicle']);
            unset($item['customer']);
            array_push($storage, $item);
        }

        $ratings = StoreLogic::calculate_store_rating($provider['rating']);
        $provider = [
            'name' => $provider->name,
            'ratings' => $provider->rating,
            'avg_rating' => $ratings['rating'],
            'rating_count' => $ratings['total'],
        ];


        $data = [
            'total_size' => (int) $reviews->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'provider' => $provider,
            'reviews' => $storage,
        ];


        return response()->json($data, 200);
    }
}
