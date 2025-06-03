<?php

namespace Modules\TaxModule\Traits;


trait VatTaxConfiguration
{

    public static function getCountryType()
    {
        return  config('taxmodule.country_type');
    }
    public static function getpagination()
    {
        return config('taxmodule.pagination');
    }
    public static function getProjectName()
    {
        return config('taxmodule.project');
    }

    public static function getPorjectWiseSystemData($key = null)
    {
        $allProjects = [
            '6ammart' => [
                'tax_claculate_from' => ['Calculate_Tax_on_Billing_Address_Location'],
                'tax_calculate_on' => ['order_wise', 'product_wise', 'category_wise'],
                'additional_tax' => ['tax_on_additional_charge', 'tax_on_packaging_charge'],
            ],
            '6valley' => [
                'tax_claculate_from' => ['Calculate_Tax_on_Billing_Address_Location', 'Calculate_Tax_on_Shipping_Address_Location'],
                'tax_calculate_on' => ['order_wise', 'product_wise', 'category_wise'],
                'additional_tax' => ['tax_on_delivery_charge'],
            ]
        ];

        return self::getDataFromPorjectArray($allProjects, $key);
    }

    public static function getProjectWiseViewPath($name)
    {
        $allProjects = [
            '6ammart' => [
                'tax_list_export' =>  'taxmodule::file-exports.tax_list_export',
                'tax_list' =>  'taxmodule::tax.tax_list',
                'system_tax_setup' =>  'taxmodule::tax.system_tax_setup',
            ],
            '6valley' => [
                'tax_list_export' =>  'taxmodule::file-exports.tax_list_export',
                'tax_list' =>  'taxmodule::tax.tax_list',
                'system_tax_setup' =>  'taxmodule::tax.system_tax_setup',
            ],

        ];

        return self::getDataFromPorjectArray($allProjects, $name);
    }
    public static function getClassNames($model)
    {
        $allProjects = [
            '6ammart' => [
                'product' => 'App\Models\Item',
                'category' =>  'App\Models\Category',
                'addon' =>  'App\Models\AddOn',
                'addon_category' =>  'App\Models\AddonCategory',
            ],
            'stackfood' => [
                'product' => 'App\Models\Food',
                'category' =>  'App\Models\Category',
                'addon' =>  'App\Models\AddOn',
                'addon_category' =>  'App\Models\AddonCategory',
            ],
            '6valley' => [
                'product' => 'App\Models\Product',
                'category' =>  'App\Models\Category',
            ],
        ];

        return self::getDataFromPorjectArray($allProjects, $model);
    }


    private static function getDataFromPorjectArray($array, $key = null)
    {
        $project = self::getProjectName();
        if ($project && array_key_exists($project, $array)) {
            return $key ? data_get($array[$project], $key, []) : $array[$project];
        }
        return $array;
    }


    public function showNotification($type, $message)
    {
        $class = \Brian2694\Toastr\Facades\Toastr::class;
        $methodTypes = [
            // message warning type => method name
            'successMessage' => 'success',
            'infoMessage' => 'info',
            'warningMessage' => 'warning',
            'errorMessage' => 'error'
        ];

        if (class_exists($class) && array_key_exists($type, $methodTypes)) {
            return call_user_func([$class, $methodTypes[$type]], $message);
        }
    }
}
