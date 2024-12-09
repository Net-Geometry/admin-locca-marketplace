<?php

namespace Modules\Rental\Http\Controllers\Web\Admin\Promotions;

use Exception;
use App\Models\User;
use App\Models\CashBack;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Config;
use Modules\Rental\Exports\CashBackExport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;



class CashBackController extends Controller
{

    public function __construct(private CashBack $cashback,private User $user)
    {
        $this->cashback = $cashback;
        $this->user = $user;
    }

    public function list(Request $request)
    {
        abort(404);
        $cashbacks = $this->getListData($request);
        $cashbacks =  $cashbacks->paginate(config('default_pagination'));
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        $users = $this->user->where('status' , 1)->get(['id','f_name','l_name']);
        return view('rental::admin.cashback.list', compact('cashbacks', 'language', 'defaultLang','zones','users'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $this->validateRequest($request);
    //     try {
    //         DB::beginTransaction();
    //         $cashback = $this->createcashback($request);
    //         Helpers::add_or_update_translations(request: $request, key_data: 'title', name_field: 'title', model_name: 'CashBack', data_id: $cashback->id, data_value: $cashback->title);
    //         DB::commit();
    //     } catch (Exception) {
    //         DB::rollBack();
    //         Toastr::error(translate('messages.failed_to_add_cashback'));
    //         return back();
    //     }
    //     Toastr::success(translate('messages.cashback_added_successfully'));
    //     return back();
    // }

    // /**
    //  * @param string $id
    //  * @return View|Factory|Application|RedirectResponse
    //  */
    // public function edit(CashBack $cashback): View|Factory|Application|RedirectResponse
    // {
    //     $cashback->load('translations');
    //     $language = getWebConfig('language') ?? [];
    //     $defaultLang = str_replace('_', '-', app()->getLocale());
    //     $zones = $this->zone->where('status' , 1)->get(['id','name']);
    //     $users = $this->user->where('status' , 1)->get(['id','f_name','l_name']);
    //     return view('rental::admin.cashback.edit', compact('cashback', 'language', 'defaultLang','zones','users'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  * @param Request $request
    //  * @param string $id
    //  * @return RedirectResponse
    //  * @throws AuthorizationException
    //  */
    // public function update(CashBack $cashback, Request $request): RedirectResponse
    // {
    //     $this->validateRequest($request, false, $cashback->id);
    //     try {
    //         DB::beginTransaction();
    //         $this->updatecashback($request, $cashback);
    //         Helpers::add_or_update_translations(request: $request, key_data: 'title', name_field: 'title', model_name: 'CashBack', data_id: $cashback->id, data_value: $cashback->title);
    //         DB::commit();
    //         Toastr::success(translate('messages.cashback_updated_successfully'));
    //         return to_route('admin.rental.cashback.add-new');
    //     } catch (Exception) {
    //         DB::rollBack();
    //         Toastr::error(translate('messages.failed_to_update_cashback'));
    //         return back();
    //     }
    // }

    // /**
    //  * @param Request $request
    //  * @param $id
    //  * @return RedirectResponse
    //  */
    // public function status(CashBack $cashback): RedirectResponse
    // {
    //     $cashback->update(['status' => !$cashback->status]);
    //     Toastr::success(translate('messages.cashback_status_updated_successfully'));
    //     return back();
    // }


    // public function destroy(CashBack $cashback): RedirectResponse
    // {
    //     $cashback?->translations()?->delete();
    //     $cashback?->delete();
    //     Toastr::success(translate('messages.cashback_deleted_successfully'));
    //     return back();
    // }


    // /**
    //  * @param Request $request
    //  * @return BinaryFileResponse
    //  */
    // public function export(Request $request): BinaryFileResponse
    // {
    //     $cashbacks = $this->getListData($request);
    //     $cashbacks =  $cashbacks->get();

    //     $data = [
    //         'data' => $cashbacks,
    //         'search' => $request['search'] ?? null,
    //     ];

    //     if ($request['type'] == 'csv') {
    //         return Excel::download(new CashBackExport($data), 'CashBacks.csv');
    //     }
    //     return Excel::download(new CashBackExport($data), 'CashBacks.xlsx');
    // }

    // /**
    //  * @param Request $request
    //  * @param $id
    //  * @return void
    //  */
    // private function validateRequest(Request $request, $image = true, $id = null): void
    // {
    //     $request->validate([
    //             'code' => 'required|max:100|unique:cashbacks,code' . ($id ? ','.$id : ''),
    //             'title.0' => 'required|max:191',
    //             'start_date' => 'required',
    //             'expire_date' => 'required',
    //             'discount' => 'required',
    //             'cashback_type' => 'required|in:zone_wise,store_wise,first_order,default',
    //             'zone_ids' => 'required_if:cashback_type,zone_wise',
    //             'store_ids' => 'required_if:cashback_type,store_wise',
    //             'title.0' => 'required',
    //         ],
    //         [
    //             'title.0.required'=>translate('default_title_is_required'),
    //         ]);
    // }

    // /**
    //  * @param Request $request
    //  * @return CashBack
    //  */
    // private function createcashback(Request $request): CashBack
    // {
    //     $cashback = $this->cashback;
    //     return  $this->updatecashback($request, $cashback);
    // }
    // private function updatecashback(Request $request, CashBack $cashback): CashBack
    // {
    //     $data  = '';
    //     $customerId  = $request->customer_ids ?? ['all'];
    //     if($request->cashback_type == 'zone_wise')
    //     {
    //         $data = $request->zone_ids;
    //     }
    //     else if($request->cashback_type == 'store_wise')
    //     {
    //         $data = $request->store_ids;
    //     }

    //     $cashback->title = $request->title[array_search('default', $request->lang)];
    //     $cashback->code = $request->code;
    //     $cashback->limit = $request->cashback_type=='first_order'?1:$request->limit;
    //     $cashback->cashback_type = $request->cashback_type;
    //     $cashback->start_date = $request->start_date;
    //     $cashback->expire_date = $request->expire_date;
    //     $cashback->min_purchase = $request?->min_purchase ??  0;
    //     $cashback->max_discount = $request?->max_discount??  0;
    //     $cashback->discount = $request->discount ?? 0;
    //     $cashback->discount_type = $request->discount_type??'';
    //     $cashback->status =  1;
    //     $cashback->created_by =  'admin';
    //     $cashback->data =  json_encode($data);
    //     $cashback->customer_id =  json_encode($customerId);
    //     $cashback->module_id =  Config::get('module.current_module_id');
    //     $cashback->store_id =  is_array($data) && $request->cashback_type == 'store_wise' ? $data[0] : null ;
    //     $cashback->save();
    //     return $cashback;
    // }

    private function getListData($request)
    {
            $key = explode(' ', $request['search']);
            $cashbacks =  $this->cashback
            ->where('module_id', Config::get('module.current_module_id'))
            ->when(isset($key), function($query)use($key){
                $query->where( function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('title', 'like', "%{$value}%");
                    }
                });
            })
            ->latest();
        return $cashbacks;
    }
}
