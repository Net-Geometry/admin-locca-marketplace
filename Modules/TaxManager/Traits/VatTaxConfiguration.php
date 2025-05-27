<?php

namespace Modules\TaxManager\Traits;


trait VatTaxConfiguration
{

    public static function getCountryType()
    {
        return  config('taxmanager.country_type');
    }
    public static function getpagination()
    {
        return config('taxmanager.pagination');
    }
    public static function getProjectName()
    {
        return config('taxmanager.project');
    }

    public static function getPorjectWiseSystemData($key = null)
    {
        $project = self::getProjectName();
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


        if ($project && array_key_exists($project, $allProjects)) {
            return $key ? data_get($allProjects[$project], $key, []) : $allProjects[$project];
        }
        return $allProjects;
    }

    public static function getProjectWiseViewPath($name)
    {
        $project = self::getProjectName();
        $allProjects = [
            '6ammart' => [
                'tax_list_export' =>  'taxmanager::file-exports.tax_list_export',
                'tax_list' =>  'taxmanager::tax.tax_list',
                'system_tax_setup' =>  'taxmanager::tax.system_tax_setup',
            ],
            '6valley' => [
                'tax_list_export' =>  'taxmanager::file-exports.tax_list_export',
                'tax_list' =>  'taxmanager::tax.tax_list',
                'system_tax_setup' =>  'taxmanager::tax.system_tax_setup',
            ],

        ];


        if ($project && array_key_exists($project, $allProjects)) {
            return $name ? data_get($allProjects[$project], $name, []) : $allProjects[$project];
        }
        return $allProjects;
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
