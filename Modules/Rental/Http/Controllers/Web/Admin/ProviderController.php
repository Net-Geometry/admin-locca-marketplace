<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;
use App\Mail\StoreRegistration;
use App\Mail\VendorSelfRegistration;
use App\Models\Admin;
use App\Models\BusinessSetting;
use App\Models\Conversation;
use App\Models\DisbursementDetails;
use App\Models\Item;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreWallet;
use App\Models\SubscriptionPackage;
use App\Models\TempProduct;
use App\Models\UserInfo;
use App\Models\Vendor;
use App\Models\Zone;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Modules\Rental\Entities\Vehicle;
use Modules\Rental\Entities\VehicleDriver;

class ProviderController extends Controller
{
    private BusinessSetting $businessSetting;
    private Zone $zone;
    private Vendor $vendor;
    private Store $store;
    private VehicleDriver $vehicleDriver;
    private Vehicle $vehicle;
    private Admin $admin;
    private StoreLogic $storeLogic;
    private SubscriptionPackage $subscriptionPackage;
    private Helpers $helpers;
    private Order $order;
    private StoreWallet $storeWallet;
    private TempProduct $tempProduct;
    private Item $item;
    private UserInfo $userInfo;
    private Conversation $conversation;
    private DisbursementDetails $disbursementDetails;

    use FileManagerTrait;

    /**
     * @param BusinessSetting $businessSetting
     * @param StoreWallet $storeWallet
     * @param Item $item
     * @param DisbursementDetails $disbursementDetails
     * @param Conversation $conversation
     * @param UserInfo $userInfo
     * @param TempProduct $tempProduct
     * @param Zone $zone
     * @param Order $order
     * @param Vendor $vendor
     * @param Store $store
     * @param Admin $admin
     * @param StoreLogic $storeLogic
     * @param SubscriptionPackage $subscriptionPackage
     * @param Helpers $helpers
     * @param VehicleDriver $vehicleDriver
     * @param Vehicle $vehicle
     */
    public function __construct(BusinessSetting $businessSetting, StoreWallet $storeWallet, Item $item, DisbursementDetails $disbursementDetails, Conversation $conversation, UserInfo $userInfo, TempProduct $tempProduct, Zone $zone, Order $order, Vendor $vendor, Store $store, Admin $admin, StoreLogic $storeLogic, SubscriptionPackage $subscriptionPackage, Helpers $helpers, VehicleDriver $vehicleDriver, Vehicle $vehicle)
    {
        $this->businessSetting = $businessSetting;
        $this->zone = $zone;
        $this->vendor = $vendor;
        $this->store = $store;
        $this->admin = $admin;
        $this->storeLogic = $storeLogic;
        $this->subscriptionPackage = $subscriptionPackage;
        $this->helpers = $helpers;
        $this->order = $order;
        $this->storeWallet = $storeWallet;
        $this->tempProduct = $tempProduct;
        $this->item = $item;
        $this->userInfo = $userInfo;
        $this->conversation = $conversation;
        $this->disbursementDetails = $disbursementDetails;
        $this->vehicleDriver = $vehicleDriver;
        $this->vehicle = $vehicle;
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    public function list(Request $request): Renderable
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', 'all');
        $type = $request->query('type', 'all');
        $module_id = $request->query('module_id', 'all');

        $stores = $this->store->with('vendor','module')->whereHas('vendor', function($query){
            return $query->where('status', 1);
        })
            ->when(is_numeric($zone_id), function($query)use($zone_id){
                return $query->where('zone_id', $zone_id);
            })
            ->when(is_numeric($module_id), function($query)use($request){
                return $query->module($request->query('module_id'));
            })
            ->when(isset($key), function($query)use($key,$request){
                return $query->where(function($query)use($key){
                    $query->orWhereHas('vendor',function ($q) use ($key) {
                        $q->where(function($q)use($key){
                            foreach ($key as $value) {
                                $q->orWhere('f_name', 'like', "%{$value}%")
                                    ->orWhere('l_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%")
                                    ->orWhere('phone', 'like', "%{$value}%");
                            }
                        });
                    })->orWhere(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%");
                        }
                    });
                })->orderByRaw("FIELD(name, ?) DESC", [$request->search]);
            })
            ->module(Config::get('module.current_module_id'))
            ->with('vendor','module')->type($type)
            ->latest()->paginate(config('default_pagination'));

        $zone = is_numeric($zone_id) ? $this->zone->findOrFail($zone_id) : null;

