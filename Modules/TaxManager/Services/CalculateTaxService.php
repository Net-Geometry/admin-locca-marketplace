<?php

namespace Modules\TaxManager\Services;


use Illuminate\Support\Facades\DB;
use Modules\TaxManager\Entities\OrderTax;
use Modules\TaxManager\Entities\SystemTaxSetup;
use Modules\TaxManager\Entities\Taxable;
use Modules\TaxManager\Entities\Tax;
use Modules\TaxManager\Traits\VatTaxConfiguration;

class CalculateTaxService
{
    use VatTaxConfiguration;
    public static function getCalculatedTax(float $amount, array $productIds, array $categoryIds, array $quantity, string $taxPayer = 'vendor',  bool $storeData = false, array $additionalCharges = [], $orderId = null, $countryCode = null)
    {
        $systemTaxVat = SystemTaxSetup::with('additionalData')
            ->when($countryCode, function ($query) use ($countryCode) {
                $query->where('country_code', $countryCode);
            }, function ($query) {
                $query->where('is_default', 1);
            })->where('tax_payer', $taxPayer)
            ->first();

        if (!$systemTaxVat || $systemTaxVat?->is_active === 0) {
            return ['include' => null, 'totalTaxPercent' => 0, 'totalTaxamount' => 0];
        }
        if ($systemTaxVat?->is_included) {
            return ['include' => 1, 'totalTaxPercent' => 0, 'totalTaxamount' => 0];
        }
        try {
            if ($storeData) {
                DB::beginTransaction();
            }

            $totalTaxPercent = 0;
            $totalTaxamount = 0;
            $taxType = $systemTaxVat?->tax_type;
            $productWiseData=[];
            $additionalDatas = [];
            $additionalsDatas = $systemTaxVat->additionalData()->select('name', 'tax_ids')->get()->toArray();
            if (count($additionalCharges)) {
                foreach ($additionalsDatas as $additionalData) {
                    if (in_array($additionalData['name'], array_keys($additionalCharges))) {
                        $taxOnAdd = self::calculateTax(
                            systemTaxVat: $systemTaxVat,
                            amount: $additionalCharges[$additionalData['name']] ?? 0,
                            taxIds: $additionalData['tax_ids'],
                            taxPayer: $taxPayer,
                            storeData: $storeData,
                            orderId: $orderId,
                            countryCode: $countryCode,
                            tax_on: $additionalData['name'],
                        );

                        $taxOnAdd['additionalData'] = $additionalData['name'];
                        $additionalDatas[] = $taxOnAdd;
                         $totalTaxamount += $taxOnAdd['totalTaxamount'];
                    }
                }
            }

            if (in_array($taxType, ['product_wise', 'category_wise'])) {
                $dataType = self::getClassNames($taxType === 'product_wise' ? 'product' : 'category');

                foreach ($productIds as $key => $price) {
                    $dataId = $taxType === 'product_wise' ? $key : data_get($categoryIds, $key);
                    $taxVatIds = Taxable::where('taxable_type', $dataType)->where('taxable_id', $dataId)
                        ->where('system_tax_setup_id', $systemTaxVat->id)
                        ->pluck('tax_id')
                        ->toArray();
                    $taxData = self::calculateTax(
                        systemTaxVat: $systemTaxVat,
                        amount: $price,
                        taxIds: $taxVatIds,
                        taxPayer: $taxPayer,
                        storeData: $storeData,
                        orderId: $orderId,
                        countryCode: $countryCode,
                        data_id: $dataId,
                        data_type: $dataType,
                        quantity: data_get($quantity, $key, 1),
                    );

                    $totalTaxPercent += $taxData['totalTaxPercent'];
                    $totalTaxamount += $taxData['totalTaxamount'];
                    $taxData['product_id'] = $key;
                    $productWiseData[] = $taxData;
                }

                if ($storeData) {
                    DB::commit();
                }

                return [
                    'include' => $systemTaxVat?->is_included,
                    'totalTaxPercent' => $totalTaxPercent,
                    'totalTaxamount' => $totalTaxamount,
                    'taxType' => $taxType,
                    'productWiseData' => $productWiseData,
                    'additionalDatas' => $additionalDatas
                ];
            }

            $orderWiseData = self::calculateTax(
                systemTaxVat: $systemTaxVat,
                amount: $amount ?? 0,
                taxIds: $systemTaxVat->tax_ids,
                taxPayer: $taxPayer,
                storeData: $storeData,
                orderId: $orderId,
                countryCode: $countryCode
            );
            $orderWiseData['totalTaxamount'] += $totalTaxamount;
            $orderWiseData['productWiseData'] = [];
            $orderWiseData['taxType'] = $taxType;
            $orderWiseData['additionalDatas'] = $additionalDatas;
            if ($storeData) {
                DB::commit();
            }

            return $orderWiseData;
        } catch (\Throwable $th) {
            if ($storeData) {
                DB::rollBack();
            }
            return ['include' => null, 'totalTaxPercent' => 0, 'totalTaxamount' => 0, 'error' => $th->getMessage() ,'line' => $th->getLine()];
        }
    }



