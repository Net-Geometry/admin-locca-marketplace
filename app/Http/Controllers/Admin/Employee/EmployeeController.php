<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Contracts\Repositories\CustomRoleRepositoryInterface;
use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\Employee as EmployeeViewPath;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\EmployeeAddRequest;
use App\Http\Requests\Admin\EmployeeUpdateRequest;
use App\Services\EmployeeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class EmployeeController extends BaseController
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepo,
        protected CustomRoleRepositoryInterface $roleRepo,
        protected EmployeeService $employeeService,
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }

    public function index(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        return $this->getAddView($request);
    }

    private function getAddView(Request $request): View
    {
        $rls = $this->roleRepo->getList();
        return view(EmployeeViewPath::ADD[VIEW], compact('rls'));
    }

    public function add(EmployeeAddRequest $request): RedirectResponse
    {
        $data = $this->employeeService->getAddData(request: $request);

        if (array_key_exists('flag', $data) && $data['flag'] == 'unauthorized') {
            Toastr::warning(translate('messages.access_denied'));
            return back();
        }

        $employee = $this->employeeRepo->add(data: $data);

        $this->translationRepo->addByModel(request: $request, model: $employee, modelPath: 'App\Models\AdminRole', attribute: 'name');

        Toastr::success(translate('messages.employee_added_successfully'));
        return redirect()->route('admin.users.employee.list');
    }

    public function getUpdateView(string|int $id): RedirectResponse|View
    {
        $e = Admin::zone()->where('role_id', '!=','1')->where(['id' => $id])->first();
        $rls = $this->roleRepo->getList();
        if (auth('admin')->id()  != $e['id']){
            return view(EmployeeViewPath::UPDATE[VIEW], compact('rls', 'e'));
        }
        Toastr::warning(translate('messages.access_denied'));
        return back();
    }

    public function update(EmployeeUpdateRequest $request, $id): RedirectResponse|View
    {
        if($id == 1)
        {
            return view('errors.404');
        }

        $employee = $this->employeeRepo->update(id: $id ,data: $this->employeeService->getAddData(request: $request));

        $this->translationRepo->updateByModel(request: $request, model: $employee, modelPath: 'App\Models\AdminRole', attribute: 'name');

        Toastr::success(translate('messages.employee_updated_successfully'));
        return back();
    }

    public function delete($id): RedirectResponse|View
    {
        if($id == 1)
        {
            return view('errors.404');
        }
        $this->employeeRepo->delete(id: $id);
        Toastr::success(translate('messages.employee_deleted_successfully'));
        return back();
    }

    public function search(Request $request): JsonResponse
    {
        $rl=$this->employeeRepo->getSearchList($request);
        return response()->json([
            'view'=>view(EmployeeViewPath::SEARCH[VIEW],compact('rl'))->render(),
            'count'=>$rl->count()
        ]);
    }
}
