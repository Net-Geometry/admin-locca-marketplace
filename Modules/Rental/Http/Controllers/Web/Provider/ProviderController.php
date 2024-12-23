<?php

namespace Modules\Rental\Http\Controllers\Web\Provider;

use App\Models\Translation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Rental\Entities\VehicleBrand;
use Modules\Rental\Entities\VehicleCategory;
use Modules\Rental\Exports\VehicleBrandExport;
use Modules\Rental\Exports\VehicleCategoryExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProviderController extends Controller
{
    private VehicleCategory $category;
    private VehicleBrand $brand;

    public function __construct(VehicleCategory $category, VehicleBrand $brand)
    {
        $this->category = $category;
        $this->brand = $brand;
    }

    /**
     * @param Request $request
     * @return View|Application|Factory
     */
    public function categoryList(Request $request): View|Application|Factory
    {
        $categories = $this->category
            ->when($request->filled('search'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('search'));
                $query->where(function ($subQuery) use ($keys) {
                    foreach ($keys as $key) {
                        $subQuery->where('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->ofStatus(1)
            ->latest()
            ->paginate(config('default_pagination'));

        return view('rental::provider.category.list', compact('categories'));
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function categoryExport(Request $request): BinaryFileResponse
    {
        $categories = $this->category
            ->when($request->filled('search'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('search'));
                $query->where(function ($subQuery) use ($keys) {
                    foreach ($keys as $key) {
                        $subQuery->where('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->ofStatus(1)
            ->latest()->get();

        $data = [
            'data' => $categories,
            'search' => $request['search'] ?? null,
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new VehicleCategoryExport($data), 'Categories.csv');
        }
        return Excel::download(new VehicleCategoryExport($data), 'Categories.xlsx');
    }

    /**
     * @param Request $request
     * @return View|Application|Factory
     */
    public function brandList(Request $request): View|Application|Factory
    {
        $brands = $this->brand
            ->when($request->filled('search'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('search'));
                $query->where(function ($subQuery) use ($keys) {
                    foreach ($keys as $key) {
                        $subQuery->where('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->ofStatus(1)
            ->latest()
            ->paginate(config('default_pagination'));

        return view('rental::provider.brand.list', compact('brands'));
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function brandExport(Request $request): BinaryFileResponse
    {
        $brands = $this->brand
            ->when($request->filled('search'), function ($query) use ($request) {
                $keys = explode(' ', $request->input('search'));
                $query->where(function ($subQuery) use ($keys) {
                    foreach ($keys as $key) {
                        $subQuery->where('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->ofStatus(1)
            ->latest()->get();

        $data = [
            'data' => $brands,
            'search' => $request['search'] ?? null,
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new VehicleBrandExport($data), 'Brands.csv');
        }
        return Excel::download(new VehicleBrandExport($data), 'Brands.xlsx');
    }
}