    protected static function calculateTax($systemTaxVat, $amount, $taxIds, $taxPayer = 'vendor', $tax_on = 'basic', $quantity = 1, $storeData = null, $orderId = null, $countryCode = null, $data_id = null, $data_type = null)
    {
        $taxRatePercent = Tax::whereIn('id', $taxIds)->select('id', 'name', 'tax_rate')->get();
        $totalTaxPercent = 0;
        $totalTaxamount = 0;

        foreach ($taxRatePercent as $taxRate) {
            $taxData = self::getTaxAmount(amount: $amount, taxRatePercent: $taxRate->tax_rate, isInclude: $systemTaxVat->is_included);
            $totalTaxPercent += $taxRate->tax_rate;
            $taxAmount = $taxData['taxAmount'] * $quantity;
            $totalTaxamount += $taxAmount;

            if ($storeData) {
                $orderTaxData = new OrderTax();
                $orderTaxData->tax_name = $taxRate->name;
                $orderTaxData->tax_type = $systemTaxVat->tax_type;
                $orderTaxData->tax_on = $tax_on;
                $orderTaxData->tax_rate = $taxRate->tax_rate;
                $orderTaxData->tax_amount = $taxAmount;
                $orderTaxData->before_tax_amount = $taxData['originalAmount'] * $quantity;
                $orderTaxData->after_tax_amount = $taxData['totalAmount'] * $quantity;
                $orderTaxData->tax_payer = $taxPayer;
                $orderTaxData->country_code = $countryCode;
                $orderTaxData->order_id = $orderId;
                $orderTaxData->tax_id = $taxRate->id;
                $orderTaxData->system_tax_setup_id = $systemTaxVat->id;
                $orderTaxData->taxable_id = $data_id;
                $orderTaxData->taxable_type = $data_type;
                $orderTaxData->quantity = $quantity;
                $orderTaxData->save();
            }
        }
        return  ['include' => $systemTaxVat?->is_included, 'totalTaxPercent' => $totalTaxPercent, 'totalTaxamount' => $totalTaxamount];
    }

    protected static function getTaxAmount($amount, $taxRatePercent, $isInclude = false)
    {
        if ($amount > 0 && $taxRatePercent > 0) {
            $taxAmount = ($amount * $taxRatePercent) / (100 + ($isInclude ? $taxRatePercent : 0));
            $totalAmount = $isInclude ? ($amount - $taxAmount) : ($amount + $taxAmount);
            return ['taxAmount' => $taxAmount, 'originalAmount' => $amount, 'totalAmount' => $totalAmount];
        }

        return ['taxAmount' => 0, 'originalAmount' => $amount, 'totalAmount' => $amount, 'taxRatePercent' => $taxRatePercent];
    }
}
