<?php

namespace App\Http\Controllers\Admin\Item;

use App\CentralLogics\Helpers;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;
use App\Exports\CategoryExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class CategoryController extends BaseController
{
    public function __construct(
        protected CategoryRepositoryInterface    $categoryRepo,
        protected CategoryService                $categoryService,
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }

    public function index(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        return $this->getCategoryView($request);
    }

    private function getCategoryView(Request $request): View
    {
        $categories = $this->categoryRepo->getListWhere(
            searchValue: $request['search'],
            filters: ['position' => $request['position']],
            relations: ['module'],
            dataLimit: config('default_pagination')
        );
        return view($this->categoryService->getViewByPosition($request['position']), compact('categories'));
    }

    public function add(CategoryUpdateRequest $request): RedirectResponse
    {
        $parentCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['parent_id']]);
        $category = $this->categoryRepo->add(
            data: $this->categoryService->getAddData(
                request: $request,
                parentModuleId: $parentCategory->module_id
            )
        );
        $this->translationRepo->addByModel(request: $request, model: $category, modelPath: 'App\Models\Category');
        Toastr::success(translate('messages.category_added_successfully'));
        return back();
    }

    public function getUpdateView(string|int $id): View
    {
        $category = $this->categoryRepo->getFirstWithoutGlobalscopeWhere(params: ['id' => $id]);
        return view(CategoryViewPath::UPDATE['view'], compact('category'));
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['status' => $request['status']]);
        Toastr::success(translate('messages.category_status_updated'));
        return back();
    }

    public function updateFeatured(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['featured' => $request['featured']]);
        Toastr::success(translate('messages.category_featured_updated'));
        return back();
    }

    public function update(CategoryUpdateRequest $request, string|int $id): RedirectResponse
    {
        $mainCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $id]);
        $category = $this->categoryRepo->update(id: $id, data: $this->categoryService->getUpdateData(request: $request, object: $mainCategory));
        $this->translationRepo->updateByModel(request: $request, model: $category, modelPath: 'App\Models\Category');
        Toastr::success(translate('messages.category_updated_successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if ($category->childes->count() == 0) {
            $category->translations()->delete();
            $category->delete();
            Toastr::success('Category removed!');
        } else {
            Toastr::warning(translate('messages.remove_sub_categories_first'));
        }
        return back();
    }

    public function get_all(Request $request)
    {
        $data = Category::where('name', 'like', '%' . $request->q . '%')
            ->when($request->module_id, function ($query) use ($request) {
                $query->where('module_id', $request->module_id);
            })->limit(8)->get()
            ->map(function ($category) {
                $data = $category->position == 0 ? translate('messages.main') : translate('messages.sub');
                return [
                    'id' => $category->id,
                    'text' => $category->name . ' (' . $data . ')',
                ];
            });


        $data[] = (object)['id' => 'all', 'text' => 'All'];
        return response()->json($data);
    }

    public function update_priority(Category $category, Request $request)
    {
        $priority = $request->priority ?? 0;
        $category->priority = $priority;
        $category->save();
        Toastr::success(translate('messages.category_priority_updated successfully'));
        return back();

    }

    public function bulk_import_index()
    {
        return view('admin-views.category.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        $request->validate([
            'products_file' => 'required|max:2048'
        ]);
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }
        $module_id = Config::get('module.current_module_id');

        if ($request->button == 'import') {
            $data = [];
            foreach ($collections as $collection) {
                if ($collection['Name'] === "") {

                    Toastr::error(translate('messages.please_fill_all_required_fields'));
                    return back();
                }
                $parent_id = is_numeric($collection['ParentId']) ? $collection['ParentId'] : 0;
                array_push($data, [
                    'name' => $collection['Name'],
                    'image' => $collection['Image'],
                    'parent_id' => $parent_id,
                    'module_id' => $module_id,
                    'position' => $collection['Position'],
                    'priority' => is_numeric($collection['Priority']) ? $collection['Priority'] : 0,
                    'status' => $collection['Status'] == 'active' ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            try {
                DB::beginTransaction();

                $chunkSize = 100;
                $chunk_categories = array_chunk($data, $chunkSize);

                foreach ($chunk_categories as $key => $chunk_category) {
                    DB::table('categories')->insert($chunk_category);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                info(["line___{$e->getLine()}", $e->getMessage()]);
                Toastr::error(translate('messages.failed_to_import_data'));
                return back();
            }
            Toastr::success(translate('messages.category_imported_successfully', ['count' => count($data)]));
            return back();
        }

        $data = [];
        foreach ($collections as $collection) {
            if ($collection['Name'] === "") {

                Toastr::error(translate('messages.please_fill_all_required_fields'));
                return back();
            }
            $parent_id = is_numeric($collection['ParentId']) ? $collection['ParentId'] : 0;
            array_push($data, [
                'id' => $collection['Id'],
                'name' => $collection['Name'],
                'image' => $collection['Image'],
                'parent_id' => $parent_id,
                'module_id' => $module_id,
                'position' => $collection['Position'],
                'priority' => is_numeric($collection['Priority']) ? $collection['Priority'] : 0,
                'status' => $collection['Status'] == 'active' ? 1 : 0,
                'updated_at' => now()
            ]);
        }
        try {
            DB::beginTransaction();

            $chunkSize = 100;
            $chunk_categories = array_chunk($data, $chunkSize);

            foreach ($chunk_categories as $key => $chunk_category) {
                DB::table('categories')->upsert($chunk_category, ['id', 'module_id'], ['name', 'image', 'parent_id', 'position', 'priority', 'status']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            info(["line___{$e->getLine()}", $e->getMessage()]);
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }
        Toastr::success(translate('messages.category_imported_successfully', ['count' => count($data)]));
        return back();
    }

    public function bulk_export_index()
    {
        return view('admin-views.category.bulk-export');
    }

    public function bulk_export_data(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'start_id' => 'required_if:type,id_wise',
            'end_id' => 'required_if:type,id_wise',
            'from_date' => 'required_if:type,date_wise',
            'to_date' => 'required_if:type,date_wise'
        ]);
        $categories = Category::when($request['type'] == 'date_wise', function ($query) use ($request) {
            $query->whereBetween('created_at', [$request['from_date'] . ' 00:00:00', $request['to_date'] . ' 23:59:59']);
        })
            ->when($request['type'] == 'id_wise', function ($query) use ($request) {
                $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
            })->module(Config::get('module.current_module_id'))
            ->get();
        return (new FastExcel(Helpers::export_categories(Helpers::Export_generator($categories))))->download('Categories.xlsx');
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $categories=Category::when($request->sub_category, function($query){
    //         return $query->where('position','1');
    //     })->module(Config::get('module.current_module_id'))
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })->limit(50)->get();

    //     if($request->sub_category)
    //     {
    //         return response()->json([
    //             'view'=>view('admin-views.category.partials._sub_category_table',compact('categories'))->render(),
    //             'count'=>$categories->count()
    //         ]);
    //     }
    //     return response()->json([
    //         'view'=>view('admin-views.category.partials._table',compact('categories'))->render(),
    //         'count'=>$categories->count()
    //     ]);
    // }

    public function export_categories(Request $request)
    {
        $key = explode(' ', $request['search']);
        $categories = Category::with('module')->where(['position' => 0])->module(Config::get('module.current_module_id'))
            ->when(isset($key), function ($q) use ($key) {
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()
            ->get();

        $data = [
            'data' => $categories,
            'search' => $request['search'] ?? null,
        ];
        if ($request->type == 'csv') {
            return Excel::download(new CategoryExport($data), 'Categories.csv');
        }
        return Excel::download(new CategoryExport($data), 'Categories.xlsx');


    }

}
