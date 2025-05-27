<?php

namespace Modules\TaxManager\Services;

use Modules\TaxManager\Entities\OrderTax;
use Modules\TaxManager\Entities\SystemTaxSetup;
use Modules\TaxManager\Entities\Taxable;
use Modules\TaxManager\Entities\Tax;


class CalculateTaxService
{

    public function getCalculatedTax($data = [])
    {

        $data['amount'] = data_get($data, 'amount', 0);
        $data['taxPayer'] = data_get($data, 'taxPayer', 'vendor');
        $data['storeData'] = data_get($data, 'storeData', null);
        $data['orderId'] = data_get($data, 'orderId', null);
        $data['countryCode'] = data_get($data, 'countryCode', null);
        $data['productIds'] = data_get($data, 'productIds', []);
        $data['categoryIds'] = data_get($data, 'categoryIds', []);
        $data['modelClass'] = data_get($data, 'modelClass', 'App\Models\Item');

        $systemTaxVat = SystemTaxSetup::with('additionalData')
            ->when($data['countryCode'], function ($query) use ($data) {
                $query->where('country_code', $data['countryCode']);
            }, function ($query) {
                $query->where('is_default', 1);
            })->where('tax_payer', $data['taxPayer'])
            ->first();

        if (!$systemTaxVat || $systemTaxVat?->is_active === 0) {
            return ['include' => null, 'totalTaxPercent' => 0, 'totalTaxamount' => 0];
        }
        if ($systemTaxVat?->is_incuded) {
            return ['include' => 1, 'totalTaxPercent' => 0, 'totalTaxamount' => 0];
        }
         if ($systemTaxVat?->tax_type == 'product_wise') {
            $totalTaxPercent = 0;
            $totalTaxamount = 0;

            foreach ($data['productIds'] as $productId) {
                $taxVatIds = Taxable::where('data_type', $data['modelClass'])->where('data_id', $productId)->where('system_tax_setup_id', $systemTaxVat->id)->pluck('tax_id')->toArray();
                $productWiseTaxData =  $this->calculateTax(
                    systemTaxVat: $systemTaxVat,
                    amount: $data['amount'],
                    taxIds: $taxVatIds,
                    taxPayer: $data['taxPayer'],
                    storeData: $data['storeData'],
                    orderId: $data['orderId'],
                    countryCode: $data['countryCode'],
                    data_id: $productId,
                    data_type: $data['modelClass']
                );

                $totalTaxPercent += $productWiseTaxData['totalTaxPercent'];
                $totalTaxamount += $productWiseTaxData['totalTaxamount'];
            }

            return [
                'include' => $systemTaxVat?->is_incuded,
                'totalTaxPercent' => $totalTaxPercent,
                'totalTaxamount' => $totalTaxamount,
            ];
        }
        elseif ($systemTaxVat?->tax_type == 'category_wise') {
            $totalTaxPercent = 0;
            $totalTaxamount = 0;

            foreach ($data['categoryIds'] as $categoryId) {
                $taxVatIds = Taxable::where('data_type', $data['modelClass'])->where('data_id', $categoryId)->where('system_tax_setup_id', $systemTaxVat->id)->pluck('tax_id')->toArray();
                $productWiseTaxData =  $this->calculateTax(
                    systemTaxVat: $systemTaxVat,
                    amount: $data['amount'],
                    taxIds: $taxVatIds,
                    taxPayer: $data['taxPayer'],
                    storeData: $data['storeData'],
                    orderId: $data['orderId'],
                    countryCode: $data['countryCode'],
                    data_id: $categoryId,
                    data_type: $data['modelClass']
                );

                $totalTaxPercent += $productWiseTaxData['totalTaxPercent'];
                $totalTaxamount += $productWiseTaxData['totalTaxamount'];
            }

            return [
                'include' => $systemTaxVat?->is_incuded,
                'totalTaxPercent' => $totalTaxPercent,
                'totalTaxamount' => $totalTaxamount,
            ];
        }
          return $this->calculateTax(
                systemTaxVat: $systemTaxVat,
                amount: $data['amount'] ?? 0,
                taxIds: $systemTaxVat->tax_ids,
                taxPayer: $data['taxPayer'],
                storeData: $data['storeData'],
                orderId: $data['orderId'],
                countryCode: $data['countryCode']
            );
    }



    protected function calculateTax($systemTaxVat, $amount, $taxIds, $taxPayer = 'vendor', $storeData = null, $orderId = null, $countryCode = null, $data_id = null, $data_type = null)
    {
        $taxRatePercent = Tax::whereIn('id', $taxIds)->select('id', 'name', 'tax_rate')->get();
        $totalTaxPercent = 0;
        $totalTaxamount = 0;

        foreach ($taxRatePercent as $taxRate) {
            $taxData = $this->getTaxAmount(amount: $amount, taxRatePercent: $taxRate->tax_rate, isInclude: $systemTaxVat->is_incuded);
            $totalTaxPercent += $taxRate->tax_rate;
            $totalTaxamount += $taxData['taxAmount'];
            if ($storeData) {
                $orderTaxData = new OrderTax();
                $orderTaxData->tax_name = $taxRate->name;
                $orderTaxData->tax_type = $systemTaxVat->tax_type;
                $orderTaxData->tax_from = 'basic';
                $orderTaxData->tax_rate = $taxRate->tax_rate;
                $orderTaxData->tax_amount = $taxData['taxAmount'];
                $orderTaxData->before_tax_amount = $taxData['originalAmount'];
                $orderTaxData->after_tax_amount = $taxData['totalAmount'];
                $orderTaxData->tax_payer = $taxPayer;
                $orderTaxData->country_code = $countryCode;
                $orderTaxData->order_id = $orderId;
                $orderTaxData->tax_id = $taxRate->id;
                $orderTaxData->system_tax_setup_id = $systemTaxVat->id;
                $orderTaxData->data_id = $data_id;
                $orderTaxData->data_type = $data_type;
                $orderTaxData->save();
            }
        }
        return  ['include' => $systemTaxVat?->is_incuded, 'totalTaxPercent' => $totalTaxPercent, 'totalTaxamount' => $totalTaxamount];
    }

    protected function getTaxAmount($amount, $taxRatePercent, $isInclude = false)
    {
        if ($amount > 0 && $taxRatePercent > 0) {
            $taxAmount = ($amount * $taxRatePercent) / (100 + ($isInclude ? $taxRatePercent : 0));
            $totalAmount = $isInclude ? ($amount - $taxAmount) : ($amount + $taxAmount);
            return ['taxAmount' => $taxAmount, 'originalAmount' => $amount, 'totalAmount' => $totalAmount];
        }

        return ['taxAmount' => 0, 'originalAmount' => $amount, 'totalAmount' => $amount, 'taxRatePercent' => $taxRatePercent];
    }
}
