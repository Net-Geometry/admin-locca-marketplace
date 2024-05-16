<?php

namespace App\Http\Controllers\Admin\Subscription;

use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Models\BusinessSetting;
use App\Models\SubscriptionTransaction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Carbon;


class SubscriptionController extends Controller
{

    public function __construct(
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }
    public function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $packages=  SubscriptionPackage::withcount('currentSubscribers')
        ->when(isset($key), function($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('package_name', 'like', "%{$value}%")
                        ->orWhere('price', 'like', "%{$value}%")
                        ->orWhere('validity', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.subscription.package.index',compact('packages'));
    }
    public function create()
    {
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('admin-views.subscription.package.create', compact('language','defaultLang'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'max:191|unique:subscription_packages',
            'package_name.0' => 'required',

            'package_price' => 'required|numeric|between:0,999999999999.999',
            'package_validity' => 'required|integer|between:0,999999999',
            'max_order' => 'nullable|integer|between:0,999999999',
            'max_product' => 'nullable|integer|between:0,999999999',
            'pos_system' => 'nullable|boolean',
            'mobile_app' => 'nullable|boolean',
            'self_delivery' => 'nullable|boolean',
            'chat' => 'nullable|boolean',
            'review' => 'nullable|boolean',
            'text' => 'nullable|max:1000',
        ], [
            'price.required' => translate('Must enter Price for the Package'),
            'validity.required' => translate('Must enter a validity period for the Package in days'),
            'package_name.0.required'=>translate('default_package_name_is_required'),
        ]);

        $package = new SubscriptionPackage;
        $package->package_name = $request->package_name[array_search('default', $request->lang)];
        $package->text = $request->text[array_search('default', $request->lang)];
        $package->price = $request->package_price;
        $package->validity = $request->package_validity;
        $package->max_order = $request->max_order  ?? 'unlimited';
        $package->max_product = $request->max_product ?? 'unlimited';
        $package->pos = $request->pos_system ?? 0;
        $package->mobile_app = $request->mobile_app ?? 0;
        $package->self_delivery = $request->self_delivery ?? 0;
        $package->chat = $request->chat ?? 0;
        $package->review = $request->review ?? 0;
        $package->colour = $request?->colour;
        $package->save();

        $this->translationRepo->addByModel(request: $request, model: $package, modelPath: 'App\Models\SubscriptionPackage', attribute: 'package_name');
        $this->translationRepo->addByModel(request: $request, model: $package, modelPath: 'App\Models\SubscriptionPackage', attribute: 'text');
        Toastr::success(translate('messages.Package_successfully_Added'));
        return redirect()->route('admin.business-settings.subscriptionackage.index');
    }

    public function statusChange(SubscriptionPackage $subscriptionackage){

        $subscriptionackage->status =!$subscriptionackage->status;
        $subscriptionackage->save();
        Toastr::success($subscriptionackage->status == 1 ? translate('messages.Package_Acitvated_successfully') : translate('Package_Deacitvated_successfully'));
        return back();
    }

    public function show(SubscriptionPackage $subscriptionackage)
    {
        $over_view_data= $this->packageOverview($subscriptionackage);
        return view('admin-views.subscription.package.package-details', compact('subscriptionackage','over_view_data'));
    }
    public function edit(SubscriptionPackage $subscriptionackage)
    {
        $subscriptionackage->load('translations')->withoutGlobalScope('translate');
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('admin-views.subscription.package.edit', compact('language','defaultLang','subscriptionackage'));
    }

    public function update(SubscriptionPackage $subscriptionackage, Request $request)
    {
        $request->validate([
            'package_name' => 'max:191|unique:subscription_packages,package_name,'.$subscriptionackage->id,
            'package_name.0' => 'required',

            'package_price' => 'required|numeric|between:0,999999999999.999',
            'package_validity' => 'required|integer|between:0,999999999',
            'max_order' => 'nullable|integer|between:0,999999999',
            'max_product' => 'nullable|integer|between:0,999999999',
            'pos_system' => 'nullable|boolean',
            'mobile_app' => 'nullable|boolean',
            'self_delivery' => 'nullable|boolean',
            'chat' => 'nullable|boolean',
            'review' => 'nullable|boolean',
            'text' => 'nullable|max:1000',
        ], [
            'price.required' => translate('Must enter Price for the Package'),
            'validity.required' => translate('Must enter a validity period for the Package in days'),
            'package_name.0.required'=>translate('default_package_name_is_required'),
        ]);
        $subscriptionackage->package_name = $request->package_name[array_search('default', $request->lang)];
        $subscriptionackage->text = $request->text[array_search('default', $request->lang)];
        $subscriptionackage->price = $request->package_price;
        $subscriptionackage->validity = $request->package_validity;
        $subscriptionackage->max_order = $request->max_order  ?? 'unlimited';
        $subscriptionackage->max_product = $request->max_product ?? 'unlimited';
        $subscriptionackage->pos = $request->pos_system ?? 0;
        $subscriptionackage->mobile_app = $request->mobile_app ?? 0;
        $subscriptionackage->self_delivery = $request->self_delivery ?? 0;
        $subscriptionackage->chat = $request->chat ?? 0;
        $subscriptionackage->review = $request->review ?? 0;
        $subscriptionackage->colour = $request?->colour;
        $subscriptionackage->save();
        $this->translationRepo->updateByModel(request: $request, model: $subscriptionackage, modelPath: 'App\Models\SubscriptionPackage', attribute: 'package_name');
        $this->translationRepo->updateByModel(request: $request, model: $subscriptionackage, modelPath: 'App\Models\SubscriptionPackage', attribute: 'text');
        Toastr::success(translate('messages.Package_Updated_successfully'));
        return redirect()->route('admin.business-settings.subscriptionackage.show',$subscriptionackage->id);
    }
    public function overView(SubscriptionPackage $subscriptionackage, Request $request)
    {
        $over_view_data= $this->packageOverview($subscriptionackage,$request?->type);
            return response()->json([
            'view'=>view('admin-views.subscription.package.partial._over-view-data',compact('over_view_data'))->render(),

            ]);
    }

    private function packageOverview($subscriptionackage,$type ='all'){
        $data=[];
        $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;

        $totalSubscribersData = $subscriptionackage->subscribers()
        ->when($type == 'this_month' ,function($query){
            $query->whereMonth('renewed_at', Carbon::now()->month );
        })
        ->when($type == 'this_year' ,function($query){
            $query->whereYear('renewed_at', Carbon::now()->year );
        })
        ->when($type == 'this_week' ,function($query){
            $query->whereBetween('renewed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()] );
        })
        ->selectRaw('COUNT(DISTINCT store_id) AS total_subscribers,
                    COUNT(DISTINCT CASE WHEN status = 1 THEN store_id END) AS active_subscriptions,
                    COUNT(DISTINCT CASE WHEN status = 0 THEN store_id END) AS expired_subscriptions,
                    COUNT(DISTINCT CASE WHEN status = 1 AND expiry_date <= ? THEN store_id END) AS expired_soon',
                    [Carbon::today()->addDays($subscription_deadline_warning_days)])
        ->first();

        $data['total_subscribed_user']= $totalSubscribersData['total_subscribers'];
        $data['active_subscription']= $totalSubscribersData['active_subscriptions'];
        $data['expired_subscription']= $totalSubscribersData['expired_subscriptions'];
        $data['expired_soon']= $totalSubscribersData['expired_soon'];

        $totals = $subscriptionackage->transactions()
        ->when($type == 'this_month' ,function($query){
            $query->whereMonth('created_at', Carbon::now()->month );
        })
        ->when($type == 'this_year' ,function($query){
            $query->whereYear('created_at', Carbon::now()->year );
        })
        ->when($type == 'this_week' ,function($query){
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()] );
        })
        ->selectRaw('COUNT(DISTINCT CASE WHEN is_trial = 1 THEN store_id END) AS total_free_trials,
                    COUNT(DISTINCT CASE WHEN is_trial = 0 THEN store_id END) AS total_renewed,
                    SUM(CASE WHEN is_trial = 0 THEN paid_amount ELSE 0 END) AS total_amount')
        ->first();

        $data['total_free_trials']= $totals['total_free_trials'];
        $data['total_renewed']= $totals['total_renewed'];
        $data['total_amount']= $totals['total_amount'];

        return $data;

    }


    public function transaction($id, Request $request){

        $filter= $request['filter'];
        $plan_type= $request['plan_type'];
        $from =$request['start_date'] ?? Carbon::now()->format('Y-m-d');
        $to =$request['expire_date'] ?? Carbon::now()->format('Y-m-d');

        $key = explode(' ', $request['search']);
        $transactions= SubscriptionTransaction::where('package_id',$id)
        ->when(isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
                $q->orWhereHas('store' , function ($q) use ($key) {
                    foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
                });
            });
        })
        ->when($filter == 'this_year' , function($query){
            $query->whereYear('created_at', Carbon::now()->year );
        })
        ->when($filter == 'this_month' , function($query){
            $query->whereMonth('created_at', Carbon::now()->month );
        })
        ->when($filter == 'this_week' , function($query){
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()] );
        })
        ->when($filter == 'custom' , function($query) use($from,$to) {
            $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })

        ->when( in_array( $plan_type,['renew','new_plan','first_purchased','free_trial'])  , function($query) use($plan_type){
            $query->where('plan_type', $plan_type );
        })

        ->latest()->paginate(config('default_pagination'));
            $subscription_deadline_warning_days = BusinessSetting::where('key','subscription_deadline_warning_days')->first()?->value ?? 7;
        return view('admin-views.subscription.package.transaction', compact('transactions','id','filter','subscription_deadline_warning_days'));

    }
    public function settings(){

        $key=['subscription_deadline_warning_days','subscription_deadline_warning_message','subscription_free_trial_days','subscription_free_trial_type','subscription_free_trial_status'];
        $settings=BusinessSetting::whereIn('key', $key)->pluck('value','key');
        return view('admin-views.subscription.settings.setting', compact('settings'));

    }
    public function trialStatus(){
        $status = BusinessSetting::firstOrNew([
            'key' => 'subscription_free_trial_status'
        ]);
        $status->value =  $status->value != 1 ?  1 : 0;
        $status->save();
        Toastr::success($status->value == 1 ? translate('messages.Free_Trial_Activated_Successfully') : translate('messages.Free_Trial_Disabled_Successfully'));
        return back();
    }
    public function settingUpdate(Request $request){

        $key=['subscription_deadline_warning_days','subscription_deadline_warning_message','subscription_free_trial_days','subscription_free_trial_type','subscription_free_trial_status'];
            foreach ($request->all() as $k => $value) {

                if(in_array($k, $key) ){
                    $status = BusinessSetting::firstOrNew([
                        'key' => $k
                    ]);
                    if( $k == 'subscription_free_trial_days'){
                        if($request->subscription_free_trial_type == 'year'){
                            $value = $value * 365;
                        } else if($request->subscription_free_trial_type == 'month'){
                            $value = $value * 30;
                        } else{
                            $value = $value;
                        }
                    }

                    $status->value =  $value;
                    $status->save();
                }
            }

        Toastr::success( translate('messages.Settings_Saved_Successfully'));
        return back();
    }
    public function invoice($id){
        return view('admin-views.subscription.subscription-invoice');
    }

}
