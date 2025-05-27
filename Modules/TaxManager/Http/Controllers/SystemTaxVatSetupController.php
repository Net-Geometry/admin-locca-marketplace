<?php

namespace Modules\TaxManager\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\TaxManager\Entities\SystemTaxSetup;
use Modules\TaxManager\Entities\Tax;
use Modules\TaxManager\Entities\TaxAdditionalSetup;
use Modules\TaxManager\Traits\VatTaxConfiguration;

class SystemTaxVatSetupController extends Controller
{
    use VatTaxConfiguration;
    private Tax $taxVat;
    private SystemTaxSetup $systemTaxVat;
    private TaxAdditionalSetup $taxOnAdditionalData;

    public function __construct(Tax $taxVat, SystemTaxSetup $systemTaxVat, TaxAdditionalSetup $taxOnAdditionalData)
    {
        $this->taxVat = $taxVat;
        $this->systemTaxVat = $systemTaxVat;
        $this->taxOnAdditionalData = $taxOnAdditionalData;
    }

    /**
     * Displays the list of tax vat data.
     *
     * @return Renderable
     */


    public function index(Request $request): Renderable
    {
        $systemTaxVat = $this->systemTaxVat->with('additionalData')->when($this->getCountryType() == 'single', function ($query) {
            $query->where('is_default', true);
        }, function ($query) use ($request) {
            $query->where('country_code', $request->country_code);
        })->first();


        $taxVats = $this->taxVat->where('is_active', 1)
            ->when($this->getCountryType() == 'single', function ($query) {
                $query->where('is_default', true);
            }, function ($query) use ($request) {
                $query->where('country_code', $request->country_code);
            })
            ->latest()->get(['id', 'name', 'tax_rate']);
        $country_code = null;

        $systemData =$this->getPorjectWiseSystemData();

        return view($this->getProjectWiseViewPath('SystemTaxVatSetupController','index'), compact('taxVats', 'systemTaxVat', 'country_code' ,'systemData'));
    }


    public function systemTaxVatStore(Request $request): RedirectResponse
    {
        $this->validateRequest($request);
        $systemTaxVat = $this->systemTaxVat->find($request->system_tax_id);
        $systemTaxVat->tax_type = $request->tax_type ?? 'order_wise';
        $systemTaxVat->tax_payer = $request->tax_payer ??  'vendor';
        $systemTaxVat->tax_ids = $request->tax_ids;
        if ($this->getCountryType() !== 'single') {
            $systemTaxVat->country_code = $request->country_code ?? $systemTaxVat?->country_code;
        }
        $systemTaxVat->is_included = $request->tax_status == 'include' ? 1 : 0;
        $systemTaxVat->save();
        foreach ($this->getPorjectWiseSystemData('additional_tax') ?? [] as $item) {
            $taxOnAdditionalData = $this->taxOnAdditionalData->where('system_tax_setup_id', $systemTaxVat->id)->where('name', $item)->firstOrNew();
            $taxOnAdditionalData->name = $item;
            $taxOnAdditionalData->system_tax_setup_id = $systemTaxVat->id;
            $taxOnAdditionalData->is_active = isset($request->additional_status[$item]) && array_key_exists($item, $request->additional_status) ? 1 : 0;
            $taxOnAdditionalData->tax_ids = $request->additional[$item] ?? $taxOnAdditionalData->tax_ids ?? [];
            $taxOnAdditionalData->save();
        }
        $this->showNotification('successMessage', translate('messages.Tax_Settings_Updated_Successfully'));
        return back();
    }


    public function vendorStatus(Request $request): JsonResponse
    {
        $systemTaxVat = $this->systemTaxVat->find($request->id);
        if (!$systemTaxVat) {
            $systemTaxVat = $this->systemTaxVat;
            $systemTaxVat->is_default = true;
            $systemTaxVat->is_included = true;
            if ($this->getCountryType() !== 'single') {
                $systemTaxVat->country_code = $request->country_code ?? $systemTaxVat?->country_code;
                $systemTaxVat->is_default = false;
            }
        }

        $systemTaxVat->is_active = !$systemTaxVat->is_active;
        $systemTaxVat->save();
        return response()->json(['id' => $systemTaxVat->id, 'status' =>  $systemTaxVat->is_active, 'message' => translate('messages.vendor_tax_status_updated')]);
    }
        private function validateRequest(Request $request, $id = null): void
    {
        $request->validate(
            [
                'tax_ids' => 'required_if:tax_type,order_wise',
            ]
        );
    }

}
