<?php

namespace Modules\TaxVat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TaxVat\Entities\TaxVat;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;


class TaxVatController extends Controller
{
    private TaxVat $taxVat;

    /**
     * Constructor for injecting TaxVat model dependency.
     *
     * @param TaxVat $taxVat
     */
    public function __construct(TaxVat $taxVat)
    {
        $this->taxVat = $taxVat;
    }

    /**
     * Displays the list of tax vat data.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $taxVats = $this->taxVat->latest()->paginate(11);
        return view('taxvat::index', compact('taxVats'));
    }


    public function store(Request $request):RedirectResponse
    {
        $this->validateRequest($request);
        $this->createTaxVatData($request);
        Toastr::success(translate('messages.New_Tax_Added_Successfully'));
        return back();
    }

    public function update(Request $request, TaxVat $taxVat): RedirectResponse
    {
        $this->validateRequest($request, $taxVat->id);
        $this->updatetaxVat($request, $taxVat);
        Toastr::success($taxVat->name . ' ' . translate('messages.updated_successfully'));
        return to_route('taxvat.index');
    }

    private function validateRequest(Request $request, $id = null): void
    {
        $request->validate(
            [
                'name' => 'required|max:50|unique:tax_vats,name' . ($id ? ',' . $id : ''),
                'tax_rate' => 'required|numeric|max:100|min:0.001',

            ]
        );
    }

    private function createTaxVatData($request): TaxVat
    {
        $taxVat = $this->taxVat;
        return  $this->updatetaxVat($request, $taxVat);
    }

    private function updatetaxVat($request, $taxVat): TaxVat
    {
        $taxVat->name = $request->name;
        $taxVat->tax_rate = $request->tax_rate;
        $taxVat->is_active = $request->status ?? 0;
        $taxVat->save();
        return $taxVat;
    }

    public function status(TaxVat $taxVat): JsonResponse
    {
        $taxVat->update(['is_active' => !$taxVat->is_active]);
        return response()->json(['id' => $taxVat->id ,'status' =>  $taxVat->is_active , 'message' => translate('messages.tax_status_updated')]);
    }
}
