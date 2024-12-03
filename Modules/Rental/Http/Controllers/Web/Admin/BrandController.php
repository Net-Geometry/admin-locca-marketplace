<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use Exception;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Traits\FileManagerTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Modules\Rental\Entities\VehicleBrand;
use Modules\Rental\Exports\VehicleBrandExport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BrandController extends Controller
{

    private VehicleBrand $brand;
    private Translation $translation;

    use AuthorizesRequests, FileManagerTrait;

    public function __construct(VehicleBrand $brand, Translation $translation)
    {
        $this->brand = $brand;
        $this->translation = $translation;
    }

    public function list(Request $request)
    {
        $brands = $this->brand
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->paginate(config('default_pagination'));
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());

        return view('rental::admin.brand.list', compact('brands', 'language', 'defaultLang'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validateRequest($request);

        try {
            DB::beginTransaction();

            $brand = $this->createBrand($request);

            $this->insertTranslations($request, $brand);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            Toastr::error(translate('messages.failed_to_add_brand'));
            return back();
        }

        Toastr::success(translate('messages.brand_added_successfully'));
        return back();
    }

    /**
     * @param string $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(string $id): View|Factory|Application|RedirectResponse
    {
        $brand = $this->brand->findOrFail($id);
        if (isset($brand)) {
            $language = getWebConfig('language') ?? [];
            $defaultLang = str_replace('_', '-', app()->getLocale());

            return view('rental::admin.brand.edit', compact('brand', 'language', 'defaultLang'));
        }

        Toastr::error(translate('messages.information not found'));
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $this->validateRequest($request, $id);

        $brand = $this->brand->find($id);
        if (!$brand) {
            Toastr::error(translate('messages.information_not_found'));
            return back();
        }

        try {
            DB::beginTransaction();

            $this->updateBrand($request, $brand);
            $this->insertTranslations($request, $brand);

            DB::commit();

            Toastr::success(translate('messages.brand_updated_successfully'));
            return back();
        } catch (Exception) {
            DB::rollBack();

            Toastr::error(translate('messages.failed_to_update_brand'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function status(Request $request, $id): RedirectResponse
    {
        $brand = $this->brand->find($id);

        if (!$brand) {
            Toastr::error(translate('messages.brand_not_found'));
            return back();
        }

        $brand->update(['status' => !$brand->status]);

        Toastr::success(translate('messages.brand_status_updated_successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $brand = $this->brand->find($id);

        if (!$brand) {
            Toastr::error(translate('messages.failed_to_delete_brand'));
            return back();
        }

        if ($brand->image) {
            $this->upload('brand/', $brand->image);
        }

        $brand->translations()->delete();
        $brand->delete();

        Toastr::success(translate('messages.brand_deleted_successfully'));
        return back();
    }


    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $brands = $this->brand
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->get();

        $data = [
            'data' => $brands,
            'search' => $request['search'] ?? null,
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new VehicleBrandExport($data), 'BrandS.csv');
        }
        return Excel::download(new VehicleBrandExport($data), 'BrandS.xlsx');
    }

    /**
     * @param Request $request
     * @param $id
     * @return void
     */
    private function validateRequest(Request $request, $id = null): void
    {
        $request->validate(
            [
                'name' => 'required|unique:categories,name' . ($id ? ','.$id : ''),
                'name.0' => 'required',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240',
            ],
            [
                'name.0.required' => translate('default_name_is_required'),
            ]
        );
    }

    /**
     * @param Request $request
     * @return VehicleBrand
     */
    private function createBrand(Request $request): VehicleBrand
    {
        $brand = $this->brand;
        $brand->name = $request->name[array_search('default', $request->lang)];
        $brand->image = $this->upload('brand/', 'png', $request->file('image'));
        $brand->save();

        return $brand;
    }

    private function updateBrand(Request $request, VehicleBrand $brand): void
    {
        $brand->name = $request->name[array_search('default', $request->lang)];

        if ($request->hasFile('image')) {
            $brand->image = $this->upload('brand/', 'png', $request->file('image'), $brand->image);
        }

        $brand->save();
    }

    /**
     * @param Request $request
     * @param VehicleBrand $brand
     * @return void
     */
    private function insertTranslations(Request $request, VehicleBrand $brand): void
    {
        $defaultLanguage = str_replace('_', '-', app()->getLocale());

        foreach ($request->lang as $index => $locale) {
            $name = $request->name[$index];

            if ($this->shouldInsertTranslation($locale, $defaultLanguage, $name)) {
                $this->translation->updateOrInsert(
                    [
                        'translationable_type' => 'Modules\Rental\Entities\VehicleBrand',
                        'translationable_id' => $brand->id,
                        'locale' => $locale,
                        'key' => 'name',
                    ],
                    [
                        'value' => $locale == 'default' ? $brand->name : ($defaultLanguage == $locale ? $brand->name : $name),
                    ]
                );
            }
        }
    }

    /**
     * @param string $locale
     * @param string $defaultLanguage
     * @param string|null $name
     * @return bool
     */
    private function shouldInsertTranslation(string $locale, string $defaultLanguage, ?string $name): bool
    {
        return ($locale == $defaultLanguage && !$name) || ($locale != 'default' && $name);
    }
}
