<?php

namespace Modules\Rental\Http\Controllers\Api\Provider;

use App\CentralLogics\Helpers;
use App\Models\Banner;
use App\Models\Store;
use App\Traits\FileManagerTrait;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    use FileManagerTrait;

    private Banner $banner;
    private Store $store;
    private Config $config;
    private Helpers $helpers;

    public function __construct(Banner $banner, Config $config, Helpers $helpers, Store $store)
    {
        $this->banner = $banner;
        $this->store = $store;
        $this->config = $config;
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

        $limit = $request['limit'];
        $offset = $request['offset'];
        $providerId = $request->vendor->stores[0]->id;
        $moduleId = $request->vendor->stores[0]->module_id;
        $banners =  $this->banner->where('data', $providerId)->where('created_by', 'store')
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('title', 'LIKE', '%' . $key . '%');
                }
            })
            ->where('module_id', $moduleId)
            ->latest()->paginate($limit, ['*'], 'page', $offset);

        $data = $this->helpers->preparePaginatedResponse(pagination:$banners, limit:$limit, offset:$offset, key:'banners', extraData:[]);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validateRequest($request);
        try {
            DB::beginTransaction();

            $banner = $this->createBanner($request);
            $this->helpers->add_or_update_translations(request: $request, key_data: 'title', name_field: 'title', model_name: 'Banner', data_id: $banner->id, data_value: $banner->title);

            DB::commit();

            return response()->json(['message' => translate('messages.banner_created_successfully.')], 200);

        } catch (Exception) {
            DB::rollBack();
            return response()->json(['message' => translate('messages.failed_to_create_banner.')], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $this->validateRequest($request, false, $id);

        try {
            DB::beginTransaction();

            $banner = $this->updateBanner($request, $id);
            $this->helpers->add_or_update_translations(request: $request, key_data: 'title', name_field: 'title', model_name: 'Banner', data_id: $banner->id, data_value: $banner->title);

            DB::commit();
            return response()->json(['message' => translate('messages.banner_updated_successfully.')], 200);

        } catch (Exception) {
            DB::rollBack();
            return response()->json(['message' => translate('messages.failed_to_update_banner.')], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $banner = $this->banner->find($id);

        if ($banner) {
            if ($banner->image) {
                $this->helpers->check_and_delete('banner/', $banner->image);
            }

            $banner->translations()->delete();
            $banner->delete();

            return response()->json(['message' => translate('messages.banner_deleted_successfully.')], 200);
        }

        return response()->json(['message' => translate('messages.failed_to_delete_banner.')], 400);
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status(Request $request, $id): JsonResponse
    {
        $banner = $this->banner->find($id);

        if ($banner) {
            $banner->update(['status' => !$banner->status]);
            return response()->json(['message' => translate('messages.banner_status_updated.')], 200);
        }

        return response()->json(['message' => translate('messages.banner_not_found.')], 400);
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function featured(Request $request, $id): JsonResponse
    {
        $banner = $this->banner->find($id);

        if ($banner) {
            $banner->update(['featured' => !$banner->featured]);
            return response()->json(['message' => translate('messages.banner_featured_updated.')], 200);
        }

        return response()->json(['message' => translate('messages.banner_not_found.')], 400);
    }

    /**
     * @param Request $request
     * @param bool $image
     * @param null $id
     * @return void
     */
    private function validateRequest(Request $request, bool $image = true, $id = null): void
    {
        $request->validate(
            [
                'title' => 'required|max:191',
                'image' => $image ? 'required' : 'nullable',
            ]
        );
    }

    /**
     * @param Request $request
     * @return Banner
     */
    private function createBanner(Request $request): Banner
    {
        $storeId = $request->vendor->stores[0]->id ?? 0;
        $zoneId = $request->vendor->stores[0]->zone_id ?? 0;
        $moduleId = $request->vendor->stores[0]->module_id ?? null;
        $defaultLangKey = array_search('default', $request->lang ?? []);

        $banner = $this->banner;
        $banner->title = $request->title[$defaultLangKey] ?? 'Default Title';
        $banner->zone_id = $zoneId;
        $banner->data = $storeId;
        $banner->image = $this->upload('banner/', 'png', $request->file('image'));
        $banner->module_id = $moduleId;
        $banner->type = 'store_wise';
        $banner->default_link = $request->default_link;
        $banner->created_by = 'store';
        $banner->save();

        return $banner;
    }

    private function updateBanner(Request $request, $id): Banner
    {
        $storeId = $request->vendor->stores[0]->id ?? 0;
        $zoneId = $request->vendor->stores[0]->zone_id ?? 0;
        $moduleId = $request->vendor->stores[0]->module_id ?? null;
        $defaultLangKey = array_search('default', $request->lang ?? []);

        $banner = $this->banner->findOrFail($id);

        if ($request->hasFile('image')) {
            $banner->image = $this->updateAndUpload('banner/', $banner->image ,'png', $request->file('image'));
        }

        $banner->title = $request->title[$defaultLangKey] ?? 'Default Title';
        $banner->type = 'store_wise';
        $banner->zone_id = $zoneId;
        $banner->data = $storeId;
        $banner->module_id = $moduleId;
        $banner->default_link = $request->default_link;
        $banner->save();

        return $banner;
    }
}
