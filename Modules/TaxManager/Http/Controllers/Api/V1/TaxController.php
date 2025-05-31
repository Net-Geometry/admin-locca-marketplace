<?php

namespace Modules\TaxManager\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\TaxManager\Entities\Tax;
use Modules\TaxManager\Services\CalculateTaxService;

class TaxController extends Controller
{
    public function getTaxVatList()
    {
        $data = Tax::where('is_active', 1)->select('id', 'name','tax_rate')->get();
        return response()->json($data, 200);
    }

    public function getCalculateTax(Request $request){

        $validator = Validator::make($request->all(), [
            'totalProductAmount' => 'required|numeric',
            'productIds' => 'required',
            'categoryIds' => 'required',
            'quantity' => 'required',
            'additionalCharges' => 'nullable|array',
            'orderId' => 'nullable',
            'countryCode' => 'nullable',
            'taxPayer' => 'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->error_processor($validator)], 403);
        }


        $data =CalculateTaxService::getCalculatedTax(amount:$request->totalProductAmount, productIds: json_decode($request->productIds, true)??[], categoryIds:json_decode($request->categoryIds, true)?? [], quantity:json_decode($request->quantity, true)??[] ,storeData: false, additionalCharges:json_decode($request->additionalCharges, true)?? [] , taxPayer:$request->taxPayer, orderId:$request?->orderId, countryCode:$request?->countryCode);
        return response()->json($data);
    }


   private function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => translate($error[0])]);
        }
        return $err_keeper;
    }

}
