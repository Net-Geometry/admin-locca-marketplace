<?php

namespace App\Http\Controllers\Admin\Item;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;
use App\Exports\CategoryExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CategoryBulkExportRequest;
use App\Http\Requests\Admin\CategoryBulkImportRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Services\CategoryService;
use App\Traits\ImportExportTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CategoryController extends BaseController
{
    use ImportExportTrait;

    public function __construct(
        protected CategoryRepositoryInterface    $categoryRepo,
        protected CategoryService                $categoryService,
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }

    /**
     * @param Request|null $request
     * @return View|Collection|LengthAwarePaginator|null
     */
    public function index(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        return $this->getCategoryView($request);
    }

    /**
     * @param Request $request
     * @return View
     */
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

    /**
     * @param CategoryUpdateRequest $request
     * @return RedirectResponse
     */
    public function add(CategoryUpdateRequest $request): RedirectResponse
    {
        $parentCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['parent_id']]);
        $category = $this->categoryRepo->add(
            data: $this->categoryService->getAddData(
                request: $request,
                parentModuleId: $parentCategory['module_id']
            )
        );
        $this->translationRepo->addByModel(request: $request, model: $category, modelPath: 'App\Models\Category');
        Toastr::success(translate('messages.category_added_successfully'));
        return back();
    }

    /**
     * @param string|int $id
     * @return View
     */
    public function getUpdateView(string|int $id): View
    {
        $category = $this->categoryRepo->getFirstWithoutGlobalscopeWhere(params: ['id' => $id]);
        return view(CategoryViewPath::UPDATE['view'], compact('category'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateStatus(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['status' => $request['status']]);
        Toastr::success(translate('messages.category_status_updated'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateFeatured(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['featured' => $request['featured']]);
        Toastr::success(translate('messages.category_featured_updated'));
        return back();
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param string|int $id
     * @return RedirectResponse
     */
    public function update(CategoryUpdateRequest $request, string|int $id): RedirectResponse
    {
        $mainCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $id]);
        $category = $this->categoryRepo->update(id: $id, data: $this->categoryService->getUpdateData(request: $request, object: $mainCategory));
        $this->translationRepo->updateByModel(request: $request, model: $category, modelPath: 'App\Models\Category');
        Toastr::success(translate('messages.category_updated_successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        if ($this->categoryRepo->delete(id: $request['id'])) {
            Toastr::success('Category removed!');
        } else {
            Toastr::warning(translate('messages.remove_sub_categories_first'));
        }
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getListOfNames(Request $request): JsonResponse
    {
        $data = $this->categoryRepo->getListOfNames(request: $request, dataLimit: 8);
        $data[] = (object)['id' => 'all', 'text' => 'All'];
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePriority(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['priority' => $request['priority']]);
        Toastr::success(translate('messages.category_priority_updated successfully'));
        return back();
    }

    /**
     * @return View
     */
    public function getBulkImportView(): View
    {
        return view(CategoryViewPath::BULK_IMPORT['view']);
    }

    /**
     * @param CategoryBulkImportRequest $request
     * @return RedirectResponse
     */
    public function importBulkData(CategoryBulkImportRequest $request): RedirectResponse
    {
        $data = $this->categoryService->getImportData(request: $request);

        if (array_key_exists('flag', $data) && $data['flag'] == 'wrong_format') {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        if (array_key_exists('flag', $data) && $data['flag'] == 'required_fields') {
            Toastr::error(translate('messages.please_fill_all_required_fields'));
            return back();
        }

        try {
            DB::beginTransaction();
            $this->categoryRepo->addByChunk(data: $data);
            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }

        Toastr::success(translate('messages.category_imported_successfully', ['count' => count($data)]));
        return back();
    }

    /**
     * @param CategoryBulkImportRequest $request
     * @return RedirectResponse
     */
    public function updateBulkData(CategoryBulkImportRequest $request): RedirectResponse
    {
        $data = $this->categoryService->getImportData(request: $request, toAdd: false);

        if (array_key_exists('flag', $data) && $data['flag'] == 'wrong_format') {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        if (array_key_exists('flag', $data) && $data['flag'] == 'required_fields') {
            Toastr::error(translate('messages.please_fill_all_required_fields'));
            return back();
        }

        try {
            DB::beginTransaction();
            $this->categoryRepo->updateByChunk(data: $data);
            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }

        Toastr::success(translate('messages.category_imported_successfully', ['count' => count($data)]));
        return back();
    }

    /**
     * @return View
     */
    public function getBulkExportView(): View
    {
        return view(CategoryViewPath::BULK_EXPORT['view']);
    }

    /**
     * @throws WriterNotOpenedException
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function exportBulkData(CategoryBulkExportRequest $request): StreamedResponse|string
    {
        $categories = $this->categoryRepo->getBulkExportList(request: $request);
        return (new FastExcel($this->categoryService->processExportData(collection: $this->exportGenerator(data: $categories))))->download('Categories.xlsx');
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function exportData(Request $request): BinaryFileResponse
    {
        $categories = $this->categoryRepo->getExportList(request: $request);

        $data = [
            'data' => $categories,
            'search' => $request['search'] ?? null,
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new CategoryExport($data), 'Categories.csv');
        }
        return Excel::download(new CategoryExport($data), 'Categories.xlsx');
    }
}
