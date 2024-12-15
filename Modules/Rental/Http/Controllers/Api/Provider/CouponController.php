<?php

namespace Modules\Rental\Http\Controllers\Api\Provider;

use App\CentralLogics\Helpers;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    private Coupon $coupon;
    private Zone $zone;
    private User $user;
    private Helpers $helpers;

    public function __construct(Coupon $coupon, Zone $zone, User $user, Helpers $helpers)
    {
        $this->coupon = $coupon;
        $this->zone = $zone;
        $this->user = $user;
        $this->helpers = $helpers;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->helpers->error_processor($validator)], 403);
        }

        $limit = $request['limit']??25;
        $offset = $request['offset']??1;
        $store_id = $request->vendor->stores[0]->id;
        $key = explode(' ', $request['search']);

        $coupons =  $this->coupon->where('created_by','vendor')
            ->where('store_id',$store_id)
            ->when(isset($key), function($query)use($key){
                $query->where( function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('code', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate($limit, ['*'], 'page', $offset);

        $data = $this->helpers->preparePaginatedResponse(pagination:$coupons, limit:$limit, offset:$offset, key:'coupons', extraData:[]);


        return response()->json($data,200);
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
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

//        $coupon->created_by = 'vendor';
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $driver = $this->coupon->find($id);

        if ($driver) {
            $driver->translations()->delete();
            $driver->delete();

            return response()->json(['message' => translate('messages.driver_deleted_successfully.')], 200);
        }

        return response()->json(['message' => translate('messages.failed_to_delete_driver.')], 400);
    }
}
