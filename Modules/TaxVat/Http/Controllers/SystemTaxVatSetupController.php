<?php

namespace Modules\TaxVat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TaxVat\Entities\TaxVat;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Modules\TaxVat\Entities\SystemTaxVat;
use Modules\TaxVat\Entities\TaxOnAdditionalData;
use Modules\TaxVat\Exports\TaxVatExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SystemTaxVatSetupController extends Controller
{
    private TaxVat $taxVat;
    private SystemTaxVat $systemTaxVat;
    private TaxOnAdditionalData $taxOnAdditionalData;

    /**
     * Constructor for injecting TaxVat model dependency.
     *
     * @param TaxVat $taxVat
     */
    public function __construct(TaxVat $taxVat, SystemTaxVat $systemTaxVat, TaxOnAdditionalData $taxOnAdditionalData)
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
        $systemTaxVat = $this->systemTaxVat->with('additionalData')->when(config('taxvat.country_type') == 'single', function ($query) {
            $query->where('is_default', true);
        }, function ($query) use ($request) {
            $query->where('country_code', $request->country_code);
        })->first();


        $taxVats = $this->taxVat->where('is_active', 1)
            ->when(config('taxvat.country_type') == 'single', function ($query) {
                $query->where('is_default', true);
            }, function ($query) use ($request) {
                $query->where('country_code', $request->country_code);
            })
            ->latest()->get(['id', 'name', 'tax_rate']);
        $country_code = null;
        return view('taxvat::system_tax_setup', compact('taxVats', 'systemTaxVat', 'country_code'));
    }


    public function systemTaxVatStore(Request $request): RedirectResponse
    {
        $systemTaxVat = $this->systemTaxVat->find($request->system_tax_id);
        $systemTaxVat->tax_type = $request->tax_type ?? 'order_wise';
        $systemTaxVat->tax_payer = $request->tax_payer ??  'vendor';
        $systemTaxVat->tax_vat_ids = $request->tax_vat_ids;
        if (config('taxvat.country_type') == 'multi') {
            $systemTaxVat->country_code = $request->country_code ?? $systemTaxVat?->country_code;
        }
        $systemTaxVat->is_included = $request->tax_status == 'include' ? 1 : 0;
        $systemTaxVat->save();
        foreach (config('taxvat.' . config('taxvat.project') . '.additional_tax') ?? [] as $item) {
            $taxOnAdditionalData = $this->taxOnAdditionalData->where('system_tax_vat_id', $systemTaxVat->id)->where('name', $item)->firstOrNew();
            $taxOnAdditionalData->name = $item;
            $taxOnAdditionalData->system_tax_vat_id = $systemTaxVat->id;
            $taxOnAdditionalData->tax_payer = $systemTaxVat->tax_payer;
            $taxOnAdditionalData->is_default =  $systemTaxVat->is_default;
            $taxOnAdditionalData->is_included =  $systemTaxVat->is_included;
            if (config('taxvat.country_type') == 'multi') {
                $taxOnAdditionalData->country_code = $request->country_code ?? $systemTaxVat?->country_code;
            }
            $taxOnAdditionalData->is_active = isset($request->additional_status[$item]) && array_key_exists($item, $request->additional_status) ? 1 : 0;
            // $taxOnAdditionalData->tax_vat_ids = json_encode($request->additional[$item] ?? $taxOnAdditionalData->tax_vat_ids ?? []);
            $taxOnAdditionalData->tax_vat_ids = $request->additional[$item] ?? $taxOnAdditionalData->tax_vat_ids ?? [];
            $taxOnAdditionalData->save();
        }

        Toastr::success(translate('messages.Tax_Settings_Updated_Successfully'));
        return back();
    }


    public function vendorStatus(Request $request): JsonResponse
    {
        $systemTaxVat = $this->systemTaxVat->find($request->id);
        if (!$systemTaxVat) {
            $systemTaxVat = $this->systemTaxVat;
            $systemTaxVat->is_default = true;
            $systemTaxVat->is_included = true;
            if (config('taxvat.country_type') == 'multi') {
                $systemTaxVat->country_code = $request->country_code ?? $systemTaxVat?->country_code;
                $systemTaxVat->is_default = false;
            }
        }

        $systemTaxVat->is_active = !$systemTaxVat->is_active;
        $systemTaxVat->save();
        return response()->json(['id' => $systemTaxVat->id, 'status' =>  $systemTaxVat->is_active, 'message' => translate('messages.vendor_tax_status_updated')]);
    }
}
