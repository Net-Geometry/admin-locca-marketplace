<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;
use App\Mail\StoreRegistration;
use App\Mail\VendorSelfRegistration;
use App\Models\Admin;
use App\Models\BusinessSetting;
use App\Models\Store;
use App\Models\SubscriptionPackage;
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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ProviderController extends Controller
{
    private BusinessSetting $businessSetting;
    private Zone $zone;
    private Vendor $vendor;
    private Store $store;
    private Admin $admin;
    private StoreLogic $storeLogic;
    private SubscriptionPackage $subscriptionPackage;
    private Helpers $helpers;

    use FileManagerTrait;

    /**
     * @param BusinessSetting $businessSetting
     * @param Zone $zone
     * @param Vendor $vendor
     * @param Store $store
     * @param Admin $admin
     * @param StoreLogic $storeLogic
     * @param SubscriptionPackage $subscriptionPackage
     * @param Helpers $helpers
     */
    public function __construct(BusinessSetting $businessSetting, Zone $zone, Vendor $vendor, Store $store, Admin $admin, StoreLogic $storeLogic, SubscriptionPackage $subscriptionPackage, Helpers $helpers)
    {
        $this->businessSetting = $businessSetting;
        $this->zone = $zone;
        $this->vendor = $vendor;
        $this->store = $store;
        $this->admin = $admin;
        $this->storeLogic = $storeLogic;
        $this->subscriptionPackage = $subscriptionPackage;
        $this->helpers = $helpers;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('rental::index');
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
        $packages = $this->subscriptionPackage->ofStatus(1)->latest()->get();

        return view('rental::admin.provider.business-basic-setup', compact('admin_commission','business_name', 'packages'));
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
     * @return bool
     */
    private function isStoreRegistrationEnabled(): bool
    {
        $status = $this->businessSetting->where('key', 'toggle_store_registration')->first();
        return isset($status) && $status->value !== '0';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateStoreRequest(Request $request): \Illuminate\Validation\Validator
    {
        $rules = [
            'f_name' => 'required',
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'email' => 'required|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:vendors',
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            'zone_id' => 'required',
            'module_id' => 'required',
            'logo' => 'required',
            'tax' => 'required',
            'delivery_time_type' => 'required',
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
            'status' => null,
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
            'logo' => $this->helpers->upload('store/', 'png', $request->file('logo')),
            'cover_photo' => $this->helpers->upload('store/cover/', 'png', $request->file('cover_photo')),
            'address' => $request->address[array_search('default', $request->lang)],
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vendor_id' => $vendor->id,
            'zone_id' => $request->zone_id,
            'module_id' => $request->module_id,
            'pickup_zone_id' => json_encode($request->pickup_zone_id ?? []),
            'tax' => $request->tax,
            'delivery_time' => "{$request->minimum_delivery_time}-{$request->maximum_delivery_time} {$request->delivery_time_type}",
            'status' => 0,
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
     * @return View|Factory|\Illuminate\Foundation\Application|Application
     */
    private function handleBusinessPlan(Request $request, Store $store): View|Factory|\Illuminate\Foundation\Application|Application
    {
        if ($this->helpers->subscription_check()){
            if ($request->business_plan == 'subscription-base' && $request->package_id != null ) {

                return $this->handleSubscriptionPlan($request, $store);

            } elseif ($request->business_plan == 'commission-base') {

                $store->update(['store_business_model' => 'commission']);

                Toastr::success(translate('messages.your_store_registration_is_successful'));
                return view('vendor-views.auth.register-complete', ['type' => 'commission']);

            } else {
                $admin_commission = $this->helpers->get_business_data('admin_commission');
                $business_name = $this->helpers->get_business_data('business_name');
                $packages = $this->subscriptionPackage->ofStatus(1)->latest()->get();

                Toastr::error(translate('messages.please_follow_the_steps_properly.'));
                return view('vendor-views.auth.register-step-2', [
                    'admin_commission' => $admin_commission?->value,
                    'business_name' => $business_name?->value,
                    'packages' => $packages,
                    'store_id' => $store->id,
                    'type' => $request->type
                ]);
            }
        }else{
            $store->update(['store_business_model' => 'commission']);

            Toastr::success(translate('messages.your_store_registration_is_successful'));
            return view('vendor-views.auth.register-complete',['type'=>'commission']);
        }
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return View
     */
    private function handleSubscriptionPlan(Request $request, Store $store): View
    {
        $free_trial_settings = $this->businessSetting
            ->whereIn('key', ['subscription_free_trial_days', 'subscription_free_trial_type', 'subscription_free_trial_status'])
            ->pluck('value', 'key');

        $store->update(['package_id' => $request->package_id]);

        return view('vendor-views.auth.register-subscription-payment', [
            'package_id' => $request->package_id,
            'store_id' => $store->id,
            'free_trial_settings' => $free_trial_settings,
            'payment_methods' => $this->helpers->getDefaultPaymentMethods(),
        ]);
    }





}
