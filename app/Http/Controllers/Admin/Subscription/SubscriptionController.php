<?php

namespace App\Http\Controllers\Admin\Subscription;

use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionPackageRequest;
use App\Contracts\Repositories\TranslationRepositoryInterface;

class SubscriptionController extends Controller
{

    public function __construct(
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }
    public function index()
    {
        $packages=  SubscriptionPackage::latest()->paginate(config('default_pagination'));
        return view('admin-views.subscription.1-subscription',compact('packages'));
    }
    public function add()
    {
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('admin-views.subscription.3-subscription', compact('language','defaultLang'));
    }
    public function packageAdd(Request $request)
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

        return redirect()->route('admin.subscription.subscription_index');
    }


}