        return view('rental::admin.provider.list', compact('stores', 'zone','type'));
    }

    /**
     * @param Request $request
     * @param $store_id
     * @param $tab
     * @param $sub_tab
     * @return Factory|\Illuminate\Foundation\Application|View|Application
     */
    public function details(Request $request, $store_id, $tab=null, $sub_tab='cash'): Factory|\Illuminate\Foundation\Application|View|Application
    {
        $filter= $request?->filter;
        $key = explode(' ', request()->search);

        $store = $this->store->findOrFail($store_id);
        $wallet = $store->vendor->wallet;

        if(!$wallet)
        {
            $wallet = $this->storeWallet;
            $wallet->vendor_id = $store->vendor->id;
            $wallet->total_earning = 0.0;
            $wallet->total_withdrawn = 0.0;
            $wallet->pending_withdraw = 0.0;
            $wallet->created_at = now();
            $wallet->updated_at = now();
            $wallet->save();
        }

        if($tab == 'settings')
        {
            return view('rental::admin.provider.details.settings', compact('store'));
        }
        else if ($tab == 'driver'){
            $query = $this->vehicleDriver->where('provider_id', $store_id);
            $totalDrivers = $query->count();
            $activeDrivers = (clone $query)->ofStatus(1)->count();
            $inactiveDrivers = (clone $query)->ofStatus(0)->count();

            if (isset($key)) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('first_name', 'like', "%{$value}%")
                            ->orWhere('last_name', 'like', "%{$value}%");
                    }
                });
            }

            $drivers = $query->latest()->paginate(config('default_pagination'));
            return view('rental::admin.provider.details.driver-list', compact('store', 'drivers', 'totalDrivers', 'activeDrivers', 'inactiveDrivers'));
        }
        else if ($tab == 'vehicle'){
            $query = $this->vehicle->where('provider_id', $store_id);
            $totalVehicles = $query->count();
            $activeVehicles = (clone $query)->ofStatus(1)->count();
            $inactiveVehicles = (clone $query)->ofStatus(0)->count();

            if (isset($key)) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            }

            $vehicles = $query->latest()->paginate(config('default_pagination'));
            return view('rental::admin.provider.details.vehicle-list', compact('store', 'vehicles', 'totalVehicles', 'activeVehicles', 'inactiveVehicles'));
        }
        else if($tab == 'order')
        {
            $orders = $this->order->where('store_id', $store->id)->latest()
                ->when(isset($key ), function ($q) use ($key){
                    $q->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('id', 'like', "%{$value}%");
                        }
                    });
                })
                ->when(isset($filter)  && $filter == 'scheduled_orders' , function($q){
                    $q->Scheduled();
                })
                ->when(isset($filter)  && $filter == 'pending_orders' , function($q){
                    $q->where(['order_status'=>'pending'])->OrderScheduledIn(30);
                })
                ->when(isset($filter)  && $filter == 'delivered_orders' , function($q){
                    $q->where(['order_status'=>'delivered']);
                })
                ->when(isset($filter)  && $filter == 'canceled_orders' , function($q){
                    $q->where(['order_status'=>'canceled']);
                })
                ->StoreOrder()
                ->Notpos()->paginate(10);
            return view('rental::admin.provider.details.order', compact('store','orders'));
        }
        else if($tab == 'item')
        {
            if($sub_tab == 'pending-items' || $sub_tab == 'rejected-items' ){

                $foods = $this->tempProduct->withoutGlobalScope(\App\Scopes\StoreScope::class)->where('store_id', $store->id)
                    ->when(isset($key) , function($q) use($key){
                        $q->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->where('name', 'like', "%{$value}%");
                            }
                        });
                    })
                    ->when($sub_tab == 'pending-items' , function($q){
                        $q->where('is_rejected' , 0);
                    })
                    ->when($sub_tab == 'rejected-items' , function($q){
                        $q->where('is_rejected' , 1);
                    })
                    ->latest()->paginate(25);
            }
            else{

                $foods = $this->item->withoutGlobalScope(\App\Scopes\StoreScope::class)->where('store_id', $store->id)
                    ->when(isset($key) , function($q) use($key){
                        $q->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->where('name', 'like', "%{$value}%");
                            }
                        });
                    })
                    ->when($sub_tab == 'active-items' , function($q){
                        $q->where('status' , 1);
                    })
                    ->when($sub_tab == 'inactive-items' , function($q){
                        $q->where('status' , 0);
                    })
                    ->latest()->paginate(25);
            }

            return view('rental::admin.provider.details.product', compact('store','foods','sub_tab'));
        }
        else if($tab == 'discount')
        {
            return view('rental::admin.provider.details.discount', compact('store'));
        }
        else if($tab == 'transaction')
        {
            return view('rental::admin.provider.details.transaction', compact('store', 'sub_tab'));
        }

        else if($tab == 'reviews')
        {
            return view('rental::admin.provider.details.review', compact('store', 'sub_tab'));

        } else if ($tab == 'conversations') {
            $user = $this->userInfo->where(['vendor_id' => $store->vendor->id])->first();
            if ($user) {
                $conversations = $this->conversation->with(['sender', 'receiver', 'last_message'])->WhereUser($user->id)
                    ->paginate(8);
            } else {
                $conversations = [];
            }
            return view('rental::admin.provider.details.conversations', compact('store', 'sub_tab', 'conversations'));

        } else if ($tab == 'meta-data') {
            $store = $this->store->withoutGlobalScope('translate')->findOrFail($store_id);
            return view('rental::admin.provider.details.meta-data', compact('store', 'sub_tab'));

        } else if ($tab == 'disbursements') {
            $disbursements = $this->disbursementDetails->where('store_id', $store->id)
                ->when(isset($key), function ($q) use ($key){
                    $q->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('disbursement_id', 'like', "%{$value}%")
                                ->orWhere('status', 'like', "%{$value}%");
                        }
                    });
                })
                ->latest()->paginate(config('default_pagination'));
            return view('rental::admin.provider.details.disbursement', compact('store','disbursements'));

        } else if ($tab == 'business_plan') {


            $store= $this->store->where('id',$store->id)->with([
                'store_sub_update_application.package','vendor','store_sub_update_application.last_transcations'
            ])->withcount('items')->first();

            $packages = $this->subscriptionPackage->where('module_type','rental')->where('status',1)->latest()->get();
            $admin_commission = $this->businessSetting->where('key', 'admin_commission')->first()?->value ;
            $business_name =  $this->businessSetting->where('key', 'business_name')->first()?->value ;

            try {
                $index=  $store->store_business_model == 'commission' ? 0 : 1+ array_search($store?->store_sub_update_application?->package_id??1 ,array_column($packages->toArray() ,'id') );
            } catch (\Throwable $th) {
                $index= 2;
            }
            return view('rental::admin.provider.details.subscription',compact('store','packages','business_name','admin_commission','index'));
        }

        return view('rental::admin.provider.details.overview', compact('store', 'wallet'));
    }


    /**
     * @return View|Factory|RedirectResponse|Application
     */
    public function create(): View|Factory|RedirectResponse|Application
    {
        if (!$this->isStoreRegistrationEnabled()) {
            Toastr::error(translate('messages.not_found'));
            return back();
        }

        $admin_commission = $this->helpers->get_business_data('admin_commission');
        $business_name = $this->helpers->get_business_data('business_name');
        $packages = $this->subscriptionPackage->where('module_type','rental')->ofStatus(1)->latest()->get();
        $zones = $this->zone->active(1)->latest()->get();

        return view('rental::admin.provider.create', compact('admin_commission','business_name', 'packages', 'zones'));
    }


    /**
     * @param Request $request
     * @return View|Factory|RedirectResponse|Application
     */
    public function store(Request $request): View|Factory|RedirectResponse|Application
    {
        if (!$this->isStoreRegistrationEnabled()) {
            Toastr::error(translate('messages.not_found'));
            return back();
        }

        $validator = $this->validateStoreRequest($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->zone_id && !$this->isValidZone($request)) {
            $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
            return back()->withErrors($validator)->withInput();
        }

        if ($request->business_plan == 'subscription-base' && !$request->package_id) {
            $validator->getMessageBag()->add('package_id', translate('messages.You_must_select_a_package'));
            return back()->withErrors($validator)->withInput();
        }

        $vendor = $this->createVendor($request);
        $store = $this->createStore($request, $vendor);


        $this->helpers->add_or_update_translations(request: $request, key_data: 'name', name_field: 'name', model_name: 'Store', data_id: $store->id, data_value: $store->name);
        $this->helpers->add_or_update_translations(request: $request, key_data: 'address', name_field: 'address', model_name: 'Store', data_id: $store->id, data_value: $store->address);

        $this->sendRegistrationEmails($request, $vendor);

        if(config('module.'.$store->module->module_type)['always_open'])
        {
            $this->storeLogic->insert_schedule($store->id);
        }

        return $this->handleBusinessPlan($request, $store);
    }


    /**
     * @param Request $request
     * @return View|Factory|RedirectResponse|Application
     */
    public function newRequests(Request $request): View|Factory|RedirectResponse|Application
    {
        $zone_id = $request->query('zone_id', 'all');
        $search_by = $request->query('search_by');
        $key = explode(' ', $search_by);
        $type = $request->query('type', 'all');
        $requestType = $request->query('request_type', 'pending_provider');
        $module_id = $request->query('module_id', 'all');

        $stores = $this->store->with('vendor','module')
            ->whereHas('vendor', function ($query) use ($requestType) {
                if ($requestType === 'pending_provider') {
                    $query->where('status', null);
                } elseif ($requestType === 'denied_provider') {
                    $query->where('status', 0);
                }
            })
            ->when(is_numeric($zone_id), function($query)use($zone_id){
                return $query->where('zone_id', $zone_id);
            })
            ->when(is_numeric($module_id), function($query)use($request){
                return $query->module($request->query('module_id'));
            })
            ->when($search_by, function($query)use($key){
                return $query->where(function($query)use($key){
                    $query->orWhereHas('vendor',function ($q) use ($key) {
                        $q->where(function($q)use($key){
                            foreach ($key as $value) {
                                $q->orWhere('f_name', 'like', "%{$value}%")
                                    ->orWhere('l_name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%")
                                    ->orWhere('phone', 'like', "%{$value}%");
                            }
                        });
                    })->orWhere(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%");
                        }
                    });
                });
            })
            ->module(Config::get('module.current_module_id'))
            ->type($type)->latest()->paginate(config('default_pagination'));
        $zone = is_numeric($zone_id)?Zone::findOrFail($zone_id):null;
        return view('rental::admin.provider.new-request', compact('stores', 'zone','type', 'search_by'));
    }

    /**
     * @param Request $request
     * @param $store_id
     * @return Factory|\Illuminate\Foundation\Application|View|Application
     */
    public function newRequestsDetails(Request $request, $store_id): Factory|\Illuminate\Foundation\Application|View|Application
    {
        $store = $this->store->findOrFail($store_id);
        $store->pickupZones = Zone::whereIn('id', json_decode($store->pickup_zone_id))->pluck('name', 'id');

        return view('rental::admin.provider.new-request-details', compact('store'));
    }

    /**
     * @param $id
     * @return Factory|\Illuminate\Foundation\Application|View|RedirectResponse|Application
     */
    public function editBasicSetup($id): Factory|\Illuminate\Foundation\Application|View|RedirectResponse|Application
    {
        if(env('APP_MODE')=='demo' && $id == 2)
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_provider_please_add_a_new_provider_to_edit'));
            return back();
        }

        $zones = $this->zone->active(1)->latest()->get();
        $store = $this->store->withoutGlobalScope('translate')->findOrFail($id);

        return view('rental::admin.provider.edit-basic-setup', compact('store', 'zones'));
    }

    /**
     * @param $id
     * @return Factory|\Illuminate\Foundation\Application|View|RedirectResponse|Application
     */
    public function editBusinessSetup($id): Factory|\Illuminate\Foundation\Application|View|RedirectResponse|Application
    {
        if(env('APP_MODE')=='demo' && $id == 2)
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_provider_please_add_a_new_provider_to_edit'));
            return back();
        }

        $admin_commission = $this->helpers->get_business_data('admin_commission');
        $business_name = $this->helpers->get_business_data('business_name');
        $packages = $this->subscriptionPackage->ofStatus(1)->where('module_type','rental')->latest()->get();
        $zones = $this->zone->active(1)->latest()->get();
        $store = $this->store->withoutGlobalScope('translate')->findOrFail($id);

        return view('rental::admin.provider.edit-business-setup', compact('store', 'zones', 'business_name', 'admin_commission', 'packages'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Factory|\Illuminate\Foundation\Application|View|RedirectResponse|Application
     */
    public function updateBasicSetup(Request $request, $id): Factory|\Illuminate\Foundation\Application|View|RedirectResponse|Application
    {
        $store = $this->store->find($id);
        if (!$store) {
            Toastr::error(translate('messages.information_not_found'));
            return back();
        }

        if (!$this->isStoreRegistrationEnabled()) {
            Toastr::error(translate('messages.not_found'));
            return back();
        }

        $validator = $this->validateStoreRequest($request, $store->vendor_id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->zone_id && !$this->isValidZone($request)) {
            $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
            return back()->withErrors($validator)->withInput();
        }

        $this->updateVendor($request, $store->vendor);
        $this->updateStore($request, $store);

        $this->helpers->add_or_update_translations(request: $request, key_data: 'name', name_field: 'name', model_name: 'Store', data_id: $id, data_value: $store->name);
        $this->helpers->add_or_update_translations(request: $request, key_data: 'address', name_field: 'address', model_name: 'Store', data_id: $id, data_value: $store->address);

        Toastr::success(translate('messages.Provider_updated_successfully'));
        return redirect()->route('admin.rental.provider.edit-business-setup', $id);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function approveOrDeny(Request $request): RedirectResponse
    {
        $store = $this->store->findOrFail($request->id);
        $store->comment = $request->message;
        $store->vendor->status = $request->status;
        $store->vendor->save();

        if($request->status) $store->status = 1;

        $add_days = 1;

        if($store?->store_sub_update_application){
            if($store?->store_sub_update_application && $store?->store_sub_update_application->is_trial == 1){
                $add_days = $this->businessSetting->where(['key' => 'subscription_free_trial_days'])->first()?->value ?? 1;
            }elseif($store?->store_sub_update_application && $store?->store_sub_update_application->is_trial == 0){
                $add_days = $store?->store_sub_update_application->validity;
            }

            $store?->store_sub_update_application->update([
                'expiry_date'=> Carbon::now()->addDays($add_days)->format('Y-m-d'),
                'status'=>1
            ]);
            $store->store_business_model= 'subscription';
        }

        $store->save();

        try{
            if($request->status == 1){
                if ( config('mail.status') && $this->helpers->get_mail_status('approve_mail_status_store') == '1' &&  $this->helpers->getNotificationStatusData('store','store_registration_approval','mail_status')) {
                    Mail::to($store?->vendor?->email)->send(new \App\Mail\VendorSelfRegistration('approved', $store->vendor->f_name.' '.$store->vendor->l_name));
                }
            }else{
                if ( config('mail.status') &&  $this->helpers->get_mail_status('deny_mail_status_store') == '1' &&  $this->helpers->getNotificationStatusData('store','store_registration_deny','mail_status')) {
                    Mail::to($store?->vendor?->email)->send(new \App\Mail\VendorSelfRegistration('denied', $store->vendor->f_name.' '.$store->vendor->l_name));
                }
            }
        }
        catch(\Exception $ex){
            info($ex->getMessage());
        }
        Toastr::success(translate('messages.application_status_updated_successfully'));
        return back();
    }


    /**
     * @return bool
     */
    private function isStoreRegistrationEnabled(): bool
    {
        $status = $this->businessSetting->where('key', 'toggle_store_registration')->first();
        return isset($status) && $status->value !== '0';
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Validation\Validator
     */
    private function validateStoreRequest(Request $request, $id = null): \Illuminate\Validation\Validator
    {
        $rules = [
            'f_name' => 'required',
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'email' => 'required|unique:vendors,id' . ($id ? ','.$id : ''),
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:vendors,phone' . ($id ? ','.$id : ''),
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required',
            'password' => [
                $id ? 'nullable' : 'required',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols()
                    ->uncompromised(),
                function ($attribute, $value, $fail) {
                    if (strpos($value, ' ') !== false) {
                        $fail('The :attribute cannot contain white spaces.');
                    }
                },
            ],
            'zone_id' => 'required',
            'logo' => [
                $id ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'tax' => 'required',
            'delivery_time_type' => 'required',
            'business_plan' => $id ? 'nullable' : 'required',
            'package_id' => $id ? 'nullable' : 'required_if:business_plan,subscription-based',
        ];

        $messages = [
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isValidZone(Request $request): bool
    {
        $zone = $this->zone->query()
            ->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))
            ->where('id', $request->zone_id)
            ->first();
        return (bool)$zone;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    private function createVendor(Request $request): mixed
    {
        return $this->vendor->create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);
    }

    /**
     * @param Request $request
     * @param Vendor $vendor
     * @return mixed
     */
    private function updateVendor(Request $request, Vendor $vendor): mixed
    {
        return $vendor->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);
    }


    /**
     * @param Request $request
     * @param Vendor $vendor
     * @return mixed
     */
    private function createStore(Request $request, Vendor $vendor): mixed
    {
        return $this->store->create([
            'name' => $request->name[array_search('default', $request->lang)],
            'phone' => $request->phone,
            'email' => $request->email,
            'logo' => $this->upload('store/', 'png', $request->file('logo')),
            'cover_photo' => $this->upload('store/cover/', 'png', $request->file('cover_photo')),
            'address' => $request->address[array_search('default', $request->lang)],
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vendor_id' => $vendor->id,
            'zone_id' => $request->zone_id,
            'module_id' => config('module')['current_module_id'],
            'pickup_zone_id' => json_encode($request->pickup_zone_id ?? []),
            'tax' => $request->tax,
            'delivery_time' => "{$request->minimum_delivery_time}-{$request->maximum_delivery_time} {$request->delivery_time_type}",
            'status' => 1,
            'store_business_model' => 'none',
        ]);
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return mixed
     */
    private function updateStore(Request $request, Store $store): mixed
    {
        return $store->update([
            'name' => $request->name[array_search('default', $request->lang)],
            'phone' => $request->phone,
            'email' => $request->email,
            'logo' => $this->updateAndUpload('store/', $store->logo,'png', $request->file('logo')),
            'cover_photo' => $this->updateAndUpload('store/cover/', $store->cover_photo,'png', $request->file('cover_photo')),
            'address' => $request->address[array_search('default', $request->lang)],
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'zone_id' => $request->zone_id,
            'module_id' => config('module')['current_module_id'],
            'pickup_zone_id' => json_encode($request->pickup_zone_id ?? []),
            'tax' => $request->tax,
            'delivery_time' => "{$request->minimum_delivery_time}-{$request->maximum_delivery_time} {$request->delivery_time_type}",
            'status' => 1,
            'store_business_model' => 'none',
        ]);
    }

    /**
     * @param Request $request
     * @param Vendor $vendor
     * @return void
     */
    private function sendRegistrationEmails(Request $request, Vendor $vendor): void
    {
        try{
            $admin = $this->admin->where('role_id', 1)->first();

            if(config('mail.status') &&
                $this->helpers->get_mail_status('registration_mail_status_store') == '1' &&
                $this->helpers->getNotificationStatusData('store','store_registration','mail_status') ){

                Mail::to($request['email'])->send(new VendorSelfRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
            }

            if(config('mail.status') &&
                $this->helpers->get_mail_status('store_registration_mail_status_admin') == '1' &&
                $this->helpers->getNotificationStatusData('admin','store_self_registration','mail_status') ){

                Mail::to($admin['email'])->send(new StoreRegistration('pending', $vendor->f_name.' '.$vendor->l_name));
            }

        }catch(\Exception $ex){
            info($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return RedirectResponse
     */
    private function handleBusinessPlan(Request $request, Store $store): RedirectResponse
    {
        if ($this->helpers->subscription_check()){
            if ($request->business_plan == 'subscription-base' && $request->package_id != null ) {

                return $this->handleSubscriptionPlan($request, $store);

            } elseif ($request->business_plan == 'commission-base') {

                $store->update(['store_business_model' => 'commission']);

                Toastr::success(translate('messages.your_store_registration_is_successful'));
                return back();

            } else {
                $admin_commission = $this->helpers->get_business_data('admin_commission');
                $business_name = $this->helpers->get_business_data('business_name');
                $packages = $this->subscriptionPackage->ofStatus(1)->where('module_type','rental')->latest()->get();

                Toastr::error(translate('messages.please_follow_the_steps_properly.'));
                return back();
            }
        }else{
            $store->update(['store_business_model' => 'commission']);

            Toastr::success(translate('messages.your_provider_registration_is_successful'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return RedirectResponse
     */
    private function handleSubscriptionPlan(Request $request, Store $store): RedirectResponse
    {
        // $free_trial_settings = $this->businessSetting
        //     ->whereIn('key', ['subscription_free_trial_days', 'subscription_free_trial_type', 'subscription_free_trial_status'])
        //     ->pluck('value', 'key');

        Helpers::subscription_plan_chosen(store_id:$store->id,package_id:$request->package_id,payment_method:'manual_payment_by_admin',discount:0,reference:'manual_payment_by_admin',type: 'new_join');
        $store->update(['package_id' => $request->package_id]);
        Toastr::success(translate('messages.your_provider_registration_is_successful'));
        return back();
    }





}
