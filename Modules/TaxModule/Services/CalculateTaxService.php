<?php

namespace Modules\TaxModule\Services;


use Illuminate\Support\Facades\DB;
use Modules\TaxModule\Entities\OrderTax;
use Modules\TaxModule\Entities\SystemTaxSetup;
use Modules\TaxModule\Entities\Taxable;
use Modules\TaxModule\Entities\Tax;
use Modules\TaxModule\Traits\VatTaxConfiguration;

class CalculateTaxService
{
    use VatTaxConfiguration;
    public static function getCalculatedTax(float $amount, array $productIds, array $categoryIds, array $quantity, string $taxPayer = 'vendor',  bool $storeData = false, array $additionalCharges = [], array $addonIds = [], array $addonQuantity = [], array $addonCategoryIds = [], $orderId = null, $countryCode = null)
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
            $productWiseData = [];
            $additionalDatas = [];
            $addonWiseData = [];
            $orderTaxIds = [];

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
                        $orderTaxIds =  array_merge($orderTaxIds, $taxOnAdd['orderTaxIds']);
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

                    // $totalTaxPercent += $taxData['totalTaxPercent'];
                    $totalTaxamount += $taxData['totalTaxamount'];
                    $taxData['product_id'] = $key;
                    $productWiseData[] = $taxData;
                    $orderTaxIds = array_merge($orderTaxIds, $taxData['orderTaxIds']);
                }


                if (count($addonIds) > 0) {
                    $addonDataType = self::getClassNames($taxType === 'product_wise' ? 'addon' : 'addon_category');
                    foreach ($addonIds as $addonkey => $addonPrice) {
                        $addonDataId = $taxType === 'product_wise' ? $addonkey : data_get($addonCategoryIds, $addonkey);

                        $addonTaxVatIds = Taxable::where('taxable_type', $addonDataType)->where('taxable_id', $addonDataId)
                            ->where('system_tax_setup_id', $systemTaxVat->id)
                            ->pluck('tax_id')
                            ->toArray();
                        // info($addonTaxVatIds);
                        $addonTaxData = self::calculateTax(
                            systemTaxVat: $systemTaxVat,
                            amount: $addonPrice,
                            taxIds: $addonTaxVatIds,
                            taxPayer: $taxPayer,
                            storeData: $storeData,
                            orderId: $orderId,
                            countryCode: $countryCode,
                            data_id: $addonDataId,
                            data_type: $addonDataType,
                            quantity: data_get($addonQuantity, $addonkey, 1),
                        );
                        //  $totalTaxPercent += $addonTaxData['totalTaxPercent'];
                        $totalTaxamount += $addonTaxData['totalTaxamount'];
                        $taxData['addon_id'] = $addonkey;
                        $addonWiseData[] = $addonTaxData;
                        $orderTaxIds = array_merge($orderTaxIds, $addonTaxData['orderTaxIds']);
                    }
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
                    'additionalDatas' => $additionalDatas,
                    'addonWiseData' => $addonWiseData,
                    'orderTaxIds' => $orderTaxIds,
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
            $orderWiseData['addonWiseData'] = $addonWiseData;
            if ($storeData) {
                DB::commit();
            }

            return $orderWiseData;
        } catch (\Throwable $th) {
            if ($storeData) {
                DB::rollBack();
            }
            return ['include' => null, 'totalTaxPercent' => 0, 'totalTaxamount' => 0, 'error' => $th->getMessage(), 'line' => $th->getLine()];
        }
    }



    protected static function calculateTax($systemTaxVat, $amount, $taxIds, $taxPayer = 'vendor', $tax_on = 'basic', $quantity = 1, $storeData = null, $orderId = null, $countryCode = null, $data_id = null, $data_type = null)
    {
        $taxRatePercent = Tax::whereIn('id', $taxIds)->select('id', 'name', 'tax_rate')->get();
        $totalTaxPercent = 0;
        $totalTaxamount = 0;
        $orderTaxIds = [];
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
                $orderTaxIds[] = $orderTaxData->id;
            }
        }
        return  ['include' => $systemTaxVat?->is_included, 'totalTaxPercent' => $totalTaxPercent, 'totalTaxamount' => $totalTaxamount, 'orderTaxIds' => $orderTaxIds];
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

    public static function updateOrderTaxData($orderId, array $orderTaxIds)
    {
        if (count($orderTaxIds) > 0) {
            OrderTax::whereIn('id', $orderTaxIds)->update(['order_id' => $orderId]);
            return true;
        }
        return false;
    }
}
